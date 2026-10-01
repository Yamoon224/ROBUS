<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des masteurs
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-master'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('masters.admin.add_master_com') }}">Ajouter une
                    Master</a>
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
                        <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                        <th class="text-center whitespace-nowrap">Email & Solde</th>
                        <th class="text-center whitespace-nowrap">Type</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                @foreach ($users as $item)
                    @php

                        $old_operators = $item->operator_services->pluck('operator_id')->toArray();

                    @endphp
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
                            <td class="w-35">

                                <div class="user-name">
                                    {{ $item->wallet->wallet_id ?? null }}
                                </div>
                                <p class="mb-3">
                                    <span class="style_table2 w-25 mt-3 text-white fs-5 px-2 py-1 rounded ">
                                        Operations :
                                    </span>
                                </p>
                                @foreach ($item->operator_services as $opera)
                                    <span
                                        class="alert alert-success mt-6 font-assign-list inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $opera->operator->name ?? null }}
                                    </span>
                                @endforeach
                            </td>


                            <td class="w-30">
                                @if ($item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('active-master'))
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
                                    @if (Auth::guard('web')->user()->hasPermissionTo('active-master'))
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

                            <td class="w-60">
                                <div class="flex mt-4 lg:mt-0 mr-6 ml-6">
                                    @if ($item->is_master)
                                        <div class="flex items-start ">

                                            <div class="absolute right-0 top-0 mr-5 mt-3 dropdown">
                                                <h2 class="intro-y text-lg font-medium ">
                                                    <i class="fas fa-lock w-3 h-4 mr-2"></i> Assigner
                                                </h2>
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


                                                <div class="dropdown-menu dropdown-large" id="_8vqni2v1b"
                                                    data-popper-placement="bottom-end"
                                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 20px);">
                                                    <div class="dropdown-content">
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
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-master'))
                                        <a class=" mr-1 mb-2"
                                            href="{{ route('masters.admins.show', ['id' => $item->id]) }}">
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


</div>
