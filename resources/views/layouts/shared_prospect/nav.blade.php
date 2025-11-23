@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
    $status = appDispoRobus(App\Models\AppCompanyStatus::class);

@endphp
<!-- BEGIN: Side Menu -->

<nav class="side-nav">
    <a href="{{ route('propects.home') }}" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Robus" class="w-20" src="{{ asset_url('dist/images/logo_robus.png') }}">

    </a>
    <div class="side-nav__devider my-4"></div>
    <ul>
        <li>
            <a href="{{ route('propects.home') }}"
                class="side-menu {{ $route == 'propects.home' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title">
                    Tableau de bord
                </div>
            </a>
        </li>


        <li class="side-nav__devider my-4"></li>

         <li>

            <a href="{{ route('propects.transferHistos.historical') }}"
                class="side-menu {{ $route == 'propects.transferHistos.historical' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="credit-card" class="block mx-auto"></i> </div>
                <div class="side-menu__title"> Historiques </div>
            </a>
        </li>
        <li class="side-nav__devider my-4"></li>

        <li>

            <a href="{{ route('propects.browse_commissions') }}"
                class="side-menu {{ $route == 'propects.browse_commissions' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i></div>
                <div class="side-menu__title"> Gains </div>
            </a>
        </li>
        <li class="side-nav__devider my-4"></li>
        <li>

            <a href="{{ route('propects.identifications') }}"
                class="side-menu {{ $route == 'propects.identifications' ||  $route == 'propects.identifications.add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="file-text" class="block mx-auto"></i></div>
                <div class="side-menu__title"> Identifications </div>
            </a>
        </li>
        <li class="side-nav__devider my-4"></li>
        <li>

            <a href="{{ route('propects.integrations') }}"
                class="side-menu {{ $route == 'propects.integrations' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="codesandbox" class="block mx-auto"></i></div>
                <div class="side-menu__title"> Integrations </div>
            </a>
        </li>
        <li class="side-nav__devider my-4"></li>



        <li>

            <a href="{{ route('propects.account.profile') }}"
                class="side-menu {{ $route == 'propects.account.profile' || $route == 'propects.account.security' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="user" class="block mx-auto"></i></div>
                <div class="side-menu__title"> Mon profil </div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->
