<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les banks
    </h2>

    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <table class="table table-bordered table-hover -mt-2 -intro-x ">
            <thead class="table-dark">
                <tr>
                    <th class="whitespace-nowrap text-center">Montant debut</th>
                    <th class="text-center whitespace-nowrap text-center">Montant Fin</th>
                    <th class="text-center whitespace-nowrap text-center">Frais</th>
                    <th class="text-center whitespace-nowrap text-center">Banque</th>
                    <th class="text-center whitespace-nowrap text-center">Robus</th>
                    <th class="text-center whitespace-nowrap text-center">SD</th>
                    <th class="text-center whitespace-nowrap text-center">Société</th>
                    <th class="text-center whitespace-nowrap text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($banksends->count() > 0)
                    @foreach ($banksends as $item)
                        <tr class="table-secondary">
                            <td colspan="8" class="text-left font-bold">
                                <h2 class="alert alert-dark  intro-y text-lg font-medium">

                                    {{ $item->name }}
                                </h2>
                            </td>
                        </tr>
                        @foreach ($item->levelBankAuthers as $itemLevelbank)
                            <tr class="intro-x">
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $itemLevelbank->amount_begin ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $itemLevelbank->amount_end ?? null }}
                                    </div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $itemLevelbank->amount ?? null }}
                                    </div>

                                </td>

                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $itemLevelbank->percent_bank ?? null }}
                                    </div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $itemLevelbank->percent_admin ?? null }}
                                    </div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $itemLevelbank->percent_under ?? null }}
                                    </div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $itemLevelbank->percent_companie ?? null }}
                                    </div>

                                </td>

                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $itemLevelbank->id }}, 'update-level-bank-modal')"
                                                class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Modifier
                                            </a>
                                        @endif
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                    </tr>
                @endif
            </tbody>

        </table>
    </div>
    @include('livewire.commissions.modals.levels.updateLevelBankSend')
  
</div>
