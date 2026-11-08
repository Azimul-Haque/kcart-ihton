<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\File;
use App\Models\FileMovement;
use App\Models\Hearing;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use View;
use PDF;

class HearingController extends Controller
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
            'next_hearing_date' => 'required|date',
            'order_details'     => 'nullable|string',
            'status'            => 'required|string',
            'to_user_id'        => 'nullable|exists:users,id', // অন্য ডেস্কে ফরোয়ার্ড করার জন্য
            'send_sms'          => 'nullable|boolean'
        ], [
            // কাস্টম এরর মেসেজ (বাংলায়)
            'next_hearing_date.required' => 'পরবর্তী ধার্য তারিখ নির্বাচন করা বাধ্যতামূলক!',
            'next_hearing_date.date'     => 'পরবর্তী ধার্য তারিখটি সঠিক তারিখ হতে হবে!',
        ]);

        // ১. শুনানির নতুন তথ্য সংরক্ষণ
        $hearing = Hearing::create([
            'file_id'           => $file->id,
            'conducted_by'      => Auth::id(),
            'hearing_date'      => $request->hearing_date,
            'next_hearing_date' => $request->next_hearing_date,
            'order_details'     => $request->order_details,
        ]);

        // ২. ট্রানজিট / ফাইল ফরোয়ার্ড লজিক
        $targetDesk = Auth::id(); // ডিফল্ট নিজের ডেস্কে থাকবে

        if ($request->filled('to_user_id') && $request->to_user_id != Auth::id()) {
            $targetDesk = $request->to_user_id;

            // ফাইল স্থানান্তরের মুভমেন্ট লগ তৈরি
            FileMovement::create([
                'file_id'      => $file->id,
                'from_user_id' => Auth::id(),
                'to_user_id'   => $targetDesk,
                'is_received'  => false, // প্রাপকের মোবাইল অ্যাপ স্ক্যানের অপেক্ষায় থাকবে
                'remarks'      => 'আদেশ প্রদানপূর্বক নথি স্থানান্তরিত করা হয়েছে'
            ]);
        }

        // ৩. মূল নথির স্ট্যাটাস, ধার্য তারিখ ও বর্তমান অবস্থান আপডেট
        $file->update([
            'status'            => $request->status,
            'next_hearing_date' => $request->next_hearing_date,
            'current_desk_id'   => $targetDesk,
        ]);

        // ৪. এসএমএস পাঠানো (যদি টিক দেওয়া থাকে)
        if ($request->boolean('send_sms') && $request->next_hearing_date) {
            $this->sendSmsNotification($file, $request->next_hearing_date);
            $hearing->update(['sms_sent' => true]);
        }

        return redirect()->route('files.show', $file->id)->with('success', 'শুনানির আদেশ ও ফাইল স্থানান্তর সফলভাবে সংরক্ষিত হয়েছে।');
    }

    public function destroy(Hearing $hearing)
    {
        $fileId = $hearing->file_id;
        $hearing->delete();

        return redirect()->route('files.show', $fileId)->with('success', 'শুনানির তথ্য সফলভাবে মুছে ফেলা হয়েছে।');
    }

    /**
     * বাদি ও বিবাদিকে পরবর্তী শুনানির তারিখ সংক্রান্ত SMS পাঠানো
     */
    private function sendSmsNotification(File $file, $nextDate)
    {
        // Format date and case number in Bangla
        $formattedDate = bangla(date('d/m/Y', strtotime($nextDate)));
        $caseNo = !empty($file->case_number) ? bangla($file->case_number) : 'রুজুকৃত';

        // 📩 Optimized SMS Content (Under 70 Unicode chars = 1 SMS credit)
        $message = "ভূমি অফিস ভৈরব: মামলা নং {$caseNo} এর পরবর্তী তারিখ {$formattedDate}।";

        // Collect recipient numbers and filter out empty or duplicate entries
        $recipients = array_unique(array_filter([
            $file->badi_mobile,
            $file->bibadi_mobile,
        ]));

        if (empty($recipients)) {
            return;
        }

        $url = config('sms.url2');
        $apiKey = config('sms.api_key');
        $senderId = config('sms.senderid');

        foreach ($recipients as $number) {
            try {
                $response = Http::asForm()
                    ->withoutVerifying() // Equivalent to CURLOPT_SSL_VERIFYPEER = false
                    ->post($url, [
                        'api_key'  => $apiKey,
                        'senderid' => $senderId,
                        'number'   => $number,
                        'message'  => $message,
                    ]);

                if ($response->successful()) {
                    $jsonResponse = $response->json();
                    $code = $jsonResponse['response_code'] ?? null;

                    if ($code == 202) {
                        Log::info("Hearing SMS sent successfully to {$number} for File ID: {$file->id}");
                    } elseif ($code == 1007) {
                        Log::warning("Insufficient SMS Balance while sending to {$number}");
                    } else {
                        Log::error("SMS Gateway Error ({$code}) for number: {$number}");
                    }
                } else {
                    Log::error("SMS HTTP Request failed for number: {$number}");
                }
            } catch (\Exception $e) {
                Log::error("SMS Dispatch Exception for {$number}: " . $e->getMessage());
            }
        }
    }

    public function downloadPdf(Request $request)
    {
        $selectedDate = $request->input('date', date('Y-m-d'));
        $search = $request->input('search');

        $query = File::whereDate('next_hearing_date', $selectedDate);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                  ->orWhere('badi_name', 'like', "%{$search}%")
                  ->orWhere('bibadi_name', 'like', "%{$search}%");
            });
        }

        // পিডিএফে পেজিনেশন ছাড়া সব ফাইল একই তালিকায় দেখানোর জন্য get() ব্যবহার করা হচ্ছে
        $files = $query->get();

        $pdf = PDF::loadView('dashboard.pdf.cause_list', [
            'files' => $files,
            'selectedDate' => $selectedDate
        ], [], [
            'format' => 'A4-L', // A4 Landscape
            'orientation' => 'L'
        ]);

        $fileName = 'Cause_List_' . $selectedDate . '.pdf';
        return $pdf->stream($fileName);
    }
}