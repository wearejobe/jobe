@extends('main')

@section('body')
@push('head')

@endpush

<div class="container wizard new-job-wizard">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-4 dark-sidebar">
                <div class="p-5">
                    {{-- <div class="steps-indicators">
                        <ul class="steps-group">
                            <li class="step-item step-1-indicator ready">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Title')}}
                            </li>
                            <li class="step-item step-2-indicator">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Description')}}
                            </li>
                            <li class="step-item step-3-indicator">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Details')}}
                            </li>
                            <li class="step-item step-4-indicator">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Location')}}
                            </li>
                            <li class="step-item step-5-indicator">
                                <span class="material-icons done">check_circle</span>
                                <span class="material-icons waiting">radio_button_unchecked</span>
                                {{__('Review')}}
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>
            <div class="col-sm-8">
                <div class="p-5">
                    <h1>Job feed</h1>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection