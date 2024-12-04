@extends('main')

@section('body')
<div class="jumbotron orange no-round welcome">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card no-border">
                    <div class="card-header bg-secondary"><h5 class="text-white mb-0">{{ __('Verify Your Email Address') }}</h5></div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                <div class="p-4">{{ __('A fresh verification link has been sent to your email address.') }}</div>
                            </div>
                        @endif

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
