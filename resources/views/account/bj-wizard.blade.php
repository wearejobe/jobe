@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{asset('css/wizard.css')}}" />
<script src="{{asset('js/bj-wizard.js')}}"></script>
@endpush
<div class="container wizard bj-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-4 dark-sidebar">
                <div class="p-5">
                    <div class="steps-indicators">
                        <ul class="steps-group">
                            <li class="step-item step-1-indicator ready">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Company name')}}
                            </li>
                            <li class="step-item step-2-indicator">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Company details')}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <div class="tab-content" id="wizard-content">
                        <div class="tab-pane fade show active" id="company-info" role="tabpanel" aria-labelledby="home-tab">
                            <h2>{{__('Company Info')}}</h2>
                            <form id="frm-company-info" method="post" action="{{route('wsave1')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{__('Company name')}}</label>
                                    <input type="text" required name="name" id="name" class="form-control">
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" id="btn-to-company-details" class="btn btn-success">{{__('Next')}}</button>
                                </div>
                            </form>
                            
                        </div>
                        <div class="tab-pane fade" id="company-details" role="tabpanel" aria-labelledby="profile-tab">
                            <h2>{{__('Company settings')}}</h2>
                            <form id="frm-company-details" method="post" action="{{route('wsave2')}}">
                                @csrf
                                <div class="form-group">
                                    <label>{{__('How many employees does your company have?')}}</label>
                                    @foreach(json_decode($num_emp_items) as $item)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="size" id="{{$item->id}}" value="{{$item->value}}">
                                        <label class="form-check-label" for="{{$item->id}}">
                                            {{__($item->text)}}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="address">{{__('Address')}}</label>
                                            <input type="text" name="address" id="address" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="city">{{__('City')}}</label>
                                            <input type="text" name="city" id="city" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="state">{{__('State')}}</label>
                                            <input type="text" name="state" id="state" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="country">{{__('Country')}}</label>
                                            <select id="country" class="form-control" name="country">
                                                <option>{{ __('Select Country') }}</option>
                                                @foreach($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <label for="currency" class="form-label">{{ __('Currency') }}</label>
                                        <div class="frm-control-container">
                                            <select id="currency" class="form-control" name="currency">
                                                <option>{{ __('Select Currency') }}</option>
                                                @forelse($currencies as $currency)
                                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                @empty
                                                    <option value="1">US Dollar</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" id="btn-continue" class="btn btn-success">{{__('Continue')}}</button>
                                </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection
