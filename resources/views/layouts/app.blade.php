<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Doc_Tracking') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <link rel="dns-prefetch" href="//fonts.bunny.net"> --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/material_green.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}" nonce="wUDPhZ1Z60inspnMCukimCi">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/apexchart.js') }}"></script>
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/secure-reveal.js') }}"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand border-0">
                    <img src="/images/logo1.svg" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- <div class="dropdown">
                                <a class="nav-item dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->first_name }}
                                        @if(Auth::user()->middle_name)
                                            {{ Auth::user()->middle_name }}
                                        @endif
                                        {{ Auth::user()->last_name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item">{{ __('Logout') }}</a></li>
                                </ul>
                            </div> --}}
                            <li class="nav-item dropdown profileDrop">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="userIcon">
                                        <img src="/images/user-solid.svg" />
                                        {{-- <img src="/images/logoIcon.svg" /> --}}
                                    </div>

                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                        {{ Auth::user()->first_name }}
                                        @if(Auth::user()->middle_name)
                                            {{ Auth::user()->middle_name }}
                                        @endif
                                        {{ Auth::user()->last_name }}
                                        <div class="empId">{{ Auth::user()->employee_id }} - {{ Auth::user()->roles->value('name') != 'super_admin' ? ucwords(str_replace('-', ' ', Auth::user()->roles->value('name'))) : 'ID Maintenance' }} </div>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        @if(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker','ins-ho-user','ins-admin','master','ro-user']) && Auth::user()->ins_user == '1')
                                         <a target="_blank" class="dropdown-item" href="{{ route('insurance_dashboard') }}">
                                            {{ __('Insurance') }}
                                        </a>
                                        @endif
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                        <a class="dropdown-item" href="{{ route('users.index') }}"> Admin Panel</a>
                                        @endunless
                                        <a class="dropdown-item logout">{{ __('Logout') }} </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="">
            @yield('content')
        </main>
    </div>
    @include('layouts.scripts')
    <button id="backToTopBtn" title="Go to top">↑</button>
</body>
</html>
