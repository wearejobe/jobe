<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function __construct(){
        
    }
    public function get_month_names(){
        
        $months = array(
                '01'=>'January',
                '02'=>'February',
                '03'=>'March',
                '04'=>'April', 
                '05'=>'May', 
                '06'=>'June',
                '07'=>'Jule',
                '08'=>'August',
                '09'=>'September',
                '10'=>'October',
                '11'=>'November',
                '12'=>'December');

        $m = json_decode(json_encode($months));

        return $m;

    }
    public function get_birth_years(){
        $current_year = date('Y');
        $min_year = intval($current_year) - 80;
        $max_year = intval($current_year) - 18;
        $years = array();
        for($i=$min_year;$i<=$max_year;$i++):
            $years[] = $i;
        endfor;

        $ys = json_decode(json_encode(array_reverse($years)));

        return $ys;
    }
    function humanTiming ($given_time){
        $time = strtotime($given_time);
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }
}
