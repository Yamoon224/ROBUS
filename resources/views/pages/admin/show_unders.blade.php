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

            @if ($user->is_api == 1 && $documents != null)


                <div>
                    @php

                        $documentsCollection = collect([
                            [
                                'name' => 'Front CNI',
                                'url' => $documents->front_document ?? null,
                                'status' =>
                                    $documents?->status === 'Validé'
                                        ? 'Validé'
                                        : ($documents?->status === 'Refusé'
                                            ? 'Refusé'
                                            : 'En attente'),

                                'type' => 'Image/PDF',
                                'file_size' => 'N/A',
                                'created_at' => $documents->created_at ?? null,
                                'expires_at' => null,
                                'icon' => 'id-card',
                                'type_color' => 'blue',
                            ],
                            [
                                'name' => 'Verso CNI',
                                'url' => $documents->piece_document ?? null,
                                'status' =>
                                    $documents?->status === 'Validé'
                                        ? 'Validé'
                                        : ($documents?->status === 'Refusé'
                                            ? 'Refusé'
                                            : 'En attente'),

                                'type' => 'Image/PDF',
                                'file_size' => 'N/A',
                                'created_at' => $documents->created_at ?? null,
                                'expires_at' => null,
                                'icon' => 'id-card',
                                'type_color' => 'green',
                            ],
                            [
                                'name' => 'Juridict Form',
                                'url' => $documents->juridict_form ?? null,
                                'status' =>
                                    $documents?->status === 'Validé'
                                        ? 'Validé'
                                        : ($documents?->status === 'Refusé'
                                            ? 'Refusé'
                                            : 'En attente'),

                                'type' => 'Image/PDF',
                                'file_size' => 'N/A',
                                'created_at' => $documents->created_at ?? null,
                                'expires_at' => null,
                                'icon' => 'file',
                                'type_color' => 'purple',
                            ],
                        ])->filter(fn($doc) => $doc['url']);
                    @endphp

                    <div class="min-h-screen bg-gray-50 py-8 mt-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                            <!-- Header Section -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                                <div class="px-6 py-6">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        @if ($documents->is_active == true)
                                            <div class="flex flex-col sm:flex-row gap-6 mt-3">

                                                <!-- Validation -->
                                                <div class="w-full sm:w-1/2 font-medium text-lg">
                                                    <div
                                                        class="sm:w-40 truncate sm:whitespace-normal font-medium text-lg mt-3 text-success">
                                                        <a class="flex items-center mr-3 mb-3 mt-3">
                                                            <i class="fas fa-unlock-alt w-4 h-4 mr-2 "></i>
                                                            Dossier Validé
                                                        </a>
                                                    </div>
                                                </div>

                                                <!-- Rejet -->
                                                <div class="w-full sm:w-1/2 font-medium text-lg">
                                                    <form class="validate-form" method="POST"
                                                        action="{{ route('prospects.reject_documents', ['id' => $user->id]) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <p>Désactiver du dossier</p>
                                                        <button type="submit"
                                                            class="btn btn-danger mt-3 w-full sm:w-auto px-4 py-2 bg-red-600 hover:bg-red-700">
                                                            Désactiver le dossier
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                        @else
                                            <div class="flex flex-col sm:flex-row gap-6 mt-3">

                                                <!-- Validation -->
                                                <div class="w-full sm:w-1/2 font-medium text-lg">
                                                    <form class="validate-form" method="POST"
                                                        action="{{ route('prospects.update_documents', ['id' => $user->id]) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <p>Validation du dossier</p>
                                                        <button type="submit"
                                                            class="btn btn-primary mt-3 w-full sm:w-auto px-4 py-2">
                                                            Validez le dossier
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Rejet -->
                                                <div class="w-full sm:w-1/2 font-medium text-lg">
                                                    <form class="validate-form" method="POST"
                                                        action="{{ route('prospects.reject_documents', ['id' => $user->id]) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <p>Rejet du dossier</p>
                                                        <button type="submit"
                                                            class="btn btn-danger mt-3 w-full sm:w-auto px-4 py-2 bg-red-600 hover:bg-red-700">
                                                            Rejeter le dossier
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                        @endif
                                        <div class="text-center mb-8 md:mb-0 mt-3">

                                            <p class="text-gray-600 mt-2">Téléchargez les documents du profil</p>
                                        </div>

                                        <div class="flex-1 text-center md:text-left mt-4 md:mt-0">
                                            <h1
                                                class="text-2xl font-bold text-gray-900 flex items-center justify-center md:justify-start">
                                                <i class="fas fa-file-alt text-blue-600 mr-3 text-blue-800"></i>
                                                Les Documents
                                            </h1>
                                            <p class="text-gray-600 mt-2">Gérez tous les documents en un seul endroit</p>
                                        </div>
                                        <div class="mt-4 md:mt-0 text-center md:text-left">
                                            <div class="flex items-center space-x-4 justify-center md:justify-start">
                                                <div class="bg-blue-50 rounded-lg p-3">
                                                    <i class="fas fa-file text-blue-600 text-xl text-blue-800"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-900 text-2xl ">{{ $totalCount ?? 0 }}
                                                    </h4>
                                                    <small class="text-gray-500">Documents au total</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Documents Grid -->
                            <div class="grid grid-cols-12 gap-6 mt-3">
                                @forelse ($documentsCollection as $document)
                                    <div class="intro-y col-span-12 lg:col-span-4">
                                        <div class="box">
                                            <div
                                                class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">

                                                @if ($document['status'] === 'En attente')
                                                    <div class="mb-4">
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full animate-pulse"
                                                                style="width: 75%"></div>
                                                        </div>
                                                        <p class="text-amber-600 text-xs text-center mt-2">
                                                            <i class="fas fa-sync-alt animate-spin mr-1"></i>En cours de
                                                            vérification
                                                        </p>
                                                    </div>
                                                @elseIf($document['status'] === 'Refusé')
                                                    <div class="mb-4">
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full animate-pulse"
                                                                style="width: 75%"></div>
                                                        </div>
                                                        <p class="text-amber-600 text-xs text-center mt-2">
                                                            <span
                                                                class="inline-flex status-badge bg-danger  items-center px-4 py-2 text-base font-semibold text-white bg-red-600 rounded-lg shadow-sm">
                                                                <i class="fas fa-times-circle mr-2"></i>
                                                                Refusé
                                                            </span>
                                                        </p>
                                                    </div>
                                                @else
                                                    <div class="mb-4">
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full animate-pulse"
                                                                style="width: 75%"></div>
                                                        </div>
                                                        <p class="text-amber-600 text-xs text-center mt-2">
                                                            <span
                                                                class="inline-flex status-badge bg-success items-center px-4 py-2 text-base font-semibold text-white bg-green-600 rounded-lg shadow-sm">
                                                                <i class="fas fa-check-circle mr-2"></i>
                                                                Validé
                                                            </span>
                                                        </p>
                                                    </div>
                                                @endif
                                                <div
                                                    class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                                    @if ($document['status'] === 'Validé')
                                                        <span
                                                            class="inline-flex status-badge bg-success items-center px-4 py-2 text-base font-semibold text-white bg-green-600 rounded-lg shadow-sm">
                                                            <i class="fas fa-check-circle mr-2"></i>
                                                            Validé
                                                        </span>
                                                    @elseIf($document['status'] === 'Refusé')
                                                        <span
                                                            class="inline-flex status-badge bg-danger  items-center px-4 py-2 text-base font-semibold text-white bg-red-600 rounded-lg shadow-sm">
                                                            <i class="fas fa-times-circle mr-2"></i>
                                                            Refusé
                                                        </span>
                                                    @else
                                                        <span class="status-badge dossier-pedding">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            En attente
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="p-6">
                                                <!-- Document Header -->
                                                <div class="flex items-start justify-between mb-4 ml-6 mt-6">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="bg-blue-50 rounded-xl p-3">
                                                            <i
                                                                class="fas fa-{{ $document['icon'] ?? 'file' }} border-blue-200  text-blue-800 text-lg"></i>
                                                        </div>
                                                        <div class="min-w-0 flex-1 mr-3 ml-3 ">
                                                            <h6 class="font-bold text-gray-900 truncate"
                                                                style="max-width: 200px;">
                                                                {{ $document['name'] ?? 'Document sans nom' }}
                                                            </h6>
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document['status'] === 'Validé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} mt-1">
                                                                {{ $document['status'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Progress Bar for Pending Documents -->


                                                <!-- Action Buttons -->
                                                <div
                                                    class="intro-y flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pt-5 border-t border-slate-200/60 dark:border-darkmode-400">


                                                    <div
                                                        class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                                        <a class="flex items-center mr-3 mb-6"
                                                            href="{{ $document['url'] }}">
                                                            <i class="fas fa-eye mr-2"></i>
                                                            Voir les détails
                                                        </a>

                                                        <div class="flex items-center mr-3 mb-6 bg-success text-white">
                                                            <a href="{{ $document['url'] }}" download
                                                                class="flex-1 bg-blue-600 text-black hover:bg-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                                                <i class="fas fa-download mr-2"></i>Télécharger
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                @empty
                                    <div class="col-span-12">
                                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                                            <div class="px-6 py-12 text-center">
                                                <div class="text-gray-300 mb-4">
                                                    <i class="fas fa-file-import text-5xl"></i>
                                                </div>
                                                <h4 class="font-bold text-gray-900 text-xl mb-2">Aucun document trouvé</h4>
                                                <p class="text-gray-600 mb-6">
                                                    @if (!$documents)
                                                        Aucune information de client trouvée. Veuillez compléter votre
                                                        profil.
                                                    @else
                                                        Il n'avez pas encore ajouté de documents à votre compte.
                                                    @endif
                                                </p>
                                                <a href="{{ route('propects.identifications.add') }}"
                                                    class="bg-blue-600 text-white hover:bg-blue-700 px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    @if (!$documents)
                                                        Compléter mon profil
                                                    @else
                                                        Il doit ajouter un document
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>


                            <!-- Statistics Section -->
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden py-6 px-5">
                                @if ($documents->is_active == true)
                                    <div class="grid grid-cols-12 gap-6 mt-6">

                                        <!-- API KEY -->
                                        <div class="col-span-12 lg:col-span-6 flex flex-col">
                                            <div
                                                class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200 hover:shadow-md transition-all duration-300">

                                                <!-- Header -->
                                                <div class="flex items-center mb-4 mt-3">
                                                    <div class="bg-blue-100 rounded-xl p-3 mr-4">
                                                        <i class="fas fa-key text-blue-500 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-900 text-lg">Clé API</h3>
                                                        <p class="text-gray-600 text-sm">Utilisez cette clé pour
                                                            authentifier vos requêtes</p>
                                                    </div>
                                                </div>

                                                <!-- Input + Copy Button -->

                                                <div class="relative">
                                                    <input type="text" value="{{ $documents->api_key }}" readonly
                                                        id="api-key"
                                                        class="w-full bg-white border border-blue-300 rounded-xl px-4 py-3
                                                            pr-24 font-mono text-sm text-gray-700
                                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                                                        onclick="this.select()">


                                                    <div class="inset-y-0 right-3 flex items-center space-x-3 mb-3 mt-3">

                                                        <!-- Show/Hide -->
                                                        <button onclick="toggleKeyVisibility()"
                                                            class="px-2 text-gray-400 hover:text-purple-600 transition-colors"
                                                            id="toggle-key" title="Afficher/Masquer">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <!-- Copy -->
                                                        <button
                                                            onclick="copyToClipboard('{{ $documents->api_key }}', 'api-key-copy')"
                                                            class="text-gray-400 hover:text-blue-600 transition-colors"
                                                            id="api-key-copy" title="Copier la clé API">
                                                            <i class="fas fa-copy text-lg"></i>
                                                        </button>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- API SECRET -->
                                        <div class="col-span-12 lg:col-span-6 flex flex-col">
                                            <div
                                                class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200 hover:shadow-md transition-all duration-300">

                                                <!-- Header -->
                                                <div class="flex items-center mb-4 mt-3">
                                                    <div class="bg-purple-100 rounded-xl p-3 mr-4">
                                                        <i class="fas fa-lock text-purple-600 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-900 text-lg">Clé Secrète</h3>
                                                        <p class="text-gray-600 text-sm">Gardez cette clé confidentielle
                                                            et sécurisée</p>
                                                    </div>
                                                </div>

                                                <!-- Input + Buttons -->
                                                <div class="relative">
                                                    <input type="password" value="{{ $documents->api_secret }}" readonly
                                                        id="secret-key"
                                                        class="w-full bg-white border border-purple-300 rounded-xl px-4 py-3 pr-24 font-mono text-sm text-gray-700
                                                     focus:outline-none focus:ring-2 focus:ring-purple-500 cursor-pointer"
                                                        onclick="this.select()">

                                                    <div class="inset-y-0 right-3 flex items-center space-x-3 mb-3 mt-3">

                                                        <!-- Show/hide Btn -->
                                                        <button onclick="toggleSecretVisibility()"
                                                            class="px-2 text-gray-400 hover:text-purple-600 transition-colors"
                                                            id="toggle-secret" title="Afficher/Masquer">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <!-- Copy Btn -->
                                                        <button
                                                            onclick="copyToClipboard('{{ $documents->api_secret }}', 'secret-key-copy')"
                                                            class="px-2 text-gray-400 hover:text-purple-600 transition-colors"
                                                            id="secret-key-copy" title="Copier la clé secrète">
                                                            <i class="fas fa-copy"></i>
                                                        </button>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="px-6 mt-6">
                                        <p class="mb-3">
                                            <span class="style_table2 w-25 text-white fs-5 px-2 py-1 rounded">
                                                Commissions
                                            </span>
                                        </p>

                                        @foreach ($user->operator_services as $operator)
                                            @if ($operator->operator->is_commission)
                                                <span
                                                    class="alert mt-3 alert-success text-white font-assign-list inline-flex items-center justify-center px-2 py-1  text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    {{ $operator->operator->name ?? null }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="px-6 mt-6">
                                        <p class="mb-3">
                                            <span class="style_table2 w-25 text-white fs-5 px-2 py-1 rounded">
                                                Operations
                                            </span>
                                        </p>

                                        @foreach ($user->operator_services as $operator)
                                            @if ($operator->operator->is_under)
                                                <span
                                                    class="alert alert-success mt-1 text-white font-assign-list inline-flex items-center justify-center px-2 py-1  text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    {{ $operator->operator->name ?? null }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <!-- Information Message -->
                                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6 mt-6 ml-3 mr-3 ">
                                        <div class="flex items-start">
                                            <i class="fas fa-info-circle text-blue-500 text-xl mr-4 mt-1"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-900 mb-2">Comment utiliser vos clés
                                                    API ?
                                                </h4>
                                                <p class="text-gray-600 text-sm mb-3">
                                                    Utilisez votre API Key et Secret Key pour authentifier vos requêtes
                                                    HTTP.
                                                    Incluez-les dans les en-têtes de vos requêtes selon notre
                                                    documentation.
                                                </p>
                                                <div class="bg-white rounded-lg p-4 border border-blue-100">
                                                    <code class="text-xs text-gray-700 font-mono">
                                                        Headers: {<br>
                                                        &nbsp;&nbsp;"X-API-Key":
                                                        "{{ substr($documents->api_key, 0, 10) }}...",<br>
                                                        &nbsp;&nbsp;"X-API-Secret":
                                                        "{{ substr($documents->api_secret, 0, 10) }}..."<br>
                                                        }
                                                    </code>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>
            @else
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
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                    Date d'envoie
                                                </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                    Image de face
                                                </th>

                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                    Arriere
                                                </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                    Image
                                                </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
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
            @endif

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
    @include('components.integrateScript')

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
