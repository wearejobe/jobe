<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App, Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Mailing;

class ApiController extends Controller
{

    public function addSkill(Request $request){
        $newskill = $request->skillName;
        $category_id = $request->catID;
        $addedSkill = App\Skills::create([
            'name' => $newskill,
            'cat_id' => $category_id
        ]);
        if($addedSkill){
            return response()->json([
                'status' => 'success',
                'skill' => $addedSkill
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'msg' => 'Error adding skill, please try later.'
            ]);
        }
    }
    public function removeDeliverable(Request $request){
        $user = auth()->user();
        if($user):
            $deli = App\JobFields::find($request->deliID);
            if($deli){
                
                $deli->delete();

                return response()->json(["status"=>"success" ]);
            }else{
                return response()->json(["status"=>"error", 'msg'=> 'Event not found']);
            }
        else:
            return response()->json(["status"=>"error", 'msg'=> 'Session expired!']);
        endif;
    }
    public function changeJobEventDate(Request $request){
        $user = auth()->user();
        if($user):
            $event = App\JobFields::where('id',$request->eid)->first();
            if($event){
                $eventData = json_decode($event->meta_value);
                $eventData->date = $request->newdate;

                $event->meta_value = json_encode($eventData);
                $event->save();

                return response()->json(["status"=>"success", 'newDate'=> App\Main::humanDate($request->newdate) ]);
            }else{
                return response()->json(["status"=>"error", 'msg'=> 'Event not found']);
            }
        else:
            return response()->json(["status"=>"error", 'msg'=> 'Session expired!']);
        endif;
    }
    public function changeJobEventStatus(Request $request){
        $user = auth()->user();
        if($user):
            $event = App\JobFields::where('id',$request->eid)->first();
            if($event){
                $eventData = json_decode($event->meta_value);
                $eventData->status = 'done';

                $event->meta_value = json_encode($eventData);
                $event->save();
                $update_meeting = false;
                if($eventData->type=='explore-meeting'){
                    $jbStatus = App\JobFields::firstOrCreate([
                        'job_id'=>$event->job_id, 'meta_key'=>'job_stage', 'meta_value'=>'explore-meeting'
                    ]);
                    $update_meeting = true;
                }

                return response()->json(["status"=>"success", 'event'=> $eventData, 'eventID'=>$request->eid, 'update_meeting'=>$update_meeting ]);
            }else{
                return response()->json(["status"=>"error", 'msg'=> 'Event not found']);
            }
        else:
            return response()->json(["status"=>"error", 'msg'=> 'Session expired!']);
        endif;
    }
    public function requestBankWithdrawal(Request $request){
        $user = auth()->user();
        if($user):
            $AccountController = new AccountController;
            $account = json_decode($AccountController->getTheAccount());
            
            if($account):
                $w_request_data = (object)[];
                $w_request_data->account_number = $request->account_number;
                $w_request_data->account_name = $request->account_name;
                $w_request_data->account_bank = $request->account_bank;
                $w_request_data->amount = $account->a;
                $w_request_data->request_from = $user->id;

                $request = App\WithdrawalRequest::withdrawalRequest($w_request_data);

                if($request):
                    return back()->with('alert-success','Request successfully sent');
                else:
                    return back()->with('alert-danger','Can\'t create a request, please try later.');
                endif;
            else:
                return back()->with('alert-danger','Account not found.');
            endif;
        else:
            return back()->with('alert-danger','Session Expired');
        endif;
    }
    public function getSkills(Request $request){
        $category_skills = DB::table('skills')->where('cat_id', $request->category)->get();
        $skills = array();
        foreach($category_skills as $sk):
            $skills[] = array('id'=>$sk->id, 'value'=> $sk->name);
        endforeach;

        $skills_source = json_encode($skills);

        return $skills_source;
    }
    public function saveTimeZone(Request $request){
        $userID = auth()->user()->id;
        $timezone = App\User_meta::firstOrCreate(
            ['meta_key' => 'timezone', 'user_id' => $userID],
            ['meta_key' => 'timezone', 'meta_value' => $request->timezone, 'user_id'=>$userID ]
        );

        return $timezone;
    }
    public function readNotification($id){
        $notification = auth()->user()->notifications()->find($id);
        if($notification) {
            $notification->markAsRead();
        }

        return response()->json(["success"=>'true']);
    }
    
    public function avatarSave(Request $request){
        $image_id = intval($request->id);
        if($image_id>0){
            $user = auth()->user();
            if($user){
                $userid = $user->id;
                $user_avatar_field = App\User_meta::updateOrCreate(
                        ['meta_key' => 'avatar', 'user_id' => $userid ],
                        ['meta_value'=>$image_id ]
                    );
                return response()->json([
                    'status' => 'success', 'avatar'=> $user_avatar_field
                ]);
            }else{
                return response()->json([
                    'status' => 'error', 'msg'=> 'Session expired! Sign in and try again.'
                ]);
            }
        }else{
            return response()->json([
                'status' => 'error', 'msg' => 'Invalid file ID'
            ]);
        }
    }
}