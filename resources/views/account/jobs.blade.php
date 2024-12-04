@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/pages/jobs.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-3 bg-dark pr-0">
                
               @include('account.jobs-sidebar')
                
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1>{{$company->name}} <span class="text-lowercase">{{__('tr_jobs.jobs')}}</span></h1>
                    
                    <div class="table-container mt-5">
                        <table id="tbl-jobs" class="table table-hover">
                            <tbody>
                                @forelse($jobs as $job)
                                <tr>
                                    <td>
                                        <h6 class="mb-0"><a href="{{ route('viewJob', ['id'=>$job->id, 'slug'=>$job->slug]) }}">{{ $job->title }}</a></h6>
                                        <small><span class="text-uppercase state-text {{ $job->status }}">{{ $job->status }}</span> | {{__('Created ')}} {{ $job->created_ht }} {{__('ago')}}</small>
                                    </td>
                                    <td class="text-center">

                                        @if($job->status == 'hired' || $job->status == 'finished')
                                        <a href="{{ route('job.pjDashboard', ['code'=>$job->md5_job_id]) }}" class="btn btn-sm btn-success text-uppercase">{{__('Dashboard')}}</a>
                                        @else
                                        <a href="{{ route('edit-job',['id'=>$job->id]) }}" class="btn btn-sm btn-outline-dark text-uppercase">{{__('Edit')}}</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <div class="empty-alert">
                                    <div class="row">
                                        <div class="col">
                                            {{__('tr_jobs.not_found')}}. <a class="btn btn-outline-dark float-right btn-sm" href="{{ route('new-job') }}">{{__('tr_jobs.post_new_job')}}</a>
                                        </div>
                                    </div>
                                </div>
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