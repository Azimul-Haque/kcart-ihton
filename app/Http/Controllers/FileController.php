<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\File;
use App\Models\User;
use App\Models\FileMovement;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use View;

class FileController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $unresolvedmessagecount = Message::where('status', 0)->count();
        View::share('unresolvedmessagecount', $unresolvedmessagecount);

        $this->middleware('auth');
        // $this->middleware(['admin'])->only('getUsers', 'storeUser', 'updateUser', 'deleteUser', 'getUser', 'getMessages', 'updateMessage', 'getNotifications', 'sendSingleNotification', 'sendSingleSMS', 'getExamSolvePDF');
        
    }

    /**
     * সকল নথি ও ফিল্টারিং তালিকা
     */
    public function index(Request $request)
    {
        $query = File::with(['currentDesk', 'movements' => function ($q) {
            $q->latest();
        }]);

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

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('desk_id')) {
            $query->where('current_desk_id', $request->desk_id);
        }

        $files = $query->latest()->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('files.create', compact('users'));
    }

    /**
     * নির্দিষ্ট নথির বিস্তারিত ও টাইমলাইন প্রদর্শন
     */
    public function show(File $file)
    {
        $file->load(['currentDesk', 'movements.fromUser', 'movements.toUser', 'hearings.conductor']);
        $users = User::orderBy('name')->get();

        return view('dashboard.files.show', compact('file', 'users'));
    }

    /**
     * নতুন নথি এন্ট্রি সেভ করা
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'              => 'required|string',
            'case_number'       => 'nullable|string',
            'badi_name'         => 'nullable|string',
            'badi_mobile'       => 'nullable|string',
            'bibadi_name'       => 'nullable|string',
            'bibadi_mobile'     => 'nullable|string',
            'next_hearing_date' => 'nullable|date',
            'to_user_id'        => 'nullable|exists:users,id',
            'remarks'           => 'nullable|string',
        ]);

        // যদি সরাসরি অন্য কারও (যেমন: সার্ভেয়ার/কানুনগো) ডেস্কে পাঠানোর সিদ্ধান্ত নেওয়া হয়
        $destinationDesk = $request->filled('to_user_id') ? $request->to_user_id : Auth::id();
        $isDirectSelf = ($destinationDesk == Auth::id());

        $file = File::create([
            'type'              => $request->type,
            'case_number'       => $request->case_number,
            'badi_name'         => $request->badi_name,
            'badi_mobile'       => $request->badi_mobile,
            'bibadi_name'       => $request->bibadi_name,
            'bibadi_mobile'     => $request->bibadi_mobile,
            'next_hearing_date' => $request->next_hearing_date,
            'remarks'           => $request->remarks,
            'status'            => File::STATUS_INITIATED,
            'current_desk_id'   => $destinationDesk,
            'created_by'        => Auth::id(),
        ]);

        // প্রাথমিক ট্রান্সফার মুভমেন্ট লগ
        FileMovement::create([
            'file_id'      => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id'   => $destinationDesk,
            'is_received'  => $isDirectSelf,
            'received_at'  => $isDirectSelf ? now() : null,
            'remarks'      => $isDirectSelf ? 'নথি সিস্টেমে রুজু করা হয়েছে' : 'নথি রুজু করে স্থানান্তরিত করা হয়েছে'
        ]);

        return redirect()->route('files.receipt', $file->id)->with('success', 'নতুন ফাইল এন্ট্রি সফল হয়েছে। QR স্টিকার প্রিন্ট করুন।');
    }

    public function edit(File $file)
    {
        $users = User::orderBy('name')->get();
        return view('dashboard.files.edit', compact('file', 'users'));
    }

    /**
     * নথির তথ্য আপডেট করা
     */
    public function update(Request $request, File $file)
    {
        $validated = $request->validate([
            'type'              => 'required|string',
            'case_number'       => 'nullable|string',
            'badi_name'         => 'nullable|string',
            'badi_mobile'       => 'nullable|string',
            'bibadi_name'       => 'nullable|string',
            'bibadi_mobile'     => 'nullable|string',
            'status'            => 'required|string',
            'next_hearing_date' => 'nullable|date',
            'remarks'           => 'nullable|string',
        ]);

        $file->update($validated);

        return redirect()->route('files.index')->with('success', 'নথির তথ্য সফলভাবে হালনাগাদ করা হয়েছে।');
    }

    /**
     * ফাইল অন্য ডেস্কে পাঠানো
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

        $file->update(['current_desk_id' => $request->to_user_id]);

        return back()->with('success', 'নথিটি সফলভাবে ফরোয়ার্ড করা হয়েছে।');
    }

    /**
     * আমার ডেস্কের ফাইলসমূহ
     */
    public function myDesk(Request $request)
    {
        $query = File::where('current_desk_id', Auth::id())
            ->with(['currentDesk', 'movements' => function ($q) { $q->latest(); }]);

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
     * ট্রানজিটে থাকা ফাইলসমূহ
     */
    public function transit(Request $request)
    {
        $unreceivedFileIds = FileMovement::where('is_received', false)->pluck('file_id')->unique();

        $files = File::whereIn('id', $unreceivedFileIds)
            ->with(['currentDesk', 'movements' => function ($q) { $q->latest(); }])
            ->latest()
            ->paginate(15);

        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'))->with('isTransit', true);
    }

    /**
     * ৫৮মিমি স্লিপ প্রিন্ট
     */
    public function receipt(File $file)
    {
        return view('dashboard.files.receipt', compact('file'));
    }
}