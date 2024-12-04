@extends('main')

@section('title', ' | ' . $job->title )
@section('body')
@push('head')
<link rel="stylesheet" href="{{asset('css/plugins/dropzone.css')}}">
<script src="{{asset('js/pages/job-view.js')}}"></script>
<script src="{{asset('js/plugins/dropzone.js')}}"></script>
@endpush
<?php 
$cfields = json_decode($c_info); 
$jfields = json_decode($j_info); 

?>

<div class="container job-view">

    <div class="card card-main-content no-border">
        <div class="row">    
            <div class="col-sm-8">
                @if ( session('alert') )
                    <div class="alert alert-info mb-4 ml-3 mt-5">
                        <div class="alert-text">{{ session('alert') }}</div>
                        <div class="alert-dismiss"><a href="javascript:void(0)" data-dismiss="alert" class=""><span class="material-icons">highlight_off</span></a></div>
                    </div>
                @endif
                <h3 class="page-title">{{ $job->title }}</h3>
                <div class="p-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="job-info">
                                <a class="badge bg-secondary text-white-90" href="javascript:void(0)">{{ $category->name }}</a>
                            </div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <div class="job-info">
                                {{__('Updated')}} {{ $job_updated }} {{__('ago')}}
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 mb-4">
                        <div class="col">
                            {!! nl2br($jfields->description ?? '') !!}
                        </div>
                    </div>
                    <hr>
                    <h4 class="text-center mt-3 mb-3">Skillset needed for this job</h4>
                    <div class="skill-set text-center mt-3 mb-4">
                        @isset($skills)
                            @foreach(json_decode($skills) as $skill)
                                <span class="badge badge-dark">{{ __($skill->name) }}</span>
                            @endforeach
                        @endisset
                    </div>
                    <hr>
                    <div class="row mt-3">
                        <div class="col-sm-4">
                            <a data-toggle="tab" class="item bg-dark d-block p-4 job-tab-item active" href="#end_user">
                                <span class="material-icons text-orangered">group</span>
                                <h6 class="text-white-75">{{__('We would help to end-user')}}</h6>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a data-toggle="tab" class="item bg-dark d-block p-4 job-tab-item" href="#target">
                                <span class="material-icons text-orangered">track_changes</span>
                                <h6 class="text-white-75">{{__('Target audience we are seeking to reach')}}</h6>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a data-toggle="tab" class="item bg-dark d-block p-4 job-tab-item" href="#objetive">
                                <span class="material-icons text-orangered">assignment_turned_in</span>
                                <h6 class="text-white-75">{{__('The objectives of this project')}}</h6>
                            </a>
                        </div>
                    </div>
                    <div class="tab-content bg-light p-5 mt-3">
                        <div class="tab-pane fade show active" id="end_user">
                            <h5>{{__('How we would help to end-user')}}</h5>
                            {{$jfields->end_user ?? '' }}
                        </div>
                        <div class="tab-pane fade" id="target">
                            <h5>{{__('Target audience we are seeking to reach')}}</h5>
                            {{$jfields->target ?? ''}}
                        </div>
                        <div class="tab-pane fade" id="objetive">
                            <h5>{{__('The objectives of this project')}}</h5>
                            {{ $jfields->objetive ?? '' }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        
                        @forelse($project_types as $pt)
                        <div class="col-sm-4">
                            <div class="item bg-light p-4 text-center">
                                {!! $pt->icon !!}
                                <h6 class="">{{ $pt->heading }}</h6>
                            </div>
                        </div>
                        @empty
                        
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-sm-4 bg-dark sidebar">
                <div class="p-4">
                    <h5 class="text-white-90 mt-4 mb-3">{{__('About ')}} {{ $company->name }}</h5>
                    <div class="info-block mb-3">
                        <label class="">{{__('Country')}}</label>
                        <span class="text-color-3">{{ $cfields->country->name }}</span>
                    </div>
                    <div class="info-block mb-3">
                        <label class="">{{__('Posted jobs')}}</label>
                        <span class="text-color-3">{{ $job_count }}</span>
                    </div>
                    <div class="info-block mb-3">
                        <label class="">{{__('Member since')}}</label>
                        <span class="text-color-3">{{ $member_since }} </span>
                    </div>
                    <hr>
                    <h5 class="text-white-90  mt-4 mb-3">{{__('Budget')}}</h5>
                    <div class="info-block mb-3">
                        <label class="">{{__('Payment')}}</label>
                        <span class="text-color-3">{{ $jfields->payment_plan->heading ?? '' }}</span>
                    </div>
                    <div class="info-block mb-3">
                        <label class="">{{__('Hourly wage')}}</label>
                        <span class="text-color-3">{{ $cfields->currency->symbol ?? '' }} {{ $jfields->hourly_wage ?? '' }} {{__('/hr')}}</span>
                    </div>
                    <div class="info-block mb-3">
                        <label class="">{{__('Experience level')}}</label>
                        <span class="text-color-3">{{ $jfields->budget_type->heading ?? '' }}</span>
                    </div>
                    <hr>
                    
                    @if($usertype == 'guest' || $usertype == 'pj')
                    
                    @if( App\Jobs::checkJobApplied($job->id) )
                        <div class="alert-info mb-3">
                            <div class="p-2 text-center text-white">
                                <h3><span class="material-icons">info</span></h3>
                                {{__('You applied for this job.')}}
                            </div>
                        </div>
                    @else
                        @if( App\User::checkUserProfile() )
                            <div class="apply-container mb-2">
                                <a id="btn-apply-mdl" {{ $usertype == 'pj' ? 'data-toggle=modal href=#mdl-apply':'href='.route('login') }} class="btn btn-block btn-success">{{__('Apply for this job')}}</a>
                            </div>
                        @else
                            <div class="alert-warning mb-3">
                                <div class="p-2 text-center text-white">
                                    <h3><span class="material-icons">info</span></h3>
                                    {{__('To apply this job you have to complete your profile first.')}}<br><br>
                                    <a class="mb-3 btn btn-dark" href="{{ route('profile') }}"><span class="material-icons">account_box</span> {{__('Complete your profile')}}</a>
                                </div>
                            </div>
                        @endif
                    @endif
                    <a href="{{ route('viewJob.save',['id'=> $job->id]) }}" class="btn btn-block btn-light">{{__('Save this job')}}</a>
                    <a href="javascript:void(0)" class="btn btn-block btn-link text-white-90"><b>{{__('Report this job')}}</b></a>
                    @else
                    
                        @if($job->status == 'published')
                            <a href="{{ route('viewJob.unpublish', ['id'=>$job->id])}}" class="btn btn-block btn-danger">{{__('Unpublish')}}</a>
                            <span class="alert alert-info mt-3">
                                <span class="p-3">
                                    {{__('This job is')}} 
                                    <b class="text-uppercase">{{ $job->status }}</b>
                                </span>
                            </span>
                        @else
                            @if($ismyjob)
                                @if($job->status!="hired" && $job->status!="finished")
                                <a href="{{ route('viewJob.publish',['id'=>$job->id])}}" class="btn btn-block btn-success">{{__('Publish this job')}}</a>
                                <span class="alert alert-info mt-3">
                                    <span class="p-3">
                                        {{__('This job is')}} 
                                        <b class="text-uppercase">{{ $job->status }}</b>
                                    </span>
                                </span>
                                @else
                                <span class="alert alert-info mt-3">
                                    <span class="p-3">
                                        <span class="material-icons">check</span> 
                                        <b class="text-uppercase">{{ $job->status }}</b>
                                    </span>
                                </span>
                                @endif
                            @endif
                        @endif
                        @if($ismyjob)
                            @if($job->status!="hired" && $job->status!="finished")
                                <a href="{{ route('edit-job',['id'=>$job->id]) }}" class="btn btn-block btn-outline-light mt-5">{{__('Edit job')}}</a>
                            @endif
                        <a href="{{ route('jobs') }}" class="btn btn-block btn-outline-light">{{__('My jobs')}}</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    
</div>


<div class="modal fade" id="mdl-apply" tabindex="-1" area-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content no-border">
            <div class="apply-form-content">
                
                <form id="frm-apply" action="{{ route('viewJob.apply') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $job->id }}" />
                    <div class="modal-body">
                        <label class="mb-0 text-color-4"><strong>Apply for</strong></label>
                        <h4 class="mt-0 mb-3 text-color-5">{{ $job->title }}</h4>
                        <hr>
                        <div class="form-group">
                            <label for="cover-letter">{{__('Write a cover letter for this position')}}</label>
                            <textarea required name="description" id="description" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cover-letter">{{__('Attach files')}}</label>
                            <div id="apply-files" class="drop-zone p-3 text-center bg-dark">
                                <div class="dz-message"><label class="text-white-90">{{__('Drop files here')}}</label></div>
                            </div>
                            <input type="hidden" id="uploads-folder" value="{{route('upload')}}">
                            <input type="hidden" id="rm-file-url" value="{{route('frm-file')}}">
                            <div id="apply-uploaded-files"></div>
                        </div>
                    </div>
                    <div class="card-footer bg-dark">
                        <button type="submit" id="btn-apply" class="btn btn-success float-right">{{__('Apply')}}</button>
                        <button id="btn-cancel-apply" data-dismiss="modal" class="btn btn-outline-light text-white-75">{{__('Cancel')}}</button>
                    </div>
                </form>
            </div>
            <div class="applied-content d-none">
                <div class="modal-body">
                    <h3 class="text-center mb-3">{{__('Application sent')}}</h3>
                    <div class="p-5 text-center">
                        <p>Thank you for applying for <b>{{ $job->title }}</b>.</p>
                        <p>Your application has already sent to <b>{{ $company->name }}</b>.</p>
                        <button data-dismiss="modal" class="btn btn-dark mt-5">{{__('Done')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- Modal -->
