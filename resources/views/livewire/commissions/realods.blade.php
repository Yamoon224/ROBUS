<div>
  
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
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Service Rechargement UV
            </h2>
            <div class="hidden md:block ml-8 text-slate-500"></div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage marchants</th>
                        <th class="text-center whitespace-nowrap">Status</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($realods->count() > 0)
                    @foreach ($realods as $item)
                    <tr class="intro-x">
                        <td class="w-40">
                            <p href="" class="font-medium whitespace-nowrap">
                                {{ $item->created_at ?? null }}
                            </p>
                        </td>

                        <td>
                            <p href="" class="font-medium whitespace-nowrap">
                                {{ $item->name ?? null }}
                            </p>

                        </td>
                        <td class="text-center">
                            <p href="" class="font-medium whitespace-nowrap">
                                {{ $item->percent  * 100  ?? null }} %
                            </p>

                        </td>
                        <td class="text-center">
                            <p href="" class="font-medium whitespace-nowrap">
                                {{ $item->percent_merchands * 100 ?? null }} %
                            </p>

                        </td>
                        <td class="w-40">
                            @if ($item->status == '1')
                            @if (Auth::guard('web')->user()->hasPermissionTo('active-caution'))
                            <div class="flex items-center justify-center text-success">

                                <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                    href="javascript:;"
                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                </a>

                            </div>
                            @endif
                            @else
                            @if (Auth::guard('web')->user()->hasPermissionTo('desactive-caution'))
                            <div class="flex items-center justify-center text-danger">
                                <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
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
                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                <a wire:click="edit({{ $item->id }}, 'update-reload-modal')"
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

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Commissions des rechargements Tresort Money
            </h2>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage depot SD</th>
                        <th class="whitespace-nowrap text-center">Pourcentage retrait</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Compagnie</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($realodArgents->count() > 0)
                    @foreach ($realodArgents as $item)
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
                        <td class="text-center">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_merchands * 100 ?? null }} %
                            </p>

                        </td>
                        <td class="w-40">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_with_drawal * 100 ?? null }} %
                            </p>
                        </td>

                        <td class="w-40">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_companie * 100 ?? null }} %
                            </p>
                        </td>


                        <td class="w-56">
                            <div class="flex justify-center items-center">
                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                             <a wire:click="editTresort({{ $item->id }}, 'show-onexbetrans-modal')"
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

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Commissions des rechargements Djamo
            </h2>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                     <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage depot SD</th>
                        <th class="whitespace-nowrap text-center">Pourcentage  retrait</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Compagnie</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($realodDjamos->count() > 0)
                    @foreach ($realodDjamos as $item)
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
                        <td class="text-center">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_merchands * 100 ?? null }} %
                            </p>

                        </td>
                        <td class="w-40">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_with_drawal * 100 ?? null }} %
                            </p>
                        </td>

                        <td class="w-40">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_companie * 100 ?? null }} %
                            </p>
                        </td>


                        <td class="w-56">
                            <div class="flex justify-center items-center">
                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                             <a wire:click="editDjamo({{ $item->id }}, 'show-onexbetrans-modal')"
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

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Commissions des rechargements Push
            </h2>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage depot SD</th>
                        <th class="whitespace-nowrap text-center">Pourcentage retrait</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Compagnie</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($realodPushs->count() > 0)
                    @foreach ($realodPushs as $item)
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
                        <td class="text-center">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_merchands * 100 ?? null }} %
                            </p>

                        </td>
                        <td class="w-40">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_with_drawal * 100 ?? null }} %
                            </p>
                        </td>

                        <td class="w-40">
                            <p class="font-medium whitespace-nowrap">
                                {{ $item->percent_companie * 100 ?? null }} %
                            </p>
                        </td>


                        <td class="w-56">
                            <div class="flex justify-center items-center">
                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                 <a wire:click="editPush({{ $item->id }}, 'show-onexbetrans-modal')"
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
    @include('livewire.commissions.modals.addReload')
    @include('livewire.commissions.modals.disabledReload')
    @include('livewire.commissions.modals.updateReload')
    @include('livewire.commissions.modals.transactions.transactionCosts.updateOnexbet')

</div>