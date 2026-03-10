<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ROBUS">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - ROBUS</title>

    @include('layouts.shared_com.styles')
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="module" src="{{ asset('build/assets/app.c57777cd.js') }}"></script>
    {{-- @vite('ressource/js/app.js') --}}


    @stack('styles')

    @livewireStyles

</head>


<body class="dark:bg-transparent">

    <!-- BEGIN: Content-->
    <div class="mobile-menu md:hidden">
        @include('layouts.shared_com.navMobile')
    </div>
    <div class="flex">
        @include('layouts.shared_com.nav')
        <div class="content">
            <div class="top-bar">
                @include('layouts.shared_com.side')
            </div>
            @yield('content')
        </div>
    </div>
    @include('layouts.shared.scripts')

    @stack('scripts')
    @livewireScripts

    @include('layouts.scripts_toastr')
    
    <script>
       

        Livewire.on('modal-hide', () => {


            //master
            $('#create-master-modal').removeClass('show');
            $('#create-master-modal').addClass('hide');

            $('#create-custumer-modal').removeClass('show');
            $('#create-custumer-modal').addClass('hide');

            $('#payment-master-modal').removeClass('show');
            $('#payment-master-modal').addClass('hide');

            $('#disabled-master-modal').removeClass('show');
            $('#disabled-master-modal').addClass('hide');


            $('#disabled-custumer-modal').removeClass('show');
            $('#disabled-custumer-modal').addClass('hide');

            $('#update-master-modal').removeClass('show');
            $('#update-master-modal').addClass('hide');

            $('#delete-master-modal').removeClass('show');
            $('#delete-master-modal').addClass('hide');

            // realod Wallet
            $('#create-reloadMaster-modal').removeClass('show');
            $('#create-reloadMaster-modal').addClass('hide');

            $('#create-unlock-modal').removeClass('show');
            $('#create-unlock-modal').addClass('hide');

        });


        Livewire.on('modal-hide-reload', () => {

            // realod Wallet
            $('#create-reloadMaster-modal').removeClass('show');
            $('#create-reloadMaster-modal').addClass('hide');

            $('#update-reloadMaster-modal').removeClass('show');
            $('#update-reloadMaster-modal').addClass('hide');

            $('#reload-nav').addClass('active');
            $('reload-nav').attr('aria-selected', true);
            $('#reload').addClass('active');
            // $('body').css('overflow', 'auto');
        });


        Livewire.on('modal-reloadMaster-show', () => {
            $('#create-reloadMaster-modal').removeClass('hide');
            $('#create-reloadMaster-modal').addClass('show');
            $('#reload-nav').addClass('active');
            $('reload-nav').attr('aria-selected', true);
            $('#reload').addClass('active');

        });



        Livewire.on('modal-unlock-show', () => {
            $('#create-unlock-modal').removeClass('hide');
            $('#create-unlock-modal').addClass('show');
            $('#reload-nav').addClass('active');
            $('reload-nav').attr('aria-selected', true);
            $('#reload').addClass('active');

        });

        // Under distributors

        Livewire.on('modal-show', () => {
            $('#create-master-modal').removeClass('hide');
            $('#create-master-modal').addClass('show');
        });
        

        Livewire.on('modal-show-master', () => {
            $('#payment-master-modal').removeClass('hide');
            $('#payment-master-modal').addClass('show');
        });


        Livewire.on('modal-show-custumer', () => {
            $('#create-custumer-modal').removeClass('hide');
            $('#create-custumer-modal').addClass('show');
        });


        Livewire.on('show-disabled-modal', () => {
            $('#disabled-master-modal').removeClass('hide');
            $('#disabled-master-modal').addClass('show');
        });

        Livewire.on('update-master-modal', () => {
            $('#update-master-modal').removeClass('hide');
            $('#update-master-modal').addClass('show');
        });

        Livewire.on('delete-master-modal', () => {
            $('#delete-master-modal').removeClass('hide');
            $('#delete-master-modal').addClass('show');
        });


        Livewire.on('delete-custumer-modal', () => {
            $('#delete-custumer-modal').removeClass('hide');
            $('#delete-custumer-modal').addClass('show');
        });
    </script>

</body>

</html>
