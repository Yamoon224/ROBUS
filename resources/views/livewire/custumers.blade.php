<div>
    {{-- <script src="{{ asset('public/js/script.js') }}"></script>
    <script src="{{ asset('public/js/app.js') }}"></script> --}}
    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des clients
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500"></div>

            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <label for="countriesFilter mt-3">Filtrer par pays</label>
                <select id="countriesFilter" wire:model="countriesFilter"
                    class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-pauto mt-3"
                    aria-placeholder="Rechercher par pays">
                    <option value="null">Rechercher par pays</option>
                    @foreach ($countries as $coutrie)
                        <option value="{{ $coutrie->id }}">{{ $coutrie->name }}</option>
                    @endforeach
                </select>
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
        <div class="intro-y col-span-12  lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom & prenom</th>
                        <th class="text-center whitespace-nowrap">Email</th>
                        <th class="text-center whitespace-nowrap">Pays/Ville</th>
                        <th class="text-center whitespace-nowrap">Status compte </th>
                        <th class="text-center whitespace-nowrap">Status client </th>
                        <th class="text-center whitespace-nowrap">Liaison </th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users->count() > 0)
                        @foreach ($users as $item)
                            <tr class="intro-x">
                                <td>
                                    {{ $item->firstname }}
                                    {{ $item->lastname }}
                                </td>
                                <td>
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->email ?? null }}
                                    </span>

                                </td>

                                <td>
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->country->name ?? null }} : {{ $item->city->name ?? null }}
                                    </span>
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        Numero : {{ $item->phone ?? null }}
                                    </span>
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

                            <td class="text-center w-50">

                                @if ($item->get_role() != '[]')
                                    @foreach ($item->get_role() as $role)
                                        @if ($role->id == 1)
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
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
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
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Aucun client pour le moment</td>
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
                    {{ $users->links() }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
    @include('livewire.modals.connectedUnders')
    @include('livewire.modals.connectedOneUnderDistributors')
</div>
