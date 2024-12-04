<div class="side-bar-menu">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item {{ Request::is('backend') ? 'active' : '' }}">
            <a title="{{__('Users')}}" data-toggle="tooltip" data-placement="bottom"  href="{{ route('backend.users') }}" class="text-center nav-link ">
                <span class="material-icons">group</span>
            </a>
          </li>
          <li class="nav-item {{ Request::is('backend/withdrawal-requests') ? 'active' : '' }}">
            <a title="{{__('Requests')}}" data-toggle="tooltip" data-placement="bottom"  href="{{ route('backend.withdrawal-requests') }}" class="text-center nav-link">
                <span class="material-icons">account_balance</span>
            </a>
          </li>
          <li class="nav-item {{ Request::is('backend/transfers') ? 'active' : '' }}">
            <a title="{{__('Transfers')}}" data-toggle="tooltip" data-placement="bottom"  href="{{ route('backend.transfers') }}" class="text-center nav-link">
                <span class="material-icons">inbox</span>
            </a>
          </li>
          <li class="nav-item {{ Request::is('translations') ? 'active' : '' }}">
            <a title="{{__('Translations')}}" data-toggle="tooltip" data-placement="bottom"  href="{{ route('backend.translations') }}" class="text-center nav-link">
                <span class="material-icons">translate</span>
            </a>
          </li>
    </nav>
</div>