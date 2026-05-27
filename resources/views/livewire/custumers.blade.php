<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des clients
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="md:block w-80 mx-auto text-slate-500">
            <a class="btn btn-primary mt-3" href="{{ route('custumers.browse_invalidated') }}">Clients invalides</a>

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

            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <label for="statusFilter_active" class="text-gray-700 text-sm font-bold mb-2">Filtrer par
                        status</label>
                    <select id="statusFilter_active" wire:model="statusFilter_active"
                        class="form-select py-3 px-4 box w-100 mt-3" aria-placeholder="Rechercher par status">
                        <option value="">Tout</option>
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>

                    </select>
                </div>
            </div>
            <div class="hidden md:block ml-8 text-slate-500"></div>


            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500">
                    <label for="citiesFilter" class="text-gray-700 text-sm font-bold mb-2">Rechercher</label>

                    <input type="text" name="table_search" wire:model="search"
                        class="form-control  w-56 box pr-10 mt-3" style="height: 3rem;" placeholder="Rechercher">
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
                        <th class="text-center whitespace-nowrap">Email & Solde</th>
                        <th class="text-center whitespace-nowrap">Status compte & doc </th>
                        <th class="text-center whitespace-nowrap">Liaison</th>
                        <th class="text-center whitespace-nowrap">Status Wallet </th>
                        <th class="text-center whitespace-nowrap">Statut utilisateur</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                @foreach ($users as $item)
                    <tbody>

                        <tr class="intro-x">
                            <td class="w-60">
                                <div class="mb-3">
                                    @include('components.infoUserDetails')
                                </div>
                                @include('components.infoDetailsUser')

                            </td>
                            <td class="w-60">

                                @include('components.solde_color')

                            </td>

                            <td class="w-48 px-4 py-3">
                                <div class="flex flex-col space-y-3">
                                    {{-- Badge Statut du compte --}}
                                    @if ($item->is_active == '1')
                                        <div
                                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-success border border-green-200 text-green-700 text-sm font-medium shadow-sm mt-6">
                                            <i class="fas fa-check-circle text-green-500 mr-2 text-success"></i>
                                            <span class="text-white">Compte activé</span>
                                        </div>
                                    @else
                                        <div
                                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-pending border border-red-200 text-red-700 text-sm font-medium shadow-sm mt-6">
                                            <i class="fas fa-ban text-red-500 mr-2 text-base"></i>
                                            <span class="text-white">Compte désactivé</span>
                                        </div>
                                    @endif

                                    {{-- Badge Statut KYC --}}
                                    @if ($item->kyc && $item->kyc->status == '1')
                                        <div
                                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-success border border-emerald-200 text-emerald-700 text-sm font-medium shadow-sm mt-6">
                                            <i class="fas fa-shield-check text-success mr-2 text-base"></i>
                                            <span class="text-white">Documents validés</span>
                                        </div>
                                    @else
                                        <div
                                            class="inline-flex items-center px-3 py-1.5 rounded-full bg-pending border border-amber-200 text-amber-700 text-sm font-medium shadow-sm mt-6">
                                            <i class="fas fa-clock text-amber-500 mr-2 text-white"></i>
                                            <span class="text-white">Documents en attente</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="w-60 align-top">
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
                                                wire:click="addConnected({{ $item->id }}, 'show-connected-zoneCom-modal'); $emit('show-connected-zoneCom-modal')"
                                                class="btn-action btn-link">
                                                <i class="fas fa-plus-circle mr-1"></i> Lier un COM ZONE
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="w-40">

                                @if ($item->wallet != null)
                                    @if ($item->wallet->status == '1' ?? null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="inline-flex items-center px-3 py-1.5 rounded-full bg-success text-white border border-emerald-200 text-emerald-700 text-sm font-medium shadow-sm mt-6"
                                                href="{{ route('custumers.activate_wallet', ['id' => $item->id]) }}">
                                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                            </a>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center text-danger">

                                            <a class="flex items-center mr-3"
                                                href="{{ route('custumers.activate_wallet', ['id' => $item->id]) }}">
                                                <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                            </a>

                                        </div>
                                    @endif
                                @else
                                @endif

                            </td>

                            <td class="w-40">
                                {{-- Desactiver --}}
                                @if ($item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('active-clients'))
                                        <div class="flex items-center justify-center text-success">
                                            <a class="flex items-center mr-3"
                                                href="{{ route('custumers.activate', ['id' => $item->id]) }}"
                                                data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                onclick="promptAlert(this)">
                                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    @if (Auth::guard('web')->user()->hasPermissionTo('desactive-client'))
                                        <div class="flex items-center justify-center text-danger">

                                            <a class="flex items-center mr-3"
                                                href="{{ route('custumers.activate', ['id' => $item->id]) }}"
                                                data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                onclick="promptAlert(this)">
                                                <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                            </a>

                                        </div>
                                    @endif
                                @endif
                            </td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-utilisateur'))
                                        <a class="flex items-center mr-3"
                                            href="{{ route('custumers.show_edit', ['id' => $item->id]) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-edit block mx-auto h-15 mr-1">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                    @if (Auth::guard('web')->user()->hasPermissionTo('voir-clients'))
                                        <a class="flex items-center mr-3"
                                            href="{{ route('custumers.show', ['id' => $item->id]) }}">
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
    @include('livewire.modals.connectedUnders')
    @include('livewire.modals.connectedOneUnderDistributors')
    @include('livewire.modals.connectedOneComZone')
    @include('livewire.modals.deconnectedOneUser')
    @include('livewire.modals.connectedCompanyUnders')

    @include('livewire.modals.connectedCompanyOneUnderDistributors')
    @include('livewire.modals.unsignUnderDistributors')
    @include('livewire.modals.deletedUsers')

</div>
