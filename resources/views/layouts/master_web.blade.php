<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">


    @include('layouts.shared_web.styles')
    @stack('styles')

    @livewireStyles
    <title>Website Menu #4</title>
</head>

<body class="bg-color">


    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close mt-3">
                <span class="icon-close2 js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>


    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="#" class=""><span class="mr-2  icon-envelope-open-o"></span> <span
                            class="d-none d-md-inline-block">info@robus.com</span></a>
                    <span class="mx-md-2 d-inline-block"></span>
                    <a href="#" class=""><span class="mr-2  icon-phone"></span> <span
                            class="d-none d-md-inline-block">1+ (234) 5678 9101</span></a>


                    <div class="float-right">

                        <a href="#" class=""><span class="mr-2  icon-twitter"></span> <span
                                class="d-none d-md-inline-block">Twitter</span></a>
                        <span class="mx-md-2 d-inline-block"></span>
                        <a href="#" class=""><span class="mr-2  icon-facebook"></span> <span
                                class="d-none d-md-inline-block">Facebook</span></a>

                    </div>

                </div>

            </div>

        </div>
    </div>

    @include('layouts.shared_web.header')


    @yield('content')


    @include('layouts.shared_web.footer')

    @include('layouts.shared_web.scripts')

    @stack('scripts')

    @livewireScripts

    <script>
        Livewire.on('success', msg => {
            Swal.fire({
                icon: 'success',
                width: 600,
                hight: 600,
                timer: 2000,
                color: '#1f2a3c',
                title: msg,
                showConfirmButton: false,
                padding: '3em',
                timerProgressBar: true,
                background: '#eff3f7 url("https://robus.home.ci/robus/public/dist/images/transaction.gif")',
                backdrop: `
                 rgba(23,204,43,0.5)
                        right top
                        no-repeat
                       `
            });
        });

        Livewire.on('warning', msg => {
            Swal.fire({
                icon: 'warning',
                color: '#1f2a3c',
                width: 600,
                timer: 2000,
                title: msg,
                padding: '3em',
                showConfirmButton: false,
                timerProgressBar: true,
                background: '#eff3f7 url("https://robus.home.ci/robus/public/dist/images/payment-error.gif")',
                backdrop: `
                 rgba(220,39,39,0.4)
                        right top
                        no-repeat
                       `
            });
        });

        Livewire.on('error', msg => {
            Swal.fire({
                width: 600,
                icon: 'error',
                timer: 2000,
                color: '#1f2a3c',
                title: msg,
                padding: '3em',
                timerProgressBar: true,
                showConfirmButton: false,
                background: '#eff3f7 url("https://robus.home.ci/robus/public/dist/images/payment-error.gif")',
                backdrop: `
                 rgba(220,39,39,0.4)
                        right top
                        no-repeat
                       `
            });

        });


        Livewire.on('info', msg => {
            Swal.fire({
                icon: 'info',
                width: 600,
                timer: 2000,
                title: msg,
                color: '#1f2a3c',
                padding: '3em',
                showConfirmButton: false,
                timerProgressBar: true,
                background: '#eff3f7',
                backdrop: `
                 rgba(51,65,85,0.4)
                        left top
                        no-repeat
                       `
            });
        });

        Livewire.on('question', msg => {
            Swal.fire({
                icon: 'question',
                width: 600,
                timer: 2000,
                title: msg,
                color: '#1f2a3c',
                padding: '3em',
                showConfirmButton: false,
                timerProgressBar: true,
                background: '#eff3f7',
                backdrop: `
                 rgba(51,65,85,0.1)
                        left top
                        no-repeat
                       `
            });
        });
    </script>


</body>

</html>
