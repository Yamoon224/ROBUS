@extends('layouts.master')

@section('title', 'Details pays')

@section('content')
    <div class="intro-y  px-5 pt-1 mt-1">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">
                <h2 class="font-medium text-base mr-auto mt-3">
                    <a href="{{ URL::previous() }}" class="btn btn-sm">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                </h2>
            </div>
            <div class="col-span-12 sm:col-span-8 xl:col-span-8 intro-y">
                <div class="alert alert-secondary show mb-2 font-medium text-lg text-center" role="alert">Les
                    Transactions des sous distributeurs en/au {{ $countrie->name ?? null }} </div>
            </div>
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">

            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-5">

            @if (Auth::guard('web')->user()->hasPermissionTo('voir-commissions'))
                <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5 style_table1">
                            <div class="flex">
                                <i data-feather="users" class="report-box__icon text-secondary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">
                                {{ $cumul_solde ?? null }} {{ $countrie->currencie->code ?? null }}
                            </div>
                            <div class="text-base text-slate-500 mt-1">Montant commissions</div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-feather="users" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $distri_countries ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Clients
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-feather="users" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $users_distri_Countries ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Sous-distributeurs
                        </div>
                    </div>
                </div>
            </div>
            @if (Auth::guard('web')->user()->hasPermissionTo('voir-commissions'))
                <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in style_table4">
                        <div class="box p-5 style_table4">

                            <div class="text-3xl font-medium leading-8 mt-6">
                                {{ $sum_wallet_drawer ?? null }} {{ $countrie->currencie->code ?? null }}
                            </div>
                            <div class="text-base text-slate-500 mt-1">Rémontée sous-distributeurs
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-feather="users" class="report-box__icon text-secondary"></i>
                            </div>
                            <div class="text-3xl font-medium leading-8 mt-6">
                                {{ $With_drawer_compagnie ?? null }} {{ $countrie->currencie->code ?? null }}
                            </div>
                            <div class="text-base text-slate-500 mt-1">Rémontée Compagnie</div>
                        </div>
                    </div>
                </div>
                @php
                    if (sabdy()->country->currencie->value > $countrie->currencie->value) {
                        //   Convertion  in CFA
                        $valeur_amount_receveid = (float) $cumul_solde * (float) $countrie->currencie->value;

                        $valeur_amount_remonted = (float) $sum_wallet_drawer * (float) $countrie->currencie->value;
                        $valeur_amount_compagnie_remonted =
                            (float) $With_drawer_compagnie * (float) $countrie->currencie->value;
                    } elseif (sabdy()->country->currencie->value == $countrie->currencie->value) {
                        $valeur_amount_receveid = $cumul_solde;
                        $valeur_amount_remonted = $sum_wallet_drawer;
                        $valeur_amount_compagnie_remonted = $With_drawer_compagnie;
                    } elseif (sabdy()->country->currencie->value < $countrie->currencie->value) {
                        $valeur_amount_receveid = (float) $cumul_solde * (float) $countrie->currencie->value;
                        $valeur_amount_remonted = (float) $sum_wallet_drawer * (float) $countrie->currencie->value;
                        $valeur_amount_compagnie_remonted =
                            (float) $With_drawer_compagnie * (float) $countrie->currencie->value;
                    }
                @endphp
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in  style-tran  ">
                    <i data-feather="trending-down" class="font-medium-5"></i>
                    <div class="font-medium text-historique mt-6">Montant commissions en CFA</div>
                    <div class="text-slate-500 mt-3">{{ $valeur_amount_receveid ?? null }} CFA</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in ">
                    <i data-feather="trending-down" class="font-medium-5"></i>
                    <div class="font-medium text-historique mt-6">Total UV S-distributeur</div>
                    <div class="text-slate-500 mt-3">{{ $sum_amount_distributer ?? null }}
                        {{ $countrie->currencie->code ?? null }}</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-6 box p-5 cursor-pointer zoom-in  style-tran ">
                    <i data-feather="trending-down" class="font-medium-5"></i>
                    <div class="font-medium text-historique mt-6">Total Somme UV clients</div>
                    <div class="text-slate-500 mt-3">{{ $sum_amount_custumers ?? null }}
                        {{ $countrie->currencie->code ?? null }}</div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-6 box p-5 cursor-pointer zoom-in">
                    <i data-feather="trending-down" class="font-medium-5"></i>
                    <div class="font-medium text-historique mt-6">Total Somme Compagnie</div>
                    <div class="text-slate-500 mt-3">{{ $sum_wallet_companies ?? null }}
                        {{ $countrie->currencie->code ?? null }}</div>
                </div>
            @endif
        </div>
        <h2 class="intro-y text-lg font-medium mt-10 style-country-info">
            Liste des commissions des sous distributeurs de la {{ $countrie->name ?? null }}
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5 mb-3">


            <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #fe6383; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #fe6383; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Transfers d'argent</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_transactions as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;"
                        href="{{ route('countries.transactions.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #ff0101; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #ff0101; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Paiement de facture</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_payments as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach

                    <a style="color: white;"
                        href="{{ route('countries.payments.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #84cc17; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #84cc17; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Versement bancaire</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_banks as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;"
                        href="{{ route('countries.banks.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #b5048f; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #b5048f; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Transfert Internationnal</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_inters as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;"href="{{ route('countries.inters.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #f97417; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #f97417; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Rechargement de carte</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_deposits as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;"
                        href="{{ route('countries.deposits.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1">
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Credit telephonique</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_phones as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('countries.phones.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 style_table_factures">
                <div class="intro-y box p-5 mt-12 sm:mt-5  style_table_factures">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                        <div>Commission remontées sous-distributeurs</div>
                        <div class="ml-auto">Date</div>
                    </div>
                    @foreach ($With_drawer_merchant as $reload)
                        <div class="ml-auto items-center text-center">{{ $reload->date_removed ?? null }}</div>

                        <div class="flex items-center mb-5 style_table_factures mt-1">
                            <div class="flex items-center">
                                <div>{{ $reload->sidUser->firstname ?? null }} {{ $reload->sidUser->lastname ?? null }}
                                </div>

                            </div>

                            <div class="ml-auto">{{ $reload->amount_with_draw ?? null }}
                                {{ $countrie->currencie->code ?? null }} </div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('drawers.historical') }}"
                        class="btn btn-outline-secondary w-full border-slate-300  border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1">
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Djamo</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_djamos as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('countries.djamos.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 ">
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Push</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_pushs as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('countries.pushs.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #b5048f; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #b5048f; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Tresort Money</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_tresorts as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;"href="{{ route('countries.tresorts.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1">
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Virements bancaire</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_virements as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('countries.virements.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>


            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #b5048f; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #b5048f; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Banque send</div>
                        <div class="ml-auto">Montant</div>
                    </div>
                    @foreach ($wallet_banksends as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->commissions ?? null }}
                                {{ $countrie->currencie->code ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;"href="{{ route('countries.banksends.Show_under_distributer', ['key' => $countrie->name]) }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>



    </div>

    @stack('scripts')

@endsection
