<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobFields extends Model
{
    //
    protected $fillable = ['job_id','meta_key','meta_value'];
    public static function getJobEvents($jobID){
        $events = Self::where('job_id',$jobID)->where('meta_key','event')->get();
        if($events):
            $evts = [];
            foreach($events as $event):
                $event_item = json_decode($event->meta_value);
                $event_item->id = $event->id;
                $evts[] = $event_item;
            endforeach;

            return $evts;
        else:
            return null;
        endif;
    }
}
