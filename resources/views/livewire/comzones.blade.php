<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des com zones
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
            <a class="btn btn-primary shadow-md mr-2" href="{{ route('comzones.admins.add') }}">Ajouter une
                COM ZONE</a>
            @endif
            <a class="btn btn-secondary shadow-md mr-2" href="{{ route('comzones.admins.statistics') }}">
                Statistiques
            </a>

            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <label for="countriesFilter">Filtrer par pays</label>
                    <select id="countriesFilter" wire:model="countriesFilter" class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto" aria-placeholder="Rechercher par pays">
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
                        <th class="text-center whitespace-nowrap">Nom & Prénom</th>
                        <th class="text-center whitespace-nowrap">Email & télépone</th>
                        <th class="text-center whitespace-nowrap">Operations</th>
                        <th class="text-center whitespace-nowrap">Statut</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                @foreach ($users as $item)
                @php

                $old_operators = getOperators($item->id);

                @endphp
                <tbody>
                    <tr class="intro-x">
                        <td class="w-60">
                            <div class="mb-3">
                                @include('components.infoUserDetails')
                            </div>
                            @include('components.infoDetailsUser')

                        </td>
                        <td class="w-50">
                            @include('components.solde_color')

                        </td>
                        <td>
                            @if ($item->is_com_zone == true)
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
                                class="alert alert-success font-assign-list inline-flex items-center justify-center px-2 py-1 mb-3 text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                {{ $opera->operator->name ?? null }}
                            </span>
                            @endforeach
                            @endif

                        </td>
                        <td class="table-report__action w-60">
                            <div class="text-center mb-3">
                                <button type="button" wire:click="openLinkedUsersModal({{ $item->id }})"
                                    wire:loading.attr="disabled" class="btn btn-primary btn-sm">
                                    Voir les utilisateurs liés
                                    <span class="ml-2">
                                        {{ $item->linked_comzone_users_count ?? 0 }}
                                    </span>
                                </button>
                            </div>
                            @if ($item->status == '1')
                            @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                            <div class="flex items-center justify-center text-success mt-6">

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
                        <td class="w-60">

                            <div class="flex mt-4 lg:mt-0 ">
                                @if (Auth::guard('web')->user()->hasPermissionTo('voir-activites'))
                                @if ($item->is_com_zone)
                                <div class="flex items-start ">

                                    <div class="absolute right-0 top-0 mr-5 mt-3 dropdown">
                                        <h2 class="intro-y text-lg font-medium ">
                                            Assigner
                                        </h2>
                                        <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"
                                            aria-expanded="false" data-tw-toggle="dropdown"> <svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                icon-name="more-horizontal" data-lucide="more-horizontal"
                                                class="lucide lucide-more-horizontal w-5 h-5 text-slate-500">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>

                                        </a>


                                        <div class="dropdown-menu dropdown-large" data-popper-placement="bottom-end"
                                            style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 20px);">
                                            <div class="dropdown-content">
                                                @foreach ($operators as $operator)
                                                <label
                                                    class="flex items-center px-3 py-2 hover:bg-slate-50 rounded-md cursor-pointer transition-colors">
                                                    <input
                                                        wire:click="edit_update({{ json_encode(['user_id' => $item->id, 'operator_id' => $operator->id]) }})"
                                                        value="{{ $operator->id }}" type="checkbox"
                                                        class="h-4 w-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 transition-colors"
                                                        @if (in_array($operator->id, $old_operators)) checked @endif>
                                                    <span class="ml-2 text-sm text-slate-700 truncate flex-1">
                                                        {{ $operator->name ?? 'Non renseigné' }}
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif


                                @if (Auth::guard('web')->user()->hasPermissionTo('voir-com-zones'))
                                <a class="mr-1 mb-2 btn-sm mr-5 ml-5"
                                    href="{{ route('comzones.admins.show', ['id' => $item->id]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" icon-name="eye" data-lucide="eye"
                                        class="lucide lucide-eye block mx-auto h-15 mr-1">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>


                                @endif
                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-com-zone'))
                                <a class="flex items-center mr-3"
                                    href="{{ route('unders.show_edit', ['id' => $item->id]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-edit block mx-auto h-15 mr-1">
                                        <path d="M12 20h9"></path>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
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
    @include('livewire.modals.detailsConnectedCustumers')
    @include('livewire.modals.connectedCustumers')
    @include('livewire.modals.connectedOneCustumersDistributors')

    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('show-details-connected-custumers-modal', function() {
                $('#details-connected-custumers-modal').removeClass('hide');
                $('#details-connected-custumers-modal').addClass('show');
            });

            Livewire.on('modal-hide', function() {
                $('#details-connected-custumers-modal').removeClass('show');
                $('#details-connected-custumers-modal').addClass('hide');
            });
        });
    </script>
</div>