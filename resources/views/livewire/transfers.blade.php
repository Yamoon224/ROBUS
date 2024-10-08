@php

    $is_under = sabdy()->is_under_distributer;
    $is_distributer = sabdy()->is_distributer;

@endphp

<div>
    <!-- inject:js -->
    <script src="{{ asset('public/dist/js/app.js') }}"></script>
    <script src="{{ asset('public/css/app.css') }}"></script>
    <h2 class="text-lg font-medium truncate mr-6 mt-6">

        <span
            class="alert alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3  font-bold leading-none text-indigo-100 bg-indigo-700 rounded">

            Solde : {{ $walletSolde ?? null }} {{ $usersCountry }}
        </span>
    </h2>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">

        @if ($is_distributer)

            <h2 class="text-lg font-medium truncate mr-5 mt-6">Telechargé les differents raports d'activités</h2>

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

            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_factures">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $transaction_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $transaction_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $transaction_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8  mt-transactions">
                            {{ $WalletTransactions ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Transactions {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in ">
                    <div class="box p-wallet ">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $payment_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $payment_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $payment_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $WalletPayments ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Paiements factures {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_cartes">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $bank_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $bank_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $bank_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $WalletBanks ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Virement {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $inter_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $inter_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $inter_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $WalletInters ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Inters {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table4">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $card_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $card_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $card_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $WalletDeposits ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Recharges cartes {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet ">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $phone_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $phone_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $phone_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $WalletPhoneCredits ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Recharges Credits {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in ">
                    <div class="box p-wallet style_table1">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $xbets_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $xbets_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $xbets_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $walletsXbets ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Service 1Xbets {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
                <div class="report-box zoom-in ">
                    <div class="box p-wallet style_table1">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success {{ $waves_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente {{ $waves_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec {{ $waves_error }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $walletsXbets ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Achat UV Waves {{ $usersCountry }} </div>
                    </div>
                </div>
            </div> --}}
        </div>


        <div class="mt-5">


            @if (sabdy()->country->code == 'CI')

                @if (sabdy()->hasPermissionTo('transfers-argent'))
                    <button class="btn btn-transuv w-22 mr-1 mb-1"
                        wire:click="$emit('modal-transfers-show' , 'create-transfersUV-modal')">Transfers
                        d'argent</button>
                @endif
                @if (sabdy()->is_under_distributer == false)

                    @if (sabdy()->hasPermissionTo('rechargement-compte'))
                        <button class="btn btn-invoice w-22 mr-1 mb-1"
                            wire:click="$emit('modal-paiements-show' , 'create-paiementInvoice-modal')">Paiements
                            facture</button>
                    @endif
                @endif
                @if (sabdy()->hasPermissionTo('virement-compte'))
                    <button class="btn btn-virement w-22 mr-1 mb-1"
                        wire:click="$emit('modal-virements-show' , 'create-virementBank-modal')">Virement
                        bancaire</button>
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-carte'))
                    <button class="btn btn-reload w-22 mr-1 mb-1"
                        wire:click="$emit('modal-reloadCards-show' , 'create-reloadCards-modal')">Rechargement de
                        carte</button>
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-credit'))
                    <button class="btn btn-pending w-22 mr-1 mb-1"
                        wire:click="$emit('modal-phoneCredit-show' , 'create-phoneCredit-modal')">Credit
                        telephonique</button>
                @endif
                @if (sabdy()->hasPermissionTo('rechargement-inters'))
                    <button class="btn btn-inter w-22 mr-1 mb-1"
                        wire:click="$emit('modal-interCivs-show' , 'create-interCivTransfers-modal')">Internationnal</button>
                @endif
            @else
                @if ($is_under)

                    @if (sabdy()->hasPermissionTo('transfers-argent'))
                        <button class="btn btn-transuv w-22 mr-1 mb-1"
                            wire:click="$emit('modal-transfers-show' , 'create-transfersUV-modal')">Transfert
                            d'argent</button>
                    @endif

                    @if (sabdy()->hasPermissionTo('rechargement-inters'))
                        <button class="btn btn-inter w-22 mr-1 mb-1"
                            wire:click="$emit('modal-inters-show' , 'create-interTransfers-modal')">Internationnal</button>
                    @endif
                @else
                    @if (sabdy()->hasPermissionTo('transfers-argent'))
                        <button class="btn btn-transuv w-22 mr-1 mb-1"
                            wire:click="$emit('modal-transfers-show' , 'create-transfersUV-modal')">Transfert
                            d'argent</button>
                    @endif

                    @if (sabdy()->hasPermissionTo('rechargement-inters'))
                        <button class="btn btn-inter w-22 mr-1 mb-1"
                            wire:click="$emit('modal-inters-show' , 'create-interTransfers-modal')">Internationnal</button>
                    @endif


                @endif

            @endif

        </div>
        <div class="alert alert-dark show ml-6 mr-6 text-center mt-3" role="alert">Liste des operations
        </div>

        @include('components.transfers_nav')

        <div class="tab-content mt-6">

            @include('components.transfer_compo')
            @if (sabdy()->is_under_distributer == false)
                @include('components.paiement_compo')
            @endif
            @include('components.virement_compo')
            @include('components.carte_compo')
            @include('components.credit_compo')
            @include('components.inter_compo')
            {{-- @include('components.xbetTransaction') --}}
            {{-- @include('components.waveTransaction') --}}
        </div>

    </div>
    @include('livewire.commissions.modals.transactions.updatePhoneCredit')
    @include('livewire.commissions.modals.transactions.updateVirementBank')
    @include('livewire.commissions.modals.transactions.updateInterTransfer')
    @include('livewire.commissions.modals.transactions.updateRelaodCards')
    @include('livewire.commissions.modals.transactions.updateTransfersUV')
    {{-- @include('livewire.commissions.modals.transactions.updateWave') --}}
    {{-- @include('livewire.commissions.modals.transactions.updateXbet') --}}
    @include('livewire.commissions.modals.transactions.updatePaiementInvoice')
    @include('livewire.commissions.modals.transactions.addPhoneCredit')
    @include('livewire.commissions.modals.transactions.addReloadCards')
    @include('livewire.commissions.modals.transactions.addInterTransfer')
    @include('livewire.commissions.modals.transactions.addInterCivTransfer')
    @include('livewire.commissions.modals.transactions.addTransfersUV')
    @include('livewire.commissions.modals.transactions.addPaiementInvoice')
    @include('livewire.commissions.modals.transactions.addVirementBank')
    @include('livewire.commissions.modals.transactions.reloadModalPage')

</div>

</div>
