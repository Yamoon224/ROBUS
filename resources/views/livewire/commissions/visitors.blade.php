<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les transactions dans les pays des visiteurs
    </h2>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        <div class="hidden md:block mx-auto text-slate-500"></div>
        
        <div class="hidden md:block ml-8 text-slate-500"></div>
        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" wire:model="search" class="form-control  w-56 box pr-10"
                    style="height: 3rem;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
           

            <table class="table table-bordered table-hover -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($countrie_percents->count() > 0)
                        @foreach ($countrie_percents->where('is_web', true) as $item)
                            <tr class="intro-x">
                                <td class="w-65">
                                    {{-- <p class="font-medium whitespace-nowrap"> --}}
                                    {{ $item->name ?? null }}
                                    {{-- </p> --}}

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_admin * 100 ?? null }} %
                                    </p>

                                </td>

                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-trans-cafrique-modal')"
                                                class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Modifier
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>
        <!-- END: Data List -->
    </div>
    @include('livewire.commissions.modals.transactions.transactionCosts.updateTransactionVisitors')

</div>
