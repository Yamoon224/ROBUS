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
                    Liste des commandes de carte
                </h2>
            </div>
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
                                        Client
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Type
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Pays/Ville
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Statut
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($all_ordercards->count() > 0)
                                    @foreach ($all_ordercards as $item)
                                        <tr class="intro-x">
                                            <td class="w-40">
                                                <p href="" class="font-medium whitespace-nowrap">
                                                    {{ $item->created_at ?? null }}</p>
                                            </td>

                                            <td>

                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    {{ $item->wallet->sidUser->firstname ?? null }}
                                                    {{ $item->wallet->sidUser->lastname ?? null }}
                                                </span>
                                                <div class="text-slate-500  mt-0.5">
                                                    @if ($item->is_either == 1)
                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ 'Commander : pour soit' }}
                                                        </span>
                                                    @elseif ($item->is_other == 1)
                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ 'Commander : pour autre' }}
                                                        </span>
                                                    @endif
                                                </div>

                                            </td>
                                            <td>
                                                <p href="" class="font-medium whitespace-nowrap">
                                                    {{ $item->typeCard->name ?? null }}
                                                </p>

                                            </td>
                                            <td>
                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    {{ $item->country->name ?? null }} Ville
                                                    :{{ $item->citie->name ?? null }}
                                                </span>
                                            </td>
                                            <td class="w-40">
                                                @if ($item->status == '1')
                                                    <div class="flex items-center justify-center text-success">

                                                        <a href="#"
                                                            class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                            href="javascript:;"
                                                            title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                            <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                            Activé
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center text-danger">


                                                        <a href="#"
                                                            class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                            href="javascript:;"
                                                            title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                            <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                        </a>


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
                    {{ $all_ordercards->links('vendor.pagination.custum') }}

                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->

    </div>
</div>
