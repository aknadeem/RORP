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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
        <link href="{{ asset('assets/css/pages/login/login-4.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <!--end::Page Custom Styles -->
        
        <!--end::Layout Skins -->
        <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon/favicon.ico') }}" />
    </head>
    <!-- end::Head -->

    <!-- begin::Body -->
    <body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

        <!-- begin:: Page -->
        <div class="kt-grid kt-grid--ver kt-grid--root">
            <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url({{ asset('assets/media/bg/bg-2.jpg') }});">
                    <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                        <div class="kt-login__container">
                            <div class="kt-login__logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('assets/media/logos/pr/vlogo-white.png') }}" style="width:120px;height: 90px;">
                                </a>
                            </div>
                            
                            <div class="">
                                <div class="kt-login__head">
                                    <h3 class="kt-login__title" style="color: #fff !important;">Forgotten Password ?</h3>
                                    <div class="kt-login__desc" style="color: #eee !important;">Enter your email to reset your password:</div>
                                </div>

                                @if (session('status'))
                                    <div class="alert alert-success fade show" role="alert">

                                        <div class="alert-text">{{ session('status') }}</div>

                                        <div class="alert-close">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                <form class="kt-form" method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="input-group validated">
                                        <input class="form-control @error('email') is-invalid @enderror" type="text" placeholder="{{ __('E-Mail Address') }}" name="email" id="email" autocomplete="off" required autocomplete="email" autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="kt-login__actions">
                                        <button type="submit" class="btn btn-brand btn-pill kt-login__btn-primary" style="background-color: #fff !important; color: #1c5b90 !important;"> {{ __('Send Password Reset Link') }} </button>&nbsp;&nbsp;

                                        <button id="kt_login_forgot_cancel" class="btn btn-danger btn-pill kt-login__btn-secondary">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}" type="text/javascript"></script>

        <script>
            $('#kt_login_forgot_cancel').click(function(e) {
                e.preventDefault();
                window.history.back();
            });
        </script>
    </body>
</html>