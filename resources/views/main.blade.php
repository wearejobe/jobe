<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        


        <title>Jobe @yield('title')</title>

        <!-- Fonts -->

        <link rel="stylesheet" href="{{asset('css/plugins/bootstrap.min.css')}}" />
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700" rel="stylesheet">
        

        <link rel="apple-touch-icon" sizes="57x57" href="{{asset('images/favicon/apple-icon-57x57.png')}}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{asset('images/favicon/apple-icon-60x60.png')}}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{asset('images/favicon/apple-icon-72x72.png')}}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{asset('images/favicon/apple-icon-76x76.png')}}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{asset('images/favicon/apple-icon-114x114.png')}}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{asset('images/favicon/apple-icon-120x120.png')}}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{asset('images/favicon/apple-icon-144x144.png')}}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{asset('images/favicon/apple-icon-152x152.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/favicon/apple-icon-180x180.png')}}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('images/favicon/android-icon-192x192.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{asset('images/favicon/favicon-96x96.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon/favicon-16x16.png')}}">
        <link rel="manifest" href="{{asset('images/favicon/manifest.json')}}">
        <meta name="msapplication-TileColor" content="#f1f1f1">
        <meta name="msapplication-TileImage" content="{{asset('images/favicon//ms-icon-144x144.png')}}">
        <meta name="theme-color" content="#f1f1f1">
        


        <script src="{{asset('js/plugins/jquery-3.4.1.min.js')}}"></script>
        <script src="{{asset('js/plugins/popper.min.js')}}"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/04ccb51404.js" crossorigin="anonymous"></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-168794763-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-168794763-1');
        </script>
        <script src="{{asset('js/jobe.js')}}"></script>
 
        

        @stack('head')
        
        <link href="{{ URL::asset('fonts/jobeicons/css/jobeicons.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/colors.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('css/jobe.css') }}" rel="stylesheet">

        
    </head>
    <body class="body ppage page-{{ $view_name }} type-{{ App\User::getUsertype() ?? '' }} @yield('bodycls')">
        <header class="common-header pt-3 mb-3">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col">
                        <a href="{{ route('start') }}">
                            <img src="{{ URL::asset('images/logo.png') }}" class="header-brand ml-3" />
                        </a>
                    </div>
                    <div class="col">
                    <ul class="nav justify-content-end">
                        {{-- @if(count(config('app.languages')) > 1) --}}
                            <li class="nav-item dropdown d-md-down-none">
                                <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    {{ strtoupper(app()->getLocale()) }}
                                </a>
                                <?php 
                                    $client_languages = [
                                            'en' => 'English',    
                                            'es' => 'Español'
                                    ];
                                ?>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @foreach($client_languages as $langLocale => $langName)
                                        <a class="dropdown-item {{ (app()->getLocale() == $langLocale) ? 'active':''}}" href="{{ url()->current() }}?lg={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                                    @endforeach
                                </div>
                            </li>
                        {{-- @endif --}}
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link" id="btn-jobs">{{ __('tr_general.find-jobs') }}</a>
                        </li> 
                        @auth
                        
                        <li class="nav-item">
                            <a href="{{ route('jobs') }}" class="nav-link" id="btn-jobs">{{ __('tr_general.my-jobs') }}</a>
                        </li>
                        
                        @endauth
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('tr_general.sign-in') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('welcome') }}">{{ __('tr_general.register') }}</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link notifications-link" href="{{ route('notifications') }}">
                                <span class="material-icons">notifications</span>
                                <span class="badge badge-danger">{{ App\User::checkUnread() ?? '' }}</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" href="javascript:void(0)">
                                <div class="avatar white xxs-avatar d-inline-block">
                                    {!! App\User::getAvatar(Auth::user()->id) !!}
                                </div>
                                
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu account-dropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">{{ __('tr_general.profile') }}</a>
                                {{-- <a class="dropdown-item" href="#">Messages</a> --}}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('tr_general.logout') }}
                                </a>
        
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>

                        @endguest
                    </ul>
                    </div>
                </div>
            </div>
        </header>

        @if ( session('alert-success') || session('alert-danger') || session('alert-warning') )
            <div class="container alert-container">
                <div class="alert {{ App\Main::alertClass() }}">

                    <div class="alert-icon"><span class="material-icons">{{ App\Main::alertIcon() }}</span></div>

                    <div class="alert-text">{{ App\Main::alertMessage()  }}</div>
                    <div class="alert-dismiss"><a href="javascript:void(0)" data-dismiss="alert" class=""><span class="material-icons">highlight_off</span></a></div>
                </div>
            </div>
        @endif

        <div id="main">
            @yield('body')
        </div>
    </body>
    
    <footer id="footer" class="mb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <span>© Copyright Jobe 2020 - <a class="" href="http://wearejobe.com">wearejobe.com</a> </span>
                </div>
            </div>
        </div>
    </footer>
</html>
