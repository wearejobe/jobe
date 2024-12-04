@extends('main')

@section('body')

@push('head')
<link rel="stylesheet" href="{{ URL::asset('css/plugins/croppie.css') }}" type="text/css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.16/moment-timezone-with-data.min.js"></script>
<script src="{{ URL::asset('js/profile_company.js') }}"></script>
<script src="{{ URL::asset('js/inputmask.jquery.js') }}"></script>
<script src="{{ URL::asset('js/plugins/croppie.min.js') }}"></script>
<script src="{{ URL::asset('js/pages/avatar-save.js') }}"></script>
<script>var firstTime = false;</script>
@endpush
<div class="container profile business-profile">

    <div class="card card-main-content no-border mb-5">
        {{-- <h3 class="page-title">Your Profile</h3> --}}
        <div class="p-3">        
            <div class="card-body">
                @if ( session('alert') )
                    <div class="alert alert-success">
                        <div class="alert-icon"><span class="material-icons">check</span></div>
                        <div class="alert-text">{{ session('alert') }}</div>
                        <div class="alert-dismiss"><a href="javascript:void(0)" data-dismiss="alert" class=""><span class="material-icons">highlight_off</span></a></div>
                    </div>
                @endif
                
            
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <button onclick="event.preventDefault(); document.getElementById('profile-save').submit();"  class="btn btn-success btn-block mt-3" id="btn-save">{{ __('Save') }}</button>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('jobs') }}" class="btn btn-outline-dark btn-block mt-3" id="btn-jobs">{{ __('Posted jobs') }}</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('new-job') }}" class="btn btn-outline-dark btn-block mt-3" id="btn-jobs">{{ __('Post new job') }}</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card bg-lightgray no-border rounded">
                            <div class="card-body text-left">
                                
                                <div class="avatar mx-auto white">
                                    {!! App\User::getAvatar(Auth::user()->id) !!}
                                    <button data-toggle="modal" data-target="#mdl-avatar"  class="btn btn-small btn-dark btn-circle btn-sm btn-edit"><span class="material-icons">create</button>
                                </div>
                            <h4 class="font-weight-bold mt-3 text-center">{{ $bj->name ?? ''}}</h4>
                            <p class="text-center text-muted">Business</p>
                            </div>
                            {{-- <div class="card-body rating text-center text-white">
                                <p>Your rating</p>
                                <span class="material-icons">star</span>
                                <span class="material-icons">star</span>
                                <span class="material-icons">star</span>
                                <span class="material-icons">star</span>
                                <span class="material-icons">star_half</span>
                                <h6>4.6/5</h6>
                                <span>15 reviews</span>
                            </div> --}}
                        </div>
                        
                        {{-- <div class="list-group mt-3 nav-tabs">
                            <a data-toggle="tab" href="#tab-personal" role="tab" aria-controls="tab-personal" aria-selected="true" class="list-group-item list-group-item-action active"><span class="material-icons">account_circle</span>{{ __('Personal') }}</a>
                            <a data-toggle="tab" href="#tab-account" role="tab" aria-controls="tab-account" aria-selected="true" class="list-group-item list-group-item-action"><span class="material-icons">tune</span>{{ __('Settings') }}</a>
                            <a data-toggle="tab" href="#tab-wallet" role="tab" aria-controls="tab-wallet" aria-selected="true" class="list-group-item list-group-item-action"><span class="material-icons">account_balance_wallet</span>{{ __('My Wallet') }}</a>
                            <a href="{{ __('logout') }}" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="list-group-item list-group-item-action"><span class="material-icons">power_settings_new</span>{{ __('Logout') }}</a>
                        </div> --}}
                    </div>
                    <div class="col-sm-9">
                        <div class="card bg-lightgray no-border rounded">
                            <div class="card-body">
                            
                                <form action="{{ route('profile.bjsave') }}" id="profile-save" method="post">
                                {{-- <form id="profile-save" action="" method="post"> --}}
                                    @csrf
                                    <input type="hidden" id="cid" name="cid" value="{{ $bj->id }}" />
                                    <input type="hidden" id="return_url" name="return_url" value="{{ Request::url() }}" />
                                    <div class="tab-content" id="tabs-profile">
                                        <div class="tab-pane fade show active p-5" id="tab-personal" role="tabpanel" aria-labelledby="tab-personal">
                                            <h4 class="mb-4">{{ __('Company info') }}</h4>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="name" class="form-label">{{ __('Company name') }}</label>
                                                    <div class="frm-control-container">
                                                        <input required id="name" type="name" class="form-control" name="name" value="{{ $bj->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="name" class="form-label">{{ __('Contact e-mail') }}</label>
                                                    <div class="frm-control-container">
                                                        <input id="email" readonly type="name" class="form-control" name="email" value="{{ Auth::user()->email }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <label for="name" class="form-label">{{ __('Address') }}</label>
                                                    <div class="frm-control-container">
                                                        <input id="address" type="text" class="form-control" name="address" value="{{ $bj_fields->address ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="name" class="form-label">{{ __('City') }}</label>
                                                    <div class="frm-control-container">
                                                        <input id="city" type="text" class="form-control" name="city" value="{{ $bj_fields->city ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="name" class="form-label">{{ __('State') }}</label>
                                                    <div class="frm-control-container">
                                                        <input id="state" type="text" class="form-control" name="state" value="{{ $bj_fields->state ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label for="country" class="form-label">{{ __('Country') }}</label>
                                                    <div class="frm-control-container">
                                                        <select id="country" class="form-control" name="country">
                                                            <option>{{ __('Select Country') }}</option>
                                                            @foreach($countries as $country)
                                                                @isset($bj_fields->country)
                                                                    <option {{ $bj_fields->country == $country->id ? 'selected':'' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                                    @else
                                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                                @endisset
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="currency" class="form-label">{{ __('Currency') }}</label>
                                                    <div class="frm-control-container">
                                                        <select id="currency" class="form-control" name="currency">
                                                            <option>{{ __('Select Currency') }}</option>
                                                            @forelse($currencies as $currency)
                                                                @isset($bj_fields->currency)
                                                                    <option {{ $currency->id == $bj_fields->currency ? 'selected':'' }} value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                                @else
                                                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                                @endisset
                                                            @empty
                                                                <option value="1">US Dollar</option>
                                                            @endforelse
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="form-group row mt-1">
                                                <div class="col-md-2">
                                                    <label class="form-label">{{ __('Password') }}</label><br>
                                                </div>
                                                <div class="col">
                                                    <a href="{{ route('password.request') }}" class="btn btn-outline-info btn-sm">{{ __('Change password')}}</a>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="tab-pane fade p-5" id="tab-account" role="tabpanel" aria-labelledby="tab-account">
                                            <h4 class="mb-4">{{ __('Settings') }}</h4>
                                            
                                        </div>
                                        
                                        <div class="tab-pane fade p-5" id="tab-wallet" role="tabpanel" aria-labelledby="tab-wallet">
                                            <h4 class="mb-4">{{ __('Wallet')}}</h4>
                                            
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


{{-- MDL AVATAR --}}
<div class="modal fade" id="mdl-avatar">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content no-border">
            <div class="card no-border">
                <div class="card-body p-0">
                    <div class="container image-selection">
                        <div class="row">
                            <div class="col-sm-12 bg-light">
                                <h6 class="mt-3">{{__('Change your business logo')}}</h6>
                                <hr>
                                <div class="form-group text-center">
                                    <label>{{__('Select Logo')}}</label>
                                </div>
                                <div class="form-group text-center">
                                    <label for="image-selector" class="btn btn-primary">{{__('Select from your device')}}</label>
                                    <input id="image-selector" class="form-control bg-light border-dark invisible" placeholder="John Doe" type="file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container image-cropper d-none">
                        <div class="row">
                            <div class="col-sm-12 bg-light">
                                <h6 class="mt-3">{{__('Crop profile picture')}}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-light text-center">
                                <div id="cropper-profile">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 bg-light text-center">
                                <div class="form-group">
                                    @csrf
                                    <input type="hidden" name="upload" value="{{ route('upload.data')}}">
                                    <input type="hidden" name="urlav" value="{{ route('avatar.save')}}">
                                    <button id="btn-change" class="btn btn-secondary" type="button">{{__('Change picture')}}</button>
                                    <button id="btn-save-avatar" class="btn btn-success" type="button">{{__('Save profile picture')}}</button>
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
{{-- MDL AVATAR --}}


@if ( session('open_tab') )
@push('head')
<script>
jQuery(function($){
    var url_h = "<?=session('open_tab')?>";
    $('.nav-tabs a[href="#' + url_h.split('#')[1] + '"]').tab('show');
});
</script>
@endpush
@endif


</div>
@endsection
<?php session()->forget('first-time'); ?>