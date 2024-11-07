<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des frais a l'international
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500">
            </div>

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap text-center">Commence</th>
                        <th class="text-center whitespace-nowrap text-center">Termine</th>
                        <th class="text-center whitespace-nowrap text-center">Pourcentage</th>
                        <th class="text-center whitespace-nowrap text-center">% Distributeur</th>
                        <th class="text-center whitespace-nowrap text-center">% Compagnie</th>
                        <th class="text-center whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($level_inters->count() > 0)
                        @foreach ($level_inters as $item)
                            <tr class="intro-x">
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->amount_begin ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->amount_end ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->percent * 100 ?? null }} %
                                    </div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $item->comission_distributer * 100 ?? null }} %
                                    </div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $item->percent_companie * 100 ?? null }} %
                                    </div>

                                </td>


                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-level-inter-modal')"
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
    @include('livewire.commissions.modals.levels.updateLevelInter')

</div>
