@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp
<div class="mobile-menu-bar">
    <a href="{{ route('propects.home') }}" class="flex mr-auto">
        <img alt="Robus" class="w-6" src="{{ asset_url('dist/images/logo_robus.png') }}">
    </a>
    <a id="mobile-menu-toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
</div>
<ul class="border-t border-white/[0.08] py-5 hidden">

    <li>
        <a href="{{ route('propects.home') }}" class="menu {{ request()->is('/') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="home"></i> </div>
            <div class="menu__title">
                Tableau de bord
            </div>
        </a>
    </li>


    <li class="nav__devider my-4"></li>

   
    <li>
        <a href="javascript:;" class="menu {{ request()->is('historicals*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="credit-card" class="block mx-auto"></i> </div>
            <div class="menu__title">
                Historiques
                <div class="menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{ request()->is('company/historicals*') ? 'menu__sub-open' : '' }}">


            <li>
                <a href="{{ route('propects.transfers.historical') }}"
                    class="menu {{ $route == 'propects.transfers.historical' ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                    </div>
                    <div class="menu__title"> Historique transfert </div>
                </a>
            </li>
            <li>
                <a href="{{ route('propects.reloads.historical') }}"
                    class="menu {{ $route == 'propects.reloads.historical' ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                    </div>
                    <div class="menu__title"> Historique réchargement</div>
                </a>
            </li>
        </ul>
    </li>
    <li class="nav__devider my-4"></li>
    <li>

        <a href="{{ route('propects.transactions.historiques.states') }}"
            class="menu {{ $route == 'propects.transactions.historiques.states' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="activity" class="block mx-auto"></i></div>
            <div class="menu__title"> Statistiques </div>
        </a>
    </li>
    <li class="nav__devider my-4"></li>

    <li>

        <a href="{{ route('propects.browse_commissions') }}"
            class="menu {{ $route == 'propects.browse_commissions' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="activity" class="block mx-auto"></i></div>
            <div class="menu__title"> Gains </div>
        </a>
    </li>
    <li class="nav__devider my-4"></li>


    <li>

        <a href="{{ route('propects.account.profile') }}"
            class="menu {{ $route == 'propects.account.profile' || $route == 'propects.account.security' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="user" class="block mx-auto"></i></div>
            <div class="menu__title"> Mon profil </div>
        </a>
    </li>

</ul>
