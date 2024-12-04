<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/* mailing */
use App\Mail\Welcome;
use App\Mail\NewJob;
use App\Mail\NewInvoice;


/* Notifications */
use App\Notifications\TaskDone;
use App\Notifications\HiringRequest;
use App\Notifications\JobApplied;
use App\Notifications\NewApplication;
use App\Notifications\HiringResponse;
use App\Notifications\NewPaymentReceived;
use App\Notifications\NewWorkingShift;
use App\Notifications\CategoryChange;
use App\Notifications\BriefDone;
use App\Notifications\RelationDone;
use App\Notifications\MeetingDone;
use App\Notifications\DeliverablesDone;
use App\Notifications\JobFinished;


class Mailing extends Controller
{
    //
    public static function sendWelcome($user){
        Mail::to($user)->send(new Welcome($user));
    }
    public static function jobCompleted($user, $jid){
        $bj = $user;
        $jbStatus = App\JobFields::firstOrCreate([
            'job_id'=>$jid, 'meta_key'=>'job_stage', 'meta_value'=>'deliverables'
        ]);
        $bj->notify(new JobFinished($bj,$jid));
    }
    public static function deliverablesDone($uid, $jid){
        $bj = App\User::find($uid);
        $jbStatus = App\JobFields::firstOrCreate([
            'job_id'=>$jid, 'meta_key'=>'job_stage', 'meta_value'=>'deliverables'
        ]);
        $bj->notify(new DelivarablesDone($bj,$jid));
    }
    public static function meetingDone($uid, $jid){
        $bj = App\User::find($uid);
        $jbStatus = App\JobFields::firstOrCreate([
            'job_id'=>$jid, 'meta_key'=>'job_stage', 'meta_value'=>'explore-meeting'
        ]);
        $bj->notify(new MeetingDone($bj,$jid));
    }
    public static function relationDone($uid, $jid){
        $bj = App\User::find($uid);
        $jbStatus = App\JobFields::firstOrCreate([
            'job_id'=>$jid, 'meta_key'=>'job_stage', 'meta_value'=>'relation'
        ]);
        $bj->notify(new RelationDone($bj,$jid));
    }
    public static function briefDone($uid, $jid){
        $bj = App\User::find($uid);

        $bj->notify((new BriefDone($bj,$jid)));

    }
    public static function categoryChange($pjID, $pjcategory){
        $user = App\User::find($pjID);
        
        $user->notify(new CategoryChange($user,$pjcategory));
    }
    public static function sendJobApplied($application){
        $user = auth()->user();
        $job = App\Jobs::find($application->job_id);
        
        /* Mail::to($user)->send(new JobApplied($job,$job->company_id)); */
        $user->notify(new JobApplied($job,$job->company_id));
    }
    public static function newApplication($application){
        
        $job = App\Jobs::find($application->job_id);
        $bjUser = App\User::find($job->created_by);
        
        //Mail::to($bjUser)->send(new NewApplication($job,$bjUser,$application));
        $bjUser->notify(new NewApplication($job,$bjUser,$application));
    }
    public static function newInvoice($invoice){
        
        $company = App\Companies::where('user_id',$invoice->user_id)->first();
        $bjUser = App\User::find($invoice->user_id);
        
        Mail::to($bjUser)->send(new NewInvoice($bjUser,$company));
    }
    public static function newJob($job){
        $user = auth()->user();
        
        $jbStatus = App\JobFields::firstOrCreate([
            'job_id'=>$job->id, 'meta_key'=>'job_stage', 'meta_value'=>'brief'
        ]);
        

        Mail::to($user)->send(new NewJob($job));
    }
    public static function taskDone($taskID){
        $pj = auth()->user();
        $task = App\Tasks::find($taskID);
        $job = App\Jobs::find($task->job_id);
        $bj = App\User::find($job->created_by);
        
        /* Mail::to($bj)->send(new TaskDone($task,$pj,$bj,$job)); */
        $bj->notify(new TaskDone($task,$pj,$bj,$job));

    }
    public static function hiringRequest($hiring,$application){
        $pj = App\User::find($hiring->applicant_id);
        
        $pj->notify(new HiringRequest($hiring,$pj,$application));
    }
    public static function hiringResponse($hiring){
        $bj = App\User::find($hiring->created_by);

        $jbStatus = App\JobFields::firstOrCreate([
            'job_id'=>$hiring->job_id, 'meta_key'=>'job_stage', 'meta_value'=>'relation'
        ]);
        $meeting_data = array(
            'title'=>'Explore Meeting',
            'description'=>'A project/job exploration stage where new ideas or objetives can be explained.',
            'date' => now(),
            'type' => 'explore-meeting',
            'status' => 'pending'
        );
        $meeting = App\JobFields::firstOrCreate([
            'job_id'=>$hiring->job_id, 'meta_key'=>'event', 'meta_value'=> json_encode($meeting_data)
        ]);
        
        $bj->notify(new HiringResponse($hiring,$bj));
    }
    public static function newPaymentReceived($payment){
        $pj = App\User::find($payment->to);
        
        $pj->notify(new NewPaymentReceived($payment,$pj));
    }
    public static function newWorkingShift($taskID,$interval){
        $task = App\Tasks::find($taskID);
        $job = App\Jobs ::find($task->job_id);
        $bj = App\User::find($job->created_by);
        
        $bj->notify(new NewWorkingShift($task,$job,$bj,$interval));
    }
    
}
