<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\File;
use App\Models\User;
use App\Models\FileMovement;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\SendsOneSignalPush; // 🚀 ১. Trait Import

use View;

class FileController extends Controller
{
    use SendsOneSignalPush; // 🚀 Trait Use করুন
    
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
        // শুধু চলমান নথি ফিল্টার (নিষ্পত্তিকৃত ফাইল বাদ দেওয়া হলো)
        $query = File::where('status', '!=', 'নিষ্পত্তি')
            ->with(['currentDesk', 'movements' => function ($q) { 
                $q->latest(); 
            }]);

        $this->applyFilters($query, $request);

        $files = $query->latest()->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('dashboard.files.create', compact('users'));
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
            'next_hearing_date' => 'required|date',
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

        return redirect()->route('files.index')->with('success', 'নতুন ফাইল এন্ট্রি সফল হয়েছে। QR স্টিকার প্রিন্ট করুন।');
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

        // ১. মুভমেন্ট লগ তৈরি
        FileMovement::create([
            'file_id'      => $file->id,
            'from_user_id' => Auth::id(),
            'to_user_id'   => $request->to_user_id,
            'is_received'  => false,
            'remarks'      => $request->remarks,
        ]);

        // ২. কারেন্ট ডেস্ক আপডেট
        $file->update(['current_desk_id' => $request->to_user_id]);

        // 🔔 ৩. প্রাপক ইউজারকে OneSignal পুশ নোটিফিকেশন পাঠানো
        $targetUser = User::find($request->to_user_id);
        if ($targetUser && !empty($targetUser->onesignal_id)) {
            $caseNo = $file->case_number ? bangla($file->case_number) : 'রুজুকৃত';
            $title = "নতুন নথি গ্রহণ";
            $message = "নথি নং {$caseNo} আপনার ডেস্কে প্রেরিত হয়েছে।";

            $this->sendOneSignalPush($targetUser->onesignal_id, $title, $message);
        }

        return back()->with('success', 'নথিটি সফলভাবে ফরোয়ার্ড করা হয়েছে।');
    }

    /**
     * আমার ডেস্কের ফাইলসমূহ
     */
    public function myDesk(Request $request)
    {
        $query = File::where('current_desk_id', Auth::id())
            ->where('status', '!=', 'নিষ্পত্তি')
            ->with(['currentDesk', 'movements' => function ($q) { 
                $q->latest(); 
            }]);

        $this->applyFilters($query, $request);

        $files = $query->latest()->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'))->with('isMyDesk', true);
    }

    public function dispose(Request $request, File $file)
    {
        $request->validate([
            'order_signed' => 'required|in:yes,no',
            'disposal_note' => 'nullable|string',
        ]);

        if ($request->order_signed === 'no') {
            return redirect()->back()->with('error', 'আদেশ স্বাক্ষরিত না হওয়া পর্যন্ত নথি নিষ্পত্তি করা যাবে না।');
        }

        // নথির স্ট্যাটাস ও পরবর্তী শুনানির তারিখ আপডেট
        $file->update([
            'status' => 'নিষ্পত্তি',
            'next_hearing_date' => null, // নিষ্পত্তির পর আর শুনানির তারিখ থাকবে না
        ]);

        // শুনানির ইতিহাসে নিষ্পত্তির নোট সেভ করা
        $file->hearings()->create([
            'user_id' => Auth::id(),
            'hearing_date' => now()->toDateString(),
            'next_hearing_date' => null,
            'order_details' => 'নথি চূড়ান্ত নিষ্পত্তি করা হয়েছে। Note: ' . ($request->disposal_note ?? 'চূড়ান্ত আদেশ প্রদান করা হলো।'),
            'sms_sent' => false
        ]);

        return redirect()->back()->with('success', 'নথিটি সফলভাবে চূড়ান্ত নিষ্পত্তি ও আর্কাইভ করা হয়েছে।');
    }

    /**
     * ৪. নিষ্পত্তিকৃত ও আর্কাইভকৃত নথিসমূহের আলাদা তালিকা
     */
    public function archivedList(Request $request)
    {
        $query = File::where('status', 'নিষ্পত্তি')
            ->with(['currentDesk', 'movements' => function ($q) { 
                $q->latest(); 
            }]);

        $this->applyFilters($query, $request);

        $files = $query->latest()->paginate(15)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('dashboard.files.index', compact('files', 'users'))->with('isArchived', true);
    }

    /**
     * ট্রানজিটে থাকা ফাইলসমূহ
     */
    public function transit(Request $request)
    {
        // ১. রিসিভ না হওয়া মুভমেন্টগুলোর ফাইল আইডি বের করা
        $unreceivedFileIds = FileMovement::where('is_received', false)->pluck('file_id')->unique();

        // ২. ট্রানজিটে থাকা ফাইলসমূহ (নিষ্পত্তিকৃত ফাইল সম্পূর্ণ বাদ দেওয়া হলো)
        $query = File::whereIn('id', $unreceivedFileIds)
            ->where('status', '!=', 'নিষ্পত্তি')
            ->with(['currentDesk', 'movements' => function ($q) { 
                $q->latest(); 
            }]);

        // ফিল্টার হেলপার কল (টেক্সট সার্চ ও টাইপ ফিল্টার সহ)
        $this->applyFilters($query, $request);

        $files = $query->latest()->paginate(15)->withQueryString();
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

    public function printSlip(File $file)
    {
        return view('dashboard.files.print_slip', compact('file'));
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'LIKE', "%{$search}%")
                  ->orWhere('badi_name', 'LIKE', "%{$search}%")
                  ->orWhere('badi_mobile', 'LIKE', "%{$search}%")
                  ->orWhere('bibadi_name', 'LIKE', "%{$search}%")
                  ->orWhere('bibadi_mobile', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('desk_id')) {
            $query->where('current_desk_id', $request->desk_id);
        }

        if ($request->has('status') && $request->status == 'overdue_14') {
            $fourteenDaysAgo = now()->subDays(14);
            $query->where(function ($q) use ($fourteenDaysAgo) {
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
            });
        } elseif ($request->filled('status') && $request->status != 'নিষ্পত্তি') {
            $query->where('status', $request->status);
        }
    }
}