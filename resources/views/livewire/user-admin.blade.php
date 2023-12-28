<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste de l'administrations
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('admin.add') }}">Ajouter</a>
            @endif
            <div class="dropdown">
                @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                    <a class="dropdown-toggle btn px-2 box" href="{{ route('admin.add') }}" aria-expanded="false"
                        data-tw-toggle="dropdown">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4"
                                data-feather="plus"></i> </span>
                    </a>
                @endif
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('admin.add_company') }}">Ajouter une
                    sociéte</a>
            @endif
            <div class="dropdown">
                @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                    <a class="dropdown-toggle btn px-2 box" href="{{ route('admin.add_company') }}"
                        aria-expanded="false" data-tw-toggle="dropdown">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4"
                                data-feather="plus"></i> </span>
                    </a>
                @endif
            </div>
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

                    <input type="text" name="table_search" wire:model="search"
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
                        <tr class="intro-x">
                            <td class="w-35">
                                <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}</div>

                            </td>
                            <td class="w-50">
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
                                @if ($item->is_custumers == '1')
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                        <p class="font-medium whitespace-nowrap">Client</p>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                @elseif($item->is_distributer == true)
                                    <div class="font-medium whitespace-nowrap">
                                        <p class="font-medium whitespace-nowrap">Distributeur </p>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
                                @elseif($item->is_under_distributer == true)
                                    <div class="font-medium whitespace-nowrap">
                                        <p class="font-medium whitespace-nowrap"> Sous-distributeur</p>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->wallet->wallet_id ?? null }}
                                        </span>
                                    </div>
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

                            <td class="text-center">
                                @foreach ($item->get_role() as $role)
                                    <span class="m-1 badge badge-info"> {{ $role->name }} </span>
                                @endforeach
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
                                <div class="flex justify-center items-center">

                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur'))
                                        <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                            href="{{ route('admin.ShowProfil', ['id' => $item->id]) }}">
                                            {{-- <i data-feather="eye" class="h-15 mr-1"></i> --}}
                                            Voir
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

</div>
