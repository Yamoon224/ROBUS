<div>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="hidden md:block mx-auto text-slate-500"></div>


        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 mt-6">
            <label for="countriesFilter">Filtrer par utilisateurs</label>
            <select id="typeUsers" wire:model="typeUsers"
                class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                aria-placeholder="Rechercher par pays">
                <option value="">Filtrer par utilisateurs</option>
                <option value="is_company">Compagnie</option>
                <option value="is_distributer">Distributeurs</option>
                <option value="is_under_distributer">Sous-distributeurs</option>
                <option value="is_master">Masters</option>
                <option value="is_supervisor">Superviseurs</option>
                <option value="is_com_zone">Com Zones</option>
                <option value="is_custumers">Clients</option>
            </select>
        </div>
    </div>
    <h2 class="intro-y text-lg font-medium mt-10 style-country-info">
        Liste des utilisateurs
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">


        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                        <th class="text-center whitespace-nowrap">Email</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users_all->count() > 0)

                        @foreach ($users_all as $item)
                            <tr class="intro-x">
                                <td class="w-40">
                                    <div class="mb-3">
                                        @include('components.infoUserDetails')
                                    </div>

                                </td>
                                <td class="w-60">
                                    @include('components.infoDetailsUser')

                                </td>
                                <td>
                                    <div class="text-slate-500 text-xs text-center whitespace-nowrap mt-0.5">
                                        {{ $item->email }}
                                    </div>

                                </td>
                                <td class="table-report__action w-30">
                                    <div class="flex justify-center items-center">
                                        @if ($item->deleted_at)
                                            @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                                                <a class="flex items-center mr-3"
                                                    href="{{ route('admin.activate', ['id' => $item->id]) }}">
                                                    <i data-feather="user-check" class="block mx-auto"></i>Activé
                                                </a>
                                            @endif
                                        @else
                                            @if (Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur'))
                                                <a class="flex items-center mr-3"
                                                    href="{{ route('admin.ShowProfil', ['id' => $item->id]) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-eye h-15 mr-1">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if (Auth::guard('web')->user()->hasPermissionTo('supprimer-administrateur'))
                                                <a class="flex items-center text-danger"
                                                    data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                    href="{{ route('admin.delete', ['id' => $item->id]) }}"
                                                    onclick="promptAlert(this)" data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        class="feather feather-trash-2 h-15 mr-1">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10"
                                                            y2="17"></line>
                                                        <line x1="14" y1="11" x2="14"
                                                            y2="17"></line>
                                                    </svg>
                                                </a>
                                            @endif
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

    </div>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
        <nav class="w-full sm:w-auto sm:mr-auto">
            <ul class="pagination">
                {{ $users_all->links() }}
            </ul>
        </nav>
    </div>
</div>
