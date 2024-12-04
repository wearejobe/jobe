<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

/* custom uses */
use App, Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    //
    
    public function __construct()
    {
        $this->middleware('verified');
    }
    protected function messages(){
        return view('account/messages');
    }
    protected function notifications(){
        $usertype = App\User::getUsertype();
        $nots = []; 
        $user_notifications = App\User::getNotifications();
        foreach($user_notifications as $noti):
            $noti->fields = (object) $noti->data;
            $nots[] = $noti;
        endforeach;
        $n = (object) $nots;
        
        $source = compact('usertype','n');
        return view('account/notifications',$source);
    }
    public function getAvatar($userid){
        $avatar_url = DB::table('user_metas')->where('user_id',$userid)->where('meta_key','profile_avatar')->value('meta_value');
        if($avatar_url):
            return $avatar_url;
        else:
            return asset('images/default-avatar.svg');
        endif;
    }
    function getFile($file_id){
        $file_info = App\Upload::find($file_id);
        $file_info->link = md5($file_id) . '_' . $file_id;
        return $file_info;
    }
    protected function contractView($md5_id){
        $homeController = new HomeController;
        $uid = auth()->user()->id;
        $id = Str::afterlast($md5_id,'_');
        $usertype = $homeController->getUsertype();
        $contract = App\Applications::find($id);
        if($contract):
            
            $pj_categories = App\PjCategories::all();
            $job = App\Jobs::find($contract->job_id);
            if($contract->applicant_id == $uid || $job->created_by == $uid):
                $applicant = DB::table('users')->where('id',$contract->applicant_id)->first();
                $applicant_fields = DB::table('user_metas')->where('user_id',$contract->applicant_id)->get();
                $files_array = [];
                if($contract->files):
                    $objectFiles = json_decode($contract->files);
                    foreach($objectFiles as $file):
                        $files_array[] = $this->getFile($file);
                    endforeach;
                endif;

                $temp_uf['avatar_url'] = $this->getAvatar($contract->applicant_id);
                foreach($applicant_fields as $field):
                    if($field->meta_key == 'profile_country'):
                        //country
                        $country = App\Countries::find($field->meta_value);
                        if($country):
                            $temp_uf[$field->meta_key] = $country->name;
                        endif;
                    else:
                        $temp_uf[$field->meta_key] = $field->meta_value;
                    endif;
                    
                endforeach;
                $lastname = (isset($temp_uf['profile_lastname'])) ? $temp_uf['profile_lastname']:'';
                $temp_uf['fullname'] = $applicant->name . ' ' . $lastname;
                $temp_uf['pj_category'] = (array_key_exists('profile_pj_category', $temp_uf)) ? App\PjCategories::find($temp_uf['profile_pj_category']):App\PjCategories::find(1);
                $controller = new Controller;
                $app_created = $controller->humanTiming($contract->created_at);
                $applicant->created_at = $controller->humanTiming($applicant->created_at);
                
                $contract->sent = $app_created;
                $files = (object) $files_array;
                $pj = (object) $temp_uf;
                

                $cards = DB::table('jobe_cards')->where('item_id',$id)
                                ->where('card_type','application_'.$usertype)
                                ->where('status','published')->orderBy('id', 'desc')->get();
                

                $source = compact('contract','applicant','pj','job','files','pj_categories','usertype','cards');

                return view('account/contract-view',$source);
            else:
                return abort(404);
            endif;
        endif;
    }       
        
    protected function contracts(){
        $homeController = new HomeController;
        $usertype = $homeController->getUsertype();
        $bj = $this->get_bj();
        
        $contracts_source = DB::table('jobs')
                            ->join('applications','jobs.id','=','applications.job_id')
                            ->join('users','applications.applicant_id','=','users.id')
                            ->where('jobs.company_id',$bj->id)
                            ->select('applications.*','users.name','jobs.title')
                            ->get();
        $controller = new Controller;
        $contracts = [];
        foreach($contracts_source->all() as $contract):
            $contract->created_at = $controller->humanTiming($contract->created_at);
            $contract->description = Str::words($contract->description,15,'...');
            $contract->md5_id = md5($contract->id) . '_' . $contract->id;
            $contracts[] = $contract;
        endforeach;
        
        $source = compact('contracts','usertype');
        
        

        return view('account/contracts', $source);
    }
    protected function newAccount(){
        $uid = auth()->user()->id;

        $accountNumber = $this->getNewAccountNumber();

        if($this->checkNumber($accountNumber)){
            $acc = $this->saveNewAccount($accountNumber);
        }else{
            //error_log('No se pudo crear cuenta para: '. $uid);
        }

        return $acc;
    }
    protected function saveNewAccount($accountNumber){
        $uid = auth()->user()->id;
        $hash = hash('sha256',auth()->user()->email);
        //$ua = App\UserAccounts::create(['number' =>  $accountNumber, 'hash'=> $hash, 'amount'=> '0.00' ]);
        $ua = new App\UserAccounts;
        $ua->number = $accountNumber;
        $ua->hash = $hash;
        $ua->amount = '0.00';
        $newaccount = $ua->save();
        
        $newaccount = DB::table('user_accounts')->where('number', $accountNumber)->where('hash',$hash)->first();
        
        $ula = new App\UserLinkAccounts;
        $ula->uid = $uid;
        $ula->aid = $newaccount->id;
        $ula->save();

        return $accountNumber;
    }
    protected function checkNumber($num){
        $account_data = DB::table('user_accounts')->where('number', $num)->first();
        if($account_data):
            return false; //existe, no puede seguir
        else:
            return true; //no existe, puede proseguir
        endif;
    }
    protected function getNewAccountNumber(){
        $uid = auth()->user()->id;
        $numbers = '';
        for($i=0;$i<=14;$i++):
            if($i==0) {
                $numbers .= rand(4,9);
            }else{
                $numbers .= rand(1,9);
            }
            
        endfor;

        return $numbers;
    }
    public function getTheAccount($userID = null){
        $user = ($userID == null) ? auth()->user():App\User::find($userID);
        //check if user have account
        $linkAccount = App\UserLinkAccounts::where('uid', $user->id)->first();
        if($linkAccount):
            //devolver la cuenta
            $hash = hash('sha256',$user->email);
            $account = App\UserAccounts::where('id',$linkAccount->aid)->where('hash',$hash)->first();
            $jobe_account_n = $account->number;
            $jobe_account_amount = $account->amount;
        else:
            //intentar crear la cuenta
            $jobe_account_n = $this->newAccount();
            $jobe_account_amount = '0.00';
        endif;
        $n_format = $this->separate($jobe_account_n);
        $account_data = array('n'=>$n_format, 'a'=>$jobe_account_amount);

        return json_encode($account_data);
    }
    public function separate($n){
        $numbers = str_split($n,4);
        $return = '';
        foreach($numbers as $num_block):
            $return .= '<span class="num_block">' . $num_block . '</span>';
        endforeach;

        return $return;
    }
    public function getUserCurrency(){
        $uf = $this->get_user_metas();
        if($uf):
            if($uf->profile_role == 'pj'):
                $currencyID = (isset($uf->profile_currency)) ? $uf->profile_currency:'1';
            else:
                $company_fiels = $this->get_company_metas();
                $currencyID = $company_fiels->currency;
            endif;
            $c = App\Currency::find($currencyID);
            if($c):
                return $c->symbol;
            else:
                return 'USD $';
            endif;
        else:
            return 'USD $';
        endif;
    }
    public function profile(){
        $homeController = new HomeController;
        $countries = App\Countries::all();
        $currencies = App\Currency::all();
        $languages = App\Language::all();
        $categories = App\Categories::all();
        $uf = $this->get_user_metas();
        
        $role = ($uf != null) ? $uf->profile_role:$homeController->getUsertype();;
        if($role == 'pj'):
            $controller = new Controller;
            $months = $controller->get_month_names();
            $years = $controller->get_birth_years();

            if(isset($uf->profile_skill_source)):
                $user_skills_ids = explode(',',$uf->profile_skill_source);
                $u_skills = array();
                foreach($user_skills_ids as $skID):
                    if($skID!=null):
                        $skillData = App\Skills::find($skID);
                        $uSkills[] = $skillData;
                    endif;
                endforeach;
                $skills = json_encode($uSkills);
            else:
                $skills = null;
            endif;
            $jobeac = $this->getTheAccount();
            $c = $this->getUserCurrency();
            $user = auth()->user();
            
            $rating = ($user) ? App\Ratings::getUserRating($user->id):null;
            return view('account/profile', compact('countries','currencies','languages','uf','months','years','categories','skills','jobeac','c','rating'));
        else:
            $bj = $this->get_bj();
            if($bj):
                $bj_fields = $this->get_bj_fields($bj->id);
                return view('account/bprofile', compact('countries','currencies','languages','uf','bj','bj_fields'));
            else:
                return redirect('wizard');
            endif;
        endif;
    }
    public function get_bj(){
        $uid = auth()->user()->id;

        $business = DB::table('companies')->where('user_id', $uid)->first();

        return $business;
    }
    public function get_bj_fields($bjID){
        $uid = auth()->user()->id;

        $bj_fields_rows = DB::table('company_fields')->where('company_id', $bjID)->get();

        $temp_bj = array();

        foreach($bj_fields_rows as $field):
            $temp_bj[$field->meta_key] = $field->meta_value;
        endforeach;

        $bj_fields = json_decode(json_encode($temp_bj));

        return $bj_fields;
    }
    public function save(Request $request){
        $uid = auth()->user()->id;
       
        

        foreach($request->all() as $key=>$value):
            $fieldKey='profile_'.$key;
            if($key!='_token' && $key!='name' && $key!='return_url' && $value!=null):
                if($this->checkField($fieldKey)):
                    $this->addField($fieldKey,$value,$uid);
                else:
                    $this->updateField($fieldKey,$value,$uid);
                endif;
            elseif($key=='name'):
                $user = App\User::find($uid);
                $user->name = $value;
                $user->save();
            endif;
        endforeach;

        

        //return back()->with('alert', 'Profile Saved');
        return back()->with('alert-success', 'Profile Saved')->with('open_tab', $request->return_url);
    }
    public function addField($fieldKey,$value,$uid){
        $uf = new App\User_meta;

        $uf->user_id = $uid;
        $uf->meta_key = $fieldKey;
        $uf->meta_value = $value;
        try {
            $uf->save();
            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
    public function updateField($fieldKey,$value,$uid){
        
        $user_field = DB::table('user_metas')->where('user_id', $uid)->where('meta_key', $fieldKey)->first();
        
        $uf = App\User_meta::find($user_field->id);
        $uf->meta_value = $value;

        try {
            $uf->save();
            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
    public function checkField($fieldKey){
        $uid = auth()->user()->id;
        $user_field = DB::table('user_metas')->where('user_id', $uid)->where('meta_key', $fieldKey)->first();

        if($user_field == null):
            $insert = true;
        else:
            $insert = false;
        endif;

        return $insert;
    }
    public function get_user_metas(){
        $uid = auth()->user()->id;

        $user_fields = DB::table('user_metas')->where('user_id', $uid)->get();

        $temp_uf = array();

        foreach($user_fields as $field):
            $temp_uf[$field->meta_key] = $field->meta_value;
        endforeach;

        $uf = json_decode(json_encode($temp_uf));

        return $uf;
    }
    public function get_company_metas(){
        $uid = auth()->user()->id;
        $company = $this->get_bj();
        if($company):
            $fields = DB::table('company_fields')->where('company_id', $company->id)->get();

            $fieldsArray = array();

            foreach($fields as $field):
                $fieldsArray[$field->meta_key] = $field->meta_value;
            endforeach;

            $fieldsOBJ = (object) $fieldsArray;

            return $fieldsOBJ;
        else:
            return redirect()->route('wizard');
        endif;
    }
    public function userSelection(){
        return view('welcome');
    }
}
