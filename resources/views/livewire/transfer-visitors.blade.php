<div wire:poll.10s.visible="$refresh">
    <!-- inject:js -->
    <script src="{{ asset('public/dist/js/app.js') }}"></script>
    <script src="{{ asset('public/css/app.css') }}"></script>
    <h2 class="text-lg font-medium truncate mr-6 mt-6">

        <span
            class="alert alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3  font-bold leading-none text-indigo-100 bg-indigo-700 rounded">

            Solde : {{ formatDisplayAmount($walletSolde ?? null) }} {{ $usersCountry }}
        </span>
    </h2>
   
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
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
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
        </div>


        <div class="alert alert-dark show ml-6 mr-6 text-center mt-3" role="alert">Liste des operations
        </div>

        @include('components.transfer_nav_visitors')

        <div class="tab-content mt-6">

            @include('components.transfer_vis_components')
            @include('components.carte_vis_components')
            @include('components.credit_vis_components')
            @include('components.inter_vis_components')
            @include('components.xbet_vis_components')
        </div>

    </div>
    @include('livewire.commissions.modals.transactions.visitors.updatePhoneCredit')
    @include('livewire.commissions.modals.transactions.visitors.updateInterTransfer')
    @include('livewire.commissions.modals.transactions.visitors.updateRelaodCards')
    @include('livewire.commissions.modals.transactions.visitors.updateTransfersUV')
    @include('livewire.commissions.modals.transactions.visitors.updateXbet')

</div>

</div>
