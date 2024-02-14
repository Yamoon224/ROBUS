@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp
<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <a href="{{ route('comzones.home') }}" class="intro-x flex items-center pl-5 pt-4">
        <img alt="" class="w-6" src="{{ asset('public/dist/images/login.jpg') }}">
        <span class="hidden xl:block text-white text-lg ml-3"> ROBUS </span>
    </a>
    <div class="side-nav__devider my-4"></div>
    <ul>
        <li>
            <a href="{{ route('comzones.home') }}"
                class="side-menu {{ request()->is('comzones.home') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title">
                    Tableau de bord
                </div>
            </a>
        </li>



        <li class="side-nav__devider my-4"></li>

        <li>

            <a href="{{ route('comzones.underdistributors.browse') }}"
                class="side-menu {{ $route == 'comzones.underdistributors.browse' || $route == 'comzones.underdistributors.show' || $route == 'comzones.underdistributors.add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="side-menu__title"> Sous distributeurs </div>
            </a>
        </li>

        <li class="side-nav__devider my-4"></li>

        <li>

            <a href="{{ route('masters.comzones.browse') }}"
                class="side-menu {{ $route == 'masters.comzones.browse' || $route == 'masters.comzones.show' || $route == 'masters.comzones.add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="side-menu__title"> Masters </div>
            </a>
        </li>

        <li class="side-nav__devider my-4"></li>

        <li>

            <a href="{{ route('custumers.comzones.browse') }}"
                class="side-menu {{ $route == 'custumers.comzones.browse' || $route == 'custumers.comzones.show' || $route == 'custumers.comzones.add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="side-menu__title"> Clients </div>
            </a>
        </li>

        {{-- <li class="side-nav__devider my-4"></li>
        <li>

            <a href="{{ route('underdistributors.browse_reloads') }}"
            class="side-menu {{ $route == 'underdistributors.browse_reloads' ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
            </div>
            <div class="side-menu__title"> Réchargements </div>
        </a>
        </li> --}}
        {{-- <li class="side-nav__devider my-4"></li>
        <li>

            <a href="{{ route('underdistributors.browse_dumps') }}"
                class="side-menu {{ $route == 'underdistributors.browse_dumps' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                </div>
                <div class="side-menu__title"> Déchargements </div>
            </a>
        </li> --}}
        {{-- <li class="side-nav__devider my-4"></li>
        <li>

            <a href="{{ route('comzones.browse.states') }}"
                class="side-menu {{ $route == 'comzones.browse.states' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i></div>
                <div class="side-menu__title"> Statistiques </div>
            </a>
        </li> --}}
        <li class="side-nav__devider my-4"></li>
        <li>

            <a href="{{ route('comzones.account.profile') }}"
                class="side-menu {{ $route == 'comzones.account.profile' || $route == 'comzones.account.security' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="user" class="block mx-auto"></i></div>
                <div class="side-menu__title"> Mon profil </div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Side Menu -->
