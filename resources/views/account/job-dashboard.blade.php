@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.js') }}"></script>
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
                    <h6>{{ $job->title }}</h6>
                    <hr>
                    <div class="stats-container container">
                        <div class="row mb-4">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        BRRE Status
                                        <a class="float-right text-primary" target="_blank" href="https://wearejobe.com/metodologia/">Learn more</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @forelse($stages as $stage)
                                                @php
                                                $job_stages_arr = (array) $job_stages;
                                                
                                                $existe = array_search($stage->key,array_column($job_stages_arr,'meta_value'));
                                                if($existe===false){
                                                    $status_suffix = 'dark';
                                                    $done_icon = '<span class="material-icons">schedule</span>';
                                                }else{
                                                    $status_suffix = 'success';
                                                    $done_icon = '<span class="material-icons">done</span>';
                                                }
                                                @endphp
                                                    <div class="col-sm-3 job-stage-item stage-cont-{{ Str::kebab($stage->heading) }} text-center text-{{ $status_suffix }}">
                                                        <h1 class="icon-stage">{!! $stage->icon !!}</h1>
                                                        <span class="badge badge-{{ $status_suffix }} md-24">
                                                            {!! $done_icon !!}
                                                            <b>{{ $stage->heading }}</b>
                                                        </span>
                                                    </div>
                                                
                                            @empty

                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="card">
                                    <div class="card-header">
                                        Tasks
                                        <div class="float-right">
                                            <span class="badge badge-info pull-right">
                                                {{ $tasksNumber ?? '0' }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($currentTask)
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <small>Current working task</small>
                                                <h6 class="mb-0">{{ $currentTask->title ?? ''}}</h6>
                                            </div>
                                            <div class="col-sm-4 text-right">
                                                <span class="badge badge-info mt-3">
                                                    {{ App\WorkInterval::humanDate($currentTask->deadline) ?? ''}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <ul class="list-group mt-3">
                                    @forelse ($events as $event)
                                        @if($event->status == 'pending')
                                        <li class="list-group-item job-event-item evt-item-{{ $event->id }}">
                                            @if($usertype == 'bj')
                                            <div class="right-toolbar float-right">
                                                <a class="btn-change-event-date" data-id="{{ $event->id }}" href="{{route('api.changeJobEventDate')}}" data-token="{{ csrf_token() }}">
                                                    <span class="material-icons">today</span>
                                                    <span class="new-event-date"></span>
                                                </a>
                                                <a class="btn-done-event" data-id="{{ $event->id }}" href="{{route('api.changeJobEventStatus')}}" data-token="{{ csrf_token() }}">
                                                    <span class="material-icons">check</span>
                                                </a>
                                            </div>
                                            @endif
                                            <h6 class="mb-0">{{ $event->title }}</h6>
                                            <small class="text-info date-label">{{ App\Main::humanDate($event->date) }}</small>
                                            <div>{{ $event->description }}</div>
                                        </li>
                                        @endif
                                    @empty
                                        
                                    @endforelse
                                </ul>
                                <ul class="list-group mt-3 list-group-deliverables">
                                    @forelse ($j_deliverables as $deli)
                                        @php
                                        $deli_data = json_decode($deli->meta_value);
                                        $deli_percentage = App\Tasks::calcDeliPercentage($deli->id);
                                        @endphp
                                        <li class="list-group-item job-delivery-item deli-item-{{ $deli->id }}">
                                            <span class="deli-progress" style="width:{{$deli_percentage}}%"></span>
                                            <h6 class="mb-0">{{ $deli_data->title }}</h6>
                                            <small class="text-info date-label">{{ $deli_data->value }}% {{__('job value')}}</small>
                                        </li>
                                    @empty
                                        
                                    @endforelse
                                </ul>
                            </div>
                            @if($job->status !='finished')
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        Worked time this week
                                    </div>
                                    <div class="card-body text-center">
                                        <h3><span class="badge badge-info">{{ App\WorkInterval::weekTimeCounter($job->id) ?? ''}}</span></h3>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-header">
                                        Total worked time
                                    </div>
                                    <div class="card-body text-center">
                                        <h3><span class="badge badge-info">{{ App\WorkInterval::jobTimeCounter($job->id) ?? ''}}</span></h3>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection