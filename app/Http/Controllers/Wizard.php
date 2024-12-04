<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/*custom uses */
use App, Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class Wizard extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('verified');
    }
    public function wizard(){
        $uid = auth()->user()->id;
        $countries = App\Countries::all();
        $num_emp_items = json_encode(array(
            ['text'=>'It\'s only me', 'value'=>'micro', 'id'=>'micro'], 
            ['text'=>'2-9 Employees', 'value'=>'small', 'id'=>'small'],
            ['text'=>'10-99 Employees', 'value'=>'medium', 'id'=>'medium'],
            ['text'=>'More than 100', 'value'=>'big', 'id'=>'big']
        ));
        
        $categories = App\Categories::all();
        $currencies = App\Currency::all();
        



        $usertype = (session()->has('usertype')) ? session('usertype') : App\User::getUsertype();
        
        if($usertype=='bj'):
            $this->addUserField('profile_role',$usertype,$uid);
            return view('account/bj-wizard', compact('countries','num_emp_items','categories','currencies'));
        else:
            $this->addUserField('profile_role',$usertype,$uid);
            //return view('pj-wizard');
            return redirect('account/profile');
        endif;
    }
    public function saveStep1(Request $request){
        $uid = auth()->user()->id;
        
        $cname = ($request->name != null) ? $request->name:'';
        $company = App\Companies::create([
            'name' => $cname,
            'user_id' => $uid
        ]);
        
        session(['company_id' => $company->id]);

        return $company;
    }
    public function saveStep2(Request $request){
        $companyID = session('company_id');

        $fields = array('size','address','city','state','country','currency');
        foreach($fields as $field):
            $this->addField($field,$request->input($field),$companyID);
        endforeach;

        return json_encode(array("success"=>'true'));
    }
    public function addField($fieldKey,$value,$uid){
        $cf = new App\CompanyFields;

        $cf->company_id = $uid;
        $cf->meta_key = $fieldKey;
        $cf->meta_value = $value;
        try {
            $cf->save();
            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
    public function addUserField($fieldKey,$value,$uid){
        $cf = new App\User_meta;

        $cf->user_id = $uid;
        $cf->meta_key = $fieldKey;
        $cf->meta_value = $value;
        try {
            $cf->save();
            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
}
