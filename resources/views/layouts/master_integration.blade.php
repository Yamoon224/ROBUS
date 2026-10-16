<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('layouts.shared_web.styles')

    @stack('styles')

    <title>@yield('title') - ROBUS</title>

</head>

<body>

    <div class="page-robus">

        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>

        @include('layouts.shared_web.header_integration')


        @yield('content')

        @include('layouts.shared_web.footer_api')

    </div>


    @include('layouts.shared_web.scripts')

    @stack('scripts')

    @livewireScripts
    @include('layouts.partials.livewire-page-expired')

    @include('layouts.scripts_toastr')

</body>

</html>
