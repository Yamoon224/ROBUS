<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ROBUS">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - ROBUS</title>

    @include('layouts.shared_masters.styles')
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="module" src="{{ vite_asset('resources/js/app.js') }}"></script>
  
    {{-- @vite('ressource/js/app.js') --}}


    @stack('styles')

    @livewireStyles

</head>


<body class="dark:bg-transparent">

    <!-- BEGIN: Content-->
    <div class="mobile-menu md:hidden">
        @include('layouts.shared_masters.navMobile')
    </div>
    <div class="flex">
        @include('layouts.shared_masters.nav')
        <div class="content">
            <div class="top-bar">
                @include('layouts.shared_masters.side')
            </div>
            @yield('content')
        </div>
    </div>
    @include('layouts.shared.scripts')

    @stack('scripts')
    @livewireScripts
    @include('layouts.partials.livewire-page-expired')

    @include('layouts.scripts_toastr')

    <script>
    

        Livewire.on('modal-hide', () => {


            //Categories
            $('#create-categorie-modal').removeClass('show');
            $('#create-categorie-modal').addClass('hide');

            $('#disabled-categorie-modal').removeClass('show');
            $('#disabled-categorie-modal').addClass('hide');

            $('#update-categorie-modal').removeClass('show');
            $('#update-categorie-modal').addClass('hide');

            $('#delete-categorie-modal').removeClass('show');
            $('#delete-categorie-modal').addClass('hide');

            //employers
            $('#create-employers-modal').removeClass('show');
            $('#create-employers-modal').addClass('hide');

            $('#payment-employers-modal').removeClass('show');
            $('#payment-employers-modal').addClass('hide');

            $('#disabled-employers-modal').removeClass('show');
            $('#disabled-employers-modal').addClass('hide');

            $('#update-employers-modal').removeClass('show');
            $('#update-employers-modal').addClass('hide');

            $('#delete-employers-modal').removeClass('show');
            $('#delete-employers-modal').addClass('hide');


        });

        // Categories

        Livewire.on('modal-show', () => {
            $('#create-categorie-modal').removeClass('hide');
            $('#create-categorie-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-categorie-modal').removeClass('hide');
            $('#disabled-categorie-modal').addClass('show');
        });

        Livewire.on('update-categorie-modal', () => {
            $('#update-categorie-modal').removeClass('hide');
            $('#update-categorie-modal').addClass('show');
        });

        Livewire.on('delete-categorie-modal', () => {
            $('#delete-categorie-modal').removeClass('hide');
            $('#delete-categorie-modal').addClass('show');
        });

        // Employers

        Livewire.on('modal-show', () => {
            $('#create-employers-modal').removeClass('hide');
            $('#create-employers-modal').addClass('show');
        });

        Livewire.on('modal-show-employers', () => {
            $('#payment-employers-modal').removeClass('hide');
            $('#payment-employers-modal').addClass('show');
        });


        Livewire.on('show-disabled-modal', () => {
            $('#disabled-employers-modal').removeClass('hide');
            $('#disabled-employers-modal').addClass('show');
        });

        Livewire.on('update-employers-modal', () => {
            $('#update-employers-modal').removeClass('hide');
            $('#update-employers-modal').addClass('show');
        });

        Livewire.on('delete-employers-modal', () => {
            $('#delete-employers-modal').removeClass('hide');
            $('#delete-employers-modal').addClass('show');
        });
    </script>

</body>

</html>
