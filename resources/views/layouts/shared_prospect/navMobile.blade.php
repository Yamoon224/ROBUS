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

            <a href="{{ route('propects.transferHistos.historical') }}"
                class="menu {{ $route == 'propects.transferHistos.historical' ? 'menu--active' : '' }}">
                <div class="menu__icon"> <i data-feather="credit-card" class="block mx-auto"></i> </div>
                <div class="menu__title"> Historiques </div>
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
