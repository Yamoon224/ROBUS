<div>

    <div class="intro-y box px-5 pt-1 mt-1 ">
        <div class="grid grid-cols-12 gap-4 mt-6">
            <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                <div class="amount-label text-sm text-gray-500">Solde actuel</div>
                <div class="amount-balance">
                    <div class="flex items-baseline justify-between">
                        <span class="amount-balance-value text-xl font-semibold">
                            {{ formatDisplayAmount($walletSolde ?? 0) }}
                        </span>
                        <span class="amount-balance-currency text-white">
                            {{ $usersCountry ?? 'CFA' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Rechargements aujourd'hui -->


            @if (Auth::guard('web')->user()->hasPermissionTo('voir-commission-admin'))
                <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                    <div class="amount-label text-sm text-gray-500">Compte des commissions SD total</div>
                    <div class="amount-commission-balance">
                        <div class="flex items-baseline justify-between">
                            <span class="amount-balance-value text-xl font-semibold">
                                {{ formatDisplayAmount($walletCommissions ?? 0) }}
                            </span>
                            <span class="amount-balance-currency text-white">
                                {{ $usersCountry ?? 'CFA' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                    <div class="amount-label text-sm text-gray-500">Compte des commissions Admin </div>
                    <div class="amount-admin-balance">
                        <div class="flex items-baseline justify-between">
                            <span class="amount-balance-value text-xl font-semibold">
                                {{ $walletAdmin ?? 0 }}
                            </span>
                            <span class="amount-balance-currency text-white">
                                {{ $usersCountry ?? 'CFA' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

        </div>


        <div class="grid grid-cols-12 gap-6 mt-6">


            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" id="info-paie-show-1">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_cartes">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($walletSolde, 2, ',', ' ') ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde wallet {{ $usersCountry }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_factures">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $WalletReloads ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Rechargements {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_facture_home3">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_facture_home3">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStats['today']['total_amount'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm  rounded">
                                {{ $dailyStats['today']['success'] ?? 0 }} succès
                            </span>
                            <span class="text-sm  px-2 py-1 rounded">
                                {{ $dailyStats['today']['total'] ?? 0 }} total
                            </span>
                        </div>
                        <div class="text-base text-slate-500 mt-1">Aujourd'hui</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_facture_home2">
                <div class="report-box zoom-in style_facture_home2">
                    <div class="box p-wallet style_facture_home2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStats['average'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="text-sm opacity-75 mt-1">{{ $usersCountry }}</div>

                        <div class="text-base text-slate-500 mt-1">Montant moyen</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_facture_home">
                <div class="report-box zoom-in style_facture_home">
                    <div class="box p-wallet style_facture_home">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStats['month']['total_amount'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="text-sm opacity-75 mt-1">{{ $dailyStats['month']['total'] ?? 0 }}
                            opérations</div>
                        <div class="text-sm opacity-75 mt-1">{{ $usersCountry }}</div>

                        <div class="text-base text-slate-500 mt-1">Total mois</div>
                    </div>
                </div>
            </div>



            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_user2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_user2">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStatsSuperVisorSender['today']['total_amount'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm  rounded">
                                {{ $dailyStatsSuperVisorSender['today']['success'] ?? 0 }} succès
                            </span>
                            <span class="text-sm  px-2 py-1 rounded">
                                {{ $dailyStatsSuperVisorSender['today']['total'] ?? 0 }} total
                            </span>
                        </div>
                        <div class="text-base text-slate-500 mt-1">Total des montants envoyés Aujourd'hui du superviseur</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_table_credits">
                <div class="report-box zoom-in style_table_credits">
                    <div class="box p-wallet style_table_credits">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStatsSuperVisorSender['average'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="text-sm opacity-75 mt-1">{{ $usersCountry }}</div>

                        <div class="text-base text-slate-500 mt-1">Total des montants envoyés moyen du superviseur</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_user3">
                <div class="report-box zoom-in style_user3">
                    <div class="box p-wallet style_user3">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStatsSuperVisorSender['month']['total_amount'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="text-sm opacity-75 mt-1">{{ $dailyStatsSuperVisorSender['month']['total'] ?? 0 }}
                            opérations</div>
                        <div class="text-sm opacity-75 mt-1">{{ $usersCountry }}</div>

                        <div class="text-base text-slate-500 mt-1">Total des montants envoyés ce mois  du superviseur</div>
                    </div>
                </div>
            </div>




            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_user1">
                <div class="report-box zoom-in">

                    <div class="box p-wallet style_user1">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStatsSuperVisorReceved['today']['total_amount'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm  rounded">
                                {{ $dailyStatsSuperVisorReceved['today']['success'] ?? 0 }} succès
                            </span>
                            <span class="text-sm  px-2 py-1 rounded">
                                {{ $dailyStatsSuperVisorReceved['today']['total'] ?? 0 }} total
                            </span>
                        </div>
                        <div class="text-base text-slate-500 mt-1">Total montants reçus Aujourd'hui du superviseur</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_table1">
                <div class="report-box zoom-in style_table1">
                    <div class="box p-wallet style_table1">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStatsSuperVisorReceved['average'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="text-sm opacity-75 mt-1">{{ $usersCountry }}</div>

                        <div class="text-base text-slate-500 mt-1">Montant reçus moyen  du superviseur</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 lg:col-span-4 intro-y style_table3">
                <div class="report-box zoom-in style_table3">
                    <div class="box p-wallet style_table3">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($dailyStatsSuperVisorReceved['month']['total_amount'] ?? 0, 2, ',', ' ') }}
                        </div>
                        <div class="text-sm opacity-75 mt-1">{{ $dailyStatsSuperVisorReceved['month']['total'] ?? 0 }}
                            opérations</div>
                        <div class="text-sm opacity-75 mt-1">{{ $usersCountry }}</div>

                        <div class="text-base text-slate-500 mt-1">Total montant reçus du mois du superviseur</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="intro-y block sm:flex items-center h-10 mt-6">
            <h2 class="text-lg font-medium truncate mr-5"> Les Activites des rechargements</h2>
            <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                    href="{{ route('reloads.historical.reporting_reload_historical') }}" style="color: white;">
                    <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
                </a>
            </div>
        </div>

        <div class="mt-5">

            @if (Auth::guard('web')->user()->hasPermissionTo('rechargement-compte'))
                <button class="btn btn-primary w-22 mr-1 mb-1"
                    wire:click="$emit('modal-reloadWallet-show' , 'create-reloadWallet-modal')">Rechargement</button>
            @endif

            @if (Auth::guard('web')->user()->hasPermissionTo('rechargement-commissions'))
                <button class="btn bg-warning w-22 mr-1 mb-1 "
                    wire:click="$emit('modal-reloadWalletCommission-show' , 'create-reloadWalletCommission-modal')">Réchargement
                    des comptes commissions</button>
            @endif

            @if (Auth::guard('web')->user()->hasPermissionTo('rechargement-commissions'))
                <button class="user-badge user-badge--distributor w-22 mr-1 mb-1 "
                    wire:click="$emit('modal-transactionWalletCommission-show' , 'create-transactionWalletCommission-modal')">

                    <div class="btn-icon">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>

                    <span class="btn-text"> Transfert des commissions ADMIN</span>

                </button>
            @endif
            @if (Auth::guard('web')->user()->hasPermissionTo('rechargement-commissions'))
                <button class="user-badge  w-22 mr-1 mb-1 "
                    wire:click="$emit('modal-transactionWalletCommissionBank-show' , 'create-transactionWalletCommissionBank-modal')">

                    <div class="btn-icon">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>

                    <span class="btn-text"> Transfert des commissions Banque</span>

                </button>
            @endif


        </div>


        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center mt-3"
            role="tablist">
            <li id="reload-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center active" data-tw-target="#reload"
                    aria-controls="reload" aria-selected="true" role="tab"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>Réchargements</a>
            </li>
            <li id="commission-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#commission"
                    aria-controls="commission" aria-selected="true" role="tab"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>Rechargements des comptes commissions et transferts</a>
            </li>

        </ul>

        <div class="tab-content mt-6">

            <div id="reload" class="tab-pane active" role="tabpanel" aria-labelledby="reload-tab">



                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;   ">
                        <div class="w-56 relative text-slate-500">

                            <input type="text" name="table_search" wire:model.debounce.250ms="searchRealod"
                                class="form-control  w-56 box pr-10" style="height: 3rem; background-color: #eff3f7;"
                                placeholder="Rechercher">
                        </div>
                    </div>
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
                                    @include('components.tableReload')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Pagination -->
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                        <nav class="w-full sm:w-auto sm:mr-auto">
                            <ul class="pagination">
                                {{ $reload_historicals->links('vendor.livewire.tailwind-reload_compte') }}

                            </ul>
                        </nav>
                    </div>
                    <!-- END: Pagination -->

                </div>

            </div>

            <div id="commission" class="tab-pane" role="tabpanel" aria-labelledby="commission-tab">



                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                        <label for="statusFilterAcompte">Filtrer par type</label>
                        <select id="statusFilterAcompte" wire:model="statusFilterAcompte"
                            class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                            aria-placeholder="Rechercher par type de transaction">
                            <option selected>Type de rechargement</option>
                            <option value="Commission">Commissions</option>
                            <option value="Admin">Compte Admin</option>
                            <option value="Transferts">Transferts</option>
                        </select>
                    </div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;   ">
                        <div class="w-56 relative text-slate-500">

                            <input type="text" name="table_search" wire:model.debounce.250ms="searchRealodAcompte"
                                class="form-control  w-56 box pr-10" style="height: 3rem; background-color: #eff3f7;"
                                placeholder="Rechercher">
                            {{-- <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                                data-feather="search"></i> --}}
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6  mt-6">
                    <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                        <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Liste des rechargements des comptes administrateurs
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
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Montant demandé
                                                </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Info transaction
                                                </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Info beneficiaire
                                                </th>

                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Statut </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($reaload_acomptes->count() > 0)
                                                @php primeWallets($reaload_acomptes, 'account_wallet'); @endphp
                                                @foreach ($reaload_acomptes as $item)
                                                    <tr class="intro-x">
                                                        <td class="text-center w-40">
                                                            @if ($item->type_transaction == 'Commission')
                                                                <div
                                                                    class="px-3 py-2 text-dark amount-befor dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                    <div class="fc-event-title-container"
                                                                        style="color: white;">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Commission

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @elseif ($item->type_transaction == 'Transferts')
                                                                <div
                                                                    class="px-3 py-2 text-dark amount-after dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                    <div class="fc-event-title-container"
                                                                        style="color: white;">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Transfert

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="px-3 py-2 text-dark amount-after dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                    <div class="fc-event-title-container"
                                                                        style="color: white;">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Administration

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div
                                                                class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                <div class="fc-event-title-container"
                                                                    style="color: white;">
                                                                    <div class="fc-event-title fc-sticky">

                                                                        Robus

                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </td>
                                                        <td class="user-info-card">
                                                            @include('components.infoAdminUser')

                                                        </td>
                                                        @php

                                                            $valeur_amount_receveid =
                                                                (float) ($item->amount_received ?? 0);

                                                            $senderCurrencyValue =
                                                                optional(optional(sabdy()->country)->currencie)
                                                                    ->value ?? 1;
                                                            $receiverCurrencyValue =
                                                                optional(
                                                                    optional(optional($item->wallet)->sidUser)->country,
                                                                )->currencie->value ?? 1;
                                                            $countryId = optional(sabdy()->country)->id;

                                                            if ($countryId == 107) {
                                                                // Conversion de montant
                                                                if ($senderCurrencyValue > $receiverCurrencyValue) {
                                                                    // Conversion en CFA
                                                                    $valeur_amount_receveid =
                                                                        (float) $item->amount_received *
                                                                        (float) $senderCurrencyValue;
                                                                } elseif (
                                                                    $senderCurrencyValue == $receiverCurrencyValue
                                                                ) {
                                                                    // Montant inchangé
                                                                    $valeur_amount_receveid =
                                                                        (float) $item->amount_received;
                                                                } elseif (
                                                                    $senderCurrencyValue < $receiverCurrencyValue
                                                                ) {
                                                                    // Conversion vers la devise du pays d’envoi
                                                                    $valeur_amount_receveid =
                                                                        (float) $item->amount_received *
                                                                        (float) $receiverCurrencyValue;
                                                                }
                                                            } else {
                                                                // Pas de conversion spécifique
                                                                $valeur_amount_receveid =
                                                                    (float) $item->amount_received;
                                                            }
                                                        @endphp
                                                        <td>
                                                            @include('components.walletBalanceAdminConvertion')

                                                        </td>
                                                        <td class="w-40">
                                                            <div class="mb-3">
                                                                @include('components.info_admin_treatment')
                                                            </div>
                                                            @include('components.walletBalanceBeforConvertion')

                                                        </td>
                                                        <td class="w-40">
                                                            @if ($item->account_wallet != null)
                                                                <div class="user-name">
                                                                    {{ $item->account_wallet ?? null }}
                                                                </div>
                                                                @include('components.infoUserReload')
                                                                <div class="amount-section mt-3">
                                                                    <div class="amount-label">Solde bénéficiaire </div>
                                                                    <div class="amount-befor">
                                                                        <div
                                                                            class="flex items-baseline justify-between">
                                                                            <span class="amount-received-value">
                                                                                {{ number_format($item->amount_solde, 2, ',', ' ') ?? '0,00' }}
                                                                            </span>
                                                                            <span class="amount-received-currency">
                                                                                {{ $item->sidUser->wallet->currencie->code ?? 'EUR' }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="user-name">
                                                                    Pas un transfert de compte wallet
                                                                </div>
                                                            @endif

                                                        </td>

                                                        <td class="w-40">
                                                            @if ($item->status == 'PENDING')
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        En-attente
                                                                    </span>

                                                                </div>
                                                            @elseif ($item->status == 'ERROR')
                                                                <div
                                                                    class="flex items-center justify-center text-danger">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                            Refusé
                                                                        </span>

                                                                    </div>

                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Traiter
                                                                    </span>

                                                                </div>
                                                            @else
                                                                <div
                                                                    class="flex items-center justify-center text-success">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Accepté
                                                                        </span>


                                                                    </div>

                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Traiter
                                                                    </span>

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
                                {{ $reload_historicals->links('vendor.livewire.tailwind-reload_compte') }}

                            </ul>
                        </nav>
                    </div>
                    <!-- END: Pagination -->

                </div>
            </div>


        </div>
      
    </div>
    @include('livewire.commissions.modals.transactions.updateReload')
    @include('livewire.commissions.modals.transactions.addReload')
    @include('livewire.commissions.modals.transactions.addCommission')
    @include('livewire.commissions.modals.transactions.addTransactionCommission')
    @include('livewire.commissions.modals.transactions.addTransactionCommissionBank')

</div>

</div>

<script>
    document.addEventListener('livewire:load', function() {
        // Configuration du graphique
        const ctx = document.getElementById('activityChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        label: 'Rechargements',
                        data: @json($chartData['data']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
