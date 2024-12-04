<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    //
    public static function getCategory($catID){
        $category = Self::find($catID);
        if($category):
            return $category;
        else:
            return null;
        endif;
    }
}
