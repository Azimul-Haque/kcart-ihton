<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\File;
use App\Models\Hearing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class HearingController extends Controller
{
    /**
     * ধার্য তারিখে বা আজকের শুনানির তালিকা প্রদর্শন
     */
    public function todayHearings(Request $request)
    {
        // ডিফল্টভাবে আজকের তারিখ ধরা হবে, চাইলে ফিল্টার থেকে অন্য তারিখও দেখা যাবে
        $selectedDate = $request->filled('date') ? $request->date : Carbon::today()->format('Y-m-d');

        $query = File::whereDate('next_hearing_date', $selectedDate)
            ->with(['currentDesk', 'hearings' => function ($q) {
                $q->latest();
            }]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'LIKE', "%{$search}%")
                  ->orWhere('badi_name', 'LIKE', "%{$search}%")
                  ->orWhere('bibadi_name', 'LIKE', "%{$search}%")
                  ->orWhere('badi_mobile', 'LIKE', "%{$search}%")
                  ->orWhere('bibadi_mobile', 'LIKE', "%{$search}%");
            });
        }

        $files = $query->latest()->paginate(15)->withQueryString();

        return view('dashboard.hearings.today', compact('files', 'selectedDate'));
    }

    /**
     * শুনানির আদেশ ও নতুন ধার্য তারিখ সংরক্ষণ
     */
    public function store(Request $request, File $file)
    {
        $request->validate([
            'hearing_date'      => 'required|date',
            'next_hearing_date' => 'nullable|date',
            'order_details'     => 'nullable|string',
            'status'            => 'required|string',
            'send_sms'          => 'nullable|boolean'
        ]);

        // ১. শুনানির নতুন রেকর্ড তৈরি
        $hearing = Hearing::create([
            'file_id'           => $file->id,
            'conducted_by'      => Auth::id(),
            'hearing_date'      => $request->hearing_date,
            'next_hearing_date' => $request->next_hearing_date,
            'order_details'     => $request->order_details,
        ]);

        // ২. মূল নথির স্ট্যাটাস, ধার্য তারিখ ও বর্তমান অবস্থান আপডেট
        $file->update([
            'status'            => $request->status,
            'next_hearing_date' => $request->next_hearing_date,
            'current_desk_id'   => Auth::id() // শুনানি নিলে ফাইল অটোমেটিক শুনানিকারীর ডেস্কে আসবে
        ]);

        // ৩. বাদি ও বিবাদিকে এসএমএস নোটিফিকেশন পাঠানো (ঐচ্ছিক)
        if ($request->boolean('send_sms') && $request->next_hearing_date) {
            $this->sendSmsNotification($file, $request->next_hearing_date);
            $hearing->update(['sms_sent' => true]);
        }

        return redirect()->route('files.receipt', $file->id)->with('success', 'শুনানির আদেশ সংরক্ষণ করা হয়েছে। ৫০৮মিমি স্লিপ প্রিন্ট করুন।');
    }

    /**
     * বাদি ও বিবাদিকে পরবর্তী শুনানির তারিখ সংক্রান্ত SMS পাঠানো
     */
    private function sendSmsNotification(File $file, $nextDate)
    {
        $formattedDate = bangla(date('d/m/Y', strtotime($nextDate)));
        $caseNo = $file->case_number ? bangla($file->case_number) : 'রুজুকৃত';
        
        $message = "উপজেলা ভূমি অফিস, ভৈরব: আপনার মোকদ্দমা নং {$caseNo} এর পরবর্তী ধার্য তারিখ {$formattedDate}।";

        // Bulk SMS Gateway Integration (e.g., BDBulkSMS / SMSQ)
        if (!empty($file->badi_mobile)) {
            // Http::post('SMS_GATEWAY_URL', ['number' => $file->badi_mobile, 'text' => $message]);
        }

        if (!empty($file->bibadi_mobile)) {
            // Http::post('SMS_GATEWAY_URL', ['number' => $file->bibadi_mobile, 'text' => $message]);
        }
    }
}