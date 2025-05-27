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
                    <label for="countriesFilter" class="block text-gray-700 text-sm font-bold mb-2">Filtrer par pays</label>
                    <select id="countriesFilter" wire:model="countriesFilter"
                    class="form-select py-3 px-4 box w-100"
                    aria-placeholder="Rechercher par pays">
                    <option value="" selected>Rechercher par pays</option> @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <label for="citiesFilter" class="text-gray-700 text-sm font-bold mb-2">Filtrer par ville</label>
                    <select id="citiesFilter" wire:model="citiesFilter"
                    class="form-select py-3 px-4 box w-100"
                    aria-placeholder="Rechercher par ville"
                    @if(empty($cities)) disabled @endif> <option value="" selected>Rechercher par ville</option> @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 md:ml-0">
                    <label for="cityNameSearch" class="block text-gray-700 text-sm font-bold mb-2">Rechercher par nom des zones</label>
                    <input type="text" id="cityNameSearch" wire:model.live.debounce.300ms="cityNameSearch"
                           placeholder="Saisir le nom d'une ville..."
                           class="form-input py-3 px-4 box w-full rounded-md border border-gray-300 focus:ring focus:ring-blue-200 focus:border-blue-500">
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
                        <th class="text-center whitespace-nowrap">Email & téléphone</th>
                        <th class="text-center whitespace-nowrap">Status compte</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>

                @foreach ($users as $item)
                    <tbody>

                        <tr class="intro-x">
                            <td class="w-35">
                                <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}</div>

                            </td>
                            <td class="w-60">
                                @if (Auth::guard('web')->user()->hasPermissionTo('voir-couleurs'))
                                @php
                                    $solde = $item->wallet->amount ?? 0.0;
                                @endphp
                                <div class="barre mb-3"
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

                                @if ($item->company_id != null && $item->company_id != 1)
                                    <div class="text-center  border-t border-slate-200/60 dark:border-darkmode-400">

                                        @if ($item->is_accepted_companie)
                                            @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                <div class="flex items-center justify-center text-success">
                                                    <a wire:click="editConnectedCompanie({{ $item->id }}, 'show-connected-company-modal'); $emit('show-connected-company-modal')"
                                                        class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                        href="javascript:;" wire:loading.attr="disabled"
                                                        title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                        Déja attribuer a
                                                        {{ $item->company_id != null ? $item->company->name : null }}
                                                    </a>
                                                </div>
                                                <div class="flex items-center justify-center text-pending mt-3">
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
                                        <div class="flex items-center justify-center text-primary ">

                                            <a wire:click="addConnectedCompanie({{ $item->id }}, 'show-connected-company-modal'); $emit('show-connected-company-modal')"
                                                class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                href="javascript:;" wire:loading.attr="disabled"
                                                title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                Lié a une compagnie
                                            </a>

                                        </div>
                                    @endif
                                @endif

                            </td>

                            <td class="w-40">

                                <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                    <div class="text-slate-300 text-xs  mt-0.5">{{ $item->email ?? null }}
                                    </div>
                                </div>

                                <div class="text-slate-300 text-xs mt-3 mb-2">
                                    <div class="text-slate-300 text-xs  mt-0.5">
                                        {{ $item->phone ?? null }}</div>
                                </div>


                            </td>

                            <td class="w-40">

                                @if ($item->wallet != null)
                                    @if ($item->wallet->status == '1' ?? null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3"
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
                                @if ($item->is_active == '1')
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
                                    <a class="flex items-center mr-3" href="{{ route('custumers.show_edit', ['id' => $item->id]) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="lucide lucide-edit block mx-auto h-15 mr-1">
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
    @include('livewire.modals.connectedCompanyUnders')
    @include('livewire.modals.connectedCompanyOneUnderDistributors')
    @include('livewire.modals.unsignUnderDistributors')

</div>
