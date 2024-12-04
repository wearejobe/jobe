<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App, Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Mailing;
use App\Http\Controllers\JobsFrontController;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('verified');
    }
    /* WORKER FUNCTIONS */
    public function pj_payments($code){
        $uid = auth()->user()->id;
        $jobID = Str::afterlast($code,'_'); //jobID
        $job = DB::table('hirings')
            ->join('users','users.id','=','hirings.applicant_id')
            ->join('jobs','jobs.id','=','hirings.job_id')
            ->join('companies','jobs.company_id','=','companies.id')
            ->where('jobs.id',$jobID)
            ->select('jobs.*')->first();
        $homeController = new HomeController;
        $usertype = $homeController->getUsertype();
        if($job):

            $job->md5_id = md5($jobID) . '_' . $jobID;
            
            $payments = App\JobPayments::join('receipts','receipts.id','=','job_payments.receipt_id')
                                        ->where('job_payments.to',$uid)
                                        ->where('job_payments.job_id',$jobID)
                                        ->where('job_payments.status','paid')
                                        ->select('receipts.*')->orderBy('receipts.updated_at','desc')->get();
            
            $source = compact('job','usertype','payments');

            return view('account/payments/pj-payments',$source);
        else:
            return abort(404);
        endif;
        
    }
    public function apply(Request $request){
        $uid = auth()->user()->id;
        $jobID = $request->id;
        $description = $request->description;
        
        $files = (isset($request->uploadedFiles)) ? json_encode($request->uploadedFiles):'';

        $application = new App\Applications;
        $application->description = $description;
        $application->applicant_id = $uid;
        $application->files = $files;
        $application->job_id = $jobID;

        if($application->save()):
            try {
                //code...
                $mailing = new Mailing();
                $mailing->sendJobApplied($application);
                $mailing->newApplication($application);
            } catch (\Throwable $th) {
                error_log($th);
            }
            return response()->json([
                'success' => 'true',
                'confirm' => md5($application->id)
            ]);
        else:
            return response()->json([
                'success' => 'false'
            ]);
        endif;
    }
    public function pjJobs(){
        $controller = new Controller;
        $homeController = new HomeController;
        $uid = auth()->user()->id;
        $usertype = $homeController->getUsertype();
        $application_rows = DB::table('applications')
                                ->join('jobs','jobs.id','=','applications.job_id')
                                ->join('companies','companies.id','=','jobs.company_id')
                                ->where('applications.applicant_id',$uid)
                                ->select('applications.*','jobs.title','jobs.status','jobs.slug','companies.name as CompanyName')->get();
        $applications = [];
        if($application_rows):
            foreach($application_rows as $app):
                $app->created_ht = $controller->humanTiming($app->created_at);
                $app->md5_id = md5($app->id) . '_' . $app->id;
                $app->md5_job_id = md5($app->job_id) . '_' . $app->job_id;
                
                $app->userHired = ($app->status == 'hired' || $app->status == 'finished' ) ? $this->checkUserHired($app->job_id):false;
                $applications[] = $app;
            endforeach;
        endif;
        

        $source = compact('applications','usertype');
        return view('account/pj-jobs',$source);
    }
    public function checkUserHired($jobID){
        $controller = new Controller;
        $uid = auth()->user()->id;
        $hiring = DB::table('hirings')->where('applicant_id',$uid)->where('job_id',$jobID)->first();
        if($hiring):
            $hiring->created_ht = $controller->humanTiming($hiring->created_at);
        endif;

        return $hiring;
    }
    public function getHire(Request $request){
        $AccountControler = new AccountController;
        $uid = auth()->user()->id;
        $id = Str::afterlast($request->id,'_');
        $hiring = DB::table('hirings')->where('id',$id)->where('applicant_id',$uid)->first();
        if($hiring):
            $job = App\Jobs::find($hiring->job_id);
            $company = App\Companies::find($job->company_id);
            $username = DB::table('users')->where('id',$hiring->created_by)->value('name');
            $files = [];
            if($hiring->files):
                $objectFiles = json_decode($hiring->files);
                if($objectFiles):
                    foreach($objectFiles as $file):
                        $file = $AccountControler->getFile($file);
                        $file->link = route('download',['code'=>md5($file->id) . '_'.$file->id]);
                        $files[] = $file;
                    endforeach;
                endif;  
            endif;  
            $hiringCode = md5($hiring->id) . '_' . $hiring->id;
            $frmAction = route('acceptHire',['code'=>$hiringCode]);
            $source = array('status'=>true,'hiring'=>$hiring, 'frmAction'=>$frmAction, 'job'=> $job, 'company'=> $company, 'company_contact'=> $username,'attachments'=>$files);
        else:
            $source = array('status'=>false,'msg'=>'Hiring not found!');
        endif;

        return response()->json($source);
    }
    public function acceptHire($code){
        $uid = auth()->user()->id;
        $id = Str::afterlast($code,'_');

        $hiring = DB::table('hirings')->where('id',$id)->where('applicant_id',$uid)->first();
        if($hiring):
            App\Hirings::where('id', $hiring->id)->update(['status' => 'accepted']);
            App\Jobs::where('id', $hiring->job_id)->update(['status' => 'hired']);
            try {
                //code...
                $mailing = new Mailing;
                $mailing->hiringResponse($hiring);
            } catch (\Throwable $th) {
                //throw $th;
                error_log($th);
            }
            return back()->with('alert','You accepted this hiring.');
        else:
            return back()->with('alert','Hiring not found!');
        endif;
    }
    /* SHARED FUNCTIONS */
    public function dashboard($code){
        $jobID = Str::afterlast($code,'_'); //jobID
        $job = DB::table('hirings')
            ->join('users','users.id','=','hirings.applicant_id')
            ->join('jobs','jobs.id','=','hirings.job_id')
            ->join('companies','jobs.company_id','=','companies.id')
            ->where('jobs.id',$jobID)
            ->select('jobs.*', 'users.name as worker_name', 'users.id as worker_id')->first();
        $homeController = new HomeController;
        $usertype = $homeController->getUsertype();
        if($job):

            $job->md5_id = md5($jobID) . '_' . $jobID;
            
            $tasksNumber = App\Tasks::where('job_id',$jobID)->count();
            $currentTask = App\Tasks::where('status','working')->where('job_id',$jobID)->first();
            
            $stages = App\FormOptions::where('associated','job-stage')->orderBy('id','asc')->get();//default stages
            $j_stages = App\JobFields::where('meta_key','job_stage')->where('job_id',$jobID)->get();//current job stages progress
            $j_deliverables = App\JobFields::where('meta_key','deliverable')->where('job_id',$jobID)->get();
            
            $job_stages = $j_stages->toArray();

            //job events
            $events = App\JobFields::getJobEvents($jobID);

            

            $source = compact('job','tasksNumber','currentTask','usertype','stages','job_stages','events','j_deliverables');

            return view('account/job-dashboard',$source);
        else:
            return abort(404);
        endif;
    }
    public function timeSheet($code){
        $jobID = Str::afterlast($code,'_');
        $user = auth()->user();
        $homeController = new HomeController;
        $usertype = $homeController->getUsertype();
        $job = $this->jobByHiring($jobID,$user->id,$usertype);

        if($job):
            $job->md5_id = md5($jobID) . '_' . $jobID;
            

            //get work intervals
            $work_intervals = App\WorkInterval::join('tasks','work_intervals.task','=','tasks.id')
                                                ->where('work_intervals.job_id',$jobID)
                                                ->where('work_intervals.end','!=',null)
                                                ->select('work_intervals.*','tasks.title as task_title', 'tasks.color as task_color')->get();

            $source = compact('job','usertype','work_intervals');
            return view('account/job-time-sheet',$source);
        else:
            return abort(404);
        endif;
    }
    public function jobByHiring($jobID,$userid,$usertype){
        if($usertype=='pj'):
            $job = DB::table('jobs')
                        ->join('hirings','hirings.job_id','=','jobs.id')
                        ->join('users','hirings.applicant_id','=','users.id')
                        ->where('jobs.id',$jobID)
                        ->where('hirings.applicant_id',$userid)
                        ->select('jobs.*', 'users.name as worker_name', 'users.id as worker_id')->first();
        else:
            $job = DB::table('jobs')
                        ->join('hirings','hirings.job_id','=','jobs.id')
                        ->join('users','hirings.applicant_id','=','users.id')
                        ->where('jobs.id',$jobID)
                        ->where('hirings.created_by',$userid)
                        ->select('jobs.*', 'users.name as worker_name', 'users.id as worker_id')->first();
        endif;
        
        return $job;
    }
    public function tasks($code){
        $jobID = Str::afterlast($code,'_');
        $controller = new Controller;
        
        $homeController = new HomeController;
        $user = auth()->user();
        $statusColors = App\Tasks::TaskColors();
        $job_deliverables = App\JobFields::where('job_id',$jobID)->where('meta_key','deliverable')->get();
        $usertype = $homeController->getUsertype();
        $job = $this->jobByHiring($jobID,$user->id,$usertype);

        $tasks_source = App\Tasks::getTasks($jobID);
        $tasks = [];
        foreach($tasks_source as $task):
            $task->status_color = $statusColors[$task->status];
            $deliverable = App\JobFields::find($task->deliverable_id);
            $task->deliverable = ($deliverable) ? (json_decode($deliverable->meta_value))->title:'';
            $task->start_time_on = ($task->start_time!='') ? $homeController->humanDateTime($task->start_time):'';
            $task->workInterval = App\WorkInterval::intervalByTask($task->id);
            $task->md5_id = md5($task->id) . '_' . $task->id;
            $tasks[] = $task;
        endforeach;

        if($job):
            $job->md5_id = md5($jobID) . '_' . $jobID;
            $source = compact('job','usertype','tasks','job_deliverables');

            return view('account/job-tasks',$source);
        else:
            return abort(404);
        endif;
    }
    public function addTask(Request $request){
        $title = $request->title;
        $deliverable = $request->deliverable;
        $description = $request->description;
        $deadline = $request->deadline;
        $jid = $request->jid;

        $newNote = App\Tasks::addTask($title,$deliverable,$description,$jid,$deadline);
        
        return response()->json(['status'=>'success','nota'=>$newNote->id]);
    }
    public function stopInterval(Request $request){
        
        $interval = App\WorkInterval::stopInterval();
        
        if($interval):
            if($request->finishTask=='true'):
                $task = App\Tasks::where('id',$interval->task)->update(['status'=>'done']);
                try {
                    //code...
                    $mailing = new Mailing;
                    $mailing->taskDone($interval->task);
                } catch (\Throwable $th) {
                    error_log($th);
                }
            else:
                $task = App\Tasks::where('id',$interval->task)->update(['status'=>'paused']);
            endif;
            return response()->json(['status'=>'success','interval'=>$interval]);
        else:
            if($request->finishTask == 'true'){
                $task = App\Tasks::where('id',$request->task)->update(['status'=>'done']);
            }
            return response()->json(['status'=>'success','alert'=>'Task done']);
        endif;
    }
    public function startInterval(Request $request){

        $interval = App\WorkInterval::startInterval($request->jid,$request->tid);
        if($interval):
            try {
                $mailing = new Mailing;
                $mailing->newWorkingShift($request->tid,$interval);
            } catch (\Throwable $th) {
                error_log($th);
            }
            
            return response()->json(['status'=>'success','interval'=>$interval]);
        else:
            return response()->json(['status'=>'error','alert'=>'Can\'t start new interval, an interval is already running.']);
        endif;
    }
    public function deleteTask($code){
        $user = auth()->user();
        $taskID = Str::afterlast($code,'_');

        $deletedRows = App\Tasks::where('id', $taskID)->where('created_by', $user->id)->delete();

        return response()->json(['status'=>'success']);
    }
    public function getSkills($skills_source){
        $sk_array = explode(',',$skills_source);
        $skills_info = [];
        foreach($sk_array as $skID):
            if($skID!=''):
                $skills_info[] = App\Skills::find($skID);
            endif;
        endforeach;
        $skills = (object) $skills_info;

        return $skills;
    }
    public function get_job_metas($job_id){

        $fields = DB::table('job_fields')->where('job_id', $job_id)->get();

        $fieldsArray = array();

        foreach($fields as $field):
            $fieldsArray[$field->meta_key] = $field->meta_value;
        endforeach;

        $fieldsObject = (object) $fieldsArray;

        return $fieldsObject;
    }
    /* COMPANY FUNCTIONS */
    public function finishAndRate(Request $request,$code){
        $jobID = Str::afterlast($code,'_'); //jobID
        $job = DB::table('hirings')
            ->join('users','users.id','=','hirings.applicant_id')
            ->join('jobs','jobs.id','=','hirings.job_id')
            ->join('companies','jobs.company_id','=','companies.id')
            ->where('jobs.id',$jobID)
            ->select('jobs.*','users.name as pjName','users.id as pjID')->first();
        $homeController = new HomeController;
        $usertype = $homeController->getUsertype();
        if($job):

            App\Jobs::where('id',$jobID)->update(['status'=>'finished']);
            $user = auth()->user();
            
            $request_fields = $request->all();
            
            $rating_val = 0;
            $rds = [];
            foreach($request_fields as $key => $value){
                if(Str::startsWith($key, 'rd_')){
                    $rds[] = [Str::after($key,'rd_'),$value[0]];
                    $rating_val += intval($value[0]);
                }
            }
            $rating_value = (count($rds) > 0) ? number_format($rating_val / count($rds),1):0;

            if($rating_value > 0):
                $rating_info = (object) [
                    'from'=>$user->id,
                    'from_company'=>$job->company_id,
                    'to'=>$job->pjID,
                    'value'=>$rating_value,
                    'job_id'=>$jobID,
                    'feedback_msg'=>$request->feedback_message
                ];

                $rating = App\Ratings::newRating($rating_info);

                if($rating):
                    foreach($rds as $rd):
                        $ratingdetail = App\RatingDetail::updateOrCreate(
                            ['rating_id' => $rating->id, 'form_option_id' => $rd[0] ],
                            ['value' => $rd[1] ]
                        );
                   endforeach;
                endif; 
            else:
                $rating = null;
            endif;


            //finished job notification
            try {
                $mailing = new Mailing;
                $mailing->jobCompleted($user,$job->id);
            } catch (\Throwable $th) {
                error_log($th);
            }
            

            $job->md5_id = md5($jobID) . '_' . $jobID;
            $form = false;
            $allowFinish = true;
            $pendingIntervals = null;
            $pendingPayments = null;
            $pendingTasks = null;
            $source = compact('job','usertype','allowFinish','pendingIntervals','pendingPayments','pendingTasks','form', 'rating');

            return view('account/job-finish',$source);
        else:
            return abort(404);
        endif;
    }
    public function finish(Request $request,$code){
        $jobID = Str::afterlast($code,'_'); //jobID
        $job = DB::table('hirings')
            ->join('users','users.id','=','hirings.applicant_id')
            ->join('jobs','jobs.id','=','hirings.job_id')
            ->join('companies','jobs.company_id','=','companies.id')
            ->where('jobs.id',$jobID)
            ->select('jobs.*','users.name as pjName','users.name as worker_name', 'users.id as worker_id')->first();
        $homeController = new HomeController;
        $usertype = $homeController->getUsertype();
        if($job):

            $job->md5_id = md5($jobID) . '_' . $jobID;
            
            $pendingIntervals = App\WorkInterval::join('tasks','tasks.id','work_intervals.task')
                                                ->where('work_intervals.job_id',$job->id)
                                                ->where('work_intervals.status',null)
                                                ->select('work_intervals.*','tasks.title')
                                                ->get();
            $pendingPayments = App\JobPayments::join('receipts','receipts.id','=','job_payments.receipt_id')
                                                ->where('job_payments.job_id',$job->id)
                                                ->where('job_payments.status','pending')
                                                ->select('receipts.*')
                                                ->get();
            $pendingTasks = App\Tasks::where('job_id',$jobID)->where('status','!=','done')->get();
            $allowFinish = true;
            
            if( count($pendingIntervals->all()) > 0 || count($pendingPayments->all()) > 0 ):
                //cant finish job because of pendint items
                $allowFinish = false;
            else:
                //finish job
            endif;
            $form = true;
            $rating_fields = App\FormOptions::getRatingFields();
            $source = compact('job','usertype','allowFinish','pendingIntervals','pendingPayments','pendingTasks','form','rating_fields');

            return view('account/job-finish',$source);
        else:
            return abort(404);
        endif;
    }
    public function jobs(){
        $controller = new Controller;
        $homeController = new HomeController;
        $uid = auth()->user()->id;
        
        $usertype = $homeController->getUsertype();
        if($usertype == 'bj'):
            $cid = $this->getCompanyID($uid);
            if($cid!=0):
            $company = App\Companies::find($cid);
            $jobs_datasource = DB::table('jobs')->where('company_id',$cid)->orderBy('created_at','desc')->get();
            $job_items = array();
            foreach($jobs_datasource as $job_item):
                $job_items[] = array(
                    'id' => $job_item->id,
                    'md5_job_id' => md5($job_item->id) . '_' . $job_item->id,
                    'title' => $job_item->title,
                    'status' => $job_item->status,
                    'slug' => $job_item->slug,
                    'created_ht' => $controller->humanTiming($job_item->created_at)
                );
            endforeach;
            $jobs = json_decode(json_encode($job_items));
            $source = compact('jobs','company','usertype');
            return view('account/jobs',$source);
            
            else:
                return redirect('wizard');
            endif;
        else:
            return $this->pjJobs();
        endif;
    }
    public function frmNewJob(){
        $categories = App\Categories::all();
        $budget_types = DB::table('form_options')->where('key','budget_type')->get();
        $project_types = DB::table('form_options')->where('key','project_type')->get();
        $project_times = DB::table('form_options')->where('key','project_time')->get();
        $payment_plans = DB::table('form_options')->where('key','payment_plan')->get();
        $project_location_types = DB::table('form_options')->where('key','project_location')->get();
        $AccountControler = new AccountController;
        $currency = $AccountControler->getUserCurrency();

        $deliverables = (object) [];


        return view('account/new-job', compact('categories','budget_types','currency','project_types','project_times','project_location_types','payment_plans','deliverables'));
    }
    public function frmEditJob($jobID){
        $job = App\Jobs::find($jobID);
        $JFC = new JobsFrontController;
        $ismyjob = $JFC->checkJobOwner($jobID);
        if($ismyjob):
            $categories = App\Categories::all();
            $budget_types = DB::table('form_options')->where('key','budget_type')->get();
            $project_types = DB::table('form_options')->where('key','project_type')->get();
            $project_times = DB::table('form_options')->where('key','project_time')->get();
            $payment_plans = DB::table('form_options')->where('key','payment_plan')->get();
            $project_location_types = DB::table('form_options')->where('key','project_location')->get();
            $AccountControler = new AccountController;
            $currency = $AccountControler->getUserCurrency();
            $jobFields = $this->get_job_metas($jobID);
            $deliverables = App\JobFields::where('meta_key','deliverable')->where('job_id',$job->id)->get();
            
            $skills = (isset($jobFields->skills_source)) ? $this->getSkills($jobFields->skills_source):null;
            $pageInfo = compact('categories','budget_types','currency','job','jobFields','project_types','project_times','project_location_types','skills','payment_plans','deliverables');

            return view('account/new-job', $pageInfo);
        else:
            return abort(404);
        endif;
    }
    
    public function save(Request $request){
        $uid = auth()->user()->id;
        $cid = $this->getCompanyID($uid);
        if(isset($request->jid)):
            $job = App\Jobs::find($request->jid);
            $slug = $this->createSlug($request->job_name);
            $job->title = $request->job_name;
            $job->slug = $slug;
            $job->save(); //update
        else:
            $slug = $this->createSlug($request->job_name);
            $job = App\Jobs::create(['title'=>$request->job_name,'company_id'=>$cid,'category'=>$request->category, 'status'=> 'draft', 'created_by'=>$uid, 'slug'=> $slug]);
            //create
        endif;
        $fields = array('description','end_user','target','objetive','type','duration','skills_source','location','location_detail','budget_type','hourly_wage','payment_plan');
        
        $this->saveDeliverables($request->deliverables,$job->id);

        foreach($request->all() as $key=>$value):
            
            if(in_array($key,$fields)):
                if($this->checkField($key,$job->id) && $value!=''):
                    $value = (is_array($value)) ? json_encode($value):$value;
                    $this->addField($key,$value,$job->id);
                else:
                    if($value!=''):
                        $this->updateField($key,$value,$job->id);
                    endif;
                endif;
            endif;
        endforeach;
        return redirect()->route('viewJob',['id'=>$job->id , 'slug' => $slug ]);
    }
    public function saveDeliverables($deliverables,$jobID){
        if($deliverables):
            foreach($deliverables as $deliverable):
                $deli['title'] = Str::beforeLast($deliverable,'|');
                $deli['value'] = Str::afterLast($deliverable,'|');
                $this->addField('deliverable',json_encode($deli),$jobID);
            endforeach;
        endif;
    }
    
    public function edit(){
        
        //return view('edit-job');
    } 
    public function statusChange($id){
        $user = auth()->user(); 
        $jfc = new JobsFrontController;
        $ismine = $jfc->checkJobOwner($id);
        
        if($ismine):
            $job = App\Jobs::find($id);
            if($job->status == 'draft' || $job->status == 'unpublished'):
                //before changing to publish check if user have payment info
                if($user->stripe_id != null):
                    $job->status = 'published';
                    try {
                        $mailing = new Mailing;
                        $mailing->newJob($job);
                        //$mailing->briefDone($user->id,$job->id);
                    } catch (\Throwable $th) {
                        error_log($th);
                    }
                    
                else:
                    return redirect()->route('payment.method');
                endif;
            else:
                $job->status = 'unpublished';
            endif;
            $job->save();
            $message = 'Job status changed to: ' . $job->status;
            return back()->with('alert',$message);
        else:
            $message = 'You dont have permission to edit this job';
            return back()->with('alert',$message);
        endif;
    }
    public static function createSlug($str){
        $delimiter = '-';
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    } 
    public function addField($fieldKey,$value,$job_id){
        $jf = new App\JobFields;

        $jf->job_id = $job_id;
        $jf->meta_key = $fieldKey;
        $jf->meta_value = $value;
        try {
            $jf->save();
            return true;
        } catch (Exception $e) {
            error_log($e);
            return false;
        }
    }
    public function getCompanyID($cid){
        $company = DB::table('companies')->where('user_id', $cid)->first();
        if($company):
            return $company->id;
        else:
            return 0;
        endif;
    }
    public function updateField($fieldKey,$value,$job_id){
        
        $db_field = DB::table('job_fields')->where('job_id', $job_id)->where('meta_key', $fieldKey)->first();
        
        $field = App\JobFields::find($db_field->id);
        
        $field->meta_value = $value;
        if($field->save()){
            return true;
        } else {
            error_log($e);
            return false;
        }
    }
    public function checkField($fieldKey,$job_id){
        
        $db_field = DB::table('job_fields')->where('job_id', $job_id)->where('meta_key', $fieldKey)->first();

        if($db_field == null):
            $insert = true;
        else:
            $insert = false;
        endif;

        return $insert;
    }
    public function hire(Request $request){
        $uid = auth()->user()->id; 
        $application = App\Applications::find($request->appid);
        if($application):
            $jfc = new JobsFrontController;
            $ismine = $jfc->checkJobOwner($application->job_id);
            if($ismine):
                $job = App\Jobs::find($application->job_id);
                $company = App\Companies::find($job->company_id);
                if($company):
                    
                    
                    //if($addCard):
                        //add hire
                        $hire = App\Hirings::create([
                            'applicant_id'=>$application->applicant_id,
                            'job_id'=>$application->job_id,
                            'created_by'=>$uid,
                            'status'=>'waiting_pj',
                            'description'=>$request->hire_message,
                            'files'=>json_encode($request->uploadedFiles)
                        ]);
                                              
                        $company_name = $company->name;
                        $actions = '[{
                            "text":"View",
                            "style":"btn-dark",
                            "href":"javascript:void(0)",
                            "onclick":"vhire('.$hire->id.')"
                            }]';
                        $description = $company_name . ' is hiring you. Respond as soon as posible.';
                        $itemID = $request->appid ;
                        $type = 'application_pj' ;
                        $style = 'bg-success' ;
                        $title = 'Hire Status' ;
                        $status = 'published' ;
                        $addCard = $this->addJobeCard($itemID,$type,$style,$title,$description,$actions,$status);
                        try {
                            $mailing = new Mailing;
                            $mailing->hiringRequest($hire,$application);
                        } catch (\Throwable $th) {
                            error_log($th);
                        }

                        return response()->json(['success' => 'true']);
                    /* else:
                        return response()->json(['success' => 'false','msg' => 'error adding card']);
                    endif; */
                else:
                    return response()->json(['success' => 'false','msg' => 'Error getting company']);
                endif;
            else:
                return response()->json(['success' => 'false','msg' => 'You dont have permission to hire for this.']);
            endif;
        else:
            return response()->json(['success' => 'false','msg' => 'Application not found']);
        endif;
    }
    function calcPayroll(Request $request){
        $user = auth()->user();
        $jid = Str::afterlast($request->jid,'_');
        $job = App\Jobs::where('id',$jid)->where('created_by',$user->id)->first();
        if($job):
            //get pj id
            $job_hiring = App\Hirings::where('job_id',$job->id)->where('status','accepted')->where('created_by',$user->id)->first();
            //get pj cost
            $hourly_wage = App\JobFields::where('meta_key','hourly_wage')->where('job_id',$jid)->first();
            $payment_plan = App\JobFields::join('form_options','form_options.id','=','job_fields.meta_value')
                                            ->where('job_fields.meta_key','payment_plan')
                                            ->where('job_fields.job_id',$jid)
                                            ->select('form_options.heading')
                                            ->first();

            //get interval times
            $work_intervals = App\WorkInterval::where('work_intervals.job_id',$job->id)
                                ->where('job_id',$job->id)
                                ->where('end','!=',null)
                                ->where('status',null)
                                ->whereBetween('start',array($request->from,$request->to))
                                ->get();
            
            if($work_intervals->all()):
                foreach($work_intervals as $interval):
                    $time = App\WorkInterval::timeCounter($interval->start,$interval->end);
                    $time = $this->convertTimeToSecond($time);
                    $floatTime = floatval($time) / 3600;
                    $times[] = $floatTime;
                    //update to checked
                    $wiUpdated = App\Workinterval::where('id',$interval->id)->update(['status'=>'checked']);
                endforeach;
                $totalInTime = array_sum($times);
                $totalInDollars = number_format($totalInTime * $hourly_wage->meta_value,2);
                $receipt_data = (object) [
                    'title'=> 'PJ payment from ' . App\WorkInterval::humanDate($request->from) . ' to '. App\WorkInterval::humanDate($request->to),
                    'description'=> 'Job/Project: ' . $job->title . ' | Time: ' . number_format($totalInTime,2) . 'hrs.',
                    'amount'=> $totalInDollars
                ];
                $receipt = App\Receipts::newReceipt($receipt_data);
                if($receipt): 
                    $payment_data = (object) [
                        'to' => $job_hiring->applicant_id,
                        'from' => $job->created_by,
                        'from_company' => $job->company_id,
                        'receipt_id' => $receipt->id,
                        'job_id' => $job->id
                    ];
                    $payment = App\JobPayments::newPayment($payment_data); 
                    //return response()->json(['status'=>'success', 'payment'=>$payment, 'receipt'=>$receipt] );
                    try {
                        $mailing = new Mailing;
                        $mailing->newInvoice($receipt);
                    } catch (\Throwable $th) {
                        error_log($th);
                    }
                    
                    return redirect('account/receipts')->with('alert-success','Payment generated successfully.');
                else:
                    return back()->with('alert-error','Cant generate payment receipt.');
                endif;
            else:
                return back()->with('alert-danger','No shifts found.');
            endif;
        else:
            return abort(404);
        endif;
    }
    function convertTimeToSecond(string $time)
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }
    function addJobeCard($itemID,$type,$style,$title,$description,$actions,$status){
        $card = new App\JobeCards;
        
        $card->item_id = $itemID;
        $card->card_type = $type;
        $card->style = $style;
        $card->title = $title;
        $card->description = $description;
        $card->actions = $actions;
        $card->status = $status;

        return $card->save();
    }
   
}
