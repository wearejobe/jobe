@extends('main')

@section('body')
@push('head')
<link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.min.css') }}" type="text/css" />
<script src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
@endpush
<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row justify-content-center"> 
            <div class="col-sm-8">
                <div class="p-5">

                    <div class="alert alert-danger">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-text">
                            Lo sentimos esta página caducó, no existe o no tienes permiso para ingresar.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection