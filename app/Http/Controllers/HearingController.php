<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Hearing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // For SMS API

class HearingController extends Controller
{
    public function store(Request $request, File $file)
    {
        $request->validate([
            'hearing_date' => 'required|date',
            'next_hearing_date' => 'nullable|date',
            'order_details' => 'nullable|string',
            'status' => 'required|string',
            'send_sms' => 'boolean'
        ]);

        $hearing = Hearing::create([
            'file_id' => $file->id,
            'conducted_by' => Auth::id(),
            'hearing_date' => $request->hearing_date,
            'next_hearing_date' => $request->next_hearing_date,
            'order_details' => $request->order_details,
        ]);

        // Update main file status & next date
        $file->update([
            'status' => $request->status,
            'next_hearing_date' => $request->next_hearing_date,
            // শুনানি নিলে ফাইল অটোমেটিক AC Land বা যিনি নিচ্ছেন তার ডেস্কে চলে আসবে
            'current_desk_id' => Auth::id() 
        ]);

        // SMS Logic (Optional)
        if ($request->send_sms && $request->next_hearing_date) {
            $this->sendSmsNotification($file, $request->next_hearing_date);
            $hearing->update(['sms_sent' => true]);
        }

        // এখান থেকে আপনি চাইলে সরাসরি আপনার সেই 58mm প্রিন্ট ভিউতে রিডাইরেক্ট করে দিতে পারেন
        return redirect()->route('files.receipt', $file->id)->with('success', 'শুনানি রেকর্ড করা হয়েছে। প্রিন্ট করুন।');
    }

    private function sendSmsNotification($file, $nextDate)
    {
        $message = "ভূমি অফিস, ভৈরব: আপনার মোকদ্দমা নং {$file->case_number} এর পরবর্তী ধার্য তারিখ {$nextDate}। - এসি ল্যান্ড";
        
        // Example Bulk SMS Gateway (e.g., BDBulkSMS / SMSQ)
        if ($file->badi_phone) {
             // Http::post('sms-gateway-api-url', ['number' => $file->badi_phone, 'text' => $message]);
        }
        if ($file->bibadi_phone) {
             // Http::post('sms-gateway-api-url', ['number' => $file->bibadi_phone, 'text' => $message]);
        }
    }
}