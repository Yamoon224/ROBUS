<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ROBUS">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src=https://touchpay.gutouch.com/touchpay/script/prod_touchpay-0.0.1.js
    type="text/javascript"></script>
    <title>@yield('title') - ROBUS</title>
    {{-- {!! htmlScriptTagJsApi() !!} --}}

    @include('layouts.shared.styles')
    {{-- <script type="module"  src="{{ asset('build/assets/app.13c293c6.js') }}"></script>
    <script type="module" crossorigin src="/boostrap/assets/app2.mjs"></script> --}}
    {{-- <script src="https://js.pusher.com/4.1/pusher.min.js"></script> --}}
    <script type="module"  src="{{ vite_asset('resources/js/app.js') }}"></script>
    {{-- <script type="module" crossorigin src="{{ asset('boostrap/ssr/app2.mjs') }}"></script> --}}
    {{-- <link rel="stylesheet" href="/build/assets/app2.mjs">
    <link href="{{ vite_asset('resources/css/app.css') }}" rel="stylesheet">
    {{-- <style>
        [x-cloak] {
            display: none !important;
        }
    </style> --}}
    {{-- @vite('ressource/js/app.js') --}}


    @stack('styles')

    @livewireStyles
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css"> --}}

</head>


<body class="dark:bg-transparent">

    <!-- BEGIN: Content-->
    <div class="mobile-menu md:hidden">
        @include('layouts.shared.navMobileRegisters')
    </div>
    <div class="flex">
        {{-- @include('layouts.shared.nav') --}}
        <div class="content">
            <div class="top-bar">
                @include('layouts.shared.sid_registers')
            </div>
            @yield('content')
            {{-- {{ $slot }} --}}
            {{-- @include('layouts.shared.footer') --}}
        </div>
    </div>
    @include('layouts.shared.scripts')

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

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-user_admin-modal').removeClass('hide');
            $('#disabled-user_admin-modal').addClass('show');
        });

    </script>

</body>

</html>
