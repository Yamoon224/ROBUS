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
            <a href="javascript:;" class="side-menu {{ request()->is('historicals*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="credit-card" class="block mx-auto"></i> </div>
                <div class="side-menu__title">
                    Historiques
                    <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ request()->is('company/historicals*') ? 'side-menu__sub-open' : '' }}">


                <li>
                    <a href="{{ route('propects.transfers.historical') }}"
                        class="side-menu {{ $route == 'propects.transfers.historical' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                        </div>
                        <div class="side-menu__title"> Historique transfert </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('propects.reloads.historical') }}"
                        class="side-menu {{ $route == 'propects.reloads.historical' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                        </div>
                        <div class="side-menu__title"> Historique réchargement</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side-nav__devider my-4"></li>
        <li>

            <a href="{{ route('propects.transactions.historiques.states') }}"
                class="side-menu {{ $route == 'propects.transactions.historiques.states' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i></div>
                <div class="side-menu__title"> Statistiques </div>
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
