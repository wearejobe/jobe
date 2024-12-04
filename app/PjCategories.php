<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PjCategories extends Model
{
    //
    public static function hourly_range($hourly_wage){
        $rangeValues = [];
        $rangeValues = json_decode($hourly_wage);
        
        $min = (is_array($rangeValues) && count($rangeValues)>0) ? number_format($rangeValues[0],2):'-'; 
        
        $max = (is_array($rangeValues) && count($rangeValues)>1) ? number_format($rangeValues[1],2):null;

        $values = ["min"=>$min,"max"=>$max];

        return (object) $values;
    }
    public static function getCategory($catID){
        $category = Self::find($catID);
        if($category):
            return $category;
        else:
            return null;
        endif;
    }
}
