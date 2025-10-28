<div>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>


        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">
                <input type="text" name="table_search" wire:model="search" class="form-control  w-56 box pr-10"
                    style="height: 3rem;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>

    </div>
    <div class="grid grid-cols-12 gap-6  mt-6">
        <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
            <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Liste des rechargements UV
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
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Montant demandé
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Envoyer le
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Statut </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($all_reloads as $item)
                                    <tr class="intro-x">
                                        <td class="text-center w-40 ">

                                            <div
                                                class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                <div class="fc-event-title-container" style="color: white;">
                                                    <div class="fc-event-title fc-sticky">
                                                        {{ $item->provider->name ?? null }}

                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                        <td class="user-info-card">
                                            @include('components.infoUser')

                                        </td>
                                        <td class="w-60">
                                            @include('components.walletBalanceConvertion')


                                        </td>


                                        <td class="w-50">
                                            <div class="flex flex-col space-y-2">
                                                <div class="mb-2">
                                                    @include('components.info_treatment')
                                                </div>

                                                <div class="user-name">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-slate-400 group-hover:text-primary"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                                    </svg>
                                                    <span
                                                        class="font-mono text-sm text-slate-700 dark:text-slate-300  truncate">
                                                        {{ $item->account_wallet ?? 'N/A' }}
                                                    </span>
                                                </div>

                                            </div>
                                        </td>

                                        <td class="w-40">
                                                @include('components.info_status')
                                          
                                        </td>
                                        <td class="w-56">
                                            <div class="flex justify-center items-center">
                                                @if ($item->status == 'PENDING')
                                                    <div class="flex items-center justify-center text-pending">
                                                        <span
                                                            class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            En-attente
                                                        </span>

                                                    </div>
                                                @elseif ($item->status == 'SUCCESS')
                                                    <a target="_bank"
                                                        href="{{ route('reaload.invoice.print', $item->id) }}"
                                                        class="btn btn-primary mr-1 mb-2 btn-sm" title="Facture">
                                                        Facture
                                                    </a>
                                                @else
                                                    <div class="flex items-center justify-center text-dark">
                                                        <div class="flex items-center justify-center text-pending">
                                                            <span
                                                                class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Aucune
                                                            </span>

                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

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
                    {{ $all_reloads->links('vendor.pagination.custum') }}

                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->

    </div>

</div>
