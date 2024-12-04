<div class="side-bar-menu mt-4 pb-5">
    <div class="list-group dark-mode">
        <a href="{{ route('job.pjDashboard',['code'=>$job->md5_id]) }}" class="list-group-item list-group-action no-round {{ Request::is('job/dashboard/*') ? 'active' : '' }}">
            <span class="material-icons">dns</span>
            {{__('Dashboard')}}
        </a>
        <a href="{{ route('job.tasks',['code'=>$job->md5_id]) }}" class="list-group-item list-group-action no-round {{ Request::is('job/tasks/*') ? 'active' : '' }}">
            <span class="material-icons">assignment_turned_in</span>
            {{__('Tasks')}}
        </a>

        <a href="{{ route('job.timeSheet',['code'=>$job->md5_id]) }}" class="list-group-item list-group-action no-round {{ Request::is('job/time-sheet/*') ? 'active' : '' }}">
            <span class="material-icons">history</span>
            {{__('Time Sheet')}}
        </a>
        @if($usertype=='pj')
        <a href="{{ route('job.payments',['code'=>$job->md5_id]) }}" class="list-group-item list-group-action no-round {{ Request::is('job/payments/*') ? 'active' : '' }}">
            <span class="material-icons">attach_money</span>
            {{__('Payments')}}
        </a>
        @endif
        @if($usertype=='bj' )
        <hr>
        <div class="p-3 text-center">
            
            @isset($job->worker_id)
            <h6 class="text-muted">{{__('Professional Jobe')}}</h6>
            <div class="avatar-container">
                <div class="avatar white md-avatar mb-2 d-inline-block">
                    {!! App\User::getAvatar($job->worker_id) !!}
                </div>
            </div>
            <h5 class="text-info">{{ $job->worker_name ?? '' }}</h5>
            @endisset
        </div>
        @if($job->status !='finished')
            <hr class="divider-dark">
            <div class="p-3 text-center {{ Request::is('job/finish/*') ? 'd-none' : '' }} {{ Request::is('job/finish-rate/*') ? 'd-none' : '' }}">
                <form id="frm-finish-job" action="{{route('job.finish',['code'=>$job->md5_id])}}" method="post">
                    @csrf
                    <button type="button" id="finish-job" class="btn btn-primary">Finish job/project</button>
                </form>
            </div>
        @endif
        @endif
    </div>
</div>