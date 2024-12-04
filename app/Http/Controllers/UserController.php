<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UserController extends Controller
{
        use AuthenticatesUsers;

        /**
         * Where to redirect users after login.
         *
         * @var string
         */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {  
        $this->middleware('guest')->except('logout');
    }
    public function welcome(Request $request){
        if(isset($request->usertype)){
                session(['usertype' => $request->usertype]);
                return redirect('register');
        }else{
                return view('welcome');
        }
    }
    public function start(){
            return view('start');
    }
}
