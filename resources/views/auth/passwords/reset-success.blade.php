<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Royal App') }}</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->

    <link href="{{ asset('assets/css/pages/login/login-4.css?v=1') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/plugins/global/plugins.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/css/style.bundle.css?v=1') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles -->

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon/favicon.ico?v=1') }}" />
</head>
<body
    class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v4 kt-login--signin" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor"
                style="background-image: url({{ asset('assets/media/bg/bg-2.jpg?v=1') }});">
                <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                    <div class="kt-login__container mt-5">
                        <div class="kt-login__logo pt-5">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('assets/media/logos/pr/vlogo-white.png?v=1') }}"
                                    style="width:120px;height: 90px;">
                            </a>
                        </div>
                        <div class="kt-login__signin">
                            <div class="kt-login__head">
                                {{-- <h3 class="kt-login__title" style="color: #fff !important;">{{ __('Reset Password') }}
                                </h3> --}}
                            </div>

                            <div class="alert alert-success fade show" role="alert">
                                <div class="alert-text">{{ $reset_message ?? '' }} You Login From Mobile App With New
                                    Password </div>
                                {{-- <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Page -->

    <!--begin::Global Theme Bundle(used by all pages) -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js?v=1') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/scripts.bundle.js" type="text/javascript?v=1') }}" type="text/javascript"></script>

    <script>
        $('#password, #confirm_password').on('keyup', function () {
                if ($('#password').val() == $('#confirm_password').val()) {
                $('#pass_message').html('Password Matching').css('color', 'green');
            } else
                $('#pass_message').html('Password Does Not Matching').css('color', 'red');
            });
    </script>

</body>
<!-- end::Body -->

</html>