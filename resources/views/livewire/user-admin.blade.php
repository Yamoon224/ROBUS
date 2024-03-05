<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste de l'administrations
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('admin.add') }}">Ajouter</a>
            @endif

            <div class="hidden md:block mx-auto text-slate-500"></div>
            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('admin.add_company') }}">Ajouter une
                    sociéte</a>
            @endif


            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <label for="countriesFilter">Filtrer par pays</label>
                    <select id="countriesFilter" wire:model="countriesFilter"
                        class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto"
                        aria-placeholder="Rechercher par pays">
                        <option selected disabled>Rechercher par pays</option>
                        @foreach ($countries as $coutrie)
                            <option value="{{ $coutrie->id }}">{{ $coutrie->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
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
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date de création</th>
                        <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                        <th class="text-center whitespace-nowrap">Email & télépone</th>
                        <th class="text-center whitespace-nowrap">Type</th>
                        <th class="text-center whitespace-nowrap">Rôle</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                @foreach ($users as $item)
                    <tbody>
                        @php

                            $old_operators = \App\Models\OperatorService::where('sid_user_id', $item->id)
                                ->pluck('operator_id')
                                ->toArray();

                        @endphp
                        <tr class="intro-x">
                            <td class="w-35">
                                <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}</div>

                            </td>
                            <td class="w-60">

                                <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                    <div class="text-slate-300 text-xs  mt-0.5">{{ $item->firstname }}
                                        {{ $item->lastname }}</div>
                                </div>
                                <div class="text-slate-500  mt-0.5">
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->country->name ?? null }} ville : {{ $item->city->name ?? null }}
                                    </span>
                                </div>


                            </td>
                            <td>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $item->phone }}
                                </div>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-3">{{ $item->email }}
                                </div>
                            </td>
                            <td>
                                @if ($item->is_custumers)
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                        <p class="font-medium whitespace-nowrap">Client</p>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                @elseif($item->is_distributer)
                                    <div class="font-medium whitespace-nowrap">
                                        <p class="font-medium whitespace-nowrap">Distributeur </p>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                @elseif($item->is_supervisor)
                                    <div class="font-medium whitespace-nowrap">
                                        <p class="font-medium whitespace-nowrap">Superviseur </p>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                @elseif($item->is_under_distributer)
                                    <div class="font-medium whitespace-nowrap">
                                        <p class="font-medium whitespace-nowrap"> Sous-distributeur</p>
                                    </div>

                                    <div class="text-slate-500  mt-0.5">

                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                    <p>Operations : </p>
                                    @foreach ($item->operator_services as $opera)
                                        <span
                                            class="alert alert-success font-assign-list inline-flex items-center justify-center px-2 py-1 mb-3 text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $opera->operator->name ?? null }}
                                        </span>
                                    @endforeach
                                @else
                                    <div class="font-medium whitespace-nowrap">Admin</div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                @endif

                            </td>

                            <td class="text-center w-50">

                                @if ($item->get_role() != '[]')
                                    @foreach ($item->get_role() as $role)
                                        @if ($role->id == 4)
                                            @if ($item->master_id != null)
                                                <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                    <p>MASTER :</p>
                                                    <div class="text-slate-300 text-xs  mt-2">
                                                        {{ $item->master_id != null ? $item->master->firstname . ' ' . $item->master->lastname : null }}
                                                    </div>
                                                </div>
                                                <div class="text-slate-500  mt-0.5">
                                                    <span
                                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                        {{ $item->country->name ?? null }} ville :
                                                        {{ $item->city->name ?? null }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="text-center  border-t border-slate-200/60 dark:border-darkmode-400">

                                                    @if ($item->is_accepted)
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                            <div class="flex items-center justify-center text-success">

                                                                <a wire:click="editConnected({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                                    class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                                    href="javascript:;" wire:loading.attr="disabled"
                                                                    title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                    Deja Lié

                                                                </a>

                                                            </div>
                                                        @endif
                                                    @else
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                            <div class="flex items-center justify-center text-danger">
                                                                <a wire:click="editConnected({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                                    class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                                    href="javascript:;"
                                                                    title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                                    En-attente de liaison

                                                                </a>

                                                            </div>
                                                        @endif
                                                    @endif


                                                </div>
                                            @else
                                                @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                    <div class="flex items-center justify-center text-primary ">

                                                        <a wire:click="addConnected({{ $item->id }}, 'show-connected-master-modal'); $emit('show-connected-master-modal')"
                                                            class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                            href="javascript:;" wire:loading.attr="disabled"
                                                            title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                            Lié a un MASTER
                                                        </a>

                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        <span class="m-1 badge badge-info mt-2"> {{ $role->name }} </span>
                                    @endforeach
                                @else
                                    @if ($item->master_id != null)
                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                            <p>MASTER :</p>
                                            <div class="text-slate-300 text-xs  mt-2">
                                                {{ $item->master_id != null ? $item->master->firstname . ' ' . $item->master->lastname : null }}
                                            </div>
                                        </div>
                                        <div class="text-slate-500  mt-0.5">
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->country->name ?? null }} ville :
                                                {{ $item->city->name ?? null }}
                                            </span>
                                        </div>
                                        <div class="text-center  border-t border-slate-200/60 dark:border-darkmode-400">

                                            @if ($item->is_accepted)
                                                @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                    <div class="flex items-center justify-center text-success">

                                                        <a wire:click="editConnected({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                            class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                            href="javascript:;" wire:loading.attr="disabled"
                                                            title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                            <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                            Deja Lié

                                                        </a>

                                                    </div>
                                                @endif
                                            @else
                                                @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                    <div class="flex items-center justify-center text-danger">
                                                        <a wire:click="editConnected({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                            class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                            href="javascript:;"
                                                            title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                            En-attente de liaison

                                                        </a>

                                                    </div>
                                                @endif
                                            @endif


                                        </div>
                                    @else
                                        @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                            <div class="flex items-center justify-center text-primary ">

                                                <a wire:click="addConnected({{ $item->id }}, 'show-connected-master-modal'); $emit('show-connected-master-modal')"
                                                    class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                    href="javascript:;" wire:loading.attr="disabled"
                                                    title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                    Lié a un MASTER
                                                </a>

                                            </div>
                                        @endif
                                    @endif

                                @endif

                            </td>
                            <td class="table-report__action w-35">
                                @if ($item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
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
                                    @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
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
                            <td class="table-report__action w-30">
                                <div class="flex mt-4 lg:mt-0">
                                    @if ($item->is_under_distributer)
                                        <div class="flex items-start ">

                                            <div class="absolute right-0 top-0 mr-5 mt-3 dropdown">
                                                <h2 class="intro-y text-lg font-medium ">
                                                    Assigner
                                                </h2>
                                                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"
                                                    aria-expanded="false" data-tw-toggle="dropdown"> <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" icon-name="more-horizontal"
                                                        data-lucide="more-horizontal"
                                                        class="lucide lucide-more-horizontal w-5 h-5 text-slate-500">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="19" cy="12" r="1"></circle>
                                                        <circle cx="5" cy="12" r="1"></circle>
                                                    </svg>

                                                </a>


                                                <div class="dropdown-menu w-50" id="_8vqni2v1b"
                                                    data-popper-placement="bottom-end"
                                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 20px);">
                                                    <div class="dropdown-content">
                                                        @foreach ($operators as $operator)
                                                            <input
                                                                wire:click="edit_update({{ json_encode(['user_id' => $item->id, 'operator_id' => $operator->id]) }})"
                                                                value="{{ $operator->id }}" type="checkbox"
                                                                class="sid-switch"
                                                                @if (in_array($operator->id, $old_operators)) checked="checked" @endif>


                                                            <p class="font-assign ">
                                                                {{ $operator->name ?? null }}

                                                            </p>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex justify-center items-center">

                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur'))
                                            <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                                href="{{ route('admin.ShowProfil', ['id' => $item->id]) }}">
                                                Voir
                                            </a>
                                        @endif

                                    </div>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                @endforeach

            </table>
        </div>
        <!-- END: Data List -->

    </div>

    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
        <nav class="w-full sm:w-auto sm:mr-auto">
            <ul class="pagination">
                {{ $users->links() }}
            </ul>
        </nav>
    </div>
    @include('livewire.modals.disabledUsers')
    @include('livewire.modals.connectedUnders')
    @include('livewire.modals.connectedOneUnderDistributors')

</div>
