<div class="side-bar-menu mt-4 pb-5">
    <div class="list-group dark-mode">
        <a href="{{ route('jobs') }}" class="list-group-item list-group-action no-round {{ Request::is('account/jobs') ? 'active' : '' }}">
            <span class="material-icons">dns</span>
            {{__('My jobs')}}
        </a>
        <a href="{{ route('contracts') }}" class="list-group-item list-group-action no-round {{ Request::is('account/contracts') ? 'active' : '' }}">
            <span class="material-icons">contacts</span>
            {{__('Contracts')}}
        </a>
        {{-- <a href="{{ route('notifications') }}" class="list-group-item list-group-action no-round {{ Request::is('account/notifications') ? 'active' : '' }}">
            <span class="material-icons">notifications</span>
            {{__('Notifications')}}
        </a>
        <a href="{{ route('messages') }}" class="list-group-item list-group-action no-round {{ Request::is('account/messages') ? 'active' : '' }}">
            <span class="material-icons">inbox</span>
            {{__('Messages')}}
        </a> --}}
        <a href="{{ route('new-job') }}" class="list-group-item list-group-action no-round">
            <span class="material-icons">add_circle_outline</span>
            {{__('Post new job')}}
        </a>
    </div>
</div>