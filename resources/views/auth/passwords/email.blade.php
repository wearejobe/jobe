@extends('main')

@section('body')
<div class="container">

    <div class="card card-main-content no-border">
        <h3 class="page-title">{{ __('Reset Password') }}</h3>
        <div class="p-3">        
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>{{ __('2Please type your email you used for register at jobe, we\'ll send an email message to your inbox to verify your identity and you will be able to change your password.') }}</p>

                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <div class="p-3">
                                    {{ session('status') }}
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ URL::asset('images/jobe-network.png') }}" alt="Jobe network">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection