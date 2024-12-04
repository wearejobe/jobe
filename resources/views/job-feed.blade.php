@extends('main')

@section('body')


<div class="container">

    <div class="card card-main-content no-border">
        <div class="row">
            <div class="col-sm-9 jobs-feed-col p-5">
                @forelse ($jobs as $job)
                    <div class="card mb-5">
                        <div class="card-body bg-light">
                            <h5>
                                <a class="text-color-5" href="{{ route('viewJob',['id'=>$job->id, 'slug'=>$job->slug]) }}">
                                    {{ $job->title }}
                                </a>
                            </h5>
                            <div class="row">
                                <div class="col">
                                    <small class="text-muted">
                                        {{ App\Main::localizeAndHuman($job->updated_at) }}
                                    </small>
                                </div>
                                <div class="col text-right">
                                    <span class="badge badge-success">{{ $job->catname }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="job-description mt-3">
                                {{ Str::words($job->description,50,'...') }}
                            </div>
                        </div>
                        <nav class="navbar nav-job navbar-dark bg-dark">
                            <div>                            
                                <div class="navbar-text text-center">
                                    <small><b><span class="material-icons">store</span> {{ $job->company_name }}</b></small>
                                </div>
                                <div class="navbar-text text-center">
                                    <small><b>{{__('Level: ')}}{{ __($job->budget_type) }}</b></small>
                                </div>
                                <div class="navbar-text">
                                    <small><b>{{ App\User::getUserCurrency() }}{{ $job->hourly_wage }} /hr</b></small>
                                </div>
                            </div>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link text-success" href="{{ route('viewJob',['id'=>$job->id, 'slug'=>$job->slug, 'apply'=>'true']) }}">Apply</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                @empty
                    <div class="empty-alert">{{ __('tr_jobs.not_found') }}</div>
                @endforelse
            </div>
            <div class="col-sm-3 bg-dark">
                <div class="pr-3 pl-1">
                    <h5 class="text-white-90 mt-4 mb-3">{{ __('tr_jobs.filters_title') }}</h5>
                    <div class="frm-filters-container">
                        <form action="" method="get">
                            <div class="form-group mb-2">
                                <input type="text" name="k" class="form-control" placeholder="{{__('tr_general.search')}}">
                            </div>
                            <div class="form-group mb-2">
                                <button type="submit" class="btn btn-block btn-small btn-primary">{{__('tr_general.apply_filters')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- container --}}
@endsection
