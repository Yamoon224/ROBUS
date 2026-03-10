<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <link href="{{ asset_url('dist/images/logo_orange.png') }}" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Tinker admin is super flexible, powerful, clean &amp; modern responsive tailwind admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Tinker Admin Template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="LEFT4CODE">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') -ROBUS</title>


    @include('layouts.shared.styles')

    @stack('styles')
    @livewireStyles
</head>


<body>

    <div class="container sm:px-10">
        @yield('content')
    </div>
    <!-- BEGIN: Dark Mode Switcher-->

    @include('layouts.shared.scripts')


    @stack('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <!-- Toastr -->
    @livewireScripts
    @include('layouts.scripts_toastr')
</body>

</html>
