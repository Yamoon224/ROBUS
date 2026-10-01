<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des MASTERS
    </h2>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">


        @if (comzone())
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            <a class="btn btn-primary shadow-md mr-2" href="{{ route('masters.comzones.add') }}"> Creer un
                Master
                </a>
        @endif
        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="hidden md:block ml-8 text-slate-500">
        </div>
        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" wire:model.debounce.250ms="search"
                    class="form-control  w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>
    </div>
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-5">
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
                    @php

                        $old_operators = \App\Models\OperatorService::where('sid_user_id', $item->id)
                            ->pluck('operator_id')
                            ->toArray();

                    @endphp
                    <tr class="intro-x">
                        <td class="w-35">
                            <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}</div>

                        </td>
                        <td class="w-60">

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
                            @if ($item->is_custumers)
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                    <p class="font-medium whitespace-nowrap">Client</p>
                                </div>
                                <div class="text-slate-500  mt-0.5">
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->wallet->wallet_id ?? null }}
                                    </span>
                                </div>
                            @elseif($item->is_distributer)
                                <div class="font-medium whitespace-nowrap">
                                    <p class="font-medium whitespace-nowrap">Distributeur </p>
                                </div>
                                <div class="text-slate-500  mt-0.5">
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->wallet->wallet_id ?? null }}
                                    </span>
                                </div>
                            @elseif($item->is_under_distributer)
                                <div class="font-medium whitespace-nowrap">
                                    <p class="font-medium whitespace-nowrap"> Sous-distributeur</p>
                                </div>

                                <div class="text-slate-500  mt-0.5">

                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->wallet->wallet_id ?? null }}
                                    </span>
                                </div>
                                <p>Operations : </p>
                                @foreach ($item->operator_services as $opera)
                                    <span
                                        class="alert alert-success font-assign-list inline-flex items-center justify-center px-2 py-1 mb-3 text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $opera->operator->name ?? null }}
                                    </span>
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

                        <td class="text-center w-50">

                            @if ($item->get_role() != '[]')
                                @foreach ($item->get_role() as $role)
                                    @if ($role->id == 4)
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
                                        @else
                                            <div class="flex items-center justify-center text-primary ">

                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    Pas encore lié
                                                </span>


                                            </div>
                                        @endif
                                    @endif
                                    <span class="m-1 badge badge-info mt-2"> {{ $role->name }} </span>
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
                                            <div class="flex items-center justify-center text-success">

                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                    Deja Lié

                                                </span>

                                            </div>
                                        @else
                                            <div class="flex items-center justify-center text-danger">
                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    En-attente de liaison

                                                </span>


                                            </div>
                                        @endif


                                    </div>
                                @else
                                    <div class="flex items-center justify-center text-primary ">

                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            Lié a un MASTER
                                        </span>


                                    </div>
                                @endif
                            @endif

                        </td>
                        <td class="table-report__action w-35">
                            @if ($item->status == '1')
                                <div class="flex items-center justify-center text-success">

                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                    </span>


                                </div>
                            @else
                                <div class="flex items-center justify-center text-danger">
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                    </span>


                                </div>
                            @endif
                        </td>
                        <td class="table-report__action w-30">
                            <div class="flex mt-4 lg:mt-0">

                                <div class="flex justify-center items-center">

                                    <a class="mr-1 mb-2"
                                        href="{{ route('masters.comzones.show', ['id' => $item->id]) }}">
                                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                icon-name="eye" data-lucide="eye"
                                                class="lucide lucide-eye block mx-auto h-15 mr-1">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                    </a>


                                </div>
                            </div>

                        </td>
                    </tr>
                </tbody>
            @endforeach

        </table>
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $users->links('vendor.livewire.bootstrap') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
    {{-- 
    @include('livewire.modals.masters.addCustumer')
    @include('livewire.modals.masters.disabledcustumer') --}}

</div>
