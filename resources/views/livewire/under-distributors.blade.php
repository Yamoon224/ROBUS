<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des sous distributeurs
    </h2>


    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('unders.admins.add') }}">Ajouter</a>
            @endif

            <div class="hidden md:block mx-auto text-slate-500"></div>



            <div class="w-80  mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-80  mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <label for="countriesFilter" class="block text-gray-700 text-sm font-bold mb-2">Filtrer par
                        pays</label>
                    <select id="countriesFilter" wire:model="countriesFilter" class="form-select py-3 px-4 box w-100"
                        aria-placeholder="Rechercher par pays">
                        <option value="" selected>Rechercher par pays</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <label for="citiesFilter" class="text-gray-700 text-sm font-bold mb-2">Filtrer par ville</label>
                    <select id="citiesFilter" wire:model="citiesFilter" class="form-select py-3 px-4 box w-100"
                        aria-placeholder="Rechercher par ville" @if (empty($cities)) disabled @endif>
                        <option value="" selected>Rechercher par ville</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 md:ml-0">
                    <label for="cityNameSearch" class="block text-gray-700 text-sm font-bold mb-2">Rechercher par nom
                        des zones</label>
                    <input type="text" id="cityNameSearch" wire:model.live.debounce.300ms="cityNameSearch"
                        placeholder="Saisir le nom d'une ville..."
                        class="form-input py-3 px-4 box w-full rounded-md border border-gray-300 focus:ring focus:ring-blue-200 focus:border-blue-500">
                </div>
            </div>

            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="mt-3 input-group input-group-md" style="margin-top: 13px;width: 250px;">
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
                        <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                        <th class="text-center whitespace-nowrap">Email & télépone</th>
                        <th class="text-center whitespace-nowrap">Type</th>
                        <th class="text-center whitespace-nowrap">Liaison</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                @foreach ($users as $item)
                    <tbody>
                        @php
                            $old_operators = getOperators($item->id);
                        @endphp
                        <tr class="intro-x">
                            <td class="w-60">
                                <div class="mb-3">
                                    @include('components.infoUserDetails')
                                </div>
                                @include('components.infoDetailsUser')

                            </td>
                            <td class="w-40">

                                <div class="user-name">
                                    {{ $item->phone ?? '' }}
                                </div>
                                <div class="user-name mt-3">
                                    {{ $item->email ?? '' }}
                                </div>

                                <p class="mb-3">
                                    <span class="style_table2 w-25 text-white fs-5 px-2 py-1 rounded">
                                        Commissions :
                                    </span>
                                </p>


                                @foreach ($item->operator_services as $operator)
                                    @if ($operator->operator->is_commission)
                                        <span
                                            class="alert mt-3 alert-success text-white font-assign-list inline-flex items-center justify-center px-2 py-1  text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $operator->operator->name ?? null }}
                                        </span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if (Auth::guard('web')->user()->hasPermissionTo('voir-couleurs'))
                                    @php
                                        $solde = $item->wallet->amount ?? 0.0;
                                    @endphp
                                    <div class="barre"
                                        style="height: 20px; 
                                            background-color: 
                                                @if ($solde >= 100000) yellow;
                                                @elseif ($solde >= 50000 && $solde < 100000)
                                                    blue;
                                                @elseif ($solde > 0)
                                                    red;
                                                @else
                                                    gray; @endif;">
                                    </div>
                                @endif

                                @if ($item->is_under_distributer)
                                    <div class="user-name">
                                        {{ $item->wallet->wallet_id ?? null }}
                                    </div>

                                    <p class="mb-3">
                                        <span class="style_table2 w-25 text-white fs-5 px-2 py-1 rounded">
                                            Operations :
                                        </span>
                                    </p>


                                    @foreach ($item->operator_services as $operator)
                                        @if ($operator->operator->is_under)
                                            <span
                                                class="alert alert-success mt-1 text-white font-assign-list inline-flex items-center justify-center px-2 py-1  text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $operator->operator->name ?? null }}
                                            </span>
                                        @endif
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

                            <td class="w-60">

                                @if ($item->company_id != null && $item->company_id != 1)
                                    <div
                                        class="text-center  border-t border-slate-200/60 dark:border-darkmode-400 mt-3">

                                        @if ($item->is_accepted_companie)
                                            @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                <div class="flex items-center justify-center text-success">
                                                    <a wire:click="editConnectedCompanie({{ $item->id }}, 'show-connected-company-modal'); $emit('show-connected-company-modal')"
                                                        class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                        href="javascript:;" wire:loading.attr="disabled"
                                                        title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                        Déja attribuer a
                                                    </a>
                                                </div>
                                                <div class="user-badge user-badge--compagnie">
                                                    {{ $item->company_id != null ? $item->company->name : null }}
                                                </div>
                                                <div class="flex items-center justify-center text-pending mt-3 mb-3">
                                                    <a wire:click="editConnectedCompanie({{ $item->id }}, 'show-desactive-company-modal'); $emit('show-desactive-company-modal')"
                                                        class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                        href="javascript:;" wire:loading.attr="disabled"
                                                        title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                        Rétirer a
                                                        {{ $item->company_id != null ? $item->company->name : null }}
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                <div class="flex items-center justify-center text-danger">
                                                    <a wire:click="editConnectedCompanie({{ $item->id }}, 'show-connected-company-modal'); $emit('show-connected-company-modal')"
                                                        class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                        href="javascript:;"
                                                        title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                        Attribution en attente
                                                    </a>

                                                </div>
                                            @endif
                                        @endif

                                    </div>
                                @else
                                    @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                        <div class="flex items-center justify-center text-primary mt-3">

                                            <a wire:click="addConnectedCompanie({{ $item->id }}, 'show-connected-company-modal'); $emit('show-connected-company-modal')"
                                                class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                href="javascript:;" wire:loading.attr="disabled"
                                                title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                Lié a une compagnie
                                            </a>

                                        </div>
                                    @endif
                                @endif
                                @if ($item->get_role() != '[]')
                                    @foreach ($item->get_role() as $role)
                                        @if ($role->id == 4)
                                            @if ($item->master_id != null)
                                                <div class="text-slate-300 text-xs  mt-0.5 mb-2 mt-3">
                                                    <div class="user-badge user-badge--distributor">
                                                        Master
                                                    </div>

                                                    <div class="user-name">
                                                        {{ $item->master_id != null ? $item->master->firstname . ' ' . $item->master->lastname : null }}
                                                    </div>
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
                                                    <div class="flex items-center justify-center text-primary mt-3">

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
                                    @endforeach
                                @else
                                    @if ($item->master_id != null)
                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                            <div class="user-badge user-badge--distributor">
                                                Master
                                            </div>
                                            <div class="text-slate-300 text-xs mt-2">
                                                {{ $item->master_id != null ? $item->master->firstname . ' ' . $item->master->lastname : null }}
                                            </div>
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

                            </td>
                            <td class="w-40">
                                @if ($item->is_under_distributer)
                                    <div class="flex items-start ">
                                        <div class="absolute  top-0 mr-5 mt-3 dropdown">
                                            <div class="text-slate-500 text-xs  mt-0.5 ">
                                                <i class="fas fa-lock w-3 h-4 mr-2"></i>Commisions
                                            </div>
                                            <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"
                                                aria-expanded="false" data-tw-toggle="dropdown"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    icon-name="more-horizontal" data-lucide="more-horizontal"
                                                    class="lucide lucide-more-horizontal w-5 h-5 text-slate-500">
                                                    <circle cx="12" cy="12" r="1"></circle>
                                                    <circle cx="19" cy="12" r="1"></circle>
                                                    <circle cx="5" cy="12" r="1"></circle>
                                                </svg>

                                            </a>

                                            <div class="dropdown-menu w-90" id="_8vqni2v1b"
                                                data-popper-placement="bottom-end"
                                                style="position: absolute; inset: 0px 0px auto auto; margin: 0px; width: 30% !import; transform: translate(0px, 20px);">
                                                <div class="dropdown-content grid grid-cols-3 gap-2 p-3">

                                                    @foreach ($operatorsCommissions as $operator)
                                                        <label
                                                            class="flex items-center px-4 py-3 hover:bg-slate-50 cursor-pointer transition-colors">
                                                            <input
                                                                wire:click="editUpdateCommission({{ json_encode(['user_id' => $item->id, 'operator_id' => $operator->id]) }})"
                                                                value="{{ $operator->id }}" type="checkbox"
                                                                class="h-4 w-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors"
                                                                @if (in_array($operator->id, $old_operators)) checked @endif>
                                                            <span class="ml-3 text-sm text-slate-700 truncate flex-1">
                                                                {{ $operator->name ?? 'Non renseigné' }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($item->is_active == '1' && $item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                                        <div class="flex items-center justify-center text-success">

                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-users-modal'); $emit('show-disabled-users-modal')"
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

                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-users-modal'); $emit('show-disabled-users-modal')"
                                                class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                href="javascript:;"
                                                title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                            </a>

                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="w-60">
                                <div class="flex mt-4 lg:mt-0">
                                    @if ($item->is_under_distributer)
                                        <div class="flex items-start mb-3">

                                            <div class="absolute right-0 top-0 mr-5 mt-3 dropdown">

                                                <div class="text-slate-500 text-xs  mt-0.5 ">
                                                    <i class="fas fa-lock w-3 h-4 mr-2"></i>Assigner
                                                </div>
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

                                                <div class="dropdown-menu w-90" data-popper-placement="bottom-end"
                                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 20px); width: 400px; z-index:50;">
                                                    <div class="dropdown-content grid grid-cols-3 gap-2 p-3">
                                                        @foreach ($operators as $operator)
                                                            <label
                                                                class="flex items-center px-4 py-3 hover:bg-slate-50 cursor-pointer transition-colors">
                                                                <input
                                                                    wire:click="edit_update({{ json_encode(['user_id' => $item->id, 'operator_id' => $operator->id]) }})"
                                                                    value="{{ $operator->id }}" type="checkbox"
                                                                    class="h-4 w-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors"
                                                                    @if (in_array($operator->id, $old_operators)) checked @endif>
                                                                <span
                                                                    class="ml-3 text-sm text-slate-700 truncate flex-1">
                                                                    {{ $operator->name ?? 'Non renseigné' }}
                                                                </span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex justify-center items-center">

                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-utilisateur'))
                                            <a class="flex items-center mr-3"
                                                href="{{ route('unders.show_edit', ['id' => $item->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-edit block mx-auto h-15 mr-1">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                                </svg>
                                            </a>
                                        @endif

                                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-sous-distributeurs'))
                                            <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                                href="{{ route('unders.admins.show', ['id' => $item->id]) }}">
                                                Voir Détails
                                            </a>
                                        @endif

                                        @include('components.tableDeleted')

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
    @include('livewire.modals.deletedUsers')
    @include('livewire.modals.disabledUnderUsers')
    @include('livewire.modals.connectedUnders')
    @include('livewire.modals.connectedOneUnderDistributors')
    @include('livewire.modals.connectedCompanyUnders')
    @include('livewire.modals.connectedCompanyOneUnderDistributors')
    @include('livewire.modals.unsignUnderDistributors')

</div>
