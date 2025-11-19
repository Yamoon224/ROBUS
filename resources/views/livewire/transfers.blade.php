@php

    $is_under = sabdy()->is_under_distributer;
    $is_distributer = sabdy()->is_distributer;

@endphp

<div>
    <!-- inject:js -->
    {{-- <script src="{{ asset_url('/dist/js/app.js') }}"></script>
    <script src="{{ asset_url('/css/app.css') }}"></script> --}}
    {{-- <script src="{{ asset_url('/css/appModal.css') }}"></script> --}}


    <h2 class="text-lg font-medium truncate mr-6 mt-6">


        <div class="amount-section mr-wallet">
            <div class="amount-label">Solde actuel</div>
            <div class="amount-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value">
                        {{ $walletSolde ?? null }}
                    </span>
                    <span class="amount-balance-currency">
                        {{ $usersCountry ?? 'CFA' }}
                    </span>
                </div>
            </div>
        </div>
    </h2>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">

        @if ($is_distributer)

            <h2 class="text-lg font-medium truncate mr-5 mt-6">
                Téléchargé les différents rapports d'activités
            </h2>

            @if (sabdy()->hasPermissionTo('transfers-argent'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('transfers.historical.reporting_transfer_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport transferts
                    </a>
                </div>

                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('transfers.historical.received.reporting_transfer_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport transferts récus
                    </a>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('reloads.historical.received.reporting_reload_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport réchargements
                    </a>
                </div>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-compte'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('payments.historical.reporting_payment_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport paiements
                    </a>
                </div>
            @endif
            @if (sabdy()->hasPermissionTo('virement-compte'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('virements.historical.reporting_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport virements
                    </a>
                </div>
            @endif
            @if (sabdy()->hasPermissionTo('rechargement-carte'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('cards.historical.reporting_card_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport rechargement carte
                    </a>
                </div>
            @endif
            @if (sabdy()->hasPermissionTo('rechargement-credit'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2 ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary "
                        href="{{ route('phones.historical.reporting_phone_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport credits
                    </a>
                </div>
            @endif
            @if (sabdy()->hasPermissionTo('rechargement-carte'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('xbets.historical.reporting_xbet_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport 1xbet
                    </a>
                </div>
            @endif
            @if (sabdy()->hasPermissionTo('rechargement-carte'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('waves.historical.reporting_wave_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport Achat UV Wave
                    </a>
                </div>
            @endif
            @if (sabdy()->hasPermissionTo('rechargement-inters'))
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                        href="{{ route('inters.historical.reporting_inter_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport inter
                    </a>
                </div>

                <div class="col-span-12 sm:col-span-4 2xl:col-span-2 " style="background-color: red">
                    <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary text-white"
                        style="background-color: red"
                        href="{{ route('echecs.historical.reporting_transfer_historical.admins', ['id' => sabdy()->wallet->id]) }}"
                        style="color: white;">
                        Rapport des échecs
                    </a>
                </div>
            @endif

        @endif

    </div>
    <div class="intro-y box px-5 pt-1 mt-1 ">
        <div class="grid grid-cols-12 gap-6 mt-6">

            @if ($is_under || $is_distributer)
            @else
                <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                    <div class="report-box zoom-in">
                        <div class="box p-wallet style_table_factures">


                            <div class="flex flex-wrap gap-2 mb-transactions">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès transferts {{ $transaction_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente transferts {{ $transaction_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec transferts {{ $transaction_error }}
                                </div>

                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès crédits {{ $djamo_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente crédits {{ $phone_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec crédits {{ $phone_error }}
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-transactions">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Cartes {{ $card_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Cartes {{ $card_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Cartes {{ $card_error }}
                                </div>
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Versements {{ $versement_success }}
                                </div>

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Versements {{ $versement_pendding }}
                                </div>

                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Versements {{ $versement_error }}
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                                class="lucide lucide-credit-card report-box__icon text-secondary">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>

                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletTransactions ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Transactions {{ $usersCountry }}</div>
                                </div>

                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletPhoneCredits ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Achats Crédits téléphoniques
                                        {{ $usersCountry }}</div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 mt-3">
                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletDeposits ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Rechargements Cartes {{ $usersCountry }}
                                    </div>
                                </div>

                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletBanks ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Versements bancaires {{ $usersCountry }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                    <div class="report-box zoom-in btn-pink">
                        <div class="box p-wallet btn-pink">
                            <div class="flex flex-wrap gap-2 mb-transactions">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Factures {{ $payment_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Factures {{ $payment_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Factures {{ $payment_error }}
                                </div>
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Virements {{ $bank_success }}
                                </div>

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Virements {{ $bank_pendding }}
                                </div>

                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Virements {{ $bank_error }}
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-transactions">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Push {{ $push_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Push {{ $push_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Push {{ $push_error }}
                                </div>
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Djamo {{ $djamo_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Djamo {{ $djamo_pendding }}
                                </div>

                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Djamo {{ $djamo_error }}
                                </div>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card"
                                data-lucide="credit-card"
                                class="lucide lucide-credit-card report-box__icon text-secondary">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                </rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletPayments ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Paiements Factures {{ $usersCountry }}</div>
                                </div>

                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletBanks ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Virements bancaires {{ $usersCountry }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 mt-3">
                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletPushs ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Réchargements Push {{ $usersCountry }}</div>
                                </div>

                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletDjamos ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Réchargements Djamo {{ $usersCountry }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                    <div class="report-box zoom-in btn-purple">
                        <div class="box p-wallet btn-purple">
                            <div class="flex flex-wrap gap-2 mb-transactions">

                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Tresorts {{ $tresortMoney_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Tresorts {{ $tresortMoney_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Tresorts {{ $tresortMoney_error }}
                                </div>

                                 <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès 1Xbets {{ $xbet_success }}
                                </div>

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente 1Xbets {{ $xbet_pendding }}
                                </div>

                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec 1Xbets {{ $xbet_error }}
                                </div>

                            </div>
                            <div class="flex flex-wrap gap-2 mb-transactions">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Inters {{ $inter_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Inters {{ $inter_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Inters {{ $inter_error }}
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-transactions">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Succès Bank sends {{ $bank_send_success }}
                                </div>
                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente Bank sends {{ $bank_send_pendding }}
                                </div>
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec Bank sends {{ $bank_send_error }}
                                </div>
                            </div>


                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="credit-card"
                                data-lucide="credit-card"
                                class="lucide lucide-credit-card report-box__icon text-secondary">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                                </rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $walletTresortMoneys ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Tresort Money {{ $usersCountry }}</div>
                                </div>

                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletXbets ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Transactions 1Xbets {{ $usersCountry }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletInters ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Paiements Internationaux  {{ $usersCountry }}</div>
                                </div>

                                <div class="flex flex-col items-start">
                                    <div class="text-3xl font-medium leading-8">
                                        {{ $WalletBankSends ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500">Bank send {{ $usersCountry }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif


        </div>


        <div class="mt-5">


            @if (sabdy()->country->code == 'CI')

                @if ($is_under || $is_distributer)

                    @if ($showDepotButton)
                        @if (sabdy()->hasPermissionTo('transfers-argent'))
                            <button class="btn-modern btn-primary"
                                wire:click="$emit('modal-transfers-show', 'create-transfersUV-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span
                                    class="btn-text">{{ sabdy()->is_under_distributer ? 'Dépot d\'argent' : 'Transfers d\'argent' }}</span>
                            </button>
                        @endif

                    @endif

                    @if ($showPaiementButton)


                        @if (sabdy()->hasPermissionTo('paiement-factures'))
                            <button class="btn-modern btn-success"
                                wire:click="$emit('modal-paiements-show' , 'create-paiementInvoice-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Paiements facture</span>
                            </button>
                        @endif

                    @endif

                    @if ($showVirementButton)

                        @if (sabdy()->hasPermissionTo('virement-compte'))
                            <button class="btn-modern btn-info"
                                wire:click="$emit('modal-virements-show' , 'create-virementBank-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <span class="btn-text">Virement bancaire</span>
                            </button>
                        @endif
                    @endif


                    @if ($showRechargementButton)
                        @if (sabdy()->hasPermissionTo('rechargement-carte'))
                            <button class="btn-modern btn-warning"
                                wire:click="$emit('modal-reloadCards-show' , 'create-reloadCards-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Rechargement de carte</span>
                            </button>
                        @endif
                    @endif

                    @if ($showCreditButton)
                        @if (sabdy()->hasPermissionTo('rechargement-credit'))
                            <button class="btn-modern btn-purple"
                                wire:click="$emit('modal-phoneCredit-show' , 'create-phoneCredit-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Crédit téléphonique</span>
                            </button>
                        @endif
                    @endif

                    @if ($showTransacInterButton)
                        @if (sabdy()->hasPermissionTo('rechargement-inters'))
                            <button class="btn-modern btn-indigo"
                                wire:click="$emit('modal-interCivs-show' , 'create-interCivTransfers-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                                    </svg>
                                </div>
                                <span class="btn-text">International</span>
                            </button>
                        @endif
                    @endif

                    @if ($showXbetButton)
                        @if (sabdy()->hasPermissionTo('rechargement-xbets'))
                            <button class="btn-modern btn-orange"
                                wire:click="$emit('modal-xbet-show' , 'create-xbet-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <span class="btn-text">Xbets</span>
                            </button>
                        @endif
                    @endif

                    @if ($showVersementButton)
                        @if (sabdy()->hasPermissionTo('rechargement-versements'))
                            <button class="btn-modern btn-teal"
                                wire:click="$emit('modal-versement-show' , 'create-versement-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                </div>
                                <span class="btn-text">Versements</span>
                            </button>
                        @endif
                    @endif

                    @if ($showPushButton)
                        @if (sabdy()->hasPermissionTo('rechargement-push'))
                            <button class="btn-modern btn-pink"
                                wire:click="$emit('modal-push-show' , 'create-push-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </div>
                                <span class="btn-text">Push</span>
                            </button>
                        @endif
                    @endif
                    @if ($showDjamoButton)
                        @if (sabdy()->hasPermissionTo('rechargement-djamo'))
                            <button class="btn-modern btn-rose"
                                wire:click="$emit('modal-djamo-show' , 'create-djamo-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Djamo</span>
                            </button>
                        @endif
                    @endif
                    @if ($showTresortButton)
                        @if (sabdy()->hasPermissionTo('rechargement-tresort'))
                            <button class="btn-modern btn-amber"
                                wire:click="$emit('modal-tresort-show' , 'create-tresort-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <span class="btn-text">Trésor Money</span>
                            </button>
                        @endif
                    @endif
                @else
                    <div class="flex flex-wrap gap-3 p-4">
                        @if (sabdy()->hasPermissionTo('transfers-argent'))
                            <button class="btn-modern btn-primary"
                                wire:click="$emit('modal-transfers-show', 'create-transfersUV-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span
                                    class="btn-text">{{ sabdy()->is_under_distributer ? 'Dépot d\'argent' : 'Transfers d\'argent' }}</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('paiement-factures'))
                            <button class="btn-modern btn-success"
                                wire:click="$emit('modal-paiements-show' , 'create-paiementInvoice-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Paiements facture</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('virement-compte'))
                            <button class="btn-modern btn-info"
                                wire:click="$emit('modal-virements-show' , 'create-virementBank-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <span class="btn-text">Virement bancaire</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('rechargement-carte'))
                            <button class="btn-modern btn-warning"
                                wire:click="$emit('modal-reloadCards-show' , 'create-reloadCards-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Rechargement de carte</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('rechargement-credit'))
                            <button class="btn-modern btn-purple"
                                wire:click="$emit('modal-phoneCredit-show' , 'create-phoneCredit-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Crédit téléphonique</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('rechargement-inters'))
                            <button class="btn-modern btn-indigo"
                                wire:click="$emit('modal-interCivs-show' , 'create-interCivTransfers-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                                    </svg>
                                </div>
                                <span class="btn-text">International</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('rechargement-xbets'))
                            <button class="btn-modern btn-orange"
                                wire:click="$emit('modal-xbet-show' , 'create-xbet-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <span class="btn-text">Xbets</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('rechargement-versements'))
                            <button class="btn-modern btn-teal"
                                wire:click="$emit('modal-versement-show' , 'create-versement-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                </div>
                                <span class="btn-text">Versements</span>
                            </button>
                        @endif



                        @if (sabdy()->hasPermissionTo('rechargement-push'))
                            <button class="btn-modern btn-pink"
                                wire:click="$emit('modal-push-show' , 'create-push-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                </div>
                                <span class="btn-text">Push</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('rechargement-djamo'))
                            <button class="btn-modern btn-rose"
                                wire:click="$emit('modal-djamo-show' , 'create-djamo-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <span class="btn-text">Djamo</span>
                            </button>
                        @endif

                        @if (sabdy()->hasPermissionTo('rechargement-tresort'))
                            <button class="btn-modern btn-amber"
                                wire:click="$emit('modal-tresort-show' , 'create-tresort-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </div>
                                <span class="btn-text">Trésor Money</span>
                            </button>
                        @endif
                    </div>



                @endif
            @else
                @if ($is_under || $is_distributer)

                    @if ($showMoneyButton)
                        @if (sabdy()->hasPermissionTo('transfers-argent'))
                            <button class="btn-modern btn-primary"
                                wire:click="$emit('modal-transfers-show', 'create-transfersUV-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span
                                    class="btn-text">{{ sabdy()->is_under_distributer ? 'Dépot d\'argent' : 'Transfers d\'argent' }}</span>
                            </button>
                        @endif

                    @endif

                    @if ($showInterButton)
                        @if (sabdy()->hasPermissionTo('rechargement-inters'))
                            <button class="btn-modern btn-indigo"
                                wire:click="$emit('modal-interCivs-show' , 'create-interCivTransfers-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                                    </svg>
                                </div>
                                <span class="btn-text">International</span>
                            </button>
                        @endif
                    @endif
                @else
                    @if ($showMoneyButton)
                        @if (sabdy()->hasPermissionTo('transfers-argent'))
                            <button class="btn-modern btn-primary"
                                wire:click="$emit('modal-transfers-show', 'create-transfersUV-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span
                                    class="btn-text">{{ sabdy()->is_under_distributer ? 'Dépot d\'argent' : 'Transfers d\'argent' }}</span>
                            </button>
                        @endif

                    @endif

                    @if ($showInterButton)
                        @if (sabdy()->hasPermissionTo('rechargement-inters'))
                            <button class="btn-modern btn-primary"
                                wire:click="$emit('modal-transfers-show', 'create-transfersUV-modal')">
                                <div class="btn-icon">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span
                                    class="btn-text">{{ sabdy()->is_under_distributer ? 'Dépot d\'argent' : 'Transfers d\'argent' }}</span>
                            </button>
                        @endif
                    @endif


                @endif

            @endif

        </div>

        <div class="menu-header mt-3">
            <h3 class="menu-title">Services Financiers</h3>
            <div class="menu-badge">{{ sabdy()->country->name }}</div>
        </div>


        @include('components.transfers_nav')

        <div class="tab-content mt-6">

            @if ($is_under || $is_distributer)

                @if ($showDepotButton && sabdy()->hasPermissionTo('transfers-argent'))
                    @include('components.transfer_compo')
                @endif

                @if ($showPaiementButton && sabdy()->hasPermissionTo('paiement-factures'))
                    @include('components.paiement_compo')
                @endif

                @if ($showVirementButton && sabdy()->hasPermissionTo('virement-compte'))
                    @include('components.virement_compo')
                @endif

                @if ($showRechargementButton && sabdy()->hasPermissionTo('rechargement-carte'))
                    @include('components.carte_compo')
                @endif

                @if ($showCreditButton && sabdy()->hasPermissionTo('rechargement-credit'))
                    @include('components.credit_compo')
                @endif

                @if ($showTransacInterButton && sabdy()->hasPermissionTo('rechargement-inters'))
                    @include('components.inter_compo')
                @endif
                {{-- Nesw sends --}}
                @if ($showXbetButton && sabdy()->hasPermissionTo('rechargement-xbets'))
                    @include('components.xbet_compo')
                @endif
                @if ($showVersementButton && sabdy()->hasPermissionTo('rechargement-versements'))
                    @include('components.versement_compo')
                @endif
                {{-- @if ($showBankSendButton && sabdy()->hasPermissionTo('rechargement-bank-sends'))
                    @include('components.bank_send_compo')
                @endif --}}
                @if ($showPushButton && sabdy()->hasPermissionTo('rechargement-push'))
                    @include('components.push_compo')
                @endif
                @if ($showDjamoButton && sabdy()->hasPermissionTo('rechargement-djamo'))
                    @include('components.djamo_compo')
                @endif
                @if ($showTresortButton && sabdy()->hasPermissionTo('rechargement-tresort'))
                    @include('components.tresort_compo')
                @endif
            @else
                @if (sabdy()->hasPermissionTo('transfers-argent'))
                    @include('components.transfer_compo')
                @endif

                @if (sabdy()->hasPermissionTo('paiement-factures'))
                    @include('components.paiement_compo')
                @endif
                @if (sabdy()->hasPermissionTo('virement-compte'))
                    @include('components.virement_compo')
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-carte'))
                    @include('components.carte_compo')
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-credit'))
                    @include('components.credit_compo')
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-inters'))
                    @include('components.inter_compo')
                @endif
                {{-- Nesw sends --}}
                @if (sabdy()->hasPermissionTo('rechargement-xbets'))
                    @include('components.xbet_compo')
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-versements'))
                    @include('components.versement_compo')
                @endif
                {{-- @if (sabdy()->hasPermissionTo('rechargement-bank-sends'))
                    @include('components.bank_send_compo')
                @endif --}}
                @if (sabdy()->hasPermissionTo('rechargement-push'))
                    @include('components.push_compo')
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-djamo'))
                    @include('components.djamo_compo')
                @endif

                @if (sabdy()->hasPermissionTo('rechargement-tresort'))
                    @include('components.tresort_compo')
                @endif

            @endif


        </div>

    </div>
    @include('livewire.commissions.modals.transactions.updatePhoneCredit')
    @include('livewire.commissions.modals.transactions.updateVirementBank')
    @include('livewire.commissions.modals.transactions.updateInterTransfer')
    @include('livewire.commissions.modals.transactions.updateRelaodCards')
    @include('livewire.commissions.modals.transactions.updateTransfersUV')
    @include('livewire.commissions.modals.transactions.updateXbet')
    @include('livewire.commissions.modals.transactions.updateVersement')
    @include('livewire.commissions.modals.transactions.updatePush')
    @include('livewire.commissions.modals.transactions.updateTresort')
    @include('livewire.commissions.modals.transactions.updateDjamo')
    @include('livewire.commissions.modals.transactions.updatePaiementInvoice')
    @include('livewire.commissions.modals.transactions.addPhoneCredit')
    @include('livewire.commissions.modals.transactions.addOneXbet')
    @include('livewire.commissions.modals.transactions.addVersement')
    @include('livewire.commissions.modals.transactions.addPush')
    @include('livewire.commissions.modals.transactions.addTresort')
    @include('livewire.commissions.modals.transactions.addDjamo')
    @include('livewire.commissions.modals.transactions.addReloadCards')
    @include('livewire.commissions.modals.transactions.addInterTransfer')
    @include('livewire.commissions.modals.transactions.addInterCivTransfer')
    @include('livewire.commissions.modals.transactions.addTransfersUV')
    @include('livewire.commissions.modals.transactions.addPaiementInvoice')
    @include('livewire.commissions.modals.transactions.addVirementBank')
    @include('livewire.commissions.modals.transactions.reloadModalPage')

</div>
