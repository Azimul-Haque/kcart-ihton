<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\File;
use App\Models\FileMovement;

class AppController extends Controller
{
    // ১. লগইন (মোবাইল ও পাসওয়ার্ড দিয়ে)
    public function login(Request $request)
    {
        $request->validate(['mobile' => 'required', 'password' => 'required']);

        $user = User::where('mobile', $request->mobile)->orWhere('email', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'মোবাইল নম্বর বা পাসওয়ার্ড ভুল!'], 401);
        }

        $token = $user->createToken('app-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'designation' => $user->designation ?? 'অফিস স্টাফ',
                'onesignal_id' => $user->onesignal_id ?? null,
            ]
        ]);
    }

    // 🚀 ২. OneSignal ID সেভ/আপডেট করা
    public function updateOnesignalId(Request $request)
    {
        $request->validate([
            'onesignal_id' => 'required|string|max:191'
        ]);

        $user = Auth::user();
        $user->onesignal_id = $request->onesignal_id;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'OneSignal ID আপডেট হয়েছে।'
        ]);
    }

    // ৩. ড্যাশবোর্ড সামারি
    public function dashboard(Request $request)
    {
        $userId = Auth::id();

        $pendingFilesCount = File::where('current_desk_id', $userId)
            ->where('status', '!=', 'নিষ্পত্তি')
            ->count();
        
        $unreceivedCount = FileMovement::where('to_user_id', $userId)
            ->where('is_received', false)
            ->whereHas('file', function ($q) {
                $q->where('status', '!=', 'নিষ্পত্তি');
            })
            ->count();

        $fourteenDaysAgo = now()->subDays(14);
        $overdueCount = File::where('current_desk_id', $userId)
            ->where('status', '!=', 'নিষ্পত্তি')
            ->where(function ($q) use ($fourteenDaysAgo) {
                $q->whereHas('movements', function ($mq) use ($fourteenDaysAgo) {
                    $mq->whereRaw('id = (select max(id) from file_movements where file_id = files.id)')
                       ->where(function ($sq) use ($fourteenDaysAgo) {
                           $sq->where('received_at', '<=', $fourteenDaysAgo)
                              ->orWhere(function ($ssq) use ($fourteenDaysAgo) {
                                  $ssq->whereNull('received_at')
                                      ->where('created_at', '<=', $fourteenDaysAgo);
                              });
                       });
                })
                ->orWhere(function ($fq) use ($fourteenDaysAgo) {
                    $fq->doesntHave('movements')
                       ->where('created_at', '<=', $fourteenDaysAgo);
                });
            })
            ->count();

        return response()->json([
            'pending_count' => $pendingFilesCount,
            'unreceived_count' => $unreceivedCount,
            'overdue_count' => $overdueCount,
        ]);
    }

    // ৪. আমার ডেস্ক
    public function myDesk(Request $request)
    {
        $userId = Auth::id();
        
        $incoming = FileMovement::where('to_user_id', $userId)
            ->where('is_received', false)
            ->whereHas('file', function ($q) {
                $q->where('status', '!=', 'নিষ্পত্তি');
            })
            ->with(['file', 'fromUser:id,name,designation']) 
            ->latest()
            ->get();

        $incomingFileIds = $incoming->pluck('file_id')->toArray();

        $onDesk = File::where('current_desk_id', $userId)
            ->where('status', '!=', 'নিষ্পত্তি')
            ->whereNotIn('id', $incomingFileIds) 
            ->latest()
            ->get();

        return response()->json([
            'incoming_files' => $incoming,
            'on_desk_files'  => $onDesk,
            'users'          => User::where('id', '!=', $userId)->select('id', 'name', 'designation')->get()
        ]);
    }

    // ৫. QR স্ক্যান করার পর নথির তথ্য
    public function fileDetails($uuid)
    {
        $file = File::where('uuid', $uuid)->with('currentDesk')->first();
        if (!$file) {
            return response()->json(['message' => 'ফাইলটি সিস্টেমে পাওয়া যায়নি'], 404);
        }
        
        return response()->json(['file' => $file]);
    }

    // ৬. নথি রিসিভ করা
    public function receiveFile(Request $request, $file_id)
    {
        $file = File::find($file_id);
        if ($file && $file->status == 'নিষ্পত্তি') {
            return response()->json(['message' => 'এই নথিটি ইতোমধ্যে নিষ্পত্তি ও আর্কাইভ করা হয়েছে।'], 400);
        }

        $movement = FileMovement::where('file_id', $file_id)
            ->where('to_user_id', Auth::id())
            ->where('is_received', false)
            ->latest()->first();

        if ($movement) {
            $movement->update([
                'is_received' => true,
                'received_at' => now(),
            ]);

            File::where('id', $file_id)->update(['current_desk_id' => Auth::id()]);

            return response()->json(['message' => 'নথিটি সফলভাবে রিসিভ করা হয়েছে।']);
        }

        return response()->json(['message' => 'এই নথিটি আপনার ডেস্কে অপেক্ষমান নেই।'], 400);
    }

    // 🚀 ৭. নথি অন্য ডেস্কে পাঠানো ও OneSignal নোটিফিকেশন সেন্ড
    public function forwardFile(Request $request, $file_id)
    {
        $request->validate(['to_user_id' => 'required', 'remarks' => 'nullable']);
        
        $file = File::findOrFail($file_id);

        if ($file->status == 'নিষ্পত্তি') {
            return response()->json(['message' => 'নিষ্পত্তিকৃত নথি ফরোয়ার্ড করা সম্ভব নয়।'], 400);
        }

        if ($file->current_desk_id != Auth::id() && Auth::user()->role != 'admin') {
            return response()->json(['message' => 'এই নথিটি আপনার ডেস্কে নেই, ফরোয়ার্ড করা যাবে না।'], 403);
        }
        
        // ১. ট্রানজিট তৈরি
        FileMovement::create([
            'file_id' => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'is_received' => false,
            'remarks' => $request->remarks,
        ]);

        // ২. কারেন্ট ডেস্ক আপডেট
        $file->update(['current_desk_id' => $request->to_user_id]);

        // 🔔 ৩. টার্গেট ইউজারকে OneSignal পুশ নোটিফিকেশন পাঠানো
        $targetUser = User::find($request->to_user_id);
        if ($targetUser && !empty($targetUser->onesignal_id)) {
            $caseNo = $file->case_number ? bangla($file->case_number) : 'রুজুকৃত';
            $title = "নতুন নথি গ্রহণ";
            $message = "নথি নং {$caseNo} আপনার ডেস্কে প্রেরিত হয়েছে।";

            $this->sendOneSignalPush($targetUser->onesignal_id, $title, $message);
        }

        return response()->json(['message' => 'নথিটি ফরোয়ার্ড করা হয়েছে।']);
    }

    // ৮. লগআউট
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'লগআউট সফল।']);
    }
}