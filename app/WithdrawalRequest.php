<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    //
    protected $fillable = [ 
        'request_from',
        'amount',
        'account_number',
        'account_bank',
        'account_name',
        'status'];
    public static function withdrawalRequest($request_data){
        $new_request = Self::create([
            'request_from' => $request_data->request_from,
            'amount' => $request_data->amount,
            'account_number' => $request_data->account_number,
            'account_bank' => $request_data->account_bank,
            'account_name' => $request_data->account_name,
            'status' => 'requested'
        ]);

        return $new_request;
    }
}
