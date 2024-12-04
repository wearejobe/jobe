<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPayments extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['to','from','from_company','receipt_id','status','job_id'];
    public static function newPayment($payment_data){
        //'to''from''from_company''receipt_id''job_id'
        
        $payment = Self::create([
            'to' => $payment_data->to,
            'from' => $payment_data->from,
            'from_company' => $payment_data->from_company,
            'receipt_id' => $payment_data->receipt_id,
            'status' => 'pending',
            'job_id' => $payment_data->job_id
        ]);

        return $payment;
    }
}
