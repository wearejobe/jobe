@extends('main')

@section('body')


<div class="container">

    <div class="card card-main-content no-border">
        <h3 class="page-title">{{ __('Welcome,') }} {{ Auth::user()->name }}</h3>
        <div class="p-3">        
            <div class="card-body">
                
            </div>
        </div>
    </div>

    
</div>
@endsection
