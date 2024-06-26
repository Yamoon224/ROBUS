<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Sabdydev">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <meta name="robots" content="index, nofollow">

    <title>@yield('title')</title>
    <!-- CSRF Token -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Start css -->
    @include('layouts.shared.styles')
    @stack('styles')

</head>
<body class="vertical-layout">

    <div id="containerbar" class="containerbar authenticate-bg">
        <!-- Start Container -->
        <div class="container">
            <div class="auth-box error-box">
                <!-- Start row -->
                 <div class="row no-gutters align-items-center justify-content-center">
                    <!-- Start col -->
                    <div class="col-md-8 col-lg-6">
                        <div class="text-center">
                            <img src="assets/images/logo.svg" class="img-fluid error-logo" alt="logo">
                            <img src="assets/images/error/internal-server.svg" class="img-fluid error-image" alt="500">
                            <h4 class="error-subtitle mb-4">@yield('code') @yield('message')</h4>
                            <p class="mb-4">The server encountered an internal error or misconfiguration and was unable to complete your request.</p>
                            <a href="#" class="btn btn-primary font-16"><i class="feather icon-home mr-2"></i> Go back to Dashboard</a>
                        </div>
                    </div>
                    <!-- End col -->
                </div>
                <!-- End row -->
            </div>
        </div>
        <!-- End Container -->
    </div>


</body>

</html>
