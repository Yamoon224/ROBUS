<div>
    <div class="grid grid-cols-12 gap-6 mt-5">

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

        </div>

    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">
                    Les Activites des rechargements des employers
                </h2>
            </div>

            <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                <div
                    class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
                    <div>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                            <polyline points="17 18 23 18 23 12"></polyline>
                        </svg>
                    </div>
                    <div class="font-medium text-historique mt-6">Total rechargement</div>
                    <div class="text-slate-500 mt-3"> {{ formatDisplayAmount($user_sum) }}
                        {{ $usersCountry }}
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
                        {{ $reload_historicals->where('status', 'PENDING')->count() ?? null }}
                    </div>
                </div>
                <div
                    class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-check font-medium-5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <div class="font-medium text-historique text-white mt-6">Success</div>
                    <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                        {{ $reload_historicals->where('status', 'SUCCESS')->count() ?? null }}
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x-circle font-medium-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <div class="font-medium text-historique mt-6">Echec</div>
                    <div class="text-slate-500 mt-3">
                        {{ $reload_historicals->where('status', 'ERROR')->count() ?? null }}</div>
                </div>

                <div
                    class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2"
                            ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    <div class="font-medium text-historique mt-6">Gains</div>
                    <div class="text-slate-500 mt-3"> {{ formatDisplayAmount($sum_commissions) }} {{ $usersCountry }}</div>
                </div>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-12 gap-6 mt-6">

        <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">
                    Statistique de la semaine {{ $usersCountry }}
                </h2>
            </div>
            <div class="intro-y box p-5 mt-12 sm:mt-5">
                <canvas id="sum" sum="{{ json_encode($reloads_days) }}"></canvas>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
            <div class="intro-y flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">
                    Les statistiques de l'année en {{ $usersCountry }}
                </h2>
            </div>
            <div class="intro-y box p-5 mt-12 sm:mt-5">
                <canvas id="reload" reload="{{ json_encode($realods) }}"></canvas>
            </div>
        </div>
    </div>

    <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
        Statistique  des gains employers
    </div>
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12 2xl:col-span-6">
                <div class="col-span-12 2xl:col-span-9">
                    <!-- BEGIN: Sales Report -->
                    <div class="col-span-12 sm:col-span-12 lg:col-span-8 mt-8">
                        <div class="intro-y block sm:flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Activités du jour {{ $usersCountry ?? null }}
                            </h2>
                        </div>
                        <div class="intro-y box p-5 mt-12 sm:mt-5">
                            <canvas id="sum_paiement" sum_paiement="{{ json_encode($gainPaiementsDays) }}"></canvas>

                        </div>
                    </div>
                </div>

            </div>

            <div class="col-span-12 2xl:col-span-6">
                <div class="col-span-12 2xl:col-span-9">
                    <!-- BEGIN: Sales Report -->
                    <div class="col-span-12 sm:col-span-12 lg:col-span-8 mt-8">
                        <div class="intro-y block sm:flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Activités du mois {{ $usersCountry ?? null }}
                            </h2>
                        </div>
                        <div class="intro-y box p-5 mt-12 sm:mt-5">
                            <canvas id="gainreload" gainreload="{{ json_encode($gainPaiementsMonth) }}"></canvas>

                        </div>
                    </div>
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
        var sumReloadPaiement = JSON.parse($('#sum_paiement').attr('sum_paiement'));
        var gainrelaods = JSON.parse($('#gainreload').attr('gainreload'));

        var _ctx = $('#reload');
        var _ctx2 = $('#sum');
        var _ctx_3 = $('#gainreload');
        var _ctx_6 = $('#sum_paiement');

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
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimande'
                ],
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
        new Chart(_ctx_3, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Rechargement année',
                    data: gainrelaods,
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


        new Chart(_ctx_6, {
            type: 'bar',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimande'
                ],
                datasets: [{
                    label: 'Rechargement',
                    data: sumReloadPaiement,
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
