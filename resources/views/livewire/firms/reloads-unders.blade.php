<div>

    <div class="intro-y box px-5 pt-1 mt-1 ">
        <!-- BEGIN: Ticket -->
        <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <form method="GET"
                    action="{{ route('employers.historical.reporting_transaction_days_historical.companies') }}">
                    <div class="relative text-slate-500 mt-3">
                        <label for="search_begin">Date de début</label>
                        <input type="date"
                            class="form-control w-56 box pr-10 mt -1 @error('search_begin') is-invalid @enderror"
                            name="search_begin" placeholder="Rechercher par date les commandes" id="search_begin"
                            aria-label="Date de début" onchange="sortDate()">
                        @error('search_begin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
            </div>

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="relative text-slate-500 mt-3">
                    <label for="search_end">Date de fin</label>
                    <input type="date"
                        class="form-control w-56 box pr-10 mt -1 @error('search_end') is-invalid @enderror"
                        name="search_end" placeholder="Rechercher par date les commandes" id="search_end"
                        aria-label="Date de fin" onchange="sortDate()">
                    @error('search_end')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 mt-3">

                <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                    <button class="btn btn-primary mr-6 ml-6 mt-3" type="submit" aria-label="Télécharger en pdf"
                        style="color: white;">
                        <i data-feather="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le
                        rapport des
                        rechargements
                    </button>
                </div>
                </form>
            </div>


        </div>
        <div class="grid grid-cols-12 gap-6 mt-1">
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
        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" id="info-paie-show-1">
                <div class="report-box zoom-in">
                    <div class="box p-wallet">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $reload_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $reload_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $reload_error }}

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
                            {{ number_format($walletSolde, 2, ',', ' ') ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde wallet {{ $usersCountry }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_factures">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $reload_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $reload_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $reload_error }}

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
                            {{ $WalletReloads ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Rechargements {{ $usersCountry }} </div>
                    </div>
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

                <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                    <label for="countriesFilter">Filtrer par status</label>
                    <select id="statusTransferFilter" wire:model="statusTransferFilter"
                        class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                        aria-placeholder="Rechercher par pays">
                        <option selected disabled>Status</option>
                        <option value="SUCCESS">Success</option>
                        <option value="ERROR">Erreur</option>
                        <option value="PENDING">En attente</option>
                    </select>
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
                                                        Envoyer le
                                                    </th>

                                                    <th
                                                        class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                        Numero
                                                    </th>

                                                    <th
                                                        class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                        Statut </th>
                                                    <th
                                                        class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                        Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($reaload_uv->count() > 0)
                                                    @php primeWallets($reaload_uv, 'account_wallet'); @endphp
                                                    @foreach ($reaload_uv as $item)
                                                        <tr class="intro-x">
                                                            <td>
                                                                <p class="font-medium whitespace-nowrap">
                                                                    {{ $item->provider->name ?? null }}
                                                                </p>

                                                            </td>
                                                            <td class="w-50">
                                                                <div class=" mt-0.5 mb-2">
                                                                    <div class=" mt-0.5">
                                                                        {{ $item->wallet->sidUser->firstname ?? null }}
                                                                        {{ $item->wallet->sidUser->lastname ?? null }}
                                                                    </div>
                                                                </div>
                                                                <div class="  mt-0.5">
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
                                                                        Transfers d'argent
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                                 text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Depot d'argent
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

                                                                @php
                                                                    $wallet_info = walletByNumber($item->account_wallet);
                                                                @endphp

                                                                <p class="font-medium whitespace-nowrap">
                                                                    {{ $wallet_info->sidUser->firstname ?? null }}
                                                                    {{ $wallet_info->sidUser->lastname ?? null }}
                                                                </p>
                                                                <p class="font-medium whitespace-nowrap mt-3">
                                                                    {{ $item->account_wallet ?? null }}</p>
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
                                                                        <div
                                                                            class="flex items-center justify-center text-dark">
                                                                            <div
                                                                                class="flex items-center justify-center text-pending">
                                                                                <span
                                                                                    class="alert  alert-warning inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                                    style="color: white;">
                                                                                    Déjà choisir
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
                                    {{ $reaload_uv->links('vendor.livewire.tailwind-reload_compte') }}

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
</div>
