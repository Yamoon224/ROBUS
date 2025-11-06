@extends('layouts.master')

@section('title', 'Details du sous distributeurs')

@php
    $currencie = $user->wallet->currencie->code;
@endphp

@section('content')

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total reçu des rechargements</div>
            <div class="text-slate-500 mt-3">

                {{ number_format($totalSumAmounts['sum_wallet_reload_received'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}

            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total compte rechargés </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_Reload'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}

            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total des Transactions </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_transaction'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total transactions recus </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_transaction_received'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total Inter rechargé </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_Inter_received'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total Inter envoyé </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_Inter'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total paiements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_payment'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total virements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_bank'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total credits telephonique</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_phone'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total rechargements carte telephonique</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_deposit'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_user1">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total 1Xbet depot</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_xbet_depot'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_user2">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total 1Xbet retrait</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_xbet_retrait'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total commissions remontées</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_wallet_drawer, 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total commissions actuelles</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_commissions'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total des retraits</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_drawals'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>


        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-alert-circle font-medium-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">En attente</div>
            <div class="text-slate-500 mt-3">
                {{ $pending_success ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-check font-medium-5">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <div class="font-medium text-historique text-white mt-6">Success</div>
            <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                {{ $sum_success ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-x-circle font-medium-5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <div class="font-medium text-historique mt-6">Echec</div>
            <div class="text-slate-500 mt-3">
                {{ $sum_error ?? null }}
            </div>
        </div>


        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Nombre de transactions inters journalières</div>
            <div class="text-slate-500 mt-3">
                {{ $count_days_inters ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_cartes">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Nombre de transactions inters mensuel</div>
            <div class="text-slate-500 mt-3">
                {{ $count_mounth_inters ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_credits">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant de transactions inters journalières</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($amount_days_inters, 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table2">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant de transactions inters mensuel</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($amount_mounth_inters, 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>
    </div>
    <h2 class="text-lg font-medium truncate mr-5 mt-6">Liste des gains à remonter et à retirer</h2>
    @include('components.rapport_remont_form')
    <h2 class="text-lg font-medium truncate mr-5 mt-6">Telechargé les differents raports d'activités</h2>
     @include('components.dawload_remont_form')


    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        @php
            $routes = [
                [
                    'label' => 'Rapport réchargements',
                    'route' => route('reloads.historical.received.reporting_reload_historical.admins', [
                    'id' => $user->wallet->id,
                    ]),
                ],
                [
                    'label' => 'Rapport des comptes rechargés',
                    'route' => route('reloads.historical.reporting_reload_historical.admins', [
                        'id' => $user->wallet->id,
                    ]),
                ],
                [
                    'label' => 'Rapport rechargement carte',
                    'route' => route('cards.historical.reporting_card_historical.admins', ['id' => $user->wallet->id]),
                ],
                [
                    'label' => 'Rapport virements',
                    'route' => route('virements.historical.reporting_historical.admins', ['id' => $user->wallet->id]),
                ],
                [
                    'label' => 'Rapport transferts',
                    'route' => route('transfers.historical.reporting_transfer_historical.admins', [
                        'id' => $user->wallet->id,
                    ]),
                ],
                [
                    'label' => 'Rapport transferts récus',
                    'route' => route('transfers.historical.received.reporting_transfer_historical.admins', [
                        'id' => $user->wallet->id,
                    ]),
                ],
                [
                    'label' => 'Rapport paiements',
                    'route' => route('inters.historical.reporting_inter_historical.admins', [
                        'id' => $user->wallet->id,
                    ]),
                ],
                [
                    'label' => 'Rapport credits',
                    'route' => route('phones.historical.reporting_phone_historical.admins', [
                        'id' => $user->wallet->id,
                    ]),
                ],
                [
                    'label' => 'Rapport paiements',
                    'route' => route('payments.historical.reporting_payment_historical.admins', [
                        'id' => $user->wallet->id,
                    ]),
                ],
            ];
        @endphp

        @foreach ($routes as $report)
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2">
                <button class="applyDateFilter flex items-center text-white btn btn-primary w-full"
                    data-url="{{ $report['route'] }}">
                    {{ $report['label'] }}
                </button>
            </div>
        @endforeach

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 " style="background-color: red">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary text-white"
                style="background-color: red"
                href="{{ route('echecs.historical.reporting_transfer_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport des échecs
            </a>
        </div>

    </div>


    @if ($user->password == null)
        <div class="grid grid-cols-12 gap-6 mt-3">

            <div class="intro-y col-span-12 lg:col-span-12">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">

                    <div class="p-5">
                        <div class="" id="step-2">
                            @include('components.update_password_form')
                        </div>
                    </div>


                </div>
                <!-- END: Form Validation -->
            </div>
        </div>
    @else
        <div class="grid grid-cols-12 gap-6 mt-3">

            <div class="intro-y col-span-12 lg:col-span-12">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">

                    <div class="p-5">
                        <div class="" id="step-2">
                            @include('components.update_password_form')
                        </div>
                    </div>


                </div>
                <!-- END: Form Validation -->
            </div>
        </div>
    @endif
    <div class="grid grid-cols-12 gap-6 mt-3">

        <div class="intro-y col-span-4 lg:col-span-12">
            <div class="intro-y box">

                <div class="p-5">
                    <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Modification du Numero</h2>

                    <div class="" id="step-2">
                        @include('components.update_phone_form')

                    </div>
                </div>

            </div>
        </div>

        <div class="intro-y col-span-4 lg:col-span-12">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

                <div class="p-5">
                    <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Modification du email</h2>

                    <div class="" id="step-2">

                        @include('components.update_email_form')
                    </div>
                </div>


            </div>
            <!-- END: Form Validation -->
        </div>
         <div class="intro-y col-span-4 lg:col-span-12">
            <div class="intro-y box">

                <div class="p-5">
                    <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Envoie des notification</h2>

                    <div class="" id="step-2">
                        @include('components.send_notfication_form')

                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('components.operator_assign_list')
    
    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        @if ($user->email_agent != null)
            @php
                $user_agent = App\Models\Sid\SidUser::where('email', $user->email_agent)->first();
            @endphp
            <span
                class="alert alert-secondary mr-10 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                Le nom de l'agent qui l'a enregistré : {{ $user_agent->firstname ?? null }}
                {{ $user_agent->lastname ?? null }} <br>
            </span>
            <span
                class="alert alert-secondary mr-10 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                Email & phone : {{ $user_agent->email ?? null }} {{ $user_agent->phone ?? null }}
            </span>
        @endif
        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details sur l'equipe {{ $user->firstname ?? null }}
        </h2>
        <h2 class="font-medium text-base mr-auto mt-3 mb-3">
            <a href="{{ URL::previous() }}" class="btn btn-sm">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                    fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </h2>

        @include('components.info_detail_wallet')

        @include('components.account_nav')

    </div>
    <!-- END: Profile Info -->

    <div class="tab-content mt-6">

        <div id="doc" class="tab-pane active" role="tabpanel" aria-labelledby="doc-tab">

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des documents
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Date d'envoie
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Image de face
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Arriere
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Image
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($kycs->count() > 0)
                                            @foreach ($kycs as $item)
                                                <tr class="intro-x">
                                                    <td class="w-40 text-center">

                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40 text-center">
                                                        <div class="flex">
                                                            <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                                <a href="{{ $item->screen }}" target="_bank">
                                                                    <img alt="image"
                                                                        class="max-w-sm h-auto shadow-lg"
                                                                        src="{{ asset($item->screen ?? 'public/dist/images/preview-8.jpg') }}"
                                                                        title="Image">
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="w-40 text-center">
                                                        <div class="flex">
                                                            <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                                <a href="{{ $item->screen_back }}" target="_bank">
                                                                    <img alt="image"
                                                                        class="max-w-sm h-auto shadow-lg"
                                                                        src="{{ asset($item->screen_back ?? 'public/dist/images/preview-8.jpg') }}"
                                                                        title="Logo">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="w-40 text-center">
                                                        <div class="flex">
                                                            <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                                <a href="{{ $item->image }}" target="_bank">
                                                                    <img alt="image"
                                                                        class="max-w-sm h-auto shadow-lg"
                                                                        src="{{ asset($item->image ?? 'public/dist/images/preview-8.jpg') }}"
                                                                        title="Logo">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == '1')
                                                            <div class="flex items-center justify-center text-success">

                                                                <a class="flex items-center mr-3">
                                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                    Validez
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-danger">

                                                                <a class="flex items-center mr-3">
                                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Invalidé
                                                                </a>

                                                            </div>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le
                                                    moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->


            </div>
        </div>
        <div id="reload" class="tab-pane" role="tabpanel" aria-labelledby="reload-tab">

            @livewire('transaction-details.reload-transactions', ['walletAllReloads' => $user->id])
        </div>
        <div id="transfers" class="tab-pane " role="tabpanel" aria-labelledby="transfers-tab">

            @livewire('transaction-details.transactions-users', ['walletAllTransactions' => $user->id])

        </div>

        <div id="paiement" class="tab-pane" role="tabpanel" aria-labelledby="paiement-tab">
            @livewire('transaction-details.payment-users', ['walletAllPayments' => $user->id])

        </div>
        <div id="order" class="tab-pane" role="tabpanel" aria-labelledby="order-tab">
            @livewire('transaction-details.orders-users', ['walletAllOrders' => $user->id])

        </div>
        <div id="virement" class="tab-pane" role="tabpanel" aria-labelledby="virement-tab">
            @livewire('transaction-details.virements-users', ['walletAllVirements' => $user->id])
        </div>
        <div id="phone" class="tab-pane" role="tabpanel" aria-labelledby="phone-tab">
            @livewire('transaction-details.phone-credit-users', ['walletAllPhoneCredits' => $user->id])
        </div>
        <div id="inter" class="tab-pane" role="tabpanel" aria-labelledby="inter-tab">
            @livewire('transaction-details.inters-users', ['walletAllInters' => $user->id])

        </div>

        <div id="carte" class="tab-pane" role="tabpanel" aria-labelledby="carte-tab">
            @livewire('transaction-details.carte-users', ['walletAllDeposits' => $user->id])

        </div>

        <div id="xbet" class="tab-pane" role="tabpanel" aria-labelledby="xbet-tab">
            @livewire('transaction-details.xbets-users', ['walletAllXbets' => $user->id])
        </div>

  
        <div id="push" class="tab-pane" role="tabpanel" aria-labelledby="push-tab">
            @livewire('transaction-details.pushs', ['walletPushs' => $user->id])

        </div>

        <div id="tresort" class="tab-pane" role="tabpanel" aria-labelledby="tresort-tab">
            @livewire('transaction-details.tresort-moneys', ['walletTresorts' => $user->id])

        </div>

        <div id="djamo" class="tab-pane" role="tabpanel" aria-labelledby="djamo-tab">
            @livewire('transaction-details.djamos', ['walletDjamos' => $user->id])

        </div>

        <div id="versments" class="tab-pane" role="tabpanel" aria-labelledby="versments-tab">
            @livewire('transaction-details.versments', ['walletVersements' => $user->id])

        </div>


    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        function sort_status(el) {
            $('#sort_status').submit();
        }
    </script>
    <script>
        document.querySelectorAll('.applyDateFilter').forEach(button => {
            button.addEventListener('click', function() {
                const baseUrl = this.dataset.url;
                const startDate = document.getElementById('startDate').value;
                const endDate = document.getElementById('endDate').value;

                const url = new URL(baseUrl, window.location.origin);
                if (startDate) url.searchParams.append('startDate', startDate);
                if (endDate) url.searchParams.append('endDate', endDate);

                window.location.href = url.toString();
            });
        });
    </script>
@endpush
