<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyFields extends Model
{
    //
    protected $fillable = ['company_id','meta_key','meta_value'];
}
