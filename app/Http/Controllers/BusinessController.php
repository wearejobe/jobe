<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*custom uses */
use App, Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Wizard;


class BusinessController extends Controller
{
    //
    public function saveBusiness(Request $request){
        $user = auth()->user();
        if($user):
            $company = App\Companies::where('user_id',$user->id)->where('id',$request->cid)->first();
            if($company):
                $wizard = new Wizard;
                //update name
                $nameUpdated = App\Companies::where('id',$company->id)->update(['name'=>$request->name]);
                //update the rest of fields
                $fields = array('address','city','state','country','currency');
                foreach($fields as $field):
                    $wizard->addField($field,$request->input($field),$company->id);
                endforeach;

                return redirect()->route('profile')->with('alert-success','Profile saved!');    
            else:
                return redirect()->route('profile')->with('alert-danger','Company not found.');    
            endif;
        else:
            return redirect()->route('profile')->with('alert-danger','Session expired!');
        endif;
    }
}
