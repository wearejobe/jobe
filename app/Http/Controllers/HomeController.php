<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*custom uses */
use App, Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usertype = $this->getUsertype();
        session(['usertype' => $usertype]);
        if($usertype=='bj'):
            return redirect('account/jobs');
        else:
            return redirect('job-feed');
        endif;
        
    }
    function getUsertype(){
        $usertype = auth()->user()->getUsertype();

        

        return $usertype;
    }
    function humanDate($date){
        $dt = new Carbon($date);

        return $dt->toFormattedDateString();
    }
    public static function humanDateTime($date){
        $dt = new Carbon($date);
        
        return $dt->toFormattedDateString();
    }
    function humanTime($date){
        $dt = new Carbon($date);
        
        return $dt->toTimeString(); 
    }
}
