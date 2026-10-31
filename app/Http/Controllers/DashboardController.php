<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\File;
use App\Models\FileMovement;
use App\Models\Hearing;
use App\Models\Message;
use App\Models\Notification;

use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;
use Hash;
use Auth;
use Image;
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
        $this->middleware(['admin'])->only('getUsers', 'storeUser', 'updateUser', 'deleteUser', 'getUser', 'getMessages', 'updateMessage', 'getNotifications', 'sendSingleNotification', 'sendSingleSMS', 'getExamSolvePDF');
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Role-based Access Control (Optional, modify as needed)
        // If you only want Admins (AC Land) to see this broad dashboard:
        // if ($user->role !== 'admin') {
        //     return redirect()->route('files.my_desk'); // Redirect normal staff to their desk
        // }

        // ---------------------------------------------------------
        // 2. Summary & Stats for Top Cards
        // ---------------------------------------------------------
        
        $totalFiles = File::count();
        
        // Hearings scheduled for today
        $todaysHearings = Hearing::whereDate('hearing_date', Carbon::today())
                                 ->orWhereDate('next_hearing_date', Carbon::today())
                                 ->count();

        // Files currently sitting on the logged-in user's desk
        $myPendingFiles = File::where('current_desk_id', $user->id)->count();

        // Critical: Files transferred but NOT received by the destination user (Lost in Transit)
        $unreceivedFiles = FileMovement::where('is_received', false)->count();


        // ---------------------------------------------------------
        // 3. Data for "Pending Files by Desk" Bar Chart
        // ---------------------------------------------------------
        
        // Group files by current_desk_id and join with users table to get the names
        $deskPendingData = File::select('current_desk_id', DB::raw('count(*) as total'))
            ->groupBy('current_desk_id')
            ->with('currentDesk:id,name,designation') // Assuming relationship exists
            ->get();

        $deskNames = [];
        $deskPendingCounts = [];

        foreach ($deskPendingData as $data) {
            // Using designation or name for the chart labels
            $label = $data->currentDesk ? $data->currentDesk->designation . ' (' . $data->currentDesk->name . ')' : 'Unknown';
            $deskNames[] = $label;
            $deskPendingCounts[] = $data->total;
        }

        // Encode for JavaScript Chart.js
        $deskNamesJson = json_encode($deskNames);
        $deskPendingCountsJson = json_encode($deskPendingCounts);


        // ---------------------------------------------------------
        // 4. Data for "File Initiation Trend" Line Chart (Last 14 Days)
        // ---------------------------------------------------------
        
        $last14DaysFiles = File::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $trendDates = [];
        $trendCounts = [];

        // To ensure days with 0 files are still plotted, we generate a 14-day date range array
        $dateRange = collect();
        for ($i = 13; $i >= 0; $i--) {
            $dateRange->push(Carbon::now()->subDays($i)->format('Y-m-d'));
        }

        foreach ($dateRange as $dateStr) {
            // Format for display (e.g., '14 Oct')
            $trendDates[] = Carbon::parse($dateStr)->format('j M'); 
            
            // Find if we have data for this date, otherwise 0
            $match = $last14DaysFiles->firstWhere('date', $dateStr);
            $trendCounts[] = $match ? $match->total : 0;
        }

        // Encode for JavaScript Chart.js
        $trendDatesJson = json_encode($trendDates);
        $trendCountsJson = json_encode($trendCounts);


        // ---------------------------------------------------------
        // 5. Return View with Data
        // ---------------------------------------------------------
        
        return view('dashboard.index', [
            'totalFiles' => $totalFiles,
            'todaysHearings' => $todaysHearings,
            'myPendingFiles' => $myPendingFiles,
            'unreceivedFiles' => $unreceivedFiles,
            
            // Chart Data
            'deskNames' => $deskNamesJson,
            'deskPendingCounts' => $deskPendingCountsJson,
            'trendDates' => $trendDatesJson,
            'trendCounts' => $trendCountsJson,
        ]);
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
        $request->validate([
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile',
            'designation' => 'required|string|max:191',
            'role'        => 'required|string|in:admin,staff',
            'password'    => 'required|string|min:6|max:191',
        ]);

        User::create([
            'name'        => $request->name,
            'mobile'      => $request->mobile,
            'designation' => $request->designation,
            'role'        => $request->role,
            'password'    => Hash::make($request->password),
        ]);

        Session::flash('success', 'নতুন স্টাফ সফলভাবে তৈরি করা হয়েছে!');
        return redirect()->route('dashboard.users');
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile,' . $id,
            'designation' => 'required|string|max:191',
            'role'        => 'required|string|in:admin,staff',
            'password'    => 'nullable|string|min:6|max:191',
        ]);

        $user = User::findOrFail($id);

        $userData = [
            'name'        => $request->name,
            'mobile'      => $request->mobile,
            'designation' => $request->designation,
            'role'        => $request->role,
        ];

        // পাসওয়ার্ড ঘর ফাঁকা না থাকলে তবেই পাসওয়ার্ড আপডেট হবে
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        Session::flash('success', 'স্টাফ তথ্য সফলভাবে হালনাগাদ করা হয়েছে!');
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
