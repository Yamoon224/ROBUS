<div>

    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y block sm:flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Les Activites du compte Aujourdh'ui
            </h2>
        </div>

        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="col-span-12 sm:col-span-4 2xl:col-span-6 box p-5 cursor-pointer zoom-in style_table_factures">
                <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-trending-down font-medium-5">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                </div>
                <div class="font-medium text-historique mt-6">Montant total</div>
                <div class="text-slate-500 mt-3">
                    {{ number_format($sum_amount, 2, ',', ' ') ?? null }} {{ $usersCountry }}
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
            <div
                class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
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

        </div>
        <div class="col-span-12 xl:col-span-6 mt-6">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">
                    Les 10 derniers employes
                </h2>
            </div>
            <!-- BEGIN: Weekly Top Products -->
            <div class="intro-y col-span-12  lg:overflow-visible mt-3">
                <table class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap">Nom & prenom</th>
                            <th class="text-center whitespace-nowrap">Email</th>
                            <th class="text-center whitespace-nowrap">Pays/Ville</th>
                            <th class="text-center whitespace-nowrap">Catégorie</th>
                            <th class="text-center whitespace-nowrap">Status compte </th>
                            <th class="text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->count() > 0)
                            @foreach ($users as $item)
                                <tr class="intro-x">
                                    <td>
                                        {{ $item->firstname }}
                                        {{ $item->lastname }}
                                    </td>
                                    <td>
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->email ?? null }}
                                        </span>

                                    </td>

                                    <td>
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->country->name ?? null }} : {{ $item->city->name ?? null }}
                                        </span>
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            Numero : {{ $item->phone ?? null }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->categorie->name ?? null }}
                                        </span>
                                    </td>

                                    <td class="table-report__action w-35">
                                        @if ($item->company_id == firm()->id)
                                            <div class="flex items-center justify-center text-success">

                                                <a wire:click="edit({{ $item->wallet->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                    class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                </a>

                                            </div>
                                        @else
                                            <div class="flex items-center justify-center text-danger">
                                                <a wire:click="edit({{ $item->wallet->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                    class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                </a>

                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a wire:click="edit({{ $item->wallet->id }}, 'update-employers-modal')"
                                                class="btn btn-warning  mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Modifier
                                            </a>
                                            <a class="btn btn-primary  mr-1 mb-2 btn-sm"
                                                href="{{ route('employes.show', ['id' => $item->id]) }}">
                                                Voir
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Aucun client pour le moment</td>
                            </tr>
                        @endif

                    </tbody>

                </table>
            </div>
            <!-- END: Weekly Top Products -->
        </div>

        <div class="grid grid-cols-12 gap-6 mt-6">

            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Statistique de la semaine {{ $usersCountry }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    {{-- <canvas id="reloads_gain" reload_gain="{{ json_encode($reloads_gain) }}"></canvas> --}}
                    <canvas id="sum" sum="{{ json_encode($reloads_days) }}"></canvas>
                </div>
            </div>
            <!-- END: Sales Report -->
            <!-- BEGIN: Sales Report -->
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
            <!-- END: Sales Report -->
            <!-- END: Sales Report -->
        </div>
    </div>
    @include('livewire.modals.firms.updatemployers')
    @include('livewire.modals.firms.disabledEmployers')
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
                    label: 'Rechargement année',
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
                    label: 'Rechargement semaine',
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
