<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ROBUS">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - ROBUS</title>

    @include('layouts.shared_web.styles')


    @stack('styles')

    @livewireStyles

</head>


<body>

        <div class="page-wrapper">
            <!-- Preloader -->
            
            @include('layouts.shared_web.header')
    
            @yield('content')
    
            <!-- End Main Content -->
            <!--Main Footer-->
            @include('layouts.shared_web.footer')
           
        </div>
        <!--End pagewrapper-->
        <!--Scroll to top-->
        <div class="scroll-to-top scroll-to-target" data-target=".main-header"><span
                class="icon fa fa-long-arrow-up"></span></div>
    
    
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
