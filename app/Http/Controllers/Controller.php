<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Message;

use View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() 
    {
      // etake cache korte hobe sathe sathe reported question gular cound dhukaite hobe
      $unresolvedmessagecount = Message::where('status', 0)->count();
      View::share('unresolvedmessagecount', $unresolvedmessagecount);
    }
}
