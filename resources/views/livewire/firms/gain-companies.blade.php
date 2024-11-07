<div>

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">


        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Total remonté</div>

            <div class="text-slate-500 mt-3">
                {{ number_format($WalletRemontes, 2, ',', ' ') ?? null }}
                {{ $user_currencie }}</div>

        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Total Gain</div>

            <div class="text-slate-500 mt-3">
                {{ number_format($company_gains, 2, ',', ' ') ?? null }}
                {{ $user_currencie }}</div>

        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
            <a wire:click="edit({{ $gain_payments }} , 'Paiements' , 'modal-show-remonte-ccommission')"
                class="btn btn-warning mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <rect x="2" y="5" width="20" height="14" rx="2" ry="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Rémonter
            </a>
            <div class="font-medium text-historique text-white mt-6">Paiements</div>


            <div class="text-slate-500 mt-3">
                {{ number_format($gain_payments, 2, ',', ' ') ?? null }}
                {{ $user_currencie }}</div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error">
           
            @if ($showRemonterTransButton)
                <a id="modify-button"
                    wire:click="edit({{ $gain_transactions }} , 'Transactions' , 'modal-show-remonte-ccommission')"
                    class="btn btn-warning mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                              stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                              stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                              stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                              stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </a>
            @endif
        
            <div class="font-medium text-historique mt-6">Transactions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_transactions, 2, ',', ' ') ?? null }} {{ $user_currencie }}
            </div>
        </div>
        
        <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in style_table_virements">
            
            @if ($showRemonterCreditButton)
            <a wire:click="edit({{ $gain_phone_credits }} , 'Crédits' , 'modal-show-remonte-ccommission')"
            class="btn btn-warning mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 mr-3">
                <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                    stroke-linecap="round" stroke-linejoin="round" />
                <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                    stroke-linejoin="round" />
                <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                    stroke-linejoin="round" />
                <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            Rémonter
        </a>
            @endif
        
            <div class="font-medium text-historique mt-6">Credits</div>
            <div class="text-slate-500 mt-3"> {{ number_format($gain_phone_credits, 2, ',', ' ') ?? null }}
                {{ $user_currencie }}</div>

        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in">
            <a wire:click="edit({{ $gain_inters }} , 'International' , 'modal-show-remonte-ccommission')"
                class="btn btn-warning mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Rémonter
            </a>
            <div class="font-medium text-historique mt-6">Inters</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_inters, 2, ',', ' ') ?? null }}
                {{ $user_currencie }}</div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in style_table2">
            <a wire:click="edit({{ $gain_banks }} , 'Virements' , 'modal-show-remonte-ccommission')"
                class="btn btn-warning mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Rémonter
            </a>
            <div class="font-medium text-historique mt-6">Virements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_banks, 2, ',', ' ') ?? null }}
                {{ $user_currencie }}</div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in style_table_factures">

            <a wire:click="edit({{ $gain_deposits }} , 'Rechargements' , 'modal-show-remonte-ccommission')"
                class="btn btn-warning mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Rémonter
            </a>
            <div class="font-medium text-historique mt-6">Réchargements cartes</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $user_currencie }}


            </div>
        </div>
    </div>

    <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
        Liste des commissions rémontés
    </div>
    <div class="intro-y box px-5 pt-1 mt-1 ">
        <!-- BEGIN: Ticket -->

        <div class="grid grid-cols-12 gap-6 mt-1">
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Statistique de la semaine {{ $user_currencie }}
                    </h2>
                </div>

                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum" sum="{{ json_encode($remonteDays) }}"></canvas>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les statistiques de l'année en {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="reload" reload="{{ json_encode($remonteYear) }}"></canvas>
                </div>
            </div>
        </div>

        <div class="intro-y block sm:flex items-center h-10 mt-6">
            <h2 class="text-lg font-medium truncate mr-5"> Les Activites des rechargements</h2>

        </div>
        <div class="intro-y col-span-12 lg:col-span-12">

            <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">
                <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                    <div class="w-56 relative text-slate-500 mt-3">
                        <label for="">Date de debut</label>

                        <input type="date" class="form-control  w-56 box pr-10 mt -1" value="{{ $startDate }}"
                            wire:model="startDate" placeholder="Rechercher par date les commandes" id="start_date"
                            aria-label="Date">

                    </div>
                </div>
                <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                    <div class="w-56 relative text-slate-500 mt-3">
                        <label for="">Date de fin</label>
                        <input type="date" class="form-control  w-56 box pr-10 mt-1" value="{{ $endDate }}"
                            wire:model="endDate" placeholder="Rechercher par date" id="end_date" aria-label="Date">

                    </div>

                </div>


                <div class="col-span-12 sm:col-span-3 2xl:col-span-3 " style="margin-top: 13px;width: 250px;">
                    <div class="w-56 relative text-slate-500 mt-3">
                        <input type="text" name="table_search"
                            wire:model.debounce.250ms="searchTransferHistorical" class="form-control w-56 box pr-10"
                            style="height: 3rem;" placeholder="Rechercher">
                    </div>
                </div>
            </div>

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
            </div>
            <div class="tab-content mt-6">

                <div id="reload" class="tab-pane active" role="tabpanel" aria-labelledby="reload-tab">

                    <div class="grid grid-cols-12 gap-6  mt-6">
                        <div class="intro-y box col-span-12" style="background-color: #eff3f7;">

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
                                                        Montant
                                                    </th>
                                                    <th
                                                        class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                        Noveau solde
                                                    </th>
                                                    <th
                                                        class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                        Description
                                                    </th>

                                                    <th
                                                        class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                        Statut </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($allRemontes->count() > 0)
                                                    @foreach ($allRemontes as $item)
                                                        <tr class="intro-x">
                                                            <td>
                                                                <p class="font-medium whitespace-nowrap">
                                                                    {{ $item->date_removed ?? null }}
                                                                </p>

                                                            </td>
                                                            <td class="w-50">
                                                                <p class="font-medium whitespace-nowrap">
                                                                    {{ $item->amount_with_draw ?? null }}
                                                                    {{ $user_currencie }}
                                                                </p>

                                                            </td>
                                                            <td class="w-40">
                                                                <p class="font-medium whitespace-nowrap">
                                                                    {{ $item->amount_solde ?? null }}
                                                                    {{ $user_currencie }}

                                                                </p>
                                                            </td>
                                                            <td class="w-40">
                                                                <p class="font-medium whitespace-nowrap">
                                                                    {{ $item->type_transaction ?? null }}

                                                                </p>
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
                                    {{ $allRemontes->links('vendor.livewire.tailwind-reload_compte') }}

                                </ul>
                            </nav>
                        </div>
                        <!-- END: Pagination -->

                    </div>
                </div>

            </div>

        </div>
        @stack('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            var relaods = JSON.parse($('#reload').attr('reload'));
            var sumReload = JSON.parse($('#sum').attr('sum'));

            var _ctx = $('#reload');
            var _ctx2 = $('#sum');

            new Chart(_ctx, {
                type: 'bar',
                data: {
                    labels: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                        'Octobre', 'Novembre', 'Décembre'
                    ],
                    datasets: [{
                        label: 'Rechargement',
                        data: relaods,
                        backgroundColor: [
                            'rgba(255, 99, 132,1)',
                            'rgba(255, 159, 64,1)',
                            'rgba(255, 205, 86,1)',
                            'rgba(75, 192, 192,1)',
                            'rgba(54, 162, 235,1)',
                            'rgba(153, 102, 255,1)',
                            'rgba(201, 203, 207,1)',
                            'rgba(255, 0, 132,1)',
                            'rgba(255, 159, 64,1)',
                            'rgba(255, 205, 86,1)',
                            'rgba(75, 192, 192,1)',
                            'rgba(54, 162, 235,1)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132,1)',
                            'rgba(255, 159, 64,1)',
                            'rgba(255, 205, 86,1)',
                            'rgba(75, 192, 192,1)',
                            'rgba(54, 162, 235,1)',
                            'rgba(153, 102, 255,1)',
                            'rgba(201, 203, 207,1)',
                            'rgba(255, 0, 132,1)',
                            'rgba(255, 159, 64,1)',
                            'rgba(255, 205, 86,1)',
                            'rgba(75, 192, 192,1)',
                            'rgba(54, 162, 235,1)',
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

            new Chart(_ctx2, {
                type: 'bar',
                data: {
                    labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimande'],
                    datasets: [{
                        label: 'Rechargement',
                        data: sumReload,
                        backgroundColor: [
                            'rgba(255, 99, 132,1)',
                            'rgba(255, 159, 64,1)',
                            'rgba(255, 205, 86,1)',
                            'rgba(75, 192, 192,1)',
                            'rgba(54, 162, 235,1)',
                            'rgba(153, 102, 255,1)',
                            'rgba(201, 203, 207,1)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132,1)',
                            'rgba(255, 159, 64,1)',
                            'rgba(255, 205, 86,1)',
                            'rgba(75, 192, 192,1)',
                            'rgba(54, 162, 235,1)',
                            'rgba(153, 102, 255,1)',
                            'rgba(201, 203, 207,1)'
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
    @include('livewire.modals.firms.remonteCommissions')

</div>
