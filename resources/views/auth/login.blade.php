<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Doc_Tracking') }}</title>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('js/validation.js') }}"></script>

    </head>
    <body>
        <div id="app">
            <div class="login">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-8">
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <div class="loginContent">
                                <h2 class="text-center">DOCUMENT <br> & <br> INSURANCE <br> TRACKER</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="login-bg">
                                <form method="POST" action="{{ route('login') }}" id="login-form">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="col-form-label">{{ __('Employee ID') }}</label>
                                        <div class="">
                                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" autocomplete="off" autofocus>
                                            @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>                                    
                                    <div class="mb-3">
                                        <label for="password" class=" col-form-label ">{{ __('Password') }}</label>
                                        <div class="">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="off">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="">
                                            <button type="submit" id="login" class="btn btn-primary">{{ __('Login') }} </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
    $(document).ready(function () {
        $('#login-form').validate({
            rules: {
                username: {
                    required: true,
                    alphanumeric: true 
                },
                password: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: "Please enter your Employee ID",
                    alphanumeric: "Employee ID must be letters and numbers only"
                },
                password: {
                    required: "Please enter your password"
                }
            },
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    
        // Trim input
        $('input').on('input', function () {
            $(this).val($(this).val().trim());
        });
    });
</script>    