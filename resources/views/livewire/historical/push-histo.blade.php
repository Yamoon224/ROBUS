<div>

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <form method="GET" action="{{ route('inters.historical.reporting_xbet_days_historical.admins') }}">
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
                <div class="w-56 relative text-slate-500 mt-6">
                    <button class="btn btn-primary mr-6 ml-6 mt-3" type="submit"
                        aria-label="Télécharger en pdf">Télécharger en pdf</button>
                </div>
                </form>
            </div>

        </div>

        <div class="intro-y col-span-12 lg:col-span-12">


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
                    <div class="font-medium text-historique mt-6">Total virement</div>
                    <div class="text-slate-500 mt-3"> {{ $user ?? null }}
                        {{ $usersCountry }}
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-pendd">
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
                        {{ $xbet_historicals->where('status', 'PENDING')->count() ?? null }}
                    </div>
                </div>
                <div
                    class="col-span-12 sm:col-span-4 2xl:col-span-3 box bg-primary p-5 cursor-pointer zoom-in style-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-check font-medium-5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <div class="font-medium text-historique text-white mt-6">Success</div>
                    <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                        {{ $xbet_historicals->where('status', 'SUCCESS')->count() ?? null }}
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x-circle font-medium-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <div class="font-medium text-historique mt-6">Echec</div>
                    <div class="text-slate-500 mt-3">
                        {{ $xbet_historicals->where('status', 'ERROR')->count() ?? null }}</div>
                </div>
            </div>
        </div>
    </div>

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

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <label for="countriesFilter">Filtrer par status</label>
                <select id="statusFilter" wire:model="statusFilter"
                    class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                    aria-placeholder="Rechercher par pays">
                    <option selected>Status</option>
                    <option value="SUCCESS">Success</option>
                    <option value="ERROR">Erreur</option>
                    <option value="PENDING">En attente</option>
                </select>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 " style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500 mt-3">
                    <input type="text" name="table_search" wire:model.debounce.250ms="searchBankHistorical"
                        class="form-control w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                    {{-- <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6  ml-10" data-feather="search"></i> --}}
                </div>
            </div>

        </div>
        <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des virements
                        </h2>
                    </div>
                    <div class="tiny-slider py-5">
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
                                                Info Push
                                            </th>
                                            <th
                                                class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
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
                                        @if ($pushs->count() > 0)
                                            @foreach ($pushs as $key => $item)
                                                <tr class="intro-x">
                                                    <td class="text-center w-40">
                                                        <div
                                                            class="px-3 py-2 text-dark btn-pink dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container"
                                                                style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    {{ $item->provider->name ?? null }}

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td class="user-info-card">
                                                        @include('components.infoUser')

                                                    </td>
                                                    <td>

                                                        @include('components.walletBalance')


                                                    </td>


                                                    <td class="w-60">

                                                        <div class="mb-3">
                                                            @include('components.info')
                                                        </div>
                                                        <div class="p-4  rounded-lg shadow-sm">
                                                            <dl class="space-y-2 text-sm text-gray-700 mr-2">
                                                                <div class="flex justify-between">
                                                                    <dt class="font-medium text-gray-900">
                                                                        Nom & Prénom :
                                                                    </dt>
                                                                    <dd>{{ $item->full_name ?? '—' }}</dd>
                                                                </div>
                                                                <div class="flex justify-between mt-3">
                                                                    <dt class="font-medium text-gray-900"> Numero :
                                                                    </dt>
                                                                    <dd>
                                                                        {{ $item->phone ?? null }}
                                                                    </dd>
                                                                </div>
                                                            </dl>
                                                        </div>
                                                    </td>

                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap mb-2">
                                                            {{ $item->transaction->created_at ?? null }}</p>
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            Maj : {{ $item->transaction->updated_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @elseif($item->status == 'REFUND')
                                                            <div class="flex items-center justify-center text-primary">
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-primary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Remboursé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
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
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-40">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->updated_by != null)
                                                                @if ($item->status == 'PENDING' && $item->updated_by == Auth::guard('web')->user()->id)
                                                                    <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        <svg class="w-4 h-4 mr-1" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                        a modifier
                                                                    </a>
                                                                @elseif ($item->status == 'SUCCESS' && $item->updated_by == Auth::guard('web')->user()->id)
                                                                    @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                        <a target="_bank"
                                                                            href="{{ route('pushs.invoice.print', $item->id) }}"
                                                                            class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                            title="Facture">
                                                                            Facture
                                                                        </a>
                                                                    @endif
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
                                                            @else
                                                                <div
                                                                    class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-warring">
                                                                        <span
                                                                            class="alert alert-dark show mb-2 inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                    text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                icon-name="alert-octagon"
                                                                                data-lucide="alert-octagon"
                                                                                class="lucide lucide-alert-octagon w-6 h-6 mr-2">
                                                                                <polygon
                                                                                    points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                                                </polygon>
                                                                                <line x1="12" y1="8"
                                                                                    x2="12" y2="12">
                                                                                </line>
                                                                                <line x1="12" y1="16"
                                                                                    x2="12.01" y2="16">
                                                                                </line>
                                                                            </svg>
                                                                            Pas encore choisir
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
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6 mb-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $pushs->links() }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>


        </div>
    </div>
</div>
