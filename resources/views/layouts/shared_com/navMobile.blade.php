@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp
<div class="mobile-menu-bar">
    <a href="{{ route('comzones.home') }}" class="flex mr-auto">
        <img alt="Robus" class="w-6" src="{{ asset_url('dist/images/logo_robus.png') }}">
    </a>
    <a id="mobile-menu-toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>
</div>
<ul class="border-t border-white/[0.08] py-5 hidden">


    <li>
        <a href="{{ route('comzones.home') }}" class="menu {{ request()->is('comzones.home') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="home"></i> </div>
            <div class="menu__title">
                Tableau de bord
            </div>
        </a>
    </li>



    <li class="menu__devider my-4"></li>

    <li>

        <a href="{{ route('comzones.underdistributors.browse') }}"
            class="menu {{ $route == 'comzones.underdistributors.browse' || $route == 'comzones.underdistributors.show' || $route == 'comzones.underdistributors.add' ? 'menu--active' : '' }}">
            <div class="menu__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div class="menu__title"> Sous distributeurs </div>
        </a>
    </li>

    <li class="menu__devider my-4"></li>

    <li>

        <a href="{{ route('masters.comzones.browse') }}"
            class="menu {{ $route == 'masters.comzones.browse' || $route == 'masters.comzones.show' || $route == 'masters.comzones.add' ? 'menu--active' : '' }}">
            <div class="menu__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div class="menu__title"> Masters </div>
        </a>
    </li>

    <li class="menu__devider my-4"></li>
    <li>

        <a href="{{ route('custumers.comzones.browse') }}"
            class="menu {{ $route == 'custumers.comzones.browse' || $route == 'custumers.comzones.show' || $route == 'custumers.comzones.add' ? 'menu--active' : '' }}">
            <div class="menu__icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div class="menu__title"> Clients </div>
        </a>
    </li>
    <li class="menu__devider my-4"></li>
    <li>

        <a href="{{ route('comzones.account.profile') }}"
            class="menu {{ $route == 'comzones.account.profile' || $route == 'comzones.account.security' ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="user" class="block mx-auto"></i></div>
            <div class="menu__title"> Mon profil </div>
        </a>
    </li>

</ul>
