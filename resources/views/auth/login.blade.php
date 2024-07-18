@extends('layouts.app')

@section('content')
    <div class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8 logo">
                    <img src="/images/logo.svg" />
                </div>
            </div>
            <div class="row justify-content-center">

                <div class="col-md-4">
                    <div class="login-bg">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="col-form-label">{{ __('Email Address') }}</label>

                                <div class="">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class=" col-form-label ">{{ __('Password') }}</label>

                                <div class="">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="mb-0">
                                <div class="">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    {{--                                        @if (Route::has('password.request'))--}}
                                    {{--                                            <a class="btn btn-link" href="{{ route('password.request') }}">--}}
                                    {{--                                                {{ __('Forgot Your Password?') }}--}}
                                    {{--                                            </a>--}}
                                    {{--                                        @endif--}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
