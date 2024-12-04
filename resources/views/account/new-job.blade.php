@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ URL::asset('css/jquery-ui.css') }}" type="text/css" />
<script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/wizard.css')}}" />
<script src="{{asset('js/job-wizard.js')}}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-4 bg-dark dark-sidebar">
                <div class="p-5">
                    <div class="steps-indicators">
                        <div class="steps-group">
                            <a data-toggle="tab" href="#job-title" class="step-item step-1-indicator ready d-block">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Title')}}
                            </a>
                            <a data-toggle="tab" href="#description" class="step-item step-2-indicator d-block" >
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Description')}}
                            </a>
                            <a data-toggle="tab" href="#details"  class="step-item step-3-indicator d-block">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Details')}}
                            </a>
                            <a data-toggle="tab" href="#location" class="step-item step-4-indicator d-block">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Location')}}
                            </a>
                            <a data-toggle="tab" href="#budget" class="step-item step-5-indicator d-block">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Budget')}}
                            </a>
                            <a data-toggle="tab" href="#deliverables" class="step-item step-6-indicator d-block">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Deliverables')}}
                            </a>
                            <a data-toggle="tab" href="#review" class="step-item step-6-indicator d-block">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Review')}}
                            </a> 
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        @if(Request::is('account/edit-job/*'))
                        <button onclick="event.preventDefault(); document.getElementById('frm-new-job').submit()" class="btn btn-primary btn-block">
                            Save job
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <div class="alert-container">
                        <div class="alert alert-danger p-3 alert-required d-none">{{__('Some Items are required')}}</div>
                    </div>
                    <form action="{{ route('new-job.save') }}" method="post" name="frm-new-job" id="frm-new-job">
                        @csrf
                        <input type="hidden" name="apiURL" value="{{ route('api.getSkills') }}">
                        @isset($job)
                        <input type="hidden" name="jid" value="{{ $job->id }}">
                        @endisset
                        <div class="tab-content" id="wizard-content">

                            <div class="tab-pane fade show active" id="job-title" role="tabpanel">{{-- STEP 1 / Job title --}}
                                <h2 class="mb-5">{{__('Project name')}}</h2>
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>{{__('Enter the project title')}}</label>
                                        <input required type="text" name="job_name" id="job_name" value="{{ $job->title ?? '' }}" class="form-control reflect-this">
                                        <div class="help-desc">
                                            A brief project description
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8 form-group">
                                        <label>{{__('Project Category')}}</label>
                                        <select id="category" required class="form-control reflect-this" name="category">
                                            <option value="">{{ __('Select category') }}</option>
                                            @foreach($categories as $cat)
                                                @isset($job->category)
                                                    <option {{ $job->category==$cat->id ? 'selected':'' }} value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @else
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endisset
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-12 form-group text-right">
                                        <a data-prev="0" data-next="2" data-toggle="tab" href="#description" class="btn btn-info btn-next">Next</a>                                        
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="description" role="tabpanel">{{-- STEP 2 / Description --}}
                                <h2>{{__('Description')}}</h2>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('Description')}}</label>
                                        <textarea required rows="2" name="description" id="description" class="form-control reflect-this">{{ $jobFields->description ?? ''}}</textarea>
                                        <div class="help-desc">
                                            {{__('Describe or list the problems we are going to solve with the deliverable you expect. What is the specific need of the client.')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('End-user')}}</label>
                                        <textarea rows="2" name="end_user" id="end_user" class="form-control reflect-this">{{ $jobFields->end_user ?? ''}}</textarea>
                                        <div class="help-desc">
                                            {{__('How we would help to end-user')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('Target')}}</label>
                                        <textarea rows="2" name="target" id="target" class="form-control reflect-this">{{ $jobFields->target ?? ''}}</textarea>
                                        <div class="help-desc">
                                            {{__('Describe the target audience you are seeking to reach with this project.')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('Objetive')}}</label>
                                        <textarea rows="2" name="objetive" id="objetive" class="form-control reflect-this">{{ $jobFields->objetive ?? ''}}</textarea>
                                        <div class="help-desc">
                                            {{__('What are the objectives of the project?')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5"> 
                                    <div class="col-sm-12 form-group text-right">
                                        <a data-prev="1" data-next="3" data-toggle="tab" href="#job-title" class="btn btn-outline-info btn-prev">Prev</a>
                                        <a data-prev="1" data-next="3" data-toggle="tab" href="#details" class="btn btn-info btn-next">Next</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="details" role="tabpanel">{{-- STEP 3 / Details --}}
                                <h2>{{__('Details')}}</h2>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('What type of project do you have')}}</label>
                                        <div class="options-container">
                                            <div class="btn-group btn-group-toggle mt-2" data-toggle="buttons">
                                                @foreach($project_types as $project_type)
                                                    <label class="btn btn-outline-dark radio-option @isset($jobFields->type) {{ $jobFields->type == $project_type->id ? 'active':'' }} @endisset">
                                                        <h3 class="text-color-3 mt-1 mb-1">{!! $project_type->icon !!}</h3>
                                                        <small class="text-color-4">{{ $project_type->heading }}</small>
                                                        <input type="radio" class="reflect-this" value="{{ $project_type->id }}" name="type" id="ptype-{{ $project_type->id }}" @isset($jobFields->type) {{ $jobFields->type == $project_type->id ? 'checked':'' }} @endisset />
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('How long do you expect this project to last')}}</label>
                                        <div class="options-container">
                                            <div class="btn-group btn-group-toggle mt-2" data-toggle="buttons">
                                                @foreach($project_times as $project_time)
                                                    <label class="btn btn-outline-dark radio-option @isset($jobFields->duration) {{ $jobFields->duration == $project_time->id ? 'active':'' }} @endisset">
                                                        <h3 class="text-color-3 mt-1 mb-1">{!! $project_time->icon !!}</h3>
                                                        <small class="text-color-4">{{ $project_time->heading }}</small>
                                                        <input type="radio" class="reflect-this" value="{{ $project_time->id }}" name="duration" id="ptime-{{ $project_time->id }}" @isset($jobFields->duration) {{ $jobFields->duration == $project_time->id ? 'checked':'' }} @endisset />
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('Skills')}}</label>
                                        <input type="text" id="skills" class="form-control jobe-autocomplete-helper" placeholder="Search for skills...">
                                        <input type="hidden" name="skills_source" id="skills_source" value="{{ $jobFields->skills_source ?? ''}}" />
                                        <div class="help-desc">
                                            {{__('Chose the skills needed for this project?')}}
                                        </div>
                                        <div class="add-suggestion-container d-none">
                                            <a id="btn-add-suggestion" href="{{ route('api.addSkill') }}" data-token="{{ csrf_token() }}">{{__('Add as new skill')}}</a>
                                        </div>
                                        <div class="skills-container">
                                            @isset($skills)
                                                @foreach($skills as $skill)
                                                    <button data-id="{{ $skill->id }}" type="button" class="btn btn-sm btn-outline-info tag-item">{{ __($skill->name) }}<span class="material-icons ml-1">highlight_off</span></button>
                                                @endforeach
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-12 form-group text-right">
                                        <a data-prev="2" data-next="4" data-toggle="tab" href="#description" class="btn btn-outline-info btn-prev">Prev</a>
                                        <a data-prev="2" data-next="4" data-toggle="tab" href="#location" class="btn btn-info btn-next">Next</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="location" role="tabpanel">{{-- STEP 4 / Location --}}
                                <h2>{{__('Location')}}</h2>
                                
                                <div class="row mt-5">
                                    <div class="col-sm-8 form-group">
                                        <label>{{__('Where this project will be available?')}}</label>
                                        <select name="location" id="location" class="form-control reflect-this">
                                            <option value="">{{__('Select location...')}}</option>
                                            @foreach($project_location_types as $plt)
                                                <option @isset($jobFields->location) {{ $jobFields->location == $plt->id ? 'selected':'' }} @endisset value="{{$plt->id}}">{{$plt->heading}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-8 form-group">
                                        <label>{{__('Explain')}}</label>
                                        <input type="text" name="location_detail" id="location_detail" class="form-control reflect-this" value="{{ $jobFields->location_detail ?? '' }}" />
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-12 form-group text-right">
                                        <a data-prev="3" data-next="5" data-toggle="tab" href="#details" class="btn btn-outline-info btn-prev">Prev</a>
                                        <a data-prev="3" data-next="5" data-toggle="tab" href="#budget" class="btn btn-info btn-next">Next</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="budget" role="tabpanel">{{-- STEP 5 / Budget --}}
                                <h2>{{__('Budget')}}</h2>
                                
                                <div class="row mt-5">
                                    <div class="col-sm-12 form-group">
                                        <label>{{__('Select experience level according to your budget')}}</label>
                                        <div class="btn-group btn-group-toggle mt-2" data-toggle="buttons">
                                            @forelse($budget_types as $btype)
                                            <?php $wage = App\PjCategories::hourly_range($btype->associated); ?>
                                            <label class="btn btn-outline-dark radio-option @isset($jobFields->budget_type) {{ $jobFields->budget_type == $btype->id ? 'active':'' }} @endisset">
                                                <h3 class="text-color-2 mt-1 mb-1">{!! $btype->icon !!}</h3>
                                                <h6 class="text-color-3">{{ $btype->heading }}</h6>
                                                <input data-min="{{ $wage->min }}" data-max="{{ $wage->max ?? '' }}" type="radio" class="reflect-this" value="{{ $btype->id }}" name="budget_type" id="btype-{{ $btype->id }}" @isset($jobFields->budget_type) {{ $jobFields->budget_type == $btype->id ? 'checked':'' }} @endisset>
                                                <small class="text-color-5">{{ $btype->description }}</small>
                                            </label>
                                            @empty
                                                <div class="alert-empty">No budget types found</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-5 form-group">
                                        <label>{{__('Payment plan')}}</label>
                                        <div class="input-group">
                                            <select name="payment_plan" id="payment_plan" class="form-control border-dark">
                                                <option value="">Select...</option>
                                                @forelse($payment_plans as $payment_plan)
                                                    <option @isset($jobFields->payment_plan) {{ $jobFields->payment_plan == $payment_plan->id ? 'selected':'' }} @endisset value="{{$payment_plan->id}}">{{ $payment_plan->heading}}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-5 form-group">
                                        <label>{{__('Hourly wage')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><div class="border-dark input-group-text bg-dark text-orangered" id="btnGroupAddon2">{{ $currency }}</div></span>
                                            <input required min="5" placeholder="0.00" type="number" step="0.01" name="hourly_wage" id="hourly_wage" class="form-control border-dark reflect-this" value="{{ $jobFields->hourly_wage ?? '' }}" />
                                            <span class="input-group-append"><div class="border-dark input-group-text bg-dark text-orangered" id="btnGroupAddon2">/hr</div></span>
                                        </div>
                                        <div class="text-danger d-none alert-hourly-wage"><b>{{ __('Hourly wage must be according to experience level. ') }}</b></div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-12 form-group text-right">
                                        <a data-prev="4" data-next="6" data-toggle="tab" href="#location" class="btn btn-outline-info btn-prev">Prev</a>
                                        <a data-prev="4" data-next="6" data-toggle="tab" href="#deliverables" class="btn btn-info btn-next">Next</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="deliverables" role="tabpanel">{{-- STEP 6 / Budget --}}
                                <h2>{{__('Deliverables')}}</h2>
                                
                                <div class="row mt-2">
                                    <div class="col-sm-12 form-group">
                                        <p>Set a list of deliverables for this project</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-3 ">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Title</span>
                                            </div>
                                            <input id="deliverable-title" type="text" class="form-control">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Value</span>
                                            </div>
                                            <select id="deliverable-value" class="form-control">
                                                @for($i=10;$i<=100;$i+=10)
                                                    <option value="{{$i}}">{{$i}}%</option>
                                                @endfor
                                            </select>
                                            <div class="input-group-append">
                                                <button id="btn-add-deliverable" class="btn btn-dark" type="button">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="deliverable-items-container list-group">
                                            @forelse($deliverables as $deliverable)
                                                @php
                                                $item = json_decode($deliverable->meta_value);
                                                @endphp
                                                <div class="list-group-item list-group-item-action delivery-item delivery-item-{{ $deliverable->id }} d-flex justify-content-between align-items-center">
                                                    {{ $item->title }}
                                                    <div class="list-group-prepend">
                                                        <span class="badge badge-info">{{ $item->value }}%</span>
                                                        <a href="{{ route('api.removeDeliverable') }}" data-id="{{ $deliverable->id }}" data-token="{{ csrf_token() }}" class="remove-item-db ml-3">
                                                            <span class="material-icons">close</span>
                                                        </a>
                                                    </div>
                                                    <input type="hidden" name="deliverable_value" value="{{ $item->value }}"> 
                                                </div>
                                            @empty

                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-sm-12 form-group text-right">
                                        <a data-prev="5" data-next="7" data-toggle="tab" href="#budget" class="btn btn-outline-info btn-prev">Prev</a>
                                        <a data-prev="5" data-next="7" data-toggle="tab" href="#review" class="btn btn-info btn-next">Next</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="review" role="tabpanel">{{-- STEP 7 / Review --}}
                                <h2>{{__('Review')}}</h2>
                                <div class="row mt-5 form-group">
                                    <div class="col-12">
                                        <label>{{__('Project title')}}</label>
                                        <div class="reflect-mirror job_name"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Hourly wage')}}</label>
                                        <div>
                                            {{ $currency }}<span class="reflect-mirror hourly_wage"></span> /hr
                                        </div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Project Category')}}</label>
                                        <div class="reflect-mirror category"></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Description')}}</label>
                                        <div class="reflect-mirror description"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('End-user')}}</label>
                                        <div class="reflect-mirror end_user"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Target')}}</label>
                                        <div class="reflect-mirror target"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Objetive')}}</label>
                                        <div class="reflect-mirror objetive"></div>
                                    </div>
                                </div>
                                <hr class="hr">
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('How long do you expect this project to last')}}</label>
                                        <div class="reflect-mirror duration"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('What type of project do you have')}}</label>
                                        <div class="reflect-mirror type"></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Skillset needed')}}</label>
                                        <div class="skills-container-mirror"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Location')}}</label>
                                        <div class="reflect-mirror location"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Location details')}}</label>
                                        <div class="reflect-mirror location_detail"></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <div class="col-12">
                                        <label>{{__('Experience level')}}</label>
                                        <div class="reflect-mirror budget_type"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-12 form-group text-right">
                                        <a data-prev="6" data-next="7" data-toggle="tab" href="#deliverables" class="btn btn-outline-info btn-prev">Prev</a>
                                        <button type="submit"  class="btn btn-info btn-next">{{ Request::is('account/edit-job/*') ? 'Save Job':'Add Job' }}</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection
