@extends('main')

@section('body')
<div class="jumbotron orange no-round welcome login">
    <div class="container">
        <div class="row justify-content-center align-items-center login-row">
            <div class="col-md-4 text-center">
                <div class="card no-round bg-dark">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row">
                                
                                <div class="col-md-12 text-left">
                                    <label for="email" class="form-label">{{ __('tr_login.email') }}</label>
                                    <input id="email" type="email" class="form-control no-round @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 text-left">
                                    <label for="password" class="form-label">{{ __('tr_login.password') }}</label>
                                    <input id="password" type="password" class="form-control no-round @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 text-right">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('tr_login.remember') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-block btn-primary">
                                        {{ __('tr_general.sign-in') }}
                                    </button>
                                </div>
                                <div class="col-md-12 mb-5">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                                            {{ __('tr_login.forgot_password') }}
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    @if (Route::has('password.request'))
                                        <span class="label-small">{{ __('tr_login.no_account_question') }}</span><br>
                                        <a class="btn btn-link text-white mt-0" href="{{ route('welcome') }}">
                                            {{ __('tr_general.register') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
