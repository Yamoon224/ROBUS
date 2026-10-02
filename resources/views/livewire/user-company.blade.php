<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des sociétés
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('admin.add_company') }}">Ajouter une
                    sociéte</a>
            @endif
            <div class="dropdown">
                @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                    <a class="dropdown-toggle btn px-2 box" href="{{ route('admin.add_company') }}" aria-expanded="false"
                        data-tw-toggle="dropdown">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4"
                                data-feather="plus"></i>
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">


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
                        <th class="text-center whitespace-nowrap">Nom</th>
                        <th class="text-center whitespace-nowrap">Solde & Forfait</th>
                        <th class="text-center whitespace-nowrap">Commissions</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                @foreach ($users as $item)
                    <tbody>

                        @php
                            $old_operators = $item->operator_services->pluck('operator_id')->toArray();
                        @endphp

                        <tr class="intro-x">
                            <td class="w-35">
                                <div class="mb-3">
                                    @include('components.infoUserDetails')
                                </div>
                                @include('components.infoDetailsCompany')

                            </td>
                            <td class="w-50">
                                @include('components.solde_color')


                                <div class="text-slate-300 text-xs w-60  mt-0.5 mb-2 mt-3">
                                    <span
                                        class="alert alert-success  inline-flex items-center text-white justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">

                                        Forfait : {{ $item->company->percent->name ?? null }}
                                    </span>

                                </div>
                            </td>
                            <td class="w-80">

                                <p class="mb-3">
                                    <span class="style_table2 w-25 text-white fs-5 px-2 py-1 rounded mb-3">
                                        Commissions Ouverte :
                                    </span>
                                </p>

                                @foreach ($item->operator_services as $opera)
                                    <span
                                        class="alert alert-success text-white  font-assign-list inline-flex items-center justify-center px-2 py-1 mb-2 text-xs leading-none text-indigo-100 bg-indigo-700  rounded">
                                        {{ $opera->operator->name ?? null }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="table-report__action w-35">
                                @if ($item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                                        <div class="flex items-center justify-center text-success">

                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                class="flex items-center w-24 h-10 bg-success text-white @if ($item->status == 1) @else @endif"
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
                                                class="flex items-center w-24 h-10 bg-success text-white @if ($item->status == 1) @else @endif"
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
                                                        wire:click="editConnected({{ $item->id }}, 'show-deconnected-zoneCom-modal'); $emit('show-deconnected-zoneCom-modal')"
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
                            </td>

                            <td class="w-60">
                                <div class="flex justify-center items-center">
                                    @if ($item->is_company)
                                        <div class="flex items-start ">
                                            <div class="absolute right-0 top-0 mr-5 mt-3 dropdown">
                                                <div class="text-slate-500 text-xs  mt-0.5 ">
                                                    <i class="fas fa-lock w-3 h-4 mr-2"></i>Commissions
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
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-utilisateur'))
                                        <a class="flex items-center mr-3"
                                            href="{{ route('admin.edit_company', ['id' => $item->id]) }}">
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
                                        <a class="mr-1 mb-2 "
                                            href="{{ route('company.show', ['id' => $item->id]) }}">
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
    @include('livewire.modals.connectedOneComZone')
    @include('livewire.modals.deconnectedOneUser')

</div>
