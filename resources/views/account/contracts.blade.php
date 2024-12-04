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
                    <h1 class="mb-5">Contracts</h1>
                    
                    <div class="list-group contracts">
                        @forelse($contracts as $contract)
                        <div class="list-group-item flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <small>{{ $contract->name }} {{__('wants to work in your project')}}</small>
                                <small>{{ $contract->created_at }} {{__('ago')}}</small>
                            </div>
                            <h5 class="mb-1">{{ $contract->title }}</h5>
                            <p class="mb-1">{{ $contract->description }}</p>
                            <div class="text-right">
                                <a href="{{ route('contract.view', ['id'=>$contract->md5_id]) }}" class="btn btn-small btn-primary">{{ __('View application')}}</a>
                            </div>
                        </div>
                        @empty
                        <div class="empty-alert">
                            {{__('No contracts')}}
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection