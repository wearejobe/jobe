<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    //
    protected $fillable = ['title','company_id','category','status','created_by','slug'];
    
    public static function getJobs($request){
        $filter_keys = ['description','end_user','target','objetive','type','duration','skills_source','location','location_detail','budget_type','payment_plan','hourly_wage'];
        if(isset($request->filter)):
            $jobs_query = Self::join('job_fields','jobs.id','=','job_fields.job_id')
                        ->where('jobs.status','published');
            if(isset($request->k)):
                $jobs_query->where('jobs.title','LIKE','%'.$request->search_key.'%');
            endif;
            
            foreach($request->all() as $key=>$value):
                if(in_array($key,$filter_keys)):
                    if($key=='skills_source'):
                        $jobs_query->where('meta_key', $key);
                        $jobs_query->where('meta_value','LIKE', '%'. $value .'%');
                    else:
                        $jobs_query->where('meta_key', $key);
                        $jobs_query->where('meta_value','LIKE', '%'. $value .'%');
                    endif;
                endif;
            endforeach;
        
            $jobs = $jobs_query->select('jobs.*')->get();
        else:
            $jobs = Self::join('categories','categories.id','=','jobs.category')
                          ->join('companies','companies.id','=','jobs.company_id')
                            ->where('status','published')
                            ->orderBy('jobs.updated_at','desc')
                            ->select('jobs.*', 'categories.name as catname', 'companies.name as company_name')->get();
        endif;

        return $jobs;
    }
    public static function checkJobApplied($jobID){
        $user = auth()->user();
        if($user):
            $application = App\Applications::where('applicant_id',$user->id)->where('job_id',$jobID)->count();
            if($application>0):
                return true;
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }
}
