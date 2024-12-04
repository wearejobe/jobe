@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/plugins/easytimer.min.js') }}"></script>
<script src="{{ asset('js/pages/dashboard.js') }}"></script>
<script src="{{ asset('js/pages/job-tasks.js') }}"></script>

@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.job-sidebar')
                
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1 class="mb-5">
                        @if($job->status !='finished')
                        <a href="#mdl-new-task" data-toggle="modal" class="btn btn-link float-right">Add task</a>
                        @endif
                        Tasks
                    </h1>
                    <div class="list-group">

                        @forelse ($tasks as $task)
                        @php
                        $daysuntildeadline = App\Main::diffDays($task->deadline);
                        $taskDateColor = '';
                        $pastDate = App\Main::checkPastDate($task->deadline);
                        if($pastDate && $task->status != 'done'):
                            $taskDateColor = 'fa-exclamation-circle text-danger';
                        else:
                            if($daysuntildeadline > 4):
                                $taskDateColor = 'fa-circle text-success';
                            elseif($daysuntildeadline > 1 && $daysuntildeadline < 5):
                                $taskDateColor = 'fa-circle text-warning';
                            elseif($daysuntildeadline < 2):
                                $taskDateColor = 'fa-circle text-danger';
                            endif;
                            if($task->status == 'done'):
                                $taskDateColor = 'fa-check-circle text-success';
                            endif;
                        endif;
                        @endphp
                        <a href="#task_{{$task->id}}" class="list-group-item list-group-item-action task-item {{ $task->status }}" data-toggle="modal" >
                            <h6 class="item-title m-0">
                                <span class="badge badge-{{$task->status_color}} text-uppercase float-right mt-2">{{$task->status}}</span>
                                {{ $task->title }}<br>
                                <small class="text-muted"><i class="fa {{$taskDateColor}} mr-1"></i>{{ App\Main::humanDate($task->deadline) }}</small>
                            </h6>
                        </a>
                        <div class="modal fade {{ ( $task->status == 'working') ? 'show active':'' }}" id='task_{{$task->id}}'>
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content no-border">
                                    <div class="card no-border">
                                        <div class="card-body">
                                            <div class="task-description pb-3">
                                                <h4 class="mb-0">{{ $task->title }}</h4>
                                                <small class="text-muted">{{ $task->deliverable }}</small>
                                                <hr>
                                                <div class="mt-3 description">
                                                    {!! nl2br($task->description) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-semi-dark">
                                            <div class="task-info text-white-75">
                                                <div class="row text-center">
                                                    {{-- @if($task->status!='done' && $task->status!='paused') --}}
                                                    @if($task->workInterval)
                                                    <div class="col-sm-4">
                                                        <label>Work Time</label><br>
                                                        <span class="{{$task->workInterval->id}} badge badge-info timer-{{ $task->status }}">{!! App\WorkInterval::taskTimeCounter($task->id) !!}</span>
                                                    </div>
                                                    @endif 
                                                    {{-- @else
                                                        <div class="col-sm-4">
                                                            <label>Task Total Time</label><br>
                                                            <span class="badge badge-info">{!! App\WorkInterval::totalTimeCounter($task->id) !!} hrs.</span>
                                                        </div>
                                                    @endif --}}
                                                    
                                                    @if($task->start_time_on!='')
                                                    <div class="col-sm-4">
                                                        <label>Started</label><br>
                                                        <span class="badge badge-info">{{$task->start_time_on}}</span>
                                                    </div>
                                                    @endif
                                                    <div class="col-sm-4">
                                                        <label>Status</label><br>
                                                        <span class="badge badge-{{$task->status_color}} text-uppercase">{{$task->status}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-dark task-{{ $task->status}}">
                                            @if($usertype == 'pj')
                                            <button data-tid="{{ $task->id }}" data-jid="{{ $task->job_id }}" data-service="{{ route('task.startInterval') }}" data-token="{{ csrf_token() }}" class="btn btn-sm btn-outline-success btn-start-interval">Start</button>
                                            <button data-tid="{{ $task->id }}" data-jid="{{ $task->job_id }}" data-service="{{ route('task.startInterval') }}" data-token="{{ csrf_token() }}" class="btn btn-sm btn-outline-success btn-continue">Continue</button>
                                            
                                            <button data-service="{{ route('task.stopInterval') }}" data-token="{{ csrf_token() }}" class="btn btn-sm btn-outline-info btn-pause">Pause</button>
                                            <button data-service="{{ route('task.stopInterval',['task'=>$task->id,'task_status'=>$task->status]) }}" data-token="{{ csrf_token() }}" class="btn btn-sm btn-outline-info btn-done">Done</button>
                                            @endif
                                            {{-- <button class="btn btn-sm btn-outline-info btn-edit">Edit</button> --}}
                                            
                                            <button data-dismiss="modal" class="btn btn-sm btn-light float-right">Close</button>
                                            @if($task->status=='pending')
                                            <a href="{{ route('task.delete',['code'=>$task->md5_id ]) }}" class="btn btn-sm btn-danger btn-delete-task float-right mr-2">Delete</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if( $task->status == 'working')
                        <script>
                            jQuery(function(e){
                                $('#task_<?=$task->id?>').modal('show');
                            });
                        </script>
                        @endif
                        @empty
                        <div class="empty-alert">
                            {{__('No tasks')}}
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id='mdl-new-task'>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content no-border">
            <div class="card no-border">
                <form id="frm-new-task" action="{{route('addTask')}}" method="post">
                    @csrf
                    <input type="hidden" value="{{$job->id}}" name="jid" />
                    <div class="card-body">
                        <div class="task-description pb-3">
                            <h4 class="mb-3">{{__('New task')}}</h4>
                            <div class="form-group">
                                <label for="task_title">Title</label>
                                <input required type="text" class="form-control" name="title" placeholder="Write a document" >
                            </div>
                            @isset($job_deliverables)
                            <div class="form-group">
                                <label for="task_title">Deliverable</label>
                                <select name="deliverable" class="form-control">
                                    @forelse ($job_deliverables as $item)
                                        @php
                                        $item_data = json_decode($item->meta_value);
                                        @endphp   
                                        <option value="{{ $item->id }}">{{ $item_data->title }}</option> 
                                    @empty
                                        
                                    @endforelse
                                </select>
                            </div>
                            @endisset
                            <div class="form-group">
                                <label for="task_title">Description</label>
                                <textarea name="description" required class="form-control" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="task_title">Deadline</label>
                                <div class="input-group">
                                    <input type="text" required name="deadline" readonly class="form-control datepicker" placeholder="2020-28-12" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-dark">
                        <button data-dismiss="modal" class="btn btn-sm btn-outline-danger">Cancel</button>
                        <button type="submit" id="btn-new-task" class="btn btn-sm btn-success btn-add-task float-right">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
</div>
@endsection