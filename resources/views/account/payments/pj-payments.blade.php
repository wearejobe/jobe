@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/pages/dashboard.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.job-sidebar')
                
            </div>
            <div class="col-sm-9">
                <div class="p-5">
                    <h1>{{ __('Payments') }}</h1>
                    <div class="payments-container mt-5">
                        <div class="row">
                            <div class="col">
                                <div class="list-group">
                                    @forelse ($payments as $payment)
                                    <div class="list-group-item">
                                        <div class="badge badge-success float-right">
                                            @php
                                            $fee = floatval($payment->subtotal) * 0.1;
                                            $added_to_wallet = floatval($payment->subtotal) - $fee; 
                                            @endphp
                                            {{ App\User::getUserCurrency()}}{{ number_format($added_to_wallet,2) }}
                                        </div>
                                        <b>{{ $payment->title }}</b><br>
                                        <small class="text-muted">{{ App\Main::humanDate($payment->updated_at) }}</small>
                                    </div>
                                    @empty
                                    <div class="empty-alert">
                                        {{__('No payments found')}}
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection