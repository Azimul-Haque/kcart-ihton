<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\File;

use Carbon\Carbon;
use DB;
use Hash;
use Auth;
use Image;
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
        return redirect()->route('public.case_list');
        // return view('index.index');
    }

    public function publicCaseList()
    {
        // শুধু 'বিবিধ মোকদ্দমা' এবং রিয়েল-টাইম গতিবিধিসহ ডাটা নিয়ে আসা
        $files = File::where('type', 'বিবিধ মোকদ্দমা')
            ->with('currentDesk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('index.case_list', compact('files'));
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

    public function getContact()
    {
        return view('index.contact');
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
