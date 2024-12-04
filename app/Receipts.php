<?php

namespace App;
use App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipts extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['title','description','subtotal','variations','total','user_id','status'];
    public static function newReceipt($receipt_data){
        //title,description,amount
        if(isset($receipt_data->userid)):
            //si viene el id del usuario
            $userid = $receipt_data->userid;
        else:
            $user = auth()->user();
            $userid = $user->id;
        endif;
        $serviceFee = floatval($receipt_data->amount) * 0.129;
        $variations = ['service_fee'=>number_format($serviceFee,2)];
        $receipt = Self::create([
            'title' => $receipt_data->title,
            'description'=>$receipt_data->description,
            'subtotal'=>$receipt_data->amount,
            'variations'=>json_encode($variations),
            'total' => number_format(floatval($serviceFee) + floatval($receipt_data->amount),2),
            'user_id' => $userid,
            'status' => 'pending'
        ]);

        return $receipt;
    }
}
