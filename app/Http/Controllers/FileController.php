<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\FileMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * সকল নথি ও ফিল্টারিং তালিকা
     */
    public function index(Request $request)
    {
        $query = File::with(['currentDesk', 'movements' => function ($q) {
            $q->latest();
        }]);

        // ১. বহুমুখী সার্চ লজিক (মামলা নং, বাদি/বিবাদির নাম, মোবাইল, UUID)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'LIKE', "%{$search}%")
                  ->orWhere('badi_name', 'LIKE', "%{$search}%")
                  ->orWhere('bibadi_name', 'LIKE', "%{$search}%")
                  ->orWhere('badi_mobile', 'LIKE', "%{$search}%")
                  ->orWhere('bibadi_mobile', 'LIKE', "%{$search}%")
                  ->orWhere('uuid', 'LIKE', "%{$search}%");
            });
        }

        // ২. নথির ধরন ফিল্টার
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // ৩. বর্তমান স্ট্যাটাস ফিল্টার
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ৪. নির্দিষ্ট ডেস্কে থাকা নথি ফিল্টার
        if ($request->filled('desk_id')) {
            $query->where('current_desk_id', $request->desk_id);
        }

        $files = $query->latest()->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'));
    }

    /**
     * শুধুমাত্র নিজের ডেস্কে থাকা ফাইলসমূহ (My Desk)
     */
    public function myDesk(Request $request)
    {
        $query = File::where('current_desk_id', Auth::id())
            ->with(['currentDesk', 'movements' => function ($q) {
                $q->latest();
            }]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'LIKE', "%{$search}%")
                  ->orWhere('badi_name', 'LIKE', "%{$search}%")
                  ->orWhere('bibadi_name', 'LIKE', "%{$search}%");
            });
        }

        $files = $query->latest()->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'))->with('isMyDesk', true);
    }

    /**
     * ট্রানজিটে থাকা নথি (পাঠানো হয়েছে কিন্তু এখনো স্ক্যান করে গ্রহণ করা হয়নি)
     */
    public function transit(Request $request)
    {
        $unreceivedFileIds = FileMovement::where('is_received', false)
            ->pluck('file_id')
            ->unique();

        $files = File::whereIn('id', $unreceivedFileIds)
            ->with(['currentDesk', 'movements' => function ($q) {
                $q->latest();
            }])
            ->latest()
            ->paginate(15);

        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'))->with('isTransit', true);
    }

    /**
     * নতুন নথি এন্ট্রি সেভ করা
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'          => 'required|string',
            'case_number'   => 'nullable|string',
            'badi_name'     => 'nullable|string',
            'badi_mobile'   => 'nullable|string',
            'bibadi_name'   => 'nullable|string',
            'bibadi_mobile' => 'nullable|string',
            'remarks'       => 'nullable|string',
        ]);

        $file = File::create(array_merge($validated, [
            'status'          => File::STATUS_INITIATED,
            'current_desk_id' => Auth::id(),
            'created_by'      => Auth::id(),
        ]));

        // শুরুর ফিজিক্যাল রেকর্ড এন্ট্রি
        FileMovement::create([
            'file_id'      => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id'   => Auth::id(),
            'is_received'  => true,
            'received_at'  => now(),
            'remarks'      => 'ফাইল সিস্টেমে রুজু করা হয়েছে'
        ]);

        return redirect()->route('files.receipt', $file->id)->with('success', 'নতুন ফাইল এন্ট্রি সফল হয়েছে। QR স্টিকার প্রিন্ট করুন।');
    }

    /**
     * ড্যাশবোর্ড থেকে ফাইল হস্তান্তরের অনুরোধ (Direct Transfer)
     */
    public function transfer(Request $request, File $file)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'remarks'    => 'nullable|string'
        ]);

        FileMovement::create([
            'file_id'      => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id'   => $request->to_user_id,
            'is_received'  => false,
            'remarks'      => $request->remarks,
        ]);

        $file->update([
            'current_desk_id' => $request->to_user_id,
        ]);

        return back()->with('success', 'নথিটি সফলভাবে ফরোয়ার্ড করা হয়েছে। প্রাপকের স্ক্যান করার অপেক্ষায় আছে।');
    }

    /**
     * ৫৮মিমি থার্মাল স্টিকার প্রিন্ট ভিউ
     */
    public function receipt(File $file)
    {
        return view('dashboard.files.receipt', compact('file'));
    }
}