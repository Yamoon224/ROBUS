@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp
<div class="mobile-menu-bar">
    <a href="{{ route('/') }}" class="flex mr-auto">
        <img alt="Sid-siika" class="w-6" src="{{ asset('public/dist/images/login.jpg') }}">
    </a>
    <a id="mobile-menu-toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
</div>
<ul class="border-t border-white/[0.08] py-5 hidden">

    <li>
        <a href="{{ route('/') }}" class="menu {{ request()->is('/') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="home"></i> </div>
            <div class="menu__title">
                Tableau de bord
            </div>
        </a>
    </li>



    <li class="side-nav__devider my-4"></li>

    <li>

        <a href="{{ route('custumers.browse') }}"
            class="menu {{ $route == 'custumers.browse' || $route == 'custumers.show' || $route == 'custumers.edit' || $route == 'custumers.add' || $route == 'custumers.activate' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="user-check" class="block mx-auto"></i></div>
            <div class="menu__title"> Employers </div>
        </a>
    </li>
    <li class="side-nav__devider my-4"></li>
    <li>
        <a href="{{ route('transactions.historiques') }}"
            class="menu {{ $route == 'transactions.historiques' || $route == 'relaods.historical' || $route == 'transfers.historical' || $route == 'paiements.historical' || $route == 'virements.historical' || $route == 'inters.historical' || $route == 'cartes.historical' || $route == 'phones.historical' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
            </div>
            <div class="menu__title"> Historique </div>
        </a>
    </li>
    <li class="side-nav__devider my-4"></li>
    <li>

        <a href="{{ route('browse.states') }}"
            class="menu {{ $route == 'browse.states' || $route == 'relaods.states' || $route == 'transfers.states' || $route == 'paiements.states' || $route == 'virements.states' || $route == 'inters.states' || $route == 'cartes.states' || $route == 'phones.states' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="activity" class="block mx-auto"></i></div>
            <div class="menu__title"> Statistiques </div>
        </a>
    </li>
    <li class="side-nav__devider my-4"></li>


    <li>

        <a href="{{ route('account.profile') }}"
            class="menu {{ $route == 'account.profile' || $route == 'account.security' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="user" class="block mx-auto"></i></div>
            <div class="menu__title"> Mon profil </div>
        </a>
    </li>

</ul>


