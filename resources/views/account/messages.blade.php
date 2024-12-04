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
                
               @include('account.bj-sidebar')
                
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1>Messages</h1>
                    
                    
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection