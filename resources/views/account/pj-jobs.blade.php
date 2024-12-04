@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.jobs-sidebar')
                
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1>{{__('tr_jobs.jobs')}}</h1>
                    
                    <div class="table-container mt-5">
                        <table id="tbl-jobs" class="table table-hover">
                            <tbody>
                                @forelse($applications as $job)
                                <tr>
                                    <td>
                                        <h6 class="mb-0"><a href="{{ route('viewJob', ['id'=>$job->job_id, 'slug'=>$job->slug]) }}">{{ $job->title }}</a></h6>
                                        @if($job->userHired)
                                            <small>
                                                {{__('Hired ')}} {{ $job->userHired->created_ht }} {{__('ago')}} 
                                                | 
                                                {{ $job->CompanyName }}
                                            </small>
                                        @else
                                        <small>
                                            {{__('Appied ')}} {{ $job->created_ht }} {{__('ago')}} 
                                            | 
                                            {{ $job->CompanyName }}
                                        </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($job->userHired)
                                        <a href="{{ route('job.pjDashboard', ['code'=>$job->md5_job_id]) }}" class="btn btn-sm btn-success text-uppercase">{{__('Dashboard')}}</a>
                                        @endif
                                        <a href="{{ route('contract.view', ['id'=>$job->md5_id]) }}" class="btn btn-sm btn-info text-uppercase">{{__('View')}}</a>
                                    </td>
                                </tr>
                                @empty
                                <div class="empty-alert">{{__('No jobs found.')}}</div>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection