<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\File;
use App\Models\FileMovement;

class AppController extends Controller
{
    // ১. লগইন (মোবাইল ও পাসওয়ার্ড দিয়ে)
    public function login(Request $request)
    {
        $request->validate(['mobile' => 'required', 'password' => 'required']);

        // আমরা ধরে নিচ্ছি users টেবিলে mobile ফিল্ড আছে (অথবা email ফিল্ডে মোবাইল নাম্বার রাখছেন)
        $user = User::where('mobile', $request->mobile)->orWhere('email', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'মোবাইল নম্বর বা পাসওয়ার্ড ভুল!'], 401);
        }

        // ২ সপ্তাহের জন্য টোকেন ভ্যালিডিটি (Sanctum default is long-lived)
        $token = $user->createToken('app-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'designation' => $user->designation ?? 'অফিস স্টাফ',
            ]
        ]);
    }

    // ২. ড্যাশবোর্ড সামারি
    public function dashboard(Request $request)
    {
        $userId = Auth::id();
        $pendingFilesCount = File::where('current_desk_id', $userId)->count();
        
        $unreceivedCount = FileMovement::where('to_user_id', $userId)
                                       ->where('is_received', false)->count();

        return response()->json([
            'pending_count' => $pendingFilesCount,
            'unreceived_count' => $unreceivedCount,
        ]);
    }

    // ৩. আমার ডেস্ক (যে ফাইলগুলো আমার কাছে আছে বা পাঠানো হয়েছে)
    public function myDesk(Request $request)
    {
        $userId = Auth::id();
        
        // ১. অপেক্ষমান (Incoming) ফাইলসমূহ
        $incoming = FileMovement::where('to_user_id', $userId)
            ->where('is_received', false)
            // সতর্কতা: আপনার FileMovement মডেলে সম্পর্কটির নাম fromUser না হয়ে from_user হলে নিচের লাইনে 'from_user:id,name,designation' লিখবেন।
            ->with(['file', 'fromUser:id,name,designation']) 
            ->latest()
            ->get();

        // অপেক্ষমান ফাইলের আইডিগুলো আলাদা করা হলো
        $incomingFileIds = $incoming->pluck('file_id')->toArray();

        // ২. রিসিভ করা ডেস্কে থাকা ফাইলসমূহ
        $onDesk = File::where('current_desk_id', $userId)
            // যে ফাইলগুলো এখনো ইনকামিং-এ আছে, সেগুলো যেন ডেস্কে ডাবল না দেখায় তার ফিল্টার
            ->whereNotIn('id', $incomingFileIds) 
            ->latest()
            ->get();

        return response()->json([
            'incoming_files' => $incoming,
            'on_desk_files'  => $onDesk,
            'users'          => User::where('id', '!=', $userId)->select('id', 'name', 'designation')->get()
        ]);
    }

    // ৪. QR স্ক্যান করার পর নথির তথ্য
    public function fileDetails($uuid)
    {
        $file = File::where('uuid', $uuid)->with('currentDesk')->first();
        if(!$file) return response()->json(['message' => 'ফাইলটি সিস্টেমে পাওয়া যায়নি'], 404);
        
        return response()->json(['file' => $file]);
    }

    // ৫. নথি রিসিভ করা (QR স্ক্যান করে বা ম্যানুয়ালি)
    public function receiveFile(Request $request, $file_id)
    {
        $movement = FileMovement::where('file_id', $file_id)
            ->where('to_user_id', Auth::id())
            ->where('is_received', false)
            ->latest()->first();

        if ($movement) {
            $movement->update([
                'is_received' => true,
                'received_at' => now(),
            ]);
            return response()->json(['message' => 'নথিটি সফলভাবে রিসিভ করা হয়েছে।']);
        }
        return response()->json(['message' => 'এই নথিটি আপনার ডেস্কে অপেক্ষমান নেই।'], 400);
    }

    // ৬. নথি অন্য ডেস্কে পাঠানো ও মন্তব্য
    public function forwardFile(Request $request, $file_id)
    {
        $request->validate(['to_user_id' => 'required', 'remarks' => 'nullable']);
        
        $file = File::findOrFail($file_id);
        
        FileMovement::create([
            'file_id' => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'is_received' => false,
            'remarks' => $request->remarks,
        ]);

        $file->update(['current_desk_id' => $request->to_user_id]);

        return response()->json(['message' => 'নথিটি ফরোয়ার্ড করা হয়েছে।']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'লগআউট সফল।']);
    }
}