<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Package;
use App\Models\Payment;

use Carbon\Carbon;
use DB;
use Exception;

class BkashController extends Controller
{
    private $base_url;
    private $app_key;
    private $app_secret;
    private $username;
    private $password;

    public function __construct()
    {
        parent::__construct();
        // $this->middleware('auth');
        // bKash Merchant API Information

        // You can import it from your Database
        $bkash_app_key = env('BKASH_APP_KEY');
        $bkash_app_secret = env('BKASH_APP_SECRET');
        $bkash_username = env('BKASH_USERNAME');
        $bkash_password = env('BKASH_PASSWORD');
        $bkash_base_url = env('BKASH_BASE_URL');

        $this->app_key = $bkash_app_key;
        $this->app_secret = $bkash_app_secret;
        $this->username = $bkash_username;
        $this->password = $bkash_password;
        $this->base_url = $bkash_base_url;
    }

    public function prodTest() {
        return view('bkash.bkash-payment');
    }

    public function prodPaymentTest(Request $request) {
        return view('bkash.final-payment')->withAmount($request->amount);
    }

    public function prodPayment($amount, $mobile, $package_id, $referral_code = null) {
        return view('bkash.final-payment')
                            ->withAmount($amount)
                            ->withMobile($mobile)
                            ->withPackageid($package_id)
                            ->withReferralcode($referral_code);
    }

    public function getToken(Request $request)
    {
        session()->forget('bkash_token');

        $post_token = array(
            'app_key' => $this->app_key,
            'app_secret' => $this->app_secret
        );

        $url = curl_init("$this->base_url/checkout/token/grant");
        $post_token = json_encode($post_token);
        $header = array(
            'Content-Type:application/json',
            "password:$this->password",
            "username:$this->username"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $post_token);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);

        $response = json_decode($resultdata, true);

        if (array_key_exists('msg', $response)) {
            return $response;
        }

        session()->put('bkash_token', $response['id_token']);

        return response()->json(['success', true]);
    }

    public function createPayment(Request $request)
    {
        // if (((string) $request->amount != (string) session()->get('bkash')['invoice_amount'])) {
        //     return response()->json([
        //         'errorMessage' => 'Amount Mismatch',
        //         'errorCode' => 2006
        //     ], 422);
        // }

        $token = session()->get('bkash_token');

        // $request['amount'] = '1.00';
        $request['intent'] = 'sale';
        $request['currency'] = 'BDT';
        $request['merchantInvoiceNumber'] = 'BCS' . random_string(10) . date('Ymd');

        $url = curl_init("$this->base_url/checkout/payment/create");
        $request_data_json = json_encode($request->all());
        $header = array(
            'Content-Type:application/json',
            "authorization: $token",
            "x-app-key: $this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function executePayment(Request $request)
    {
        $token = session()->get('bkash_token');

        $paymentID = $request->paymentID;
        $url = curl_init("$this->base_url/checkout/payment/execute/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$token",
            "x-app-key:$this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    public function queryPayment(Request $request)
    {
        $token = session()->get('bkash_token');
        $paymentID = $request->payment_info['payment_id'];

        $url = curl_init("$this->base_url/checkout/payment/query/" . $paymentID);
        $header = array(
            'Content-Type:application/json',
            "authorization:$token",
            "x-app-key:$this->app_key"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }

    // public function bkashSuccess(Request $request)
    // {
    //     $user = User::where('mobile', $request->mobile)->first();
        
    //     $payment = new Payment;
    //     $payment->user_id = $user->id;
    //     $payment->package_id = $request->package_id;
    //     $payment->uid = $user->uid;
    //     $payment->payment_status = 1;
    //     $payment->card_type = 'bKash';
    //     $payment->trx_id = $request->payment_info['trxID'];
    //     $payment->amount = $request->payment_info['amount'];
    //     $payment->store_amount = $request->payment_info['amount'] - ($request->payment_info['amount'] * 0.02);
    //     $payment->save();
        
    //     $current_package_date = Carbon::parse($user->package_expiry_date);
    //     $package = Package::findOrFail($request->package_id);
    //     if($current_package_date->greaterThanOrEqualTo(Carbon::now())) {
    //         $package_expiry_date = $current_package_date->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
    //     } else {
    //         $package_expiry_date = Carbon::now()->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
    //     }
    //     // dd($package_expiry_date);
    //     $user->package_expiry_date = $package_expiry_date;
    //     $user->save();
    //     return response()->json(['status' => true]);
    // }

    public function bkashSuccess(Request $request)
    {
        // ১. ডাটাবেস ট্রানজেকশন শুরু (যাতে একটি ফেল করলে সব রোলব্যাক হয়)
        DB::beginTransaction();

        try {
            // ২. ইউজার এবং প্যাকেজ চেক
            $user = User::where('mobile', $request->mobile)->first();
            if (!$user) throw new Exception("ইউজার খুঁজে পাওয়া যায়নি (Mobile: {$request->mobile})");

            $package = Package::find($request->package_id);
            if (!$package) throw new Exception("প্যাকেজ আইডি ভুল!");

            // ৩. পেমেন্ট রেকর্ড সেভ করা
            $payment = new Payment;
            $payment->user_id = $user->id;
            $payment->package_id = $request->package_id;
            // $payment->uid = $user->uid;
            $payment->payment_status = 1;
            $payment->card_type = 'bKash';
            // এখানে এপিআই রেসপন্স থেকে আসা ফিল্ডগুলো সাবধানে চেক করতে হবে
            $payment->trx_id = $request->payment_info['trxID'] ?? 'N/A';
            $payment->amount = $request->payment_info['amount'] ?? 0;
            $payment->store_amount = $payment->amount - ($payment->amount * 0.02); // ২% বিকাশ চার্জ বাদে
            $payment->referral_code = $request->referral_code ?? '';
            $payment->save();

            // ৪. ইউজার মেয়াদ (Expiry Date) আপডেট লজিক
            $now = Carbon::now();
            // যদি মেয়াদের তারিখ না থাকে বা আজ শেষ হয়ে গিয়ে থাকে তবে 'আজ' থেকে শুরু হবে
            $current_expiry = $user->package_expiry_date ? Carbon::parse($user->package_expiry_date) : $now;
            
            $baseDate = $current_expiry->greaterThan($now) ? $current_expiry : $now;
            $user->package_expiry_date = $baseDate->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
            
            // ৫. প্রোমো/রেফারেল লজিক (আপনার রিকোয়ারমেন্ট অনুযায়ী)
            if ($request->has('referral_code') && !empty($request->referral_code)) {
                $promoOwner = User::where('referral_code', $request->referral_code)->first();

                if ($promoOwner && $promoOwner->id != $user->id) {
                    if ($promoOwner->role == 'ambassador') {
                        // --- ক. অ্যাম্বাসেডর হলে ২০% কমিশন ---
                        $commission = $package->price * 0.20;
                        $profile = $promoOwner->ambassadorProfile;
                        if ($profile) {
                            $profile->increment('balance', $commission);
                            $profile->increment('total_earned', $commission);
                        }
                    } else {
                        // --- খ. সাধারণ ইউজার হলে উভয়কেই ১০ দিন বোনাস ---
                        // ইউজারের ১০ দিন বাড়ানো (ইতিমধ্যে প্যাকেজের সাথে যোগ হবে)
                        $user->package_expiry_date = Carbon::parse($user->package_expiry_date)->addDays(10)->format('Y-m-d') . ' 23:59:59';
                        
                        // রেফারেল দাতার ১০ দিন বাড়ানো
                        $owner_current_expiry = $promoOwner->package_expiry_date ? Carbon::parse($promoOwner->package_expiry_date) : $now;
                        $owner_base = $owner_current_expiry->greaterThan($now) ? $owner_current_expiry : $now;
                        $promoOwner->package_expiry_date = $owner_base->addDays(10)->format('Y-m-d') . ' 23:59:59';
                        $promoOwner->save();
                    }
                }
            }

            // ৬. ইউজারের ডাটা সেভ
            $user->save();

            // ৭. সব ঠিক থাকলে কনফার্ম করা
            DB::commit();
            return response()->json(['status' => true, 'message' => 'পেমেন্ট সফল এবং মেয়াদ আপডেট হয়েছে।']);

        } catch (Exception $e) {
            // ৮. এরর হলে যা যা ডাটাবেসে সেভ হয়েছিল সব বাতিল (Rollback)
            DB::rollBack();
            
            // এরর মেসেজটি লগ ফাইলে সেভ করুন এবং রেসপন্সে পাঠান
            \Log::error("bKash Success Logic Error: " . $e->getMessage());
            
            return response()->json([
                'status' => false, 
                'message' => 'ত্রুটি: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bkashCancelPage()
    {
        return view('bkash.bkash-payment-cancel');
    }

    public function bkashSuccessPage()
    {
        return view('bkash.bkash-payment-success');
    }

    public function bkashFailedPage()
    {
        return view('bkash.bkash-payment-failed');
    }

    public function bkashCancelPageWeb()
    {
        Session::flash('info','পেমেন্টটি ক্যানসেল করা হয়েছে!');
        return redirect()->route('index.index');
    }

    public function bkashSuccessPageWeb()
    {
        Session::flash('swalsuccess', 'পেমেন্ট সফল হয়েছে। অ্যাপটি ব্যবহার করুন। ধন্যবাদ!');
            return redirect()->route('index.index');
    }

    public function bkashFailedPageWeb()
    {
        Session::flash('info','পেমেন্টটি ব্যর্থ হয়েছে! অনুগ্রহ করে যোগাযোগ করুন।');
        return redirect()->route('index.index');
    }
}