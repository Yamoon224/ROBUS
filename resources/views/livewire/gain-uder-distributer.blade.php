<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des gains de sous distributeur
    </h2>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 sm:col-span-6 2xl:col-span-6 box p-5 cursor-pointer zoom-in style_3">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
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
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;  ">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" wire:model.debounce.250ms="search"
                    class="form-control  w-56 box pr-10" style="height: 3rem; "
                    placeholder="Rechercher">
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
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->created_at ?? null }}</p>
                                </td>
                                <td class="w-56">
                                    <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                        <div class="text-slate-300 text-xs  mt-0.5"> {{ $item->amount_distributer ?? null }}  {{ $item->sidUser->wallet->currencie->code ?? null }}
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
                                    <div class="mt-0.5 mb-2">
                                        <div class="mt-0.5">
                                            <div
                                            class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                            <div class="fc-event-title-container">
                                                <div class="fc-event-title fc-sticky">
                                                    Sous-distributeur</div>
                                            </div>
                                        </div>
                                            {{ $item->sidUser->firstname ?? null }}
                                            {{ $item->sidUser->lastname ?? null }}
                                        </div>
                                    </div>
                                    <div class="mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->sidUser->country->name ?? null }} ville :
                                            {{ $item->sidUser->city->name ?? null }}
                                        </span>
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
