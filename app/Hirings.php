<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class Hirings extends Model
{
    //
    protected $fillable = ['applicant_id','job_id','created_by','status','description','files'];
    public static function canHire($applicant_id,$job_id){
        $hiringCount = Self::where('applicant_id',$applicant_id)->where('job_id',$job_id)->count();
        if($hiringCount > 0):
            return false;
        else: 
            return true;
        endif;
    }
}
