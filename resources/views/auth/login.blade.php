<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Royal App') }}</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700?v=1">

    <link href="{{ asset('assets/css/pages/login/login-4.css?v=1') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/style.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles -->
    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon/favicon.ico?v=1') }}" />
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
                style="background-image: url(assets/media/bg/bg-2_2.jpg);">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container">
                        <div class="kt-login__logo">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/media/logos/pr/vlogo-white.png?v=1') }}"
                                    style="width:120px;height: 90px;">
                            </a>
                        </div>
                        <div class="kt-login__signin mt-0">
                            <div class="kt-login__head">
                                <h3 class="kt-login__title">{{ __('Login') }}</h3>
                            </div>
                            <form class="kt-form" method="POST" action="{{ route('customlogin') }}">
                                @csrf

                                <div class="input-group validated">
                                    <input class="form-control @error('email') is-invalid @enderror" type="text"
                                        placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}"
                                        required autocomplete="off" autofocus>

                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="input-group">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                        placeholder="{{ __('Password') }}" name="password" required autocomplete="off">

                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                                {{-- <div class="row kt-login__extra">
                                    @if (Route::has('password.request'))
                                    <div class="col kt-align-right">
                                        <a href="{{ route('password.request') }}" id="kt_login_forgot"
                                            class="kt-login__link" style="color: #fff !important;">
                                            {{ __('Forgot Your Password ?') }} </a>
                                    </div>
                                    @endif
                                </div> --}}
                                <div class="kt-login__actions">
                                    <button type="submit" {{-- id="kt_login_signin_submit" --}}
                                        class="btn btn-brand btn-pill kt-login__btn-primary"
                                        style="background-color: #fff !important; color: #1c5b90 !important;"> {{
                                        __('Login') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Page -->
</body>
<!-- end::Body -->

</html>