@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/dataTables.bootstrap4.min.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">

<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/pages/dashboard.js') }}"></script>
<script src="{{ asset('js/pages/time-sheet.js') }}"></script>
@endpush
<div class="container time-sheet">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.job-sidebar')
                
            </div>
            <div class="col-sm-9">
                <div class="p-5">
                    <h1 class="mb-5">
                        Time Sheet
                    </h1>
                    
                    <div class="time-sheets-container">
                        <div class="loader-container">
                            {{-- <span class="loading xs-avatar">loading</span> --}}
                            <table id="tbl-time-sheets" class="table table-hover table-stripe">
                                <thead>
                                    <tr>
                                        <td>Task</td>
                                        <td>Start</td>
                                        <td>End</td>
                                        <td>Time Worked</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($work_intervals as $item)
                                        <tr>
                                            <td><div class="text-truncate" data-toggle="tooltip" title="{{$item->task_title}}" style="max-width:200px"><b>{{$item->task_title}}</b></div></td>
                                            <td><small>{!! App\WorkInterval::humanDateTime($item->start) !!}</small></td>
                                            <td><small>{!! App\WorkInterval::humanDateTime($item->end) !!}</small></td>
                                            <td class="text-center"><span class="badge badge-info" style="background-color:{{$item->task_color}};">{!! App\WorkInterval::timeCounter($item->start,$item->end) !!}</span></td>
                                        </tr>  
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($usertype=='bj')
                        @if($job->status !='finished')
                        <hr>
                        <div class="bg-dark p-4 mb-5">
                            <h5 class="mb-3 text-white-75">Create report for payroll</h5>
                            <form action="{{ route('job.calcPayroll') }}" method="post">
                                @csrf
                                <input type="hidden" name="jid" value="{{$job->md5_id}}">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <div class="input-group light">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                            </div>
                                            <input type="text" required name="from" id="from" class="form-control datepicker">
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="input-group light">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">To</span>
                                            </div>
                                            <input type="text" required name="to" id="to" class="form-control datepicker">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-primary">{{__('Generate')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <form action="{{ route('job.getTimeSheets',['code'=>$job->md5_id, '_token'=> csrf_token() ]) }}" method="post" id="frm-request-time-sheets">
    </form>

    
</div>
@endsection