    <div>

        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        
            <div class="col-span-12 lg:col-span-4">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Activites d'aujourd'hui 
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        {{-- <div class="report-chart"> --}}
                        <canvas id="sum" virement="{{ json_encode($sum_bank) }}"
                            deposit="{{ json_encode($sum_deposit) }}" payment="{{ json_encode($sum_payment) }}"
                            reload="{{ json_encode($sum_reload) }}" transaction="{{ json_encode($sum_transaction) }}"
                            inter="{{ json_encode($sum_inter) }}" phone="{{ json_encode($sum_phoneCredit) }}"></canvas>

                        {{-- </div> --}}
                    </div>
                </div>
            </div>
            <!-- END: Ticket -->
            <!-- BEGIN: Item List -->
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les Activites des transactions d'aujourd'hui
                    </h2>
                </div>
                
                <div class=" intro-y grid grid-cols-12 gap-5 mt-5">
                    <div
                        class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
                        <div>

                            <i data-feather="trending-down" class="font-medium-5"></i>
                        </div>
                        <div class="font-medium text-historique mt-6">Solde </div>
                        <div class="text-slate-500 mt-3"> {{ $walletSolde ?? null }} {{ $usersCountry }}
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-pendd">
                        <div>

                            <i data-feather="alert-circle" class="font-medium-5"></i>
                        </div>
                        <div class="font-medium text-historique mt-6">En attente</div>
                        <div class="text-slate-500 mt-3"> {{ $pending_success }} </div>
                    </div>
                    <div
                        class="col-span-12 sm:col-span-4 2xl:col-span-3 box bg-primary p-5 cursor-pointer zoom-in style-success">
                        <i data-feather="check" class="font-medium-5"></i>
                        <div class="font-medium text-historique text-white mt-6">Success</div>
                        <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                            {{ $sum_success ?? null }}</div>
                    </div>
                    <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error ">
                        <i data-feather="x-circle" class="font-medium-5"></i>
                        <div class="font-medium text-historique mt-6">Echec</div>
                        <div class="text-slate-500 mt-3">{{ $sum_error ?? null }}</div>
                    </div>
                     <div class="col-span-12 sm:col-span-4 2xl:col-span-6 box p-5 cursor-pointer zoom-in style-tran ">
                        <i data-feather="trending-down" class="font-medium-5"></i>
                        <div class="font-medium text-historique mt-6">Total UV S-distributeur</div>
                        <div class="text-slate-500 mt-3">{{ $sum_under_distributers ?? null }} FRCFA</div>
                    </div>
                     <div class="col-span-12 sm:col-span-4 2xl:col-span-6 box p-5 cursor-pointer zoom-in ">
                        <i data-feather="trending-down" class="font-medium-5"></i>
                        <div class="font-medium text-historique mt-6">Total Somme UV clients</div>
                        <div class="text-slate-500 mt-3">{{ $sum_custumers ?? null }} FRCFA</div>
                    </div>
                </div>
            </div>
            
            <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #ff0000; color:white;">

                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #ff0000; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Rechargement</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Reloads_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('relaods.historical') }}"
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
            <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #fe6383; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #fe6383; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Transfers d'argent</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Transactions_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('transfers.historical') }}"
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
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Payments_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('paiements.historical') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
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
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($wallet_Banks_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('virements.historical') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
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
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Inters_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('inters.historical') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
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
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Deposits_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('cartes.historical') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
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
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_PhoneCredits_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('phones.historical') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
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
                        <div class="flex items-center mb-5 style_table_factures">
                            <div class="flex items-center">
                                <div>{{ $reload->sidUser->firstname ?? null }}  {{ $reload->sidUser->lastname ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('drawers.historical') }}"
                        class="btn btn-outline-secondary w-full border-slate-300  border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        @stack('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var sumVirement = JSON.parse($('#sum').attr('virement'));
            var sumDeposit = JSON.parse($('#sum').attr('deposit'));
            var sumPayment = JSON.parse($('#sum').attr('payment'));
            var sumReload = JSON.parse($('#sum').attr('reload'));
            var sumTransaction = JSON.parse($('#sum').attr('transaction'));
            var sumInter = JSON.parse($('#sum').attr('inter'));
            var sumPhone = JSON.parse($('#sum').attr('phone'));
            var _ctx = $('#sum');

            new Chart(_ctx, {
                type: 'pie',
                data: {
                    labels: [
                        'Virements',
                        'Cartes',
                        'Paiements',
                        'Rechargement',
                        'Transactions',
                        'Inters',
                        'Credits'
                    ],
                    datasets: [{

                        label: 'Activites',
                        data: [sumVirement, sumDeposit, sumPayment, sumReload, sumTransaction, sumInter,
                            sumPhone
                        ],
                        backgroundColor: [
                            '#84cc17',
                            '#f97417',
                            '#ff0101',
                            '#ff0000',
                            '#ff6384',
                            '#b5048f',
                            '#FFFF00'

                        ],
                        borderColor: [
                            '#84cc17',
                            '#f97417',
                            '#ff0101',
                            '#ff0000',
                            '#ff6384',
                            '#b5048f',
                            '#FFFF00'
                        ],
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        labels: {
                            render: 'percentage',
                            fontColor: ['white', 'white'],
                        }
                    },
                }


            });
        </script>
    </div>
