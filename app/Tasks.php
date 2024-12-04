<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Tasks extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['created_by','deliverable_id','job_id','minutes','color','title','description','person_id','status','deadline'];
    
    public static function TaskColors(){
        return $status_colors = array(
            'pending' => 'warning',
            'working' => 'info',
            'paused' => 'warning',
            'done' => 'success'
        );
    }
    public static function addTask($title,$deliverable,$description,$jobID,$deadline){
        $user = auth()->user();

        $task = Tasks::create([
            'created_by' => $user->id,
            'job_id' => $jobID,
            'deliverable_id' => $deliverable,
            'color' => '#FF5722',
            'title' => $title,
            'description' => $description,
            'person_id' => $user->id,
            'status' => 'pending',
            'deadline' => $deadline
        ]);
        
        return $task;
    }
    public static function getTasks($jobID){
        $user = auth()->user();

        $tasks = Self::where('job_id',$jobID)->orderBy('id','desc')->get();

        return $tasks;
    }
    public static function getTaskStatusOptions(){
        $options = DB::table('form_options')->where('key','task_status')->get();

        return $options;
    }
    public static function calcDeliPercentage($deliID){
        $tasksCount = Self::where('deliverable_id',$deliID)->where('deleted_at',null)->count();
        $tasksDoneCount = Self::where('deliverable_id',$deliID)->where('status','done')->where('deleted_at',null)->count();

        if($tasksCount > 0):
            $x = ($tasksDoneCount * 100) / $tasksCount;
        else:
            $x = 0;
        endif;
        
        return $x;
    }
}
