@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
    $countries = getCountriesForUser(Auth::guard('web')->user());
    $countrie_civ = getCountriesForVisitorsUser(Auth::guard('web')->user());
    $cumulPendding = cumulTransaction();
@endphp
<!-- BEGIN: Side Menu -->

@if (sabdyVerify())
    <nav class="side-nav">
        <a href="{{ route('/') }}" class="intro-x flex items-center pl-5 pt-4">
            <img alt="Robus" class="w-20" src="{{ asset_url('dist/images/logo_robus.png') }}">
            <span class="hidden xl:block text-white text-lg ml-3"> </span>
        </a>
        <div class="side-nav__devider my-4"></div>
        <ul>
            <li>
                <a href="{{ route('/') }}" class="side-menu {{ request()->is('/') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                    <div class="side-menu__title">
                        Tableau de bord
                    </div>
                </a>
            </li>

            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('pays'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('countries*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="bookmark" class="block mx-auto"></i> </div>
                        <div class="side-menu__title">
                            Finance(soldes)
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="{{ request()->is('countries*') ? 'side-menu__sub-open' : '' }}">


                        @foreach ($countries as $countrie)
                            <li>
                                <a href="{{ route('countries.show', ['key' => $countrie->name]) }}"
                                    class="side-menu {{ $route == 'countries.show' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="airplay" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> {{ $countrie->name }} </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif
            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('transferts'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('transactions*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="credit-card" class="block mx-auto"></i> </div>
                        <div class="side-menu__title flex items-center justify-between">
                            <span>Transactions</span>
                            <div class="flex items-center gap-2 mr-3 ml-3">
                                @if ($cumulPendding > 0)
                                    <span
                                        class="inline-flex items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                                        {{ $cumulPendding }}
                                    </span>
                                @endif
                                <div
                                    class="side-menu__sub-icon transform transition-transform duration-200 group-hover:rotate-180">
                                    <i data-feather="chevron-down" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                    <ul class="{{ request()->is('transactions*') ? 'side-menu__sub-open' : '' }}">


                        @if (Auth::guard('web')->user()->hasPermissionTo('achat-uv'))
                            <li>
                                <a href="{{ route('transactions.uv.browse') }}"
                                    class="side-menu {{ $route == 'transactions.uv.browse' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Rechargement </div>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('transactions.browse') }}"
                                class="side-menu {{ $route == 'transactions.browse' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Transferts d'argent </div>
                            </a>
                        </li>

                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-transaction-visiteurs'))
                            <li>
                                <a href="{{ route('transactions.visitors') }}"
                                    class="side-menu {{ $route == 'transactions.visitors' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title">Transactions Visiteurs </div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('historiques'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('historiques*') || request()->is('winnings*') || request()->is('historicals*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="clipboard"
                                data-lucide="clipboard" class="lucide lucide-clipboard block mx-auto">
                                <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </div>
                        <div class="side-menu__title">
                            Historiques
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul
                        class="{{ request()->is('historiques*') || request()->is('winnings*') || request()->is('historicals*') ? 'side-menu__sub-open' : '' }}">

                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-historique-transfers'))
                            <li>
                                <a href="{{ route('transactions.historiques') }}"
                                    class="side-menu {{ $route == 'transactions.historiques' || $route == 'relaods.historical' || $route == 'transfers.historical' || $route == 'paiements.historical' || $route == 'virements.historical' || $route == 'inters.historical' || $route == 'cartes.historical' || $route == 'phones.historical' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Historique transactions </div>
                                </a>
                            </li>
                        @endif
                        {{-- @if (Auth::guard('web')->user()->hasPermissionTo('gain-commissions')) --}}
                        <li>
                            <a href="{{ route('unders.browse_winnings') }}"
                                class="side-menu {{ $route == 'unders.browse_winnings' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Gains commissions </div>
                            </a>
                        </li>
                        {{-- @endif --}}

                    </ul>
                </li>
            @endif
            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('clients'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('users*') || request()->is('masters*') || request()->is('comzones*') || request()->is('distribitors*') || request()->is('underdistributors*') || request()->is('rankings*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" clea
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="side-menu__title">
                            Utilisateurs
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul
                        class="{{ request()->is('users*') || request()->is('masters*') || request()->is('comzones*') || request()->is('distribitors*') || request()->is('underdistributors*') || request()->is('rankings*') ? 'side-menu__sub-open' : '' }}">


                        @if (Auth::guard('web')->user()->hasPermissionTo('clients'))
                            <li>

                                <a href="{{ route('custumers.browse') }}"
                                    class="side-menu {{ $route == 'custumers.browse' || $route == 'custumers.show' || $route == 'custumers.edit' || $route == 'custumers.add' || $route == 'custumers.activate' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon">
                                        <i data-feather="user-check" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Clients </div>
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->hasPermissionTo('clients'))
                            <li>

                                <a href="{{ route('company.browse') }}"
                                    class="side-menu {{ $route == 'company.browse' || $route == 'company.show_company' || $route == 'admin.add_company' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="user-check"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Societés </div>
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->hasPermissionTo('masters'))
                            <li>

                                <a href="{{ route('masters.admins.browse') }}"
                                    class="side-menu {{ $route == 'masters.admins.browse' || $route == 'masters.admins.show' || $route == 'masters.admins.add' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="user-check"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> MASTERS </div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->hasPermissionTo('com-zone'))
                            <li>

                                <a href="{{ route('comzones.admins.browse') }}"
                                    class="side-menu {{ $route == 'comzones.admins.browse' || $route == 'comzones.admins.add' || $route == 'comzones.admins.show' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="user-check"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> COM ZONE </div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->hasPermissionTo('com-zone'))
                            <li>

                                <a href="{{ route('unders.admins.browse') }}"
                                    class="side-menu {{ $route == 'unders.admins.browse' || $route == 'unders.admins.add' || $route == 'unders.admins.show' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="user-check"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Sous-distributeurs</div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->hasPermissionTo('integrateurs'))
                            <li>

                                <a href="{{ route('users.prospects.browse') }}"
                                    class="side-menu {{ $route == 'users.prospects.browse' || $route == 'users.prospects.details' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon">
                                        <i data-feather="codesandbox" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Integrateurs</div>
                                </a>
                            </li>
                        @endif


                        @if (Auth::guard('web')->user()->hasPermissionTo('com-zone'))
                            <li>

                                <a href="{{ route('operators.admins.browse') }}"
                                    class="side-menu {{ $route == 'operators.admins.browse' || $route == 'operators.admins.add' || $route == 'operators.admins.show' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="user-check"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Operatrices</div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->hasPermissionTo('utilisateurs'))
                            <li>

                                <a href="{{ route('rankings.browse') }}"
                                    class="side-menu {{ $route == 'rankings.browse' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="award" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Classements</div>
                                </a>
                            </li>
                        @endif


                    </ul>
                </li>
            @endif


            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('cartes'))
                <li>

                    <a href="{{ route('cards.browse') }}"
                        class="side-menu {{ $route == 'cards.browse' || $route == 'cards.show' || $route == 'cards.add' || $route == 'cards.edit' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="clipboard" class="block mx-auto"></i></div>
                        <div class="side-menu__title">Commades Cartes </div>
                    </a>
                </li>
            @endif

            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('statistiques'))
                <li>

                    <a href="{{ route('browse.states') }}"
                        class="side-menu {{ $route == 'browse.states' || $route == 'relaods.states' || $route == 'transfers.states' || $route == 'paiements.states' || $route == 'virements.states' || $route == 'inters.states' || $route == 'cartes.states' || $route == 'phones.states' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i></div>
                        <div class="side-menu__title"> Statistiques </div>
                    </a>
                </li>
            @endif

            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('service-web'))
                <li>

                    <a href="{{ route('services.browse') }}"
                        class="side-menu {{ $route == 'services.browse' || $route == 'services.offers.browse' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"><i data-feather="package" class="block mx-auto"></i>
                        </div>
                        <div class="side-menu__title"> Services mobile </div>
                    </a>
                </li>
            @endif
            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('visiteurs'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('visitors*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">
                            <i data-feather="package" class="block mx-auto"></i>
                        </div>
                        <div class="side-menu__title">
                            Visiteurs
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="{{ request()->is('visitors*') ? 'side-menu__sub-open' : '' }}">



                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-historique-transfers'))
                            <li>
                                <a href="{{ route('transactions.historiques.visitors') }}"
                                    class="side-menu {{ $route == 'transactions.historiques.visitors' || $route == 'transfers.historical' || $route == 'paiements.historical' || $route == 'virements.historical' || $route == 'inters.historical' || $route == 'cartes.historical' || $route == 'phones.historical' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="activity"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Historique visiteurs </div>
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-service-visiteurs'))
                            <li>

                                <a href="{{ route('services.browse_visitores') }}"
                                    class="side-menu {{ $route == 'services.browse_visitores' || $route == 'services.edit_visitors' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"><i data-feather="package" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Services web </div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->hasPermissionTo('gain-admin'))
                            <li>
                                <a href="{{ route('visitors.admin.browse_gain') }}"
                                    class="side-menu {{ $route == 'visitors.admin.browse_gain' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="credit-card"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Gains admin visiteurs </div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->hasPermissionTo('cautions'))
                            <li>
                                <a href="{{ route('commissions.browse_visitors') }}"
                                    class="side-menu {{ $route == 'commissions.browse_visitors' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="credit-card"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title">Commissions pays visiteurs </div>
                                </a>
                            </li>
                        @endif
                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-distributeurs'))
                            <li>
                                <a href="{{ route('visitors.countries.Show_distri_distributer', ['key' => $countrie_civ->name]) }}"
                                    class="side-menu {{ $route == 'visitors.countries.Show_distri_distributer' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="credit-card"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title">Validations visiteurs</div>
                                </a>
                            </li>
                        @endif

                        @if (Auth::guard('web')->user()->hasPermissionTo('active-pays'))
                            <li>
                                <a href="{{ route('countries.visitors') }}"
                                    class="side-menu {{ $route == 'countries.visitors' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="globe" class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> Pays visiteurs </div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('cautions'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('commissions*') || request()->is('levels*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="refresh-cw" class="block mx-auto"></i> </div>
                        <div class="side-menu__title">
                            Commissions
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul
                        class="{{ request()->is('commissions*') || request()->is('levels*') ? 'side-menu__sub-open' : '' }}">
                        <li>
                            <a href="{{ route('commissions.browse_banks') }}"
                                class="side-menu {{ $route == 'commissions.browse_banks' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Bancaire </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.browse_bankSends') }}"
                                class="side-menu {{ $route == 'commissions.browse_bankSends' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Rechargement Bank</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.browse_deposits') }}"
                                class="side-menu {{ $route == 'commissions.browse_deposits' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Rechargement carte</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.browse_companies') }}"
                                class="side-menu {{ $route == 'commissions.browse_companies' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Compagnies</div>
                            </a>
                        </li>


                        <li>
                            <a href="{{ route('commissions.browse_payments') }}"
                                class="side-menu {{ $route == 'commissions.browse_payments' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Paiements</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.browse_phones') }}"
                                class="side-menu {{ $route == 'commissions.browse_phones' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Credit telephonique</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.browse_reloads') }}"
                                class="side-menu {{ $route == 'commissions.browse_reloads' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Rechargement</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.browse_transactions') }}"
                                class="side-menu {{ $route == 'commissions.browse_transactions' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Transactions</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.browse_orders') }}"
                                class="side-menu {{ $route == 'commissions.browse_orders' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> 
                            <span>Commande carte</span>
                                    
                                     <div class="flex items-center gap-2 mr-3 ml-3">
                                @if ($cumulPendding > 0)
                                    <span
                                        class="inline-flex items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                                        {{ $cumulPendding }}
                                    </span>
                                @endif
                                <div
                                    class="side-menu__sub-icon transform transition-transform duration-200 group-hover:rotate-180">
                                    <i data-feather="chevron-down" class="w-4 h-4"></i>
                                </div>
                            </div>

                                </div>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('commissions.browse_virements') }}"
                                class="side-menu {{ $route == 'commissions.browse_virements' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Service virements</div>
                            </a>
                        </li> --}}

                        <li>
                            <a href="{{ route('commissions.browse_onexbets') }}"
                                class="side-menu {{ $route == 'commissions.browse_onexbets' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Service 1xbets</div>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('commissions.browse_versement_banks') }}"
                                class="side-menu {{ $route == 'commissions.browse_versement_banks' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Service versements</div>
                            </a>
                        </li>

                    </ul>
                </li>
            @endif
            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('cautions'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('pays_commissions*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="refresh-cw" class="block mx-auto"></i> </div>
                        <div class="side-menu__title">
                            Commissions Pays
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="{{ request()->is('pays_commissions*') ? 'side-menu__sub-open' : '' }}">


                        <li>
                            <a href="{{ route('commissions.west_africa') }}"
                                class="side-menu {{ $route == 'commissions.west_africa' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title"> Afrique de L'Ouest</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.centre_africa') }}"
                                class="side-menu {{ $route == 'commissions.centre_africa' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Centre Afrique</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.europe') }}"
                                class="side-menu {{ $route == 'commissions.europe' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Europe</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.maghredb') }}"
                                class="side-menu {{ $route == 'commissions.maghredb' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Maghreb</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.usa') }}"
                                class="side-menu {{ $route == 'commissions.usa' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Etat-Unis</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.asie') }}"
                                class="side-menu {{ $route == 'commissions.asie' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Asie</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.east') }}"
                                class="side-menu {{ $route == 'commissions.east' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Afrique de l'EST</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('commissions.civ') }}"
                                class="side-menu {{ $route == 'commissions.civ' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="activity" class="block mx-auto"></i>
                                </div>
                                <div class="side-menu__title">Cote d'Ivoire</div>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('administrateurs'))

                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('application*') || request()->is('operators*') || request()->is('faqs.browse*') || request()->is('list_countries*') || request()->is('admin*') || request()->is('features*') || request()->is('admin*') || request()->is('roles*') || request()->is('permissions*') || request()->is('close_app*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="users" class="block mx-auto"></i> </div>
                        <div class="side-menu__title">
                            Administrateur
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    @if (Auth::guard('web')->user()->hasPermissionTo('utilisateurs') ||
                            Auth::guard('web')->user()->hasPermissionTo('fonctionnalites') ||
                            Auth::guard('web')->user()->hasPermissionTo('permissions') ||
                            Auth::guard('web')->user()->hasPermissionTo('active-pays') ||
                            Auth::guard('web')->user()->hasPermissionTo('close-robus') ||
                            Auth::guard('web')->user()->hasPermissionTo('Faqs'))

                        <ul
                            class="{{ request()->is('application*') || request()->is('admin*') || request()->is('operators*') || request()->is('features*') || request()->is('faqs*') || request()->is('roles*') || request()->is('list_countries*') || request()->is('close_app*') ? 'side-menu__sub-open' : '' }}">

                            @if (Auth::guard('web')->user()->hasPermissionTo('utilisateurs'))
                                <li>
                                    <a href="{{ route('admin.browse') }}"
                                        class="side-menu {{ $route == 'admin.browse' || $route == 'admin.show' || $route == 'admin.showUpdate' || $route == 'admin.add' || $route == 'admin.activate' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i class="fas fa-user-friends"></i> </div>
                                        <div class="side-menu__title"> Equipes </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->user()->hasPermissionTo('close-robus'))
                                <li>
                                    <a href="{{ route('close_app.browse') }}"
                                        class="side-menu {{ $route == 'close_app.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="package"
                                                class="block mx-auto"></i></div>
                                        <div class="side-menu__title"> App S-distributeurs </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->user()->hasPermissionTo('close-robus'))
                                <li>
                                    <a href="{{ route('browse_close_app_masters.browse') }}"
                                        class="side-menu {{ $route == 'browse_close_app_masters.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="package"
                                                class="block mx-auto"></i></div>
                                        <div class="side-menu__title"> App Masters </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->user()->hasPermissionTo('close-robus'))
                                <li>
                                    <a href="{{ route('browse_companies.browse') }}"
                                        class="side-menu {{ $route == 'browse_companies.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="package"
                                                class="block mx-auto"></i></div>
                                        <div class="side-menu__title"> App Compagnie </div>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('web')->user()->hasPermissionTo('close-robus'))
                                <li>
                                    <a href="{{ route('browse_close_app_comzones.browse') }}"
                                        class="side-menu {{ $route == 'browse_close_app_comzones.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="package"
                                                class="block mx-auto"></i></div>
                                        <div class="side-menu__title"> App Com zones </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->user()->hasPermissionTo('close-robus'))
                                <li>
                                    <a href="{{ route('browse_close_app_visitors.browse') }}"
                                        class="side-menu {{ $route == 'browse_close_app_visitors.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="package"
                                                class="block mx-auto"></i></div>
                                        <div class="side-menu__title">App web Visiteurs </div>
                                    </a>
                                </li>
                            @endif


                            @if (Auth::guard('web')->user()->hasPermissionTo('Faqs'))
                                <li>
                                    <a href="{{ route('operators.browse') }}"
                                        class="side-menu {{ $route == 'operators.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i class="fas fa-user-tag"></i> </div>
                                        <div class="side-menu__title"> Assignations </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->user()->hasPermissionTo('roles'))
                                <li>
                                    <a href="{{ route('roles.browse') }}"
                                        class="side-menu {{ $route == 'roles.browse' || $route == 'roles.show' || $route == 'roles.add' || $route == 'roles.activate' || $route == 'roles.permissions' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i class="fas fa-user-tag"></i> </div>
                                        <div class="side-menu__title"> Roles </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->user()->hasPermissionTo('roles'))
                                <li>
                                    <a href="{{ route('activites.browse_activites') }}"
                                        class="side-menu {{ $route == 'activites.browse_activites' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="activity"
                                                class="block mx-auto"></i></div>
                                        <div class="side-menu__title"> Activités </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->user()->hasPermissionTo('active-pays'))
                                <li>
                                    <a href="{{ route('countries.browse') }}"
                                        class="side-menu {{ $route == 'countries.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="globe"
                                                class="block mx-auto"></i>
                                        </div>
                                        <div class="side-menu__title"> Pays </div>
                                    </a>
                                </li>
                            @endif


                            @if (Auth::guard('web')->user()->hasPermissionTo('active-pays'))
                                <li>
                                    <a href="{{ route('cities.browse') }}"
                                        class="side-menu {{ $route == 'cities.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="globe"
                                                class="block mx-auto"></i>
                                        </div>
                                        <div class="side-menu__title"> Villes </div>
                                    </a>
                                </li>
                            @endif
                            @if (Auth::guard('web')->user()->hasPermissionTo('devises'))
                                <li>
                                    <a href="{{ route('currencies.browse') }}"
                                        class="side-menu {{ $route == 'currencies.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="credit-card"
                                                class="block mx-auto"></i>
                                        </div>
                                        <div class="side-menu__title"> Devises </div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->hasPermissionTo('Faqs'))
                                <li>

                                    <a href="{{ route('faqs.browse') }}"
                                        class="side-menu {{ $route == 'faqs.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon"> <i data-feather="activity"
                                                class="block mx-auto"></i></div>
                                        <div class="side-menu__title">Faqs</div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->hasPermissionTo('envoie-messages'))
                                <li>
                                    <a href="{{ route('messages.browse') }}"
                                        class="side-menu {{ $route == 'messages.browse' || $route == 'messages.add' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon">
                                            <i data-feather="send" class="block mx-auto"></i>
                                        </div>
                                        <div class="side-menu__title">Messages</div>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->hasPermissionTo('pub'))
                                <li>
                                    <a href="{{ route('pubs.browse') }}"
                                        class="side-menu {{ $route == 'pubs.browse' ? 'side-menu--active' : '' }}">
                                        <div class="side-menu__icon">
                                            <i data-feather="speaker" class="block mx-auto"></i>
                                        </div>
                                        <div class="side-menu__title">Publicité</div>
                                    </a>
                                </li>
                            @endif

                        </ul>

                    @endif
                </li>

            @endif
            <li class="side-nav__devider my-4"></li>

            @if (Auth::guard('web')->user()->hasPermissionTo('gain-admin'))
                <li>
                    <a href="{{ route('admin.browse_gain') }}"
                        class="side-menu {{ $route == 'admin.browse_gain' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="credit-card" class="block mx-auto"></i>
                        </div>
                        <div class="side-menu__title"> Gains commissions </div>
                    </a>
                </li>
            @endif

            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('voir-s-distibuteurs'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('under_commissions*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94
                             3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0
                              0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016
                              18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995
                              0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="side-menu__title">
                            S-distributeurs
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="{{ request()->is('under_commissions*') ? 'side-menu__sub-open' : '' }}">


                        @foreach ($countries as $countrie)
                            <li>
                                <a href="{{ route('countries.Show_under_distributer', ['key' => $countrie->name]) }}"
                                    class="side-menu {{ $route == 'countries.Show_under_distributer' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="airplay"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> {{ $countrie->name }} </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif

            <li class="side-nav__devider my-4"></li>
            @if (Auth::guard('web')->user()->hasPermissionTo('voir-distributeurs'))
                <li>
                    <a href="javascript:;"
                        class="side-menu {{ request()->is('distributer_commissions*') ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="side-menu__title">
                            Operatrices mobile
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        </div>
                    </a>
                    <ul class="{{ request()->is('distributer_commissions*') ? 'side-menu__sub-open' : '' }}">


                        @foreach ($countries as $countrie)
                            <li>
                                <a href="{{ route('countries.Show_distri_distributer', ['key' => $countrie->name]) }}"
                                    class="side-menu {{ $route == 'countries.Show_distri_distributer' ? 'side-menu--active' : '' }}">
                                    <div class="side-menu__icon"> <i data-feather="airplay"
                                            class="block mx-auto"></i>
                                    </div>
                                    <div class="side-menu__title"> {{ $countrie->name }} </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endif


            <li class="side-nav__devider my-4"></li>

            <li>

                <a href="{{ route('account.profile') }}"
                    class="side-menu {{ $route == 'account.profile' || $route == 'account.security' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="user" class="block mx-auto"></i></div>
                    <div class="side-menu__title"> Mon profil </div>
                </a>
            </li>
        </ul>
    </nav>
@else
    abort(599);
@endif
<!-- END: Side Menu -->
