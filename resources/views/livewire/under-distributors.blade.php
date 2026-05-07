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

            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <label for="statusFilter_active" class="text-gray-700 text-sm font-bold mb-2">Filtrer par
                        status</label>
                    <select id="statusFilter_active" wire:model="statusFilter_active"
                        class="form-select py-3 px-4 box w-100 mt-3" aria-placeholder="Rechercher par status">
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>

                    </select>
                </div>
            </div>
            <div class="hidden md:block ml-8 text-slate-500"></div>


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
                        <th class="text-center whitespace-nowrap">Statut & Liaison</th>
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
                            <td class="w-60">
                                @include('components.solde_color')


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


                            <td class="w-80">

                                @if ($item->is_under_distributer)
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="text-slate-500 text-xs flex items-center">
                                            <i class="fas fa-lock w-3 h-3 mr-2"></i>
                                            Commissions
                                        </div>

                                        <div class="dropdown relative">
                                            <a class="dropdown-toggle block" href="javascript:;"
                                                data-tw-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-5 h-5 text-slate-500 hover:text-slate-700" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="1" />
                                                    <circle cx="19" cy="12" r="1" />
                                                    <circle cx="5" cy="12" r="1" />
                                                </svg>
                                            </a>

                                            <div class="dropdown-menu dropdown-large">
                                                <div
                                                    class="dropdown-content grid grid-cols-2 gap-2 p-3 max-h-72 overflow-y-auto">

                                                    @php
                                                        $limitedOperators = $operatorsCommissions->take(50);
                                                    @endphp

                                                    @foreach ($limitedOperators as $operator)
                                                        <label
                                                            class="flex items-center px-2 py-1 hover:bg-slate-50 rounded cursor-pointer">
                                                            <input
                                                                wire:click.debounce.150ms="editUpdateCommission({{ json_encode(['user_id' => $item->id, 'operator_id' => $operator->id]) }})"
                                                                type="checkbox"
                                                                class="h-4 w-4 text-blue-600 border-slate-300 rounded"
                                                                @checked(in_array($operator->id, $old_operators))>

                                                            <span class="ml-2 text-sm text-slate-700 truncate">
                                                                {{ $operator->name ?? 'Non renseigné' }}
                                                            </span>
                                                        </label>
                                                    @endforeach

                                                    @if ($operatorsCommissions->count() > 50)
                                                        <div
                                                            class="col-span-2 text-center pt-2 border-t text-xs text-gray-500">
                                                            {{ $operatorsCommissions->count() - 50 }} autres non
                                                            affichés
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($item->is_active == '1' && $item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                                        <div class="flex items-center justify-center w-25 text-success">

                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-users-modal'); $emit('show-disabled-users-modal')"
                                                class="flex items-center  w-24 h-10 bg-success text-white  @if ($item->status == 1) @else @endif"
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
                                                class="flex items-center  w-24 h-10  bg-pending text-white mr-3 @if ($item->status == 1) @else @endif"
                                                href="javascript:;"
                                                title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                            </a>

                                        </div>
                                    @endif
                                @endif

                                <div class="mt-6">

                                    @if ($item->com_zone_id != null)
                                        <div class="comzone-card">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="badge-comzone">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    COM ZONE
                                                </span>
                                                <span
                                                    class="badge-status {{ $item->is_com_accepted ? 'badge-status-linked' : 'badge-status-pending' }}">
                                                    @if ($item->is_com_accepted)
                                                        <i class="fas fa-check-circle mr-1 text-xs"></i> Lié
                                                    @else
                                                        <i class="fas fa-clock mr-1 text-xs"></i> En attente
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="comzone-name">{{ $item->comzone->firstname ?? '' }}
                                                {{ $item->comzone->lastname ?? '' }}</div>
                                            @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                <div class="mt-3 pt-2 border-t border-slate-200 text-center">
                                                    <button
                                                        wire:click="editConnectedComZone({{ $item->id }}, 'show-deconnected-zoneCom-modal'); $emit('show-deconnected-zoneCom-modal')"
                                                        class="btn-action {{ $item->is_com_accepted ? 'btn-detach' : 'btn-validate' }}">
                                                        @if ($item->is_com_accepted)
                                                            <i class="fas fa-unlink-alt mr-1"></i> Détacher
                                                        @else
                                                            <i class="fas fa-link mr-1"></i> Valider la liaison
                                                        @endif
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="comzone-empty-card">
                                            <i class="fas fa-user-friends comzone-empty-icon"></i>
                                            <div class="comzone-empty-text">Aucun COM ZONE associé</div>
                                            @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                <button
                                                    wire:click="addConnectedComZone({{ $item->id }}, 'show-connected-zoneCom-modal'); $emit('show-connected-zoneCom-modal')"
                                                    class="btn-action btn-link">
                                                    <i class="fas fa-plus-circle mr-1"></i> Lier un COM ZONE
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>


                                <div class="mt-3">
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
                                                    <div
                                                        class="flex items-center justify-center text-pending mt-3 mb-3">
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
                                                                <div
                                                                    class="flex items-center justify-center text-success">

                                                                    <a wire:click="editConnected({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                                        class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                                        href="javascript:;"
                                                                        wire:loading.attr="disabled"
                                                                        title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                                        <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                        Deja Lié

                                                                    </a>

                                                                </div>
                                                            @endif
                                                        @else
                                                            @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                                <div
                                                                    class="flex items-center justify-center text-danger">
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
                                                        <div
                                                            class="flex items-center justify-center text-primary mt-3">

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
                                </div>

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

                                                <div class="dropdown-menu dropdown-xlarge">
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
                                            <a class=" mr-1 mb-2 "
                                                href="{{ route('unders.admins.show', ['id' => $item->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    icon-name="eye" data-lucide="eye"
                                                    class="lucide lucide-eye block mx-auto h-15 mr-1">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>

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
    @include('livewire.modals.connectedOneComZone')
    @include('livewire.modals.deconnectedOneUser')
    @include('livewire.modals.connectedCompanyOneUnderDistributors')
    @include('livewire.modals.unsignUnderDistributors')

</div>
