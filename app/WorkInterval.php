<?php

namespace App;

use App;
use App\Http\Controllers\JobsController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class WorkInterval extends Model
{
    //
    
    use SoftDeletes;
    protected $fillable = ['job_id','worker_id','task','start','end'];

    public static function startInterval($job_id,$task_id){
        $user = auth()->user()->id;
        $now = new Carbon();

        if(!Self::checkInterval()):
            $task = App\Tasks::where('id',$task_id)->update(['status'=>'working']);
            if($task): 
                $interval = Self::create([
                    'job_id'=>$job_id,
                    'worker_id'=>$user,
                    'task'=>$task_id,
                    'start'=>$now
                ]);
            endif;
            return $interval;
        else: 
            return false;
        endif;
    }
    public static function stopInterval(){
        $int = Self::checkInterval();
        if($int): 
            $now = new Carbon();
            $intervalID = Self::where('id',$int->id)->update(['end'=>$now]);

            return $int;
        else:
            return false;
        endif;
    }
    public static function checkInterval(){
        $user = auth()->user()->id;

        $interval = Self::where('worker_id',$user)->where('start','!=','')->whereNull('end')->first();

        if($interval):
            return $interval;
        else:
            return false;
        endif;
    }
    public static function intervalByTask($taskID){
        $user = auth()->user()->id;

        $interval = Self::where('task',$taskID)->orderBy('id','desc')->first();

        if($interval):
            return $interval;
        else:
            return false;
        endif;
    }
    public static function timeCounter($start,$end = null){
        //$start = Self::localize($start);
        if($end):
            $finishTime = new Carbon($end);
        else:
            $finishTime = new Carbon();
        endif;
        
        $hours = $finishTime->diffInHours($start);
        $minutes = $finishTime->diff($start)->format('%I');
        $seconds = $finishTime->diff($start)->format('%S');

        $hours = (intval($hours)<10) ? '0'.intval($hours):$hours;
        $minutes = (intval($minutes)<10) ? '0'.intval($minutes):$minutes;
        $seconds = (intval($seconds)<10) ? '0'.intval($seconds):$seconds;
        
        //return $finishTime->diff($start)->format('%H:%I:%S');
        return $hours . ':' . $minutes . ':' . $seconds ;        
    }
    public static function totalTimeCounter($taskID){
        $jc = new JobsController;
        $work_intervals = Self::where('task',$taskID)->get();
        if($work_intervals):
            foreach($work_intervals as $interval):
                $time = Self::timeCounter($interval->start,$interval->end);
                $time = $jc->convertTimeToSecond($time);
                $floatTime = floatval($time) / 3600;
                $times[] = $floatTime;
            endforeach;
            $totalInTime = array_sum($times);
        endif;
        //return $finishTime->diff($firstTask->start)->format('%H:%I:%S');
        //return $finishTime->diffInHours($firstTask->start) . ':' . $finishTime->diff($firstTask->start)->format('%I') . ':' . $finishTime->diff($firstTask->start)->format('%S') ;
        return number_format($totalInTime,2);
    }
    public static function jobTimeCounter($jobid){
        $jc = new JobsController;
        $work_intervals = Self::where('job_id',$jobid)->get();
        
        if($work_intervals):
            $times = [];
            foreach($work_intervals as $interval):
                $time = Self::timeCounter($interval->start,$interval->end);
                $time = $jc->convertTimeToSecond($time);
                
                $times[] = $time;
            endforeach;
            $totalTimeinSeconds = array_sum($times);
        endif;
        $hours = floor($totalTimeinSeconds / 3600);
        $minutes = floor(($totalTimeinSeconds / 60) % 60);
        $seconds = $totalTimeinSeconds % 60;

        $hours = (intval($hours)<10) ? '0'.intval($hours):$hours;
        $minutes = (intval($minutes)<10) ? '0'.intval($minutes):$minutes;
        $seconds = (intval($seconds)<10) ? '0'.intval($seconds):$seconds;

        return $hours.':'.$minutes.':'.$seconds;
    }
    public static function taskTimeCounter($taskID){
        $jc = new JobsController;
        $work_intervals = Self::where('task',$taskID)->get();
        if($work_intervals):
            foreach($work_intervals as $interval):
                $time = Self::timeCounter($interval->start,$interval->end);
                $time = $jc->convertTimeToSecond($time);
                
                $times[] = $time;
            endforeach;
            $totalTimeinSeconds = array_sum($times);
        endif;
        $hours = floor($totalTimeinSeconds / 3600);
        $minutes = floor(($totalTimeinSeconds / 60) % 60);
        $seconds = $totalTimeinSeconds % 60;

        $hours = (intval($hours)<10) ? '0'.intval($hours):$hours;
        $minutes = (intval($minutes)<10) ? '0'.intval($minutes):$minutes;
        $seconds = (intval($seconds)<10) ? '0'.intval($seconds):$seconds;

        return $hours.':'.$minutes.':'.$seconds;
    }
    public static function weekTimeCounter($jobID){
        $jc = new JobsController;
        //SELECT * from work_intervals where created_at BETWEEN date_add(now(), interval  -WEEKDAY(now())-1 day) and now()
        $work_intervals = Self::where('job_id',$jobID)->whereRaw('created_at BETWEEN date_add(now(), interval  -WEEKDAY(now())-1 day) and now()')->get();
        $times=[];
        if($work_intervals):
            foreach($work_intervals as $interval):
                $time = Self::timeCounter($interval->start,$interval->end);
                $time = $jc->convertTimeToSecond($time);
                
                $times[] = $time;
            endforeach;
            $totalTimeinSeconds = array_sum($times);
        endif;
        $hours = floor($totalTimeinSeconds / 3600);
        $minutes = floor(($totalTimeinSeconds / 60) % 60);
        $seconds = $totalTimeinSeconds % 60;

        $hours = (intval($hours)<10) ? '0'.intval($hours):$hours;
        $minutes = (intval($minutes)<10) ? '0'.intval($minutes):$minutes;
        $seconds = (intval($seconds)<10) ? '0'.intval($seconds):$seconds;

        return $hours.':'.$minutes.':'.$seconds;
    }
    public static function localize($dateField = null)
    {
        $timezone = App\User::getUserTimeZone();
        $date = ($dateField) ? $dateField : new Carbon();
        return Carbon::parse($date)->timezone($timezone);
    }
    public static function humanDate($date){
        //$dt = new Carbon($date);
        $dt = Self::localize($date);
        
        return $dt->toFormattedDateString();
    }
    public static function humanDateTime($date){
        //$dt = new Carbon($date);
        $dt = Self::localize($date);
        
        return $dt->toDayDateTimeString();
    }
    public static function humanTime($date){
        //$dt = new Carbon($date);
        $dt = Self::localize($date);
        
        return $dt->toTimeString(); 
    }
    
    
}
