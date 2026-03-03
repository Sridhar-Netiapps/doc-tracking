<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Doc_Tracking') }}</title>
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/material_green.css') }}">
        <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}" nonce="wUDPhZ1Z60inspnMCukimCi">
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset('js/select2.min.js') }}"></script>
        <script rel="stylesheet" src="{{ asset('js/sweetalert.min.js') }}"></script>
        {{-- <script src="{{ asset('js/sweetalert2.all.min.js') }}" nonce="wUDPhZ1Z60inspnMCukimCi"></script> --}}
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/validation.js') }}"></script>
        <script src="{{ asset('js/apexchart.js') }}"></script>
        <script src="{{ asset('js/flatpickr.js') }}"></script>
        <script src="{{ asset('js/flatpickr.min.js') }}"></script>
        <script src="{{ asset('js/secure-reveal.js') }}"></script>
    </head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand border-0" href="{{ url('/home') }}">
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
                            <li class="nav-item dropdown profileDrop">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="userIcon">
                                        <img src="/images/user-solid.svg" />
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
                                        <a class="dropdown-item logout">
                                            {{ __('Logout') }}
                                        </a>
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
        <main class="page-container">
            <div class="row h-100 px-0">
                <div class="col-2 bg-tran-white h-100 px-0 position-fixed">
                    <div class="leftMenu">
                        @include('layouts.sidemenu')
                    </div>
                </div>
                <div class="col-10 px-0 pushLeft">
                    @yield('content')
                </div>
            </div>
        </main>
        <div id="confirmModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-0">
                    <div class="modal-body text-center">
                        <h4 class="p-2">Are You Sure You Want to Submit this Form?</h4>
                        <div>

                        </div>
                    </div>
                    <div class="modal-footer flex-nowrap p-0">
                        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end no"  data-dismiss="modal"><strong>Cancel</strong></button>
                        <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 yes" data-bs-dismiss="modal"><strong>Submit</strong></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.scripts')
</body>
</html>
