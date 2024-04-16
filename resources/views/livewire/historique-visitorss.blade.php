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

                    <canvas id="sum" 
                        deposit="{{ json_encode($sum_deposit) }}"  transaction="{{ json_encode($sum_transaction) }}"
                        inter="{{ json_encode($sum_inter) }}" phone="{{ json_encode($sum_phoneCredit) }}"
                        xbet="{{ json_encode($sum_xbets) }}"></canvas>
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
                <a style="color: white;" href="{{ route('visitors.transfers.historical') }}"
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
                <a style="color: white;" href="{{ route('visitors.inters.historical') }}"
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
                <a style="color: white;" href="{{ route('visitors.cartes.historical') }}"
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
                <a href="{{ route('visitors.phones.historical') }}"
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

        <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 style_user1">
            <div class="intro-y box p-5 mt-12 sm:mt-5 style_user1">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                    <div>Service 1xbets</div>
                    <div class="ml-auto">Date</div>
                </div>
                @foreach ($Wallet_xbet_last as $xbet)
                    <div class="flex items-center mb-5">
                        <div class="flex items-center">
                            <div>{{ $xbet->provider->name ?? null }}</div>

                        </div>
                        <div class="ml-auto">{{ $xbet->created_at ?? null }}</div>
                    </div>
                @endforeach
                <a style="color: white;" href="{{ route('visitors.xbets.historical') }}"
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
        var sumXbet = JSON.parse($('#sum').attr('xbet'));
        var sumDeposit = JSON.parse($('#sum').attr('deposit'));
        var sumTransaction = JSON.parse($('#sum').attr('transaction'));
        var sumInter = JSON.parse($('#sum').attr('inter'));
        var sumPhone = JSON.parse($('#sum').attr('phone'));
        var _ctx = $('#sum');

        new Chart(_ctx, {
            type: 'pie',
            data: {
                labels: [
                    'Xbets',
                    'Cartes',
                    'Transactions',
                    'Inters',
                    'Credits'
                ],
                datasets: [{

                    label: 'Activites',
                    data: [sumXbet, sumDeposit, sumTransaction, sumInter,
                        sumPhone
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
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
