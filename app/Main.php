<?php

namespace App;
use App;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Main extends Model
{
    //
    public static function alertClass(){
        if(session('alert-success')!=null) $alertClass = 'alert-success';
        if(session('alert-danger')!=null) $alertClass = 'alert-danger';
        if(session('alert-warning')!=null) $alertClass = 'alert-warning';
       
        return $alertClass;
    }
    public static function alertIcon(){
        if(session('alert-success')!=null) $alertIcon =  'check';
        if(session('alert-danger')!=null) $alertIcon = 'close';
        if(session('alert-warning')!=null) $alertIcon = 'warning';

        return $alertIcon;
    }
    public static function alertMessage(){
        
        if(session('alert-success')!=null) $alertMessage = session('alert-success');
        if(session('alert-danger')!=null) $alertMessage = session('alert-danger');
        if(session('alert-warning')!=null) $alertMessage = session('alert-warning');

        return $alertMessage;
    }
    public static function localize($dateField = null)
    {
        $timezone = App\User::getUserTimeZone();
        $date = ($dateField) ? $dateField : new Carbon();
        return Carbon::parse($date)->timezone($timezone);
    }
    public static function localizeAndHuman($dateField = null)
    {
        $timezone = App\User::getUserTimeZone();
        $date = ($dateField) ? $dateField : new Carbon();
        return Self::humanDateTime(Carbon::parse($date)->timezone($timezone));
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
    public static function diffDays($start,$end = null){
        $start = Carbon::parse($start);
        $finish = ($end) ? $end:Carbon::now();

        return intval($start->diffInDays($finish));
    }
    public static function checkPastDate($deadline){
        $deadline_date = Carbon::parse($deadline);
        $today = Carbon::now();

        return $today->greaterThan($deadline_date);
    }
}
