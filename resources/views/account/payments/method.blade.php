@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/pages/jobs.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.jobs-sidebar')
                
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1>{{__('Payment method')}}</h1>
                    <form action="{{ route('payment.save') }}" id="frmPaymentMethods" method="post">
                        @csrf
                        <div class="payment-method mt-5">
                            
                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="btn-group btn-group-toggle mt-2 payment-methods" data-toggle="buttons">
                                    @forelse ($payment_methods as $item)
                                        <label  class="btn btn-outline-dark">
                                            <h3 class="text-color-3 mt-1 mb-1">{!! $item->icon !!}</h3>
                                            <h6 class="text-color-3">{{ $item->heading }}</h6>
                                            <input data-target="#{{$item->associated}}" type="radio" value="{{ $item->associated }}" name="pmethod" id="pmethod-{{ $item->id }}" @isset($user_payment_method) {{ $user_payment_method == $item->associated ? 'checked':'' }} @endisset>
                                            <small class="text-color-5">{{ $item->description }}</small>
                                        </label>
                                    @empty
                                        
                                    @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="tab-content" id="myTabContent">
                                        @forelse ($payment_methods as $item)
                                            @switch($item->associated)
                                                @case('bank-account')
                                                    <div class="tab-pane fade {{ ($item->associated==$user_payment_method) ? 'active show':'' }}" id="{{$item->associated}}" role="tabpanel" aria-labelledby="{{$item->associated}}-tab">
                                                        <h3 class="page-title">{{$item->heading}} <span class="float-right">{!! $item->icon !!}</span></h3>
                                                        <p>{{ __('To pay with this payment method you have to make a transfer with the amount of the payment to:') }}</p>
                                                        <div class="form-group">
                                                            <label>Bank</label>
                                                            <input type="text" readonly value="BAC Credomatic El Salvador" class="form-control border-black">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Account number</label>
                                                            <input type="text" readonly value="107074973" class="form-control border-black">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>SWIFT CODE</label>
                                                            <input type="text" readonly value="BAMCSVSSXXX" class="form-control border-black">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>SWIFT CODE(8 ch)</label>
                                                            <input type="text" readonly value="BAMCSVSS" class="form-control border-black">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <input type="text" readonly value="CENTRO ROOSEVELT EDIF D 55 AV SUR" class="form-control border-black">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" readonly value="Julio Mejía" class="form-control border-black">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Bank Country</label>
                                                            <input type="text" readonly value="El Salvador" class="form-control border-black">
                                                        </div>
                                                        <p>{{ __('When you have made the deposit please process the payment in the payments section and we will check the transfer information you bring.') }}</p>
                                                        <div class="form-group text-center">
                                                            <button class="btn btn-success" type="submit">
                                                                {{ __('Select this payment method') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @break
                                                @case('paypal')
                                                    <div class="tab-pane fade {{ ($item->associated==$user_payment_method) ? 'active show':'' }}" id="{{$item->associated}}" role="tabpanel" aria-labelledby="{{$item->associated}}-tab">
                                                        <h3 class="page-title">{{$item->heading}} <span class="float-right">{!! $item->icon !!}</span></h3>
                                                    </div>
                                                    @break
                                                @case('stripe')
                                                    
                                                    <div class="tab-pane fade {{ ($item->associated==$user_payment_method) ? 'active show':'' }}" id="{{$item->associated}}" role="tabpanel" aria-labelledby="{{$item->associated}}-tab">
                                                        <h3 class="page-title">{{$item->heading}} <span class="float-right">{!! $item->icon !!}</span></h3>
                                                        @if($payment_method_info)
                                                        <div class="form-group row">
                                                            <div class="col-sm-3 text-right"><label for="card-holder-name ">{{__('Card Holder Name')}}</label></div>
                                                            <div class="col-sm-9">{{ $payment_method_info->card_holder_name ?? ''}}</div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-3 text-right"><label for="card-holder-name">{{__('Type')}}</label></div>
                                                            <div class="col-sm-9 text-uppercase">{{ $payment_method_info->card_brand ?? ''}}</div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-3 text-right"><label for="card-holder-name">{{__('Card')}}</label></div>
                                                            <div class="col-sm-9 text-uppercase">{!! $payment_method_info->card_last_four ?? '' !!}</div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-9 offset-sm-3 text-uppercase"><button type="button" class="btn btn-outline-dark" data-toggle="collapse" data-target="#card_update">{{__('Change')}}</button></div>
                                                        </div>
                                                        @endif

                                                        <div class="card no-border card_update {{ $payment_method_info!=false ? 'collapse':'' }}" id="card_update">
                                                            <div class="card-body bg-light">
                                                                <h4 class="mb-4">Update payment method information</h4>
                                                                <div class="form-group">
                                                                    <label for="card-holder-name">{{__('Card Holder Name')}}</label>
                                                                    <input id="card-holder-name" name="card_holder_name" class="form-control border-dark" type="text" placeholder="John Doe">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="card-element">{{__('Card Details')}}</label>
                                                                    <div id="card-element"></div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer bg-dark">
                                                                <input type="hidden" name="pmID" id="pmID" value="">
                                                                <button type="button" class="btn btn-success" id="card-button" data-secret="{{ $intent->client_secret ?? '' }}">
                                                                    Update Payment Method
                                                                </button>
                                                            </div>
                                                        </div>
                                                       
                                                        
                                                    </div>
                                                    @break
                                                @default

                                            @endswitch
                                        @empty

                                        @endforelse
                                    </div>                                      
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@push('head')
<script src="https://js.stripe.com/v3/"></script>

<script src="{{ asset('js/pages/payment-method.js') }}"></script>    
@endpush
    
</div>
@endsection