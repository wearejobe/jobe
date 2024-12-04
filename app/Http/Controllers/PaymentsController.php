<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App, Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Laravel\Cashier\Cashier;
use Illuminate\Support\Str;
use App\Http\Controllers\Mailing;


class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user = auth()->user();
        $receipts = App\Receipts::where('user_id',$user->id)->orderBy('id','desc')->get();
        $homeController = new HomeController;
        $usertype = $homeController->getUsertype();
        $PaymentMethod = $this->getCompanyPaymentMethod();
        
        $source = compact('usertype','receipts','PaymentMethod');


        return view('account/payments/receipts',$source);
    }
    public function transaction(Request $request){
        $user = auth()->user();
        $receiptID = Str::afterLast($request->pid,'_');
        $validatedData = $request->validate([
            'transaction_name' => 'required',
            'transaction_number' => 'required',
            'transaction_date' => 'required',
        ]);
        if($user){
            $transfer = App\BankTransfer::create([
                'user_id'=>$user->id,
                't_number'=> $request->transaction_number,
                't_name'=> $request->transaction_name,
                't_date'=> $request->transaction_date,
                'payment_id'=> $receiptID,
                'status'=> 'pending'
            ]);
            $payment = App\Receipts::find($receiptID);
            $payment->status = 'pending-validation';
            $payment->save();

            return back()->with('alert-success','Your transaction was successfully registered, the payment will automatically processed once transaction validation.');
        }else{
            return redirect()->route('login');
        }
    }

    public function getCompanyPaymentMethod(){
        $user = auth()->user();
        if($user){
            //get the payment
            $company = App\Companies::where('user_id',$user->id)->first();
            if($company){
                $payment = App\CompanyFields::where('company_id',$company->id)->where('meta_key','payment_method')->first();
                if($payment){
                    return $payment;
                }else{
                    return null;
                }
            }else{
                //no company found
                return null;
            }
        }else{
            return redirect()->route('login');
        }
    }
    public function pay(Request $request)
    {
        if(isset($request->pmethod) && isset($request->pid)):
            $user = auth()->user();
            $pid = Str::afterLast($request->pid,'_');
            $payment = App\Receipts::where('user_id',$user->id)->where('id',$pid)->first();

            if($payment):
                //
                if($payment->status == 'pending'){
                    $total = floatval($payment->total) * 100;
                    $response = $user->invoiceFor($payment->title, $total);

                    $receipt = $response->invoice;
                    
                    $payment->status = 'paid';
                    $payment->stripe_receipt = $response->id;
                    
                    $payment->save();

                    $transferPayment = $this->transfer($payment);

                    if($transferPayment):
                        return redirect('account/receipts')->with('alert-success','Payment processed!');
                    else:
                        return redirect('account/receipts')->with('alert-danger','Payment error!');
                    endif;
                }else{
                    return redirect('account/receipts')->with('alert-warning','This receipt is not available to pay.');
                }


            else:
                return abort(403);
            endif;
        else: 
            return abort(404);
        endif;
    }
    function transfer($receipt,$bj = null){
        $user = ($bj == null) ? auth()->user():$bj;
        if($receipt):
            $payment_router = App\JobPayments::where('from',$user->id)
                                                ->where('receipt_id',$receipt->id)
                                                ->first();
            //get the wallet
            $account = App\UserAccounts::join('user_link_accounts','user_accounts.id','=','user_link_accounts.aid')
                                        ->where('user_link_accounts.uid',$payment_router->to)
                                        ->select('user_accounts.*')->first();
            if($account):
                
                $current = floatval($account->amount);
                $serviceFee = floatval($receipt->subtotal) * 0.1;
                $amount_to_transfer = floatval($receipt->subtotal) - $serviceFee;
                $new = $current + $amount_to_transfer;

                $accountRow = App\UserAccounts::where('id',$account->id)->update(['amount'=>$new]);
                $prouter = App\JobPayments::where('id',$payment_router->id)->update(['status'=>'paid']);

                try {
                    $mailing = new Mailing;
                    $mailing->newPaymentReceived($payment_router);
                } catch (\Throwable $th) {
                    error_log($th);
                }
                
                
                return $account->number;
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }
    function processWithdrawal($amount,$pj){
        /* $user = App\User::find($pj); */

        $account = App\UserAccounts::join('user_link_accounts','user_accounts.id','=','user_link_accounts.aid')
                                    ->where('user_link_accounts.uid',$pj->id)
                                    ->select('user_accounts.*')->first();
        if($account):
            
            $current = floatval($account->amount);
            
            $new = $current - $amount;

            $accountRow = App\UserAccounts::where('id',$account->id)->update(['amount'=>$new]);
            /* $prouter = App\JobPayments::where('id',$payment_router->id)->update(['status'=>'paid']); */

            /* try {
                $mailing = new Mailing;
                $mailing->newPaymentReceived($payment_router);
            } catch (\Throwable $th) {
                error_log($th);
            } */
            
            
            return $account->number;
        else:
            return false;
        endif;
        
    }
    function getInvoice($invoice_id){
        $user = auth()->user();
        $jobe_invoice = App\Receipts::where('stripe_receipt',$invoice_id)->first();
        $date = $jobe_invoice->updated_at;
        return $user->downloadInvoice($invoice_id, [
            'vendor' => 'JOBE',
            'product' => $jobe_invoice->title,
        ], 'Invoice ' . $date );
    }
    function getPaymentMethod(){
        $uid = auth()->user()->id;
        $company = DB::table('companies')->where('user_id',$uid)->first();
        if($company):
            $payment_method = DB::table('company_fields')->where('company_id',$company->id)->where('meta_key','payment_method')->first();
            if($payment_method):
                return $payment_method->meta_value; //return the stored tag[associated field] for payment method
            else:
                return 'stripe'; //default
            endif;
        else:
            return null;
        endif;
    }

    public function methodForm(){
        $user = auth()->user();
        $uid = $user->id;
        $company = DB::table('companies')->where('user_id',$uid);
        
        $stripeID = $user->stripe_id;

        if($stripeID):
            $stripeUser = Cashier::findBillable($stripeID);
        else:
            $stripeUser = $user->createAsStripeCustomer();
        endif;

        if($company):
            $payment_methods = DB::table('form_options')->where('key','payment_methods')->orderBy('id','desc')->get();

            $homeController = new HomeController;
            $usertype = $homeController->getUsertype();
            $user_payment_method = $this->getPaymentMethod(); 
            
            $intent = $user->createSetupIntent();

            if($user_payment_method=='stripe'){
                $payment_method_info = $this->getStripeInfo();
            }else{
                $payment_method_info = $this->getPaypalInfo();
            }
             
            
            $source = compact('usertype','payment_methods','user_payment_method','intent','payment_method_info');
            return view('account/payments/method',$source);
        else:
            return redirect()->route('/wizard');
        endif;
    }
    public function getStripeInfo(){
        $user = auth()->user();
        $company = DB::table('companies')->where('user_id',$user->id)->first();
        $hidden_numbers = '';
        for($i=1;$i<=15;$i++){ 
            if($i == 5 || $i == 10 || $i == 15){
                $hidden_numbers .= ' ';
            }else{
                $hidden_numbers .= '<i class="fa card-hidden-numbers fa-asterisk"></i>';
            }
        }
        if($user):
            $card_holder_name_field = DB::table('company_fields')->where('company_id',$company->id)->where('meta_key','card_holder_name')->first();
            $cardname = ($card_holder_name_field) ? $card_holder_name_field->meta_value:'';
            $payment_info = (object) ['card_brand'=>$user->card_brand,'card_last_four'=>$hidden_numbers.$user->card_last_four,'card_holder_name'=>$cardname];
        else:
            $payment_method = false;
        endif;
        return $payment_info;
    }
    public function getPaypalInfo(){
        return true;
    }
    public function methodSave(Request $request){
        $user = auth()->user();
        $company = DB::table('companies')->where('user_id',$user->id)->first();
        $payment_method = App\CompanyFields::firstOrCreate(
            ['meta_key' => 'payment_method','company_id'=>$company->id], 
            [
            'meta_key' => 'payment_method', 
            'meta_value' => $request->pmethod,
            'company_id' => $company->id
            ]
        );
        if($payment_method)://update
           $payment_method->meta_value = $request->pmethod; 
           $payment_method->save();
        endif;
        
        if($request->pmethod == 'stripe'): 
            $card_honder_name = App\CompanyFields::firstOrCreate(
                ['meta_key' => 'card_holder_name','company_id'=>$company->id], 
                [
                'meta_key' => 'card_holder_name', 
                'meta_value' => $request->card_holder_name,
                'company_id' => $company->id
                ]
            );
            /* if ($user->hasPaymentMethod()) {
                $user->updateDefaultPaymentMethod($request->pmID);
            }else{ */
                $user->updateDefaultPaymentMethod($request->pmID);
            /* } */
            if($user->hasPaymentMethod()):
                return response()->json(['status'=>'success']);
            else:
                return response()->json(['status'=>'fail']);
            endif;    
        elseif($request->pmethod =='paypal'):
            
            return back()->with(['alert-success'=>'Paypal is now your payment method']);
        elseif($request->pmethod =='bank-account'):
            
            //return response()->json(['status'=>'bank account saved']);
            return back()->with(['alert-success'=>'Deposit to bank account is now your payment method']);
        endif;

    }
}
