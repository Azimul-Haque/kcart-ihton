<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Exam;
use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Meritlist;
use App\Models\PayoutRequest;

use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;
use Hash;
use Auth;
use Image;
use File;
use Session;
use Artisan;
// use Redirect;
use OneSignal;
use Purifier;
use Cache;
use View;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $unresolvedmessagecount = Message::where('status', 0)->count();
        View::share('unresolvedmessagecount', $unresolvedmessagecount);

        $this->middleware('auth')->except('clear');
        $this->middleware(['admin'])->only('getUsers', 'storeUser', 'updateUser', 'deleteUser', 'getUser', 'deleteBalance', 'getCreditors', 'getSingleCreditor', 'getAddDuePage', 'deleteCreditorDue', 'getSiteCategorywise', 'getPackages', 'storePackage', 'updatePackage', 'deletePackage', 'getPayments', 'getMessages', 'updateMessage', 'getNotifications', 'sendSingleNotification', 'sendSingleSMS', 'getExamSolvePDF');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // if user is a manager, redirect him to his profile
        // if user is a manager, redirect him to his profile
        if(Auth::user()->role == 'user') {
            abort(403, 'Access Denied');
        } elseif(Auth::user()->role == 'volunteer') {
            // echo 'ase';
            return redirect()->route('dashboard.questions.reported');
        }

        // $totalsites = Site::count();
        $totalusers = User::count();

        $totalexamsattendedtoday = Meritlist::whereDate('created_at', Carbon::today())->count();

        

        $totalpayment = Payment::sum('store_amount');
        // $totalbalance = Balance::sum('store_amount');
        // $totalexpense = Expense::sum('store_amount');

        $totalmonthlypayment = DB::table('payments')
                                ->select(DB::raw('SUM(store_amount) as totalamount'))
                                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                // ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                ->first();
        $last14daysusersdaily = DB::table('users')
                                    ->select('created_at', DB::raw('COUNT(*) as totalusers'))
                                    ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                    ->orderBy('created_at', 'DESC')
                                    ->take(14)
                                    ->get();
        $daysforchartc = [];
        foreach ($last14daysusersdaily as $key => $days) {
            $daysforchartc[] = date_format(date_create($days->created_at), "M d");
        }
        $daysforchartc = json_encode(array_reverse($daysforchartc));

        $totalusersforchartc = [];
        foreach ($last14daysusersdaily as $key => $days) {
            $totalusersforchartc[] = $days->totalusers;
        }
        $originalforcumulitive = array_reverse($totalusersforchartc);
        $totalusersforchartc = json_encode(array_reverse($totalusersforchartc));

        $totaluserscumulitiveforchartc = [];
        $cumulitiveusersprimary = 0;
        foreach ($originalforcumulitive as $totalusersforc) {
            $cumulitiveusersprimary += $totalusersforc;
            $totaluserscumulitiveforchartc[] = $cumulitiveusersprimary;
        }
        $totaluserscumulitiveforchartc = json_encode($totaluserscumulitiveforchartc);
        // dd($totaluserscumulitiveforchartc);

        return view('dashboard.index')->withTotalusers($totalusers)
                                      ->withTotalpayment($totalpayment)
                                      ->withTotalmonthlypayment($totalmonthlypayment)
                                      ->withTotalexamsattendedtoday($totalexamsattendedtoday)
                                      ->withDaysforchartc($daysforchartc)
                                      ->withTotalusersforchartc($totalusersforchartc)
                                      ->withTotaluserscumulitiveforchartc($totaluserscumulitiveforchartc);
                                    // ->withTotalbalance($totalbalance)
                                    // ->withTotalexpense($totalexpense)
                                    // ->withTodaystotalexpense($todaystotalexpense)
                                    // ->withTodaystotaldeposit($todaystotaldeposit);
    }

    public function clearQueryCache()
    {
        Cache::flush();
        Session::flash('success', 'সকল কোয়েরি ক্যাশ মুছে দেওয়া হয়েছে!');
        return redirect()->route('dashboard.index');
    }

    public function getUsers()
    {
        $userscount = User::count();
        $users = User::where('name', '!=', null)->orderBy('id', 'asc')->paginate(10);
        return view('dashboard.users.index')
                    ->withUsers($users)
                    ->withUserscount($userscount);
    }

    public function getUsersSort()
    {
        // $users = User::where('name', '!=', null)->orderBy('id', 'asc')->get(10);
        $userscount = User::count();
        $users = User::withCount('meritlists')
                     ->orderBy('meritlists_count', 'desc')
                     ->paginate(10);

        // dd($users);
        // $users = $users->join('meritlists', function ($join) {
        //                 $join->on('meritlists.user_id', '=', 'users.id');
        //             })
        //             ->groupBy('users.id')
        //             ->orderBy('count', $order)
        //             ->select((['users.*', DB::raw('COUNT(meritlists.user_id) as count')]))->paginate(10);

        return view('dashboard.users.index')
                    ->withUsers($users)
                    ->withUserscount($userscount);
    }

    public function getExpiredUsers()
    {
        $paidusersids = DB::table('payments')->select('user_id')->groupBy('user_id')->get()->pluck('user_id')->toArray();
        // dd($paidusersids);
        $userscount = User::where('package_expiry_date', '<', Carbon::now())
                          ->whereIn('id', $paidusersids)
                          ->count();
        $users = User::where('package_expiry_date', '<', Carbon::now())
                     ->whereIn('id', $paidusersids)
                     ->orderBy('package_expiry_date', 'asc')
                     ->paginate(10);
                     // ->get();
        
        // dd($users);
        // $usermobiles = $users->pluck('mobile')->toArray();
        // dd(implode(', ', $usermobiles));
        return view('dashboard.users.expiredusers')
                    ->withUsers($users)
                    ->withUserscount($userscount);
    }

    public function sendExpiredSMS(Request $request)
    {
        $this->validate($request,array(
            'randtotalhidden'         => 'required',
            'randtotalvisible'        => 'required|string|max:191',
            'sms'                     => 'required|string',
        ));
        if($request->randtotalhidden == $request->randtotalvisible) {
            $paidusersids = DB::table('payments')->select('user_id')->groupBy('user_id')->get()->pluck('user_id')->toArray();
            $users = User::select('name', 'mobile')
                         ->where('package_expiry_date', '<', Carbon::now())
                         ->whereIn('id', $paidusersids)
                         ->orderBy('package_expiry_date', 'asc')
                         ->get();
            
            foreach ($users as $user) {
                $mobile_number = 0;
                if(strlen($user->mobile) == 11) {
                    $mobile_number = $user->mobile;
                } elseif(strlen($user->mobile) > 11) {
                    if (strpos($user->mobile, '+') !== false) {
                        $mobile_number = substr($user->mobile, -11);
                    }
                }
                $numbersarray[] = $mobile_number;
            }
            $numbersstr = implode (",", $numbersarray);
            // dd($numbersstr);
            
            $url = config('sms.url');
            $number = $mobile_number;
            $text = $request->sms; // . ' Customs and VAT Co-operative Society (CVCS).';
            $data= array(
                'username'=>config('sms.username'),
                'password'=>config('sms.password'),
                'number'=>"$numbersstr",
                'message'=>"$text",
            );
            // initialize send status
            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is important
            $smsresult = curl_exec($ch);

            // $sendstatus = $result = substr($smsresult, 0, 3);
            $p = explode("|",$smsresult);
            $sendstatus = $p[0];
            // send sms
            if($sendstatus == 1101) {
                Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
            } elseif($sendstatus == 1006) {
                Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
            } else {
                Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
            }
        } else {
            Session::flash('warning', 'অংক মেলেনি!');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function getUsersSearch($search)
    {
        $userscount = User::where('name', 'LIKE', "%$search%")
                          ->orWhere('email', 'LIKE', "%$search%")
                          ->orWhere('mobile', 'LIKE', "%$search%")
                          ->orWhere('uid', 'LIKE', "%$search%")
                          ->orWhere('onesignal_id', 'LIKE', "%$search%")
                          ->orderBy('id', 'desc')
                          ->count();
        $users = User::where('name', 'LIKE', "%$search%")
                     ->orWhere('email', 'LIKE', "%$search%")
                     ->orWhere('mobile', 'LIKE', "%$search%")
                     ->orWhere('uid', 'LIKE', "%$search%")
                     ->orWhere('onesignal_id', 'LIKE', "%$search%")
                     ->orderBy('id', 'desc')
                     ->paginate(10);

        // $sites = Site::all();
        return view('dashboard.users.index')
                    ->withUsers($users)
                    ->withUserscount($userscount);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        
        // dd($totaldeposit);

        return view('dashboard.users.single')
                    ->withUser($user);
    }

    public function storeUser(Request $request)
    {
        // dd(serialize($request->sitecheck));
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile',
            'role'        => 'required',
            // 'sitecheck'   => 'sometimes',
            'password'    => 'required|string|min:8|max:191',
        ));

        $user = new User;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        // if(!empty($request->sitecheck)) {
        //     $user->sites = implode(',', $request->sitecheck);
        // }
        $user->password = Hash::make($request->password);
        $user->save();

        Cache::forget('user_approval_status_' . $user->id);

        Session::flash('success', 'User created successfully!');
        return redirect()->route('dashboard.users');
    }

    public function updateUser(Request $request, $id)
    {
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile,'.$id,
            'role'        => 'required',
            'packageexpirydate'        => 'required',
            'uid'        => 'sometimes',
            'onesignal_id'        => 'sometimes',
            // 'sitecheck'   => 'sometimes',
            'password'    => 'nullable|string|min:8|max:191',
        ));

        $user = User::find($id);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        
        $user->package_expiry_date = date('Y-m-d', strtotime($request->packageexpirydate)) . ' 23:59:59';
        // if(!empty($request->sitecheck)) {
        //     $user->sites = implode(',', $request->sitecheck);
        // }
        $user->uid = $request->uid;
        $user->onesignal_id = $request->onesignal_id;
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        Cache::forget('user_approval_status_' . $user->id);

        Session::flash('success', 'User updated successfully!');
        return redirect()->route('dashboard.users');
    }

    public function toggleApprove($id)
    {
        $user = User::findOrFail($id);
        
        // বর্তমান স্ট্যাটাসটি উল্টে দিবে (১ থাকলে ০ হবে, ০ থাকলে ১ হবে)
        $user->is_approved = !$user->is_approved;
        $user->save();

        // মেসেজ তৈরি
        $status = $user->is_approved ? 'অনুমোদিত' : 'অনুমোদন বাতিল';

        Cache::forget('user_approval_status_' . $user->id);
        
        return redirect()->back()->with('success', "ব্যবহারকারীর স্ট্যাটাস সফলভাবে {$status} করা হয়েছে।");
    }

    public function updateBulkPackageDate(Request $request)
    {
        $this->validate($request,array(
            'numbers'                      => 'required',
            'packageexpirydatebulk'        => 'required',
        ));

        $numbersarray = explode(',', $request->numbers);

        $counter = 0;
        foreach($numbersarray as $number) {
            $user = User::where('mobile', 'LIKE', '%' . $number . '%')->first();
            if($user) {
                $user->package_expiry_date = date('Y-m-d', strtotime($request->packageexpirydatebulk)) . ' 23:59:59';
                $user->save();
                $counter++;
            }
        }

        Session::flash('success', $counter . ' Users updated successfully!');
        return redirect()->route('dashboard.users');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        Cache::forget('user_approval_status_' . $user->id);
        $user->delete();

        Session::flash('success', 'User deleted successfully!');
        return redirect()->route('dashboard.users');
    }

    public function approvePayout(Request $request)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'payout_id' => 'required|exists:payout_requests,id',
            'transaction_id' => 'required|string|max:100',
        ]);

        // ২. রিকোয়েস্ট খুঁজে বের করা
        $payout = PayoutRequest::findOrFail($request->payout_id);

        // ৩. নিরাপত্তা চেক: অলরেডি পেইড কি না
        if ($payout->status == 1) {
            return redirect()->back()->with('error', 'এই পেমেন্টটি ইতিমধ্যে সম্পন্ন করা হয়েছে।');
        }

        // ৪. স্ট্যাটাস আপডেট
        $payout->status = 1;
        $payout->transaction_id = $request->transaction_id;
        $payout->save();
        // $payout->update([
        //     'status' => 1, // পেইড
        //     'transaction_id' => $request->transaction_id,
        // ]);

        return redirect()->back()->with('success', 'পেমেন্ট সফলভাবে আপডেট করা হয়েছে এবং ট্রানজেকশন আইডি সংরক্ষিত হয়েছে।');
    }



    public function getPackages()
    {
        $packages = Package::all();
        
        return view('dashboard.packages.index')->withPackages($packages);
    }

    public function getPayments()
    {
        $payments = Payment::orderBy('id', 'desc')->paginate(15);
        
        return view('dashboard.payments.index')->withPayments($payments);
    }

    public function getPaymentsSearch($search)
    {
        $payments = Payment::where('trx_id', 'LIKE', "%$search%")->orWhereHas('User', function($q) use ($search){
                        $q->where('name', 'like', '%' . $search . '%');
                        $q->orWhere('mobile', 'like', '%' . $search . '%');
                        $q->orWhere('amount', 'like', '%' . $search . '%');
                        $q->orWhere('store_amount', 'like', '%' . $search . '%');
                        $q->orWhere('trx_id', 'like', '%' . $search . '%');
                    })->paginate(15);

        return view('dashboard.payments.index')->withPayments($payments);
    }

    public function getExamsToday()
    {
        $examstoday = Meritlist::whereDate('created_at', Carbon::today())->paginate(15);
        
        return view('dashboard.exams.examstoday')->withExamstoday($examstoday);
    }

    public function storePackage(Request $request)
    {
        $this->validate($request,array(
            'name'                    => 'required|string|max:191',
            'tagline'                 => 'required|string|max:191',
            'duration'                => 'required|string|max:191',
            'numeric_duration'        => 'required|integer',
            'price'                   => 'required|integer',
            'strike_price'            => 'required|integer',
            'status'                  => 'required',
            'suggested'               => 'required',
            'image'                   => 'required',
        ));

        $package = new Package;
        $package->name = $request->name;
        $package->tagline = $request->tagline;
        $package->duration = $request->duration;
        $package->numeric_duration = $request->numeric_duration;
        $package->price = $request->price;
        $package->strike_price = $request->strike_price;
        $package->status = $request->status;
        $package->suggested = $request->suggested;
        $package->image = $request->image;
        $package->save();

        Session::flash('success', 'Package added successfully!');
        return redirect()->route('dashboard.packages');
    }

    public function updatePackage(Request $request, $id)
    {
        $this->validate($request,array(
            'name'                    => 'required|string|max:191',
            'tagline'                 => 'required|string|max:191',
            'duration'                => 'required|string|max:191',
            'numeric_duration'        => 'required|integer',
            'price'                   => 'required|integer',
            'strike_price'            => 'required|integer',
            'status'                  => 'required',
            'suggested'               => 'required',
            'image'                   => 'required',
        ));

        $package = Package::findOrFail($id);
        $package->name = $request->name;
        $package->tagline = $request->tagline;
        $package->duration = $request->duration;
        $package->numeric_duration = $request->numeric_duration;
        $package->price = $request->price;
        $package->strike_price = $request->strike_price;
        $package->status = $request->status;
        $package->suggested = $request->suggested;
        $package->image = $request->image;
        $package->save();

        Session::flash('success', 'Package updated successfully!');
        return redirect()->route('dashboard.packages');
    }

    public function deletePackage($id)
    {
        $package = Package::find($id);
        $package->delete();

        Session::flash('success', 'Package deleted successfully!');
        return redirect()->route('dashboard.packages');
    }

    // public function getMessages()
    // {
    //     $messages = Message::orderBy('id', 'desc')->paginate(12);

    //     return view('dashboard.messages.index')->withMessages($messages);
    // }

    public function getMessages()
    {
        // Eager loading added to prevent N+1 query issue
        $messages = Message::with(['user', 'user.payments'])
                            ->orderBy('id', 'desc')
                            ->paginate(12);

        // Summary counts for dashboard stat cards
        $totalMessages = Message::count();
        $pendingMessages = Message::where('status', 0)->count();
        $resolvedMessages = Message::where('status', 1)->count();

        return view('dashboard.messages.index', compact('messages', 'totalMessages', 'pendingMessages', 'resolvedMessages'));
    }

    public function updateMessage(Request $request, $id)
    {
        $message = Message::find($id);
        $message->status = 1;
        $message->save();

        Session::flash('success', 'Message updated successfully!');
        return redirect()->route('dashboard.messages');
    }

    public function deleteMessage($id)
    {
        $message = Message::find($id);
        $message->delete();

        Session::flash('success', 'Message deleted successfully!');
        return redirect()->route('dashboard.messages');
    }

    public function sendSingleNotification(Request $request, $id)
    {
        $user = User::find($id);
        if($user->onesignal_id !=null) {
            OneSignal::sendNotificationToUser(
                $request->message,
                $user->onesignal_id,
                $url = null, 
                $data = null,
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );  

            Session::flash('success', 'Notification sent successfully!');
            return redirect()->route('dashboard.users');
        } else {
            Session::flash('warning', 'OneSignal ID নেই');
            return redirect()->route('dashboard.users');
        }
    }

    public function sendSingleSMS(Request $request, $id)
    {
        $this->validate($request,array(
            'message'           => 'required',
        ));

        $user = User::find($id);

        // send sms
        $mobile_number = 0;
        if(strlen($user->mobile) == 11) {
            $mobile_number = $user->mobile;
        } elseif(strlen($user->mobile) > 11) {
            if (strpos($user->mobile, '+') !== false) {
                $mobile_number = substr($user->mobile, -11);
            }
        }

        // $url = config('sms.url');
        // $number = $mobile_number;
        // $text = $request->message;

        // NEW PANEL
        $url = config('sms.url2');
        $api_key = config('sms.api_key');
        $senderid = config('sms.senderid');
        $number = $mobile_number;
        $message = $request->message;

        $data = [
            "api_key" => $api_key,
            "senderid" => $senderid,
            "number" => $number,
            "message" => $message,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $jsonresponse = json_decode($response);

        if($jsonresponse->response_code == 202) {
            Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        } elseif($jsonresponse->response_code == 1007) {
            Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
        } else {
            Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
        }
        // NEW PANEL
        
        // $data= array(
        //     'username'=>config('sms.username'),
        //     'password'=>config('sms.password'),
        //     'number'=>"$number",
        //     'message'=>"$text",
        // );

        // // initialize send status
        // $ch = curl_init(); // Initialize cURL
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is important
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus = $p[0];
        // // dd($smsresult);
        // // send sms
        // if($sendstatus == 1101) {
        //     Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
        // } elseif($sendstatus == 1006) {
        //     Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
        // } else {
        //     Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
        // }

        return redirect()->back();
    }

    public function getNotifications()
    {
        $notifications = Notification::orderBy('id', 'desc')->paginate(12);

        return view('dashboard.notifications.index')->withNotifications($notifications);
    }

    public function sendNotification(Request $request)
    {
        $this->validate($request,array(
            'type'         => 'required',
            'headings'     => 'required',
            'message'      => 'required',
        ));

        if($request->type == 'premium') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'equal', 'value' => 'Premium']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'free') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'not_exists']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'all') {
            OneSignal::sendNotificationToAll(
                $request->message,
                $url = null, 
                $data = null, 
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'update') {
            // LIVE HOILE ETA DEOA HOBE
            // LIVE HOILE ETA DEOA HOBE
            OneSignal::sendNotificationToAll(
                $request->message,
                $url = null, 
                $data = array("a" => 'update'),
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );

            // OneSignal::sendNotificationToUser(
            //     $request->message,
            //     ['716ffeb3-f6c2-4a4a-a253-710f339aa863'],
            //     $url = null, 
            //     $data = array("a" => 'update'),
            //     $buttons = null, 
            //     $schedule = null,
            //     $headings = $request->headings,
            // );
        }

        $notification = new Notification;
        $notification->type = $request->type;
        $notification->headings = $request->headings;
        $notification->message = $request->message;
        $notification->save();

        Session::flash('success', 'নোটিফিকেশন সফলভাবে পাঠানো হয়েছে!');
        return redirect()->route('dashboard.notifications');
    }

    public function deleteNotification($id)
    {
        $notification = Notification::find($id);
        $notification->delete();

        Session::flash('success', 'Notification deleted successfully!');
        return redirect()->route('dashboard.notifications');
    }

    public function sendAgainNotification(Request $request)
    {
        $this->validate($request,array(
            'type'         => 'required',
            'headings'     => 'required',
            'message'      => 'required',
        ));

        if($request->type == 'premium') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'equal', 'value' => 'Premium']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'free') {
            OneSignal::sendNotificationUsingTags(
                $request->message,
                array(['field' => 'tag', 'key' => 'user_type', 'relation' => 'not_exists']),
                $url = null,
                $data = null,
                $buttons = null,
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'all') {
            OneSignal::sendNotificationToAll(
                $request->message,
                $url = null, 
                $data = null, 
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        } elseif($request->type == 'update') {
            // LIVE HOILE ETA DEOA HOBE
            // LIVE HOILE ETA DEOA HOBE
            // OneSignal::sendNotificationToAll(
            //     $request->message,
            //     $url = null, 
            //     $data = array("a" => 'update'),
            //     $buttons = null, 
            //     $schedule = null,
            //     $headings = $request->headings,
            // );
            
            OneSignal::sendNotificationToUser(
                $request->message,
                ['716ffeb3-f6c2-4a4a-a253-710f339aa863'],
                $url = null, 
                $data = array("a" => 'update'),
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        }

        $notification = new Notification;
        $notification->type = $request->type;
        $notification->headings = $request->headings;
        $notification->message = $request->message;
        $notification->save();

        Session::flash('success', 'নোটিফিকেশন সফলভাবে পাঠানো হয়েছে!');
        return redirect()->route('dashboard.notifications');
    }

    // test html question data
    // public function getExamSolvePDF($softtoken, $examid)
    // {
    //     if($softtoken == env('SOFT_TOKEN'))
    //     {
    //         $exam = Exam::findOrFail($examid);

    //         return view('index.pdf.test')->withExam($exam);

    //         $pdf = PDF::loadView('index.pdf.examsolvepdf', ['exam' => $exam]);
    //         $fileName = 'Single-Exam-Solve-Sheet-' . $exam->id . '.pdf';
    //         return $pdf->stream($fileName); // download/stream
    //     } else {
    //         return response()->json([
    //             'success' => false
    //         ]);
    //     }
    // }


    public function getBlogs()
    {
        $totalblogs = Blog::count();
        $blogs = Blog::orderBy('id', 'desc')->paginate(10);
        $blogcategories = Blogcategory::orderBy('id', 'asc')->get();

        // dd($questions);
        return view('dashboard.blogs.index')
                    ->withBlogs($blogs)
                    ->withBlogcategories($blogcategories)
                    ->withTotalblogs($totalblogs);
    }

    public function getBlogsSearch($search)
    {
        
        $totalblogs = Blog::where('title', 'LIKE', "%$search%")->count();
        $blogs = Blog::where('title', 'LIKE', "%$search%")
                             ->orWhere('body', 'LIKE', "%$search%")
                             ->orWhere('slug', 'LIKE', "%$search%")
                             ->orderBy('id', 'desc')
                             ->paginate(10);
        $blogcategories = Blogcategory::orderBy('id', 'asc')->get();

        Session::flash('success', $totalblogs . ' টি ব্লগ পাওয়া গিয়েছে!');
        return view('dashboard.blogs.index')
                    ->withBlogs($blogs)
                    ->withBlogcategories($blogcategories)
                    ->withTotalblogs($totalblogs);
    }

    

    public function storeBlog(Request $request)
    {
        $this->validate($request,array(
            'title'          => 'required|max:255|unique:blogs,title',
            'slug'           => 'sometimes|max:255|unique:blogs,slug',
            'body'           => 'required',
            'blogcategory_id'    => 'required|integer',
            'featured_image'  => 'sometimes|image|max:800',
            'keywords' => 'sometimes',
            'description' => 'sometimes',
        ));

        //store to DB
        $blog              = new Blog();
        $blog->title       = $request->title;
        $blog->user_id     = Auth::user()->id;
        // dd($request->slug);
        if(isset($request->slug)) {
            $blog->slug        = str_replace(['?',':', '\\', '/', '*', ' '], '-', strtolower($request->slug));
        } else {
            $blog->slug        = str_replace(['?',':', '\\', '/', '*', ' '], '-', strtolower($request->title)) . '-' .time();
        }
        $blog->blogcategory_id = $request->blogcategory_id;
        $blog->body        = Purifier::clean($request->body, 'youtube');

        $blog->keywords = $request->keywords;
        $blog->description = $request->description;

        
        // image upload
        if($request->hasFile('featured_image')) {
            $image      = $request->file('featured_image');
            $filename   = str_replace(['?',':', '\\', '/', '*', ' '], '_',$request->slug).time() .'.' . "webp";
            $location   = public_path('images/blogs/'. $filename);
            // Image::make($image)->resize(600, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            Image::make($image)->fit(800, 420)->save($location);
            $blog->featured_image = $filename;
        }

        $blog->save();

        Session::flash('success', 'Blog created successfully!');
        return redirect()->route('dashboard.blogs');
    }

    public function updateBlog(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $this->validate($request,array(
            'title'          => 'required|max:255',
            'slug'           => 'required|max:255|unique:blogs,slug,'.$blog->id,
            'body'           => 'required',
            'blogcategory_id'    => 'required|integer',
            'featured_image' => 'sometimes|image|max:800',
            'keywords' => 'sometimes',
            'description' => 'sometimes',
        ));

        //update to DB
        $blog->title       = $request->title;
        // $blog->user_id     = Auth::user()->id;
        if($blog->slug == $request->slug) {

        } else {
            $blog->slug        = str_replace(['?',':', '\\', '/', '*', ' '], '-', strtolower($request->slug));
        }
        $blog->blogcategory_id = $request->blogcategory_id;
        // $blog->body        = Purifier::clean($request->body, 'youtube');
        $blog->body        = $request->body;

        $blog->keywords = $request->keywords;
        $blog->description = $request->description;
        
        // image upload
        if($request->hasFile('featured_image')) {
            $image_path = public_path('images/blogs/'. $blog->featured_image);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $image      = $request->file('featured_image');
            $filename   = str_replace(['?',':', '\\', '/', '*', ' '], '-', strtolower($request->slug)) . '-' .time() . '.' . "webp";
            $location   = public_path('images/blogs/'. $filename);
            // Image::make($image)->resize(600, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            Image::make($image)->fit(800, 420)->save($location);
            $blog->featured_image = $filename;
        }

        $blog->save();

        Session::flash('success', 'Article updated successfully!');
        return redirect()->route('dashboard.blogs');
    }

    public function deleteBlog($id)
    {
        $blog = Blog::findOrFail($id);

        $image_path = public_path('images/blogs/'. $blog->featured_image);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
        $blog->delete();

        Session::flash('success', 'Deleted Successfully!');
        return redirect()->route('dashboard.blogs');
    }

    public function storeBlogCategory(Request $request)
    {
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
        ));

        $blogcategory = new Blogcategory;
        $blogcategory->name = $request->name;
        $blogcategory->save();

        Session::flash('success', 'Blog Category created successfully!');
        return redirect()->route('dashboard.blogs');
    }

    public function updateBlogCategory(Request $request, $id)
    {
        $this->validate($request,array(
            'name' => 'required|string|max:191',
        ));

        $blogcategory = Blogcategory::find($id);;
        $blogcategory->name = $request->name;
        $blogcategory->save();

        Session::flash('success', 'Blog Category updated successfully!');
        return redirect()->route('dashboard.blogs');
    }

    public function generateCodes()
    {
        // ১. শুধুমাত্র এডমিন এটি রান করতে পারবে (নিরাপত্তার জন্য)
        if (!(auth()->user()->role == 'admin')) {
            abort(403);
        }

        // ২. টাইম লিমিট তুলে দেওয়া যাতে ১৭ হাজার ডাটা প্রসেস হতে সময় পায়
        set_time_limit(0); 

        $count = 0;

        // ৩. চাস্কিং (Chunking) পদ্ধতিতে প্রসেস করা
        User::whereNull('referral_code')->chunk(300, function ($users) use (&$count) {
            foreach ($users as $user) {
                $user->referral_code = $this->makeUniqueCode();
                $user->save();
                $count++;
            }
        });

        return "সফলভাবে $count জন ইউজারের জন্য ইউনিক কোড জেনারেট করা হয়েছে!";
    }

    /**
     * ইউনিক কোড তৈরির প্রাইভেট ফাংশন
     */
    private function makeUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (User::where('referral_code', $code)->exists());
        return $code;
    }


    

    public function getComponents()
    {
        return view('components');
    }

    // clear configs, routes and serve
    public function clear()
    {
        Artisan::call('route:clear');
        // Artisan::call('optimize');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        // Artisan::call('key:generate');
        Artisan::call('config:clear');
        Session::flush();
        return 'Config and Route Cached. All Cache Cleared';
    }
}
