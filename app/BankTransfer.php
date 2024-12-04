<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    //
    protected $fillable = ['user_id','t_number','t_name','t_date','payment_id','status','checked_by','checked_at'];
}
