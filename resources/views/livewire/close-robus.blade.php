<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des jours d'ouverture de l'application
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500">
            </div>
            <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500">

                    <input type="text" name="table_search" wire:model.debounce.250ms="search"
                        class="form-control  w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                        data-feather="search"></i>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center whitespace-nowrap text-center">Jour</th>
                        <th class="text-center whitespace-nowrap text-center">Heure debut</th>
                        <th class="text-center whitespace-nowrap text-center">Heure Fin</th>
                        <th class="text-center whitespace-nowrap text-center">Motif</th>
                        <th class="text-center whitespace-nowrap text-center">Status</th>
                        <th class="text-center whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($closeapps->count() > 0)
                        @foreach ($closeapps as $item)
                            <tr class="intro-x">
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->day_of_week ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->time_start ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->time_end ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->description ?? null }}</div>

                                </td>
                                <td class="w-40 ">
                                    @if ($item->status == '1')
                                        @if (Auth::guard('web')->user()->hasPermissionTo('open-robus'))
                                            <div class="flex items-center justify-center text-success">

                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-App-modal'); $emit('show-disabled-App-modal')"
                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                </a>

                                            </div>
                                        @endif
                                    @else
                                        @if (Auth::guard('web')->user()->hasPermissionTo('open-robus'))
                                            <div class="flex items-center justify-center text-danger">
                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-App-modal'); $emit('show-disabled-App-modal')"
                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                </a>

                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('open-robus'))
                                            <a wire:click="edit({{ $item->id }}, 'update-AppRobus-modal')"
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
    @include('livewire.commissions.modals.disabledCloseApp')
    @include('livewire.commissions.modals.updateAppClose')

</div>
