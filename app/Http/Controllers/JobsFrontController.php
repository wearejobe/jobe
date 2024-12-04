<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App, Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AccountController;
class JobsFrontController extends Controller
{
    //
    public function viewJob($id,$slug){
        $controller = new Controller;
        $job = App\Jobs::find($id);
        $ismyjob = $this->checkJobOwner($id);
        if($job->status == 'published' || $ismyjob):
            $job_fields = DB::table('job_fields')->where('job_id',$id)->get();
            $company = App\Companies::find($job->company_id);
            $company_fields = DB::table('company_fields')->where('company_id',$company->id)->get();
            $job_count = App\Jobs::where('company_id', $job->company_id)->count();
            $category = App\Categories::find($job->category);

            $job_updated = $controller->humanTiming($job->updated_at);
            $member_since = $controller->humanTiming($company->created_at);

            
            
            foreach($company_fields as $fr):
                if($fr->meta_key=='country'):
                    $country = App\Countries::find($fr->meta_value);
                    $company_info[$fr->meta_key] = $country;
                elseif($fr->meta_key=='currency'):
                    $currency = App\Currency::find($fr->meta_value);
                    $company_info[$fr->meta_key] = $currency;
                else:
                    $company_info[$fr->meta_key] = $fr->meta_value;
                endif;
            endforeach;

            $project_type_tags = array('type','duration','location');
            $project_types = []; $job_info = [];
            $jobFF = array('payment_plan','budget_type');
            foreach($job_fields as $fr):
                if(in_array($fr->meta_key,$project_type_tags)):
                    $project_types[] = $this->getFormOption($fr->meta_value);
                elseif(in_array($fr->meta_key,$jobFF)):
                    $job_info[$fr->meta_key] = $this->getFormOption($fr->meta_value);
                else:
                    $job_info[$fr->meta_key] = $fr->meta_value;
                endif;
            endforeach;

            $j_info = json_encode($job_info);
            $c_info = json_encode($company_info);

            if(isset($job_info['skills_source'])):
                $job_skills_ids = explode(',',$job_info['skills_source']);
                $skills = App\Skills::find($job_skills_ids);
            else:
                $skills = null;
            endif;
            $usertype = App\User::getUsertype();
            if ($usertype) {
                $usertype = $usertype;
            }else{
                $usertype = 'guest';
            }
            $source = compact(
                'j_info',
                'job',
                'company',
                'c_info',
                'job_count',
                'category',
                'job_updated',
                'member_since',
                'ismyjob',
                'skills',
                'project_types',
                'usertype'
            );
            return view('account/job',$source);
            
        else:
            return abort(404);
        endif;
    }
    public function saveJob($id){
        $this->middleware('auth');
        if(Auth::check()):
            $uid = auth()->user()->id;
            $count = DB::table('user_metas')->where('user_id',$uid)->where('meta_key','favorite-job')->where('meta_value',$id)->count();
            if($count < 1):
                $account_controller = new AccountController;
                $account_controller->addField('favorite-job',$id,$uid);
            endif;
            
            return back()->with('alert','Job added to saved jobs.');
        else:
            return redirect('login');
        endif;
    }
    public function checkJobOwner($job_id){
        if(Auth::check()):
            $uid = auth()->user()->id;
            $job = App\Jobs::find($job_id);
            if($uid == $job->created_by):
                return true;
            else:
                //check user has access if hired
                $user = auth()->user();
                $hired = App\Hirings::where('applicant_id',$user->id)->where('job_id',$job_id)->where('status','accepted')->first();
                if($hired):
                    return true;
                else:
                    return false;
                endif;
            endif;
        else:

            return false;
        endif;
    }
    public function jobs(){
        return view('jobs');
    }
    public function jobFeed(Request $request){
        $filter = false;
        $job_s = [];
        $foreing_key_options = ['type','duration','location','budget_type','payment_plan'];
        if($request):
            $jobs_source = App\Jobs::getJobs($request);
            $filter = true;
        else:
            $jobs_source = App\Jobs::getJobs();
        endif;
        
        foreach($jobs_source->all() as $job_row):
            if($job_row):
                if($job_row->status == 'published'):
                    $job_fields = App\JobFields::where('job_id',$job_row->id)->get();
                    
                    foreach($job_fields as $field):
                        if(in_array($field->meta_key,$foreing_key_options)): 
                            $job_row->{$field->meta_key} = App\FormOptions::getOptionValue($field->meta_value);
                        else:
                            $job_row->{$field->meta_key} = $field->meta_value;
                        endif;
                    endforeach;
                    $job_s[] = $job_row;
                endif;
            endif;
        endforeach;

        $jobs = (object) $job_s;

        $source = compact('jobs');
        if(isset($request->filter)):
            return response()->json($source);
        else:
            return view('job-feed',$source);
        endif;
    }
    public function getFormOption($optionID){
        $type = App\FormOptions::find($optionID);
        
        return $type;
    }
}
