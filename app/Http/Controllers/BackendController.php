<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* custom uses */
use App, Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class BackendController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('admin');
        
    }
    public function translations(){
        App::setLocale('en');
        return redirect('languages/?lg=en');
    }
    public function showUsers(){
        $users_rows = App\User::join('user_metas','user_metas.user_id','=','users.id')
                            ->where('user_metas.meta_key','profile_role')
                            ->select('users.*','user_metas.meta_value as Role')->get();
        $users_array = [];
        foreach($users_rows as $user):
            $user_cat = App\User_meta::where('meta_key','profile_category')->where('user_id',$user->id)->first();
            if($user_cat){
                //$category_id = $user_cat->meta_value;
                $user->category_id = $user_cat->meta_value;
            }else{
                $user->category_id = 0;
            }
            $users_array[] = $user;
        endforeach;
        $users = (object) $users_array;
        $source = compact('users');
        return view('backend.users',$source);
    }
    public function showWithdrawalRequests(){
        $requests = App\WithdrawalRequest::join('users','users.id','=','withdrawal_requests.request_from')
                                            ->join('form_options','form_options.id','withdrawal_requests.account_bank')
                            ->select('withdrawal_requests.*','form_options.heading as BankName','users.email as email','users.id as user_id')->get();
        foreach($requests as $request):
            $AccountControler = new AccountController;
            $jobeac = $AccountControler->getTheAccount($request->user_id);
            $wallet = json_decode($jobeac);
            $request->balance = 'USD $'.number_format($wallet->a,2) ;
        endforeach;

        $source = compact('requests');
        return view('backend.bank-requests',$source);
    }
    public function validateRequest(Request $request){
        $user = auth()->user();
        if($user):
            /* $request = App\WithdrawalRequest::where('id',$request->requestID)
                        ->update([
                            'status'=>'validated',
                            'transaction_n'=>$request->transactionNumber,
                            'checked_at'=> now(),
                            'checked_by'=> $user->id
                            ]); */
            $WdR = App\WithdrawalRequest::find($request->requestID);
            $WdR->status = 'validated';
            $WdR->transaction_n= $request->transactionNumber;
            $WdR->checked_at= now();
            $WdR->checked_by= $user->id;
            $WdR->save();

            $paymentsController = new PaymentsController;
            $pj = App\User::find($WdR->request_from);
            $paymentsController->processWithdrawal(floatval($WdR->amount),$pj);
            return response()->json(['status'=>'success','request'=>$WdR]);
        else:
            return response()->json(['status'=>'error','msg'=>'Session Expired!']);
        endif;
    
    }
    public function denyRequest(Request $request){
        $user = auth()->user();
        if($user):
            $request = App\WithdrawalRequest::where('id',$request->requestID)
                        ->update([
                            'status'=>'denied',
                            'checked_at'=> now(),
                            'checked_by'=> $user->id
                            ]);
            

            return response()->json(['status'=>'success','request'=>$request]);
        else:
            return response()->json(['status'=>'error','msg'=>'Session Expired!']);
        endif;
    
    }
    public function validateTransfer(Request $request){
        $user = auth()->user();
        if($user):
            /* $transfer = App\BankTransfer::where('id',$request->transferID)
                        ->update([
                            'status'=>'pending',
                            'checked_at'=> now(),
                            'checked_by'=> $user->id
                            ]); */
            $transfer = App\BankTransfer::find($request->transferID);
            $transfer->status = 'validated';
            $transfer->checked_at = now();
            $transfer->checked_by = $user->id;
            $transfer->save();
            
            $payment = App\Receipts::find($transfer->payment_id);
            if($payment):
                //
                
                if($payment->status == 'pending-validation'){
                    
                    
                    $payment->status = 'paid';
                    
                    $payment->save();
                    $paymentsController = new PaymentsController;
                    $bj = App\User::find($payment->user_id);
                    $transferPayment = $paymentsController->transfer($payment,$bj);

                    if($transferPayment):
                        return response()->json(['status'=>'success','msg'=>'Payment processed!']);
                    else:
                        return response()->json(['status'=>'error','msg'=>'Payment error!']);
                    endif;
                }else{
                    return response()->json(['status'=>'error','msg'=>'This receipt is not available to pay.']);
                }


            else:
                return response()->json(['status'=>'error','msg'=>'This invoice not found.']);
            endif;

        else:
            return response()->json(['status'=>'error','msg'=>'Session Expired!']);
        endif;
    
    }
    public function showIncommingTransfers(){
        $transfers = App\BankTransfer::join('users','users.id','=','bank_transfers.user_id')
                                    ->join('receipts','receipts.id','=','bank_transfers.payment_id')
                            ->select('bank_transfers.*','receipts.total','users.email as user_email')->get();

        $source = compact('transfers');
        return view('backend.incomming-transfers',$source);
    }

}
