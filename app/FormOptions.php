<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormOptions extends Model
{
    //
    public static function getOptionValue($id){
        $option = Self::find($id);

        if($option):
            return $option->heading;
        else:
            return 'not_found';
        endif;
    }
    public static function getSvBanks(){
        $banks = Self::where('key','sv-bank')->get();

        return $banks;
    }
    public static function getRatingFields(){
        $rating_fields = Self::where('key','rating_field')->get();

        return $rating_fields;
    }
}
