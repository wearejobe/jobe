@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/pages/payment.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.jobs-sidebar')
                
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1>{{__('Invoice')}}</h1>
                    
                    <div class="table-container mt-5">
                        @if ($errors->any())
                            <div class="alert alert-danger pt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table id="tbl-receipts" class="table table-hover">
                            <tbody>
                                @forelse($receipts as $receipt)
                                <tr>
                                    <td>
                                        <h6>{{ $receipt->title }}</h6>
                                        <p class="mb-0"><span class="state-text {{ $receipt->status }}">{{ $receipt->description }}</p>
                                        <small><span class="text-uppercase state-text {{ $receipt->status }}">{{ Str::replaceFirst('-',' ',$receipt->status) }}</span> | {{__('Generated ')}} {{ App\WorkInterval::humanDate($receipt->created_at) }} </small>
                                    </td>
                                    <td class="text-center bg-light">
                                        <h6 class="">{{ App\User::getUserCurrency()}}{{ $receipt->total }}</h6>
                                        @if($receipt->status == 'pending')
                                            
                                            <a data-subtotal="{{ App\User::getUserCurrency()}}{{ $receipt->subtotal }}" data-currency="{{ App\User::getUserCurrency()}}" data-variations="{{ $receipt->variations }}" data-toggle="modal" href="#mdl-payment" data-pid="{{md5($receipt->id) .'_' . $receipt->id }}" data-total="{{ App\User::getUserCurrency()}}{{ $receipt->total }}" class="btn btn-sm btn-primary text-uppercase">

                                            {{__('Pay now')}}

                                            </a>
                                        @endif
                                        @if($receipt->status=='paid' && $receipt->stripe_receipt!=null)
                                        <a target="_blank" href="{{ route('account.invoice',['id'=>$receipt->stripe_receipt]) }}" class="btn btn-sm btn-primary text-uppercase">{{__('Invoice')}}</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <div class="empty-alert">{{__('No receipts found.')}}</div>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id='mdl-payment'>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content no-border">
            <div class="card no-border">
                    <div class="card-body p-0">
                        <div class="container">
                        <div class="row">
                            <div class="col-sm-12 bg-light">
                                @if($PaymentMethod!=null)
                                    @if($PaymentMethod->meta_value=='stripe')
                                    <h6 class="mt-3">Pay with Credit/Debit card</h6>
                                    <hr>
                                    <div class="form-group">
                                        <label for="card-holder-name">{{__('Name')}}</label>
                                        <input required id="card-holder-name" class="form-control bg-light border-dark" placeholder="John Doe" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="card-holder-name">{{__('Card information')}}</label>
                                        <div id="card-element"></div>
                                    </div>
                                    <form id="frm-stripe-pay" action="{{route('receipt.pay')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="pid" id="pid" value="0">
                                        <input type="hidden" name="pmethod" id="pmethod" value="0">
                                    </form>
                                    @endif
                                    @if($PaymentMethod->meta_value=='bank-account')
                                    <h6 class="mt-3">Pay with bank account transaction</h6>
                                    <hr>
                                    <div class="alert-warning alert d-none text-required">
                                        <div class="p-3">{{__('required_fields')}}</div>
                                    </div>
                                    <form id="frm-transaction-pay" action="{{ route('receipt.transaction') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="pid" id="pid" value="0">
                                        <div class="form-group">
                                            <label for="transaction-name">{{__('Name')}}</label>
                                            <input required name="transaction_name" class="form-control" type="text">
                                        </div>
                                        <div class="form-group">
                                            <label for="transaction_number">{{__('Transaction/Confirmation/Reference number')}}</label>
                                            <input required type="text" name="transaction_number" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="transaction_date">{{__('Transaction date')}}</label>
                                            <div class="input-group">
                                                <input required readonly type="text"  name="transaction_date" class="datepicker form-control">
                                            </div>
                                        </div>
                                        <hr>
                                    </form>
                                    @endif
                                @endif
                                <div class="form-group">
                                    <label class="d-block">{{__('SUBTOTAL: ')}}<b class="subtotal amount float-right"></b></label>
                                    <div class="variations"></div>
                                    <label class="d-block">{{__('TOTAL: ')}}<b class="total amount text-success float-right"></b></label>
                                    
                                </div>
                                <hr>
                                <div class="form-group text-center">
                                    @if($PaymentMethod)
                                        @if($PaymentMethod->meta_value=='stripe')
                                        <button type="button" id="card-button" class="btn btn-success mb-3">
                                            Pay
                                        </button>
                                        @endif
                                        @if($PaymentMethod->meta_value=='bank-account')
                                        <button type="button" id="btn-register-transaction" class="btn btn-success mb-3">
                                            Register transaction
                                        </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-footer bg-dark">
                        <button data-dismiss="modal" class="btn btn-light float-right">Cancel</button>
                        {{-- <button type="submit" id="btn-new-task" class="btn btn-sm btn-success btn-add-task float-right">Save</button> --}}
                    </div>
            </div>
        </div>
    </div>
</div>
@push('head')
<script src="https://js.stripe.com/v3/"></script>
    @if($PaymentMethod!=null)
        @if($PaymentMethod->meta_value=='stripe')
            <script src="{{ asset('js/pages/stripe-payment.js') }}"></script>    
        @endif
        @if($PaymentMethod->meta_value=='bank-account')
            <script src="{{ asset('js/pages/ba-payment.js') }}"></script>    
        @endif
    @endif
@endpush
    
@endsection