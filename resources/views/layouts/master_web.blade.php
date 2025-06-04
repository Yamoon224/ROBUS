<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">


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

            @include('layouts.shared_web.header')
        

            @yield('content')

            @include('layouts.shared_web.footer')
        </div>
      

        {{-- <div class="scroll-to-top scroll-to-target" data-target=".main-header" style="display: block;">
        
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/>
              </svg>
        </div> --}}

    @include('layouts.shared_web.scripts')

    @stack('scripts')

    @livewireScripts

    @include('layouts.scripts_toastr')


</body>

</html>
