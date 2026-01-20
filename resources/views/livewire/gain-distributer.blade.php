<div>


    <div class="intro-y block sm:flex items-center h-10">
        <h2 class="text-lg font-medium truncate mr-5"> Liste des gains des sous distributeurs</h2>
        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

            <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                href="{{ route('virements.historical.reporting_historical') }}" style="color: white;">
                <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
            </a>
        </div>
    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 sm:col-span-6 2xl:col-span-6 box p-5 cursor-pointer zoom-in style_3">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant journalier</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_gain_days, 2, ',', ' ') ?? null }} FCFA
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 2xl:col-span-6 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_gains, 2, ',', ' ') ?? null }} FCFA
            </div>
        </div>

    </div>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Date de debut</label>

                    <input type="date" class="form-control  w-56 box pr-10 mt -1" value="{{ $startDate }}"
                        wire:model="startDate" placeholder="Rechercher par date les commandes" id="start_date"
                        aria-label="Date">

                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 ml-6">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Date de fin</label>
                    <input type="date" class="form-control  w-56 box pr-10 mt-1" value="{{ $endDate }}"
                        wire:model="endDate" placeholder="Rechercher par date" id="end_date" aria-label="Date">

                </div>

            </div>


        </div>
        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;  ">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" wire:model.debounce.250ms="search"
                    class="form-control  w-56 box pr-10" style="height: 3rem; " placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">

        <div class="intro-y col-span-12  lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="text-center whitespace-nowrap">Montant</th>
                        <th class="text-center whitespace-nowrap">Nom et prenom </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($gains->count() > 0)
                        @foreach ($gains as $item)
                            <tr class="intro-x">
                                <td class="w-40">
                                    <div class="mb-3">
                                        @include('components.info')
                                    </div>
                                </td>
                                <td class="w-56">
                                    <div class="amount-section">
                                        <div class="amount-label">Montant reçu</div>
                                        <div class="amount-received">
                                            <div class="flex items-baseline justify-between">
                                                <span class="amount-received-value">
                                                    {{ number_format($item->amount_distributer, 2, ',', ' ') ?? '0,00' }}
                                                    {{ $item->sidUser->wallet->currencie->code ?? null }}
                                                </span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->description_distributer ?? null }}
                                        </span>
                                    </div>

                                </td>
                                <td class="w-40">

                                    @include('components.infoUserGain')

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
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $gains->links() }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>

</div>
