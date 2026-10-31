<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Package;

use App\Models\Course;
use App\Models\Temppayment;
use App\Models\Payment;
use App\Models\Blog;

use App\Models\Exam;

use Carbon\Carbon;
use DB;
use Hash;
use Auth;
use Image;
use File;
use Session;
use Artisan;
// use Redirect;
use OneSignal;
use PDF;
use Shipu\Aamarpay\Facades\Aamarpay;


class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
        
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return redirect('https://play.google.com/store/apps/details?id=com.orbachinujbuk.bcs');
        
        $packages = Package::where('status', 1)->get();
        $blogs = Blog::orderBy('id', 'desc')->get()->take(3);
        $courses = Course::where('status', 1)
                         ->where('type', 1)
                         ->orderBy('priority', 'asc')
                         ->get()
                         ->take(6);

        return view('index.index')
                    ->withPackages($packages)
                    ->withBlogs($blogs)
                    ->withCourses($courses);
    }

    public function termsAndConditions()
    {
        return view('index.termsandconditions');
    }

    public function privacyPolicy()
    {
        return view('index.privacypolicy');
    }

    public function refundPolicy()
    {
        return view('index.refundpolicy');
    }

    public function getFaq()
    {
        return view('index.faq');
    }

    public function paymentProceed(Request $request)
    {
        $this->validate($request,array(
            'user_number'    =>   'required',
            'package_id'     =>   'required',
            'amount'         =>   'required',
        ));

        $user = User::where('mobile', $request->user_number)->first();
        $package = Package::findOrFail($request->package_id);

        if($user) {
            // $temppayment = new Temppayment;
            // $temppayment->user_id = $user->id;
            // $temppayment->package_id = $request->package_id;
            // $temppayment->uid = $user->uid;
            // // generate Trx_id
            // $trx_id = 'BJS' . random_string(10);
            // $temppayment->trx_id = $trx_id;
            // $temppayment->amount = $request->amount;
            // $temppayment->save();

            Session::flash('info','পেমেন্টটি সম্পন্ন করুন!');
            return view('index.payments.bkashpay')
                            ->withUser($user)
                            ->withAmount($request->amount)
                            ->withPackageid($package->id)
                            ->withPackagedesc($package->name . ' - ' . $package->duration . ' - ৳ ' . $package->price);
        } else {
            // Session::flash('warning','নাম্বারটি পাওয়া যায়নি! অ্যাপে গিয়ে রেজিস্ট্রেশন করুন।');
            Session::flash('swalwarning','নাম্বারটি পাওয়া যায়নি! অ্যাপে গিয়ে রেজিস্ট্রেশন করুন।');
            // Session::flash('swalwarningappling','https://play.google.com/store/apps/details?id=com.orbachinujbuk.bcs_constitution&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1');
            return redirect()->route('index.index');
        }
    }

    public function paymentSuccess(Request $request)
    {
        
        $user_id = $request->get('opt_a');
        
        if($request->get('pay_status') == 'Failed') {
            Session::flash('info', 'পেমেন্ট সম্পন্ন হয়নি, আবার চেষ্টা করুন!');
            return redirect()->route('index.index');
        }
        
        $amount_request = $request->get('opt_b');
        $amount_paid = $request->get('amount');

        if($request->pay_status == "Successful" && $amount_paid == $amount_request) {
            // OLD VERIFICATION METHOD
            
            $temppayment = Temppayment::where('trx_id', $request->mer_txnid)->first();
            // dd($request->all());
            $payment = new Payment;
            $payment->user_id = $user_id;
            $payment->package_id = $temppayment->package_id;
            $payment->uid = $temppayment->uid;
            $payment->payment_status = 1;
            $payment->card_type = $request->card_type;
            $payment->trx_id = $request->mer_txnid;
            $payment->amount = $request->amount;
            $payment->store_amount = $request->store_amount;
            $payment->save();

            $user = User::findOrFail($user_id);
            $current_package_date = Carbon::parse($user->package_expiry_date);
            $package = Package::findOrFail($temppayment->package_id);
            if($current_package_date->greaterThanOrEqualTo(Carbon::now())) {
                $package_expiry_date = $current_package_date->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
            } else {
                $package_expiry_date = Carbon::now()->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
            }
            // dd($package_expiry_date);
            $user->package_expiry_date = $package_expiry_date;
            $user->save();
            // ARO KAAJ THAKTE PARE, JODI FIREBASE EO UPDATE KORA LAAGE
            // ARO KAAJ THAKTE PARE, JODI FIREBASE EO UPDATE KORA LAAGE
            // dd($payment);

            $temppayment->delete();

            Session::flash('swalsuccess', 'পেমেন্ট সফল হয়েছে। অ্যাপটি ব্যবহার করুন। ধন্যবাদ!');
            return redirect()->route('index.index');
        } else {
            // dd($request->all());
            // $paymentdata = json_encode($request->all());
            // Session::flash('swalsuccess', $paymentdata);
            Session::flash('info', 'পেমেন্ট সম্পন্ন হয়নি, অনুগ্রহ করে Contact ফর্ম এর মাধ্যমে আমাদের জানান।');
            return redirect()->route('index.index');
        }

        // $valid  = Aamarpay::valid($request, $amount_request);
        // if($valid)
        // {
        //     // dd($request->all());
        // } 
    }

    public function paymentCancel(Request $request)
    {
        Session::flash('info','পেমেন্টটি ক্যানসেল করা হয়েছে!');
        return redirect()->route('index.index');
    }

    public function paymentFailed(Request $request)
    {
        Session::flash('info','পেমেন্টটি ব্যর্থ হয়েছে! অনুগ্রহ করে যোগাযোগ করুন।');
        return redirect()->route('index.index');
    }



    public function paymentSuccessApp(Request $request)
    {
        if($request->get('pay_status') == 'Failed') {
            Session::flash('info', 'পেমেন্ট সম্পন্ন হয়নি, আবার চেষ্টা করুন!');
            return redirect()->route('index.index');
        }
        
        $amount_request = $request->get('opt_b');
        $amount_paid = $request->get('amount');

        if($request->pay_status == "Successful" && $amount_paid == $amount_request) {
            // OLD VERIFICATION METHOD
            
            $temppayment = Temppayment::where('trx_id', $request->mer_txnid)->first();
            // dd($request->all());
            $payment = new Payment;
            $payment->user_id = $temppayment->user_id;
            $payment->package_id = $temppayment->package_id;
            $payment->uid = $temppayment->uid;
            $payment->payment_status = 1;
            $payment->card_type = $request->card_type;
            $payment->trx_id = $request->mer_txnid;
            $payment->amount = $request->amount;
            $payment->store_amount = $request->store_amount;
            $payment->save();

            $user = User::findOrFail($temppayment->user_id);
            $current_package_date = Carbon::parse($user->package_expiry_date);
            $package = Package::findOrFail($temppayment->package_id);
            if($current_package_date->greaterThanOrEqualTo(Carbon::now())) {
                $package_expiry_date = $current_package_date->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
            } else {
                $package_expiry_date = Carbon::now()->addDays($package->numeric_duration)->format('Y-m-d') . ' 23:59:59';
            }
            // dd($package_expiry_date);
            $user->package_expiry_date = $package_expiry_date;
            $user->save();
            // ARO KAAJ THAKTE PARE, JODI FIREBASE EO UPDATE KORA LAAGE
            // ARO KAAJ THAKTE PARE, JODI FIREBASE EO UPDATE KORA LAAGE
            // dd($payment);

            $temppayment->delete();

            Session::flash('swalsuccess', 'পেমেন্ট সফল হয়েছে। অ্যাপটি ব্যবহার করুন। ধন্যবাদ!');
            // return redirect()->route('index.index');
        } else {
            // dd($request->all());
            // $paymentdata = json_encode($request->all());
            // Session::flash('swalsuccess', $paymentdata);
            Session::flash('info', 'পেমেন্ট সম্পন্ন হয়নি, অনুগ্রহ করে Contact ফর্ম এর মাধ্যমে আমাদের জানান।');
            // return redirect()->route('index.index');
        }

        // $valid  = Aamarpay::valid($request, $amount_request);
        // if($valid)
        // {
        //     // dd($request->all());
        // } 
    }

    public function paymentCancelApp(Request $request)
    {
        Session::flash('info','পেমেন্টটি ক্যানসেল করা হয়েছে!');
    }


    // Generate PDF...

    public function getExamSolvePDF($softtoken, $examid)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $exam = Exam::findOrFail($examid);

            // return view('index.pdf.test')->withExam($exam);

            $pdf = PDF::loadView('index.pdf.examsolvepdf', ['exam' => $exam]);
            $fileName = 'Single-Exam-Solve-Sheet-' . $exam->id . '.pdf';
            return $pdf->stream($fileName); // download/stream
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function checkIP()
    {
        $response = file_get_contents('http://66.45.237.70/api.php');

        dd($response);
    }

    public function requestACDelete(Request $request)
    {
        Session::flash('swalsuccess', 'Thank you for the deletion request. We will update you soon!');
        return redirect()->route('index.index');
    }

    public function redirectPlayStore()
    {
        return redirect('https://play.google.com/store/apps/details?id=com.orbachinujbuk.bcs');
    }

    public function getAPIStatus()
    {
        return view('index.apistatus');
    }

    public function getDocumentation()
    {
        return view('index.documentation');
    }

    // delete sessions folder data
    public function deleteSessionFiles()
    {
        $files = glob(storage_path('framework/sessions/*')); 
        foreach($files as $file){
            if(is_file($file)) {
                unlink($file); // একটি একটি করে ফাইল ডিলিট করবে
            }
        }
        return "Old session files are being deleted!";
    }

    // clear configs, routes and serve
    public function clear()
    {
        // Artisan::call('route:clear');
        // Artisan::call('cache:clear');
        // Artisan::call('view:clear');
        // // Artisan::call('key:generate'); // not good for server and api or api token
        // Artisan::call('config:clear');
        Artisan::call('optimize:clear');

        DB::table('sessions')
                ->where('last_activity', '<', Carbon::now()->subMinutes(config('session.lifetime'))->getTimestamp())
                ->delete();
                
        Session::flush();
        return 'Config and Route Cached. All Cache Cleared';
    }

    public function generateSiteMap()
    {
        Artisan::call('sitemap:generate');

        return 'Sitemap generated Successfully!';
    }
    
}
