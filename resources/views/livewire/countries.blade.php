<div>
    
    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des pays
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
                        <th class="whitespace-nowrap text-center">Code</th>
                        <th class="text-center whitespace-nowrap text-center">Name</th>
                        <th class="text-center whitespace-nowrap text-center">Compagnie</th>
                        <th class="text-center whitespace-nowrap text-center">Devise</th>
                        <th class="text-center whitespace-nowrap text-center">Plafond</th>
                        <th class="text-center whitespace-nowrap text-center">Montant Maxi a envoyé</th>
                         {{-- <th class="text-center whitespace-nowrap text-center">Plafond sans piece</th> --}}
                        <th class="text-center whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($countries->count() > 0)
                        @foreach ($countries as $item)
                            <tr class="intro-x">
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->code ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->name ?? null }}</div>

                                </td>
                                <td class="w-40 ">
                                    @if ($item->robus == '1')
                                        @if (Auth::guard('web')->user()->hasPermissionTo('active-caution'))
                                            <div class="flex items-center justify-center text-success">

                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                    class="flex items-center mr-3 @if ($item->robus == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->robus == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                </a>

                                            </div>
                                        @endif
                                    @else
                                        @if (Auth::guard('web')->user()->hasPermissionTo('desactive-caution'))
                                            <div class="flex items-center justify-center text-danger">
                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                    class="flex items-center mr-3 @if ($item->robus == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->robus == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                </a>

                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $item->currencie->name ?? 'Pas encore' }}
                                    </div>
                                </td>
                                 <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $item->silver_ceiling ?? '00' }}
                                    </div>
                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $item->amount_max_send ?? '00' }}
                                    </div>
                                </td>
                                {{-- <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">
                                        {{ $item->silver_ceiling_no_identity ?? '00' }}
                                    </div>
                                </td> --}}
                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-countrie-modal')"
                                                class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Ajouter devise & Indecatif
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
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $countries->links() }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
    @include('livewire.commissions.modals.disabledCountries')
    @include('livewire.commissions.modals.updateCountries')

</div>
