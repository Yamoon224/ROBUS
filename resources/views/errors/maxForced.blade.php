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
            <!-- BEGIN: Error Page -->
            <div class="error-page flex flex-col lg:flex-row items-center justify-center h-screen text-center lg:text-left">
                <div class="-intro-x lg:mr-20">
                    <img alt="Rubick Tailwind HTML Admin Template" class="h-48 lg:h-auto" src="{{asset('public/dist/images/error-illustration.svg')}}">
                </div>
                <div class="text-white mt-10 lg:mt-0">
                    <div class="intro-x text-8xl font-medium">@yield('code')</div>
                    <h1 class="text-4xl font-bold text-red-600">Application Hors Ligne</h1>
                   
                    <div class="intro-x text-xl lg:text-3xl font-medium mt-5">{{ $message ?? 'L’application est actuellement hors ligne pour maintenance.' }}</div>
                    <div class="intro-x text-lg mt-3"> 
                     </div>
                    <a href="#" class="intro-x btn py-3 px-4 text-white border-white dark:border-darkmode-400 dark:text-slate-200 mt-10"><i class="feather icon-home mr-2"></i> Retour sur votre adresse</a>
                </div>
            </div>
            <!-- END: Error Page -->
        </div>
        <!-- End Container -->
    </div>


</body>

</html>
