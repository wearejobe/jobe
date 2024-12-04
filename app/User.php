<?php

namespace App;

use App;
use App\Http\Controllers\AccountController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use Billable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function uploads(){
        return $this->hasMany(Upload::class);
    }
    public static function getUserTimeZone(){
        $user = auth()->user();
        if($user):
            $timezone = App\User_meta::where('meta_key','timezone')->where('user_id',$user->id)->first();

            if($timezone):
                return $timezone->meta_value;
            else:
                return 'America/El_Salvador';
            endif;
        else:
            return 'America/El_Salvador';
        endif;
    }
    public static function getUserCurrency(){
        $accountController = new AccountController;
        if(Auth::check()):
            $uf = $accountController->get_user_metas();
            if($uf):
                if($uf->profile_role == 'pj'):
                    $currencyID = (isset($uf->profile_currency)) ? $uf->profile_currency:'1';
                else:
                    $company_fiels = $accountController->get_company_metas();
                    $currencyID = (property_exists($company_fiels,'currency')) ? $company_fiels->currency:'USD $';
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
        else:
            return 'USD $';
        endif;
    }
    public static function getUsertype(){
        
        $user = auth()->user();
        if($user): 
            $user_type_row = App\User_meta::where('user_id', $user->id)->where('meta_key', 'profile_role')->first();
            if($user_type_row):
                return $user_type_row->meta_value;
            else:
                return redirect()->route('user.selection');
            endif;
        else:
            return 'guest';
        endif;
    }
    public static function checkUnread(){
        $user = auth()->user();
        $number = count($user->unreadNotifications);
        return ($number==0) ? '':$number;
    }
    public static function getNotifications(){
        
        $user = auth()->user();
        
        if($user): 
            return $user->notifications;
        else:
            return null;
        endif;
    }
    public static function checkUserProfile(){
        $user = auth()->user();
        if($user):
            $fieldstocheck = ['profile_pj_category','profile_category','timezone','profile_lastname','profile_profession','profile_country_code','profile_prof_des'];
            
            $n_fields = App\User_meta::where('user_id',$user->id)->whereIn('meta_key',$fieldstocheck)->count();

            if($n_fields==count($fieldstocheck)):
                return true;
            else:
                return false;
            endif;
        else:
            return true;
        endif;
    }
    public static function getAvatar($userid,$cls = ''){
        $uf = App\User_meta::where('meta_key','avatar')->where('user_id',$userid)->first();
        if( $uf ):
            $imgFile = App\Upload::getAvatarFile($uf->meta_value); 
            $imgElement = '<img src="'. $imgFile . '" class="rounded-circle custom-avatar img-fluid '.$cls.'">';
        else:
            $imgElement = '<img src="' . asset('images/default-avatar.svg') . '" class="rounded-circle default-avatar img-fluid '.$cls.'">';
        endif;

        return $imgElement;
    }
}
