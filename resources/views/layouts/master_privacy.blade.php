<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ROBUS">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - ROBUS</title>

    @include('layouts.shared.styles')
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="module"  src="{{ vite_asset('resources/js/app.js') }}"></script>


    @stack('styles')

</head>


<body class="dark:bg-transparent">

    <!-- BEGIN: Content-->
    <div class="mobile-menu md:hidden">
    </div>
    <div class="flex">
        <div class="content">
           
            @yield('content')
        
        </div>
    </div>
    @include('layouts.shared.scripts')

    @stack('scripts')

</body>

</html>
