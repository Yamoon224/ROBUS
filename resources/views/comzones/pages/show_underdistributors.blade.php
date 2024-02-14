@extends('layouts.master_comzone')

@section('title', 'Details du equipes')

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

                {{ number_format($reloadAmounts, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}

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
                {{ number_format($transactionAmounts, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>

            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($transaction_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
                {{ number_format($InternationalAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($International_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
                {{ number_format($paiementAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($paiement_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total virements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($virementAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($virement_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total credits telephonique</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($phoneCreditAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($phoneCredit_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total rechargements carte </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($reloadCardAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($reloadCard_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total commande de carte </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($walletOrderCarteAmounts, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($walletOrderCarte_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total commissions </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_wallet_transaction, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>
        </div>

        {{-- <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
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
        </div> --}}

    </div>

    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details sur l'equipe {{ $user->firstname }}
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


        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">

            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Midone - HTML Admin Template"
                        src="{{ asset($user->image ?? 'public/dist/images/profil.png') }}">
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg mb-3">
                        {{ $user->lastname ?? null }} {{ $user->firstname ?? null }}
                    </div>
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                        Code wallet : <br> {{ $user->wallet->wallet_id ?? null }}
                    </div>
                </div>

            </div>

            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Les details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail"
                            class="w-4 h-4 mr-2"></i> {{ $user->email ?? 'Inconnu' }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="mic" data-lucide="mic"
                            class="lucide lucide-mic w-4 h-4 text-slate-500 ml-auto mr-2">
                            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"></path>
                            <path d="M19 10v2a7 7 0 01-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                        {{ $user->phone ?? 'Inconnu' }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Creer : {{ $user->created_at ?? 'Inconnu' }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Date connexion : {{ $user->last_login ?? null }}
                    </div>

                    <div class="truncate sm:whitespace-normal flex items-center mt-3">

                        @if ($user->last_logout == null)
                            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                                    class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg> Toujours connecté
                            </div>
                        @else
                            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                                    class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg> Date deconnexion : {{ $user->last_logout ?? null }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet">
                <div class="text-center rounded-md  py-3" style="color: white; font-size: 16px;">
                    <div class="font-medium text-lg mb-3">Solde Wallet</div>
                    <div class="font-medium text-primary text-xl" style="color: white;">
                        {{ number_format($user->wallet()->sum('amount'), 2, ',', ' ') ?? null }}
                        {{ $user->country->currencie->code ?? null }}</div>
                </div>

            </div>

        </div>
        @include('components.account_nav_master')

    </div>
    <!-- END: Profile Info -->

    <div class="tab-content mt-6">


        <div id="order" class="tab-pane" role="tabpanel" aria-labelledby="order-tab">

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
                            Liste des commandes de carte
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Date
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Client
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Type
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Pays/Ville
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($walletOrderCarte->count() > 0)
                                            @foreach ($walletOrderCarte as $item)
                                                <tr class="intro-x">
                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>

                                                    <td>

                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $item->wallet->sidUser->firstname ?? null }}
                                                            {{ $item->wallet->sidUser->lastname ?? null }}
                                                        </span>
                                                        <div class="text-slate-500  mt-0.5">
                                                            @if ($item->is_either == 1)
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ 'Commander : pour soit' }}
                                                                </span>
                                                            @elseif ($item->is_other == 1)
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ 'Commander : pour autre' }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->typeCard->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td>
                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $item->country->name ?? null }} Ville
                                                            :{{ $item->citie->name ?? null }}
                                                        </span>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == '1')
                                                            <div class="flex items-center justify-center text-success">

                                                                <a href="#"
                                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                                    href="javascript:;"
                                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                    Activé
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-danger">


                                                                <a href="#"
                                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                                    href="javascript:;"
                                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
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
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $walletOrderCarte->links('vendor.pagination.custum') }}

                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>
        <div id="reload" class="tab-pane" role="tabpanel" aria-labelledby="reload-tab">

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
                            Liste des rechargements
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($realoadUvs->count() > 0)
                                            @foreach ($realoadUvs as $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}
                                                            </div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }}
                                                                ville :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->wallet->wallet_id != $item->account_wallet)
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                             text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Achat d'UV
                                                            </span>
                                                        @else
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                                 text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Vente d'UV
                                                            </span>
                                                        @endif
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}

                                                        </p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->paymentReload->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->account_wallet ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>


                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a wire:click="editReload({{ $item->id }}, 'update-reloadWallet-modal')"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        Modifier
                                                                    </a>
                                                                @endif
                                                            @elseif ($item->status == 'SUCCESS')
                                                                <a target="_bank"
                                                                    href="{{ route('reaload.invoice.print', $item->id) }}"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    title="Facture">
                                                                    Facture
                                                                </a>
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
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
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $realoadUvs->links('vendor.pagination.custum') }}

                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>
        <div id="carte" class="tab-pane" role="tabpanel" aria-labelledby="carte-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>
            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des rechargements de carte
                        </h2>
                    </div>
                    <div class=" py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($reloadCards->count() > 0)
                                            @foreach ($reloadCards as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->deposit->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->num_carte ?? null }}
                                                        </p>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>

                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                <a wire:click="editReloadCard({{ $item->id }}, 'update-reloadCards-modal')"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    href="javascript:;" title="Modifier">
                                                                    Modifier
                                                                </a>
                                                            @elseif ($item->status == 'SUCCESS')
                                                                <a target="_bank"
                                                                    href="{{ route('reload_card.invoice.print', $item->id) }}"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    title="Facture">
                                                                    Facture
                                                                </a>
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $reloadCards->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>
        <div id="phone" class="tab-pane" role="tabpanel" aria-labelledby="phone-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des rechargements de credit telephonique
                        </h2>
                    </div>
                    <div class="py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($phoneCredits->count() > 0)
                                            @foreach ($phoneCredits as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                                :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->paymentPhone->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }}
                                                        </p>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>

                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                    <a wire:click="editPhoneCredit({{ $item->id }}, 'update-phoneCredit-modal')"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        Modifier
                                                                    </a>
                                                                
                                                            @elseif ($item->status == 'SUCCESS')
                                                                    <a target="_bank"
                                                                        href="{{ route('phone.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $phoneCredits->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>
        <div id="inter" class="tab-pane" role="tabpanel" aria-labelledby="inter-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>



                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des transfers internationnaux
                        </h2>
                    </div>
                    <div class="py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($internationals->count() > 0)
                                            @foreach ($internationals as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        @if ($item->provider_type == 1)
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                               text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Paiement Mooney
                                                            </span>
                                                        @elseif ($item->provider_type == 5)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1
                                               mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Paiement Facture
                                                            </span>
                                                        @elseif ($item->provider_type == 6)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 
                                              mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Credit telephonique
                                                            </span>
                                                        @elseif ($item->provider_type == 7)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Versement bancaire
                                                            </span>
                                                        @elseif($item->provider_type == 8)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Rechargement carte
                                                            </span>
                                                        @endif
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                                :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->transactionInter->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }}
                                                            {{ $item->number_compte ?? null }}
                                                        </p>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>

                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                    <a wire:click="editInter({{ $item->id }}, 'update-interTransfers-modal')"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        Modifier
                                                                    </a>
                                                                
                                                            @elseif ($item->status == 'SUCCESS')
                                                                    <a target="_bank"
                                                                        href="{{ route('inters.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $internationals->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->
            </div>
        </div>
        <div id="paiement" class="tab-pane" role="tabpanel" aria-labelledby="paiement-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>

                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des paiements des factures
                        </h2>
                    </div>
                    <div class=" py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($paiements->count() > 0)
                                            @foreach ($paiements as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                                :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->payment->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>

                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                    <a wire:click="editPaiement({{ $item->id }}, 'update-paiementInvoice-modal')"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        Modifier
                                                                    </a>
                                                              
                                                            @elseif ($item->status == 'SUCCESS')
                                                                    <a target="_bank"
                                                                        href="{{ route('paiements.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $paiements->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->
            </div>
        </div>
        <div id="transfers" class="tab-pane " role="tabpanel" aria-labelledby="transfers-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des transfers d'argent
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($transactions->count() > 0)
                                            @foreach ($transactions as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                                :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->type_transaction == 'Depot')
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                                    text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Depot
                                                            </span>
                                                        @elseif ($item->type_transaction == 'Transfert')
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                transfert
                                                            </span>
                                                        @else
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Retrait
                                                            </span>
                                                        @endif
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->transaction->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }} {{ $item->num_wallet ?? null }}
                                                        </p>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>


                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                    <a wire:click="editTransaction({{ $item->id }}, 'update-transfersUV-modal')"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        Modifier
                                                                    </a>
                                                                
                                                            @elseif ($item->status == 'SUCCESS')
                                                                <a target="_bank"
                                                                    href="{{ route('transfers.invoice.print', $item->id) }}"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    title="Facture">
                                                                    Facture
                                                                </a>
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $transactions->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->
            </div>
        </div>
        <div id="virement" class="tab-pane" role="tabpanel" aria-labelledby="virement-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>

                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des demandes de virements
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($virements->count() > 0)
                                            @foreach ($virements as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                                :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->paymentBank->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }}
                                                        </p>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>

                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                       
                                                                    <a wire:click="editVirement({{ $item->id }}, 'update-virementBank-modal')"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        Modifier
                                                                    </a>
                                                              
                                                            @elseif ($item->status == 'SUCCESS')
                                                       
                                                                    <a target="_bank"
                                                                        href="{{ route('virements.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                              
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $virements->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        function sort_status(el) {
            $('#sort_status').submit();
        }
    </script>
@endpush
