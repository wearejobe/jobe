@extends('main')

@section('body')
<div class="container">

    <div class="card card-main-content no-border">
        <h3 class="page-title">{{ __('Reset Password') }}</h3>
        <div class="p-3">        
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>{{ __('Please confirm your password before continuing.') }}</p>         
                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Confirm Password') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
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
