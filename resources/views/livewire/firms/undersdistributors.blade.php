<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des sous distributeurs
    </h2>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 sm:col-span-4 2xl:col-span-6 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_amount, 2, ',', ' ') ?? null }} {{ $usersCountry }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-alert-circle font-medium-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">En attente</div>
            <div class="text-slate-500 mt-3">
                {{ $pending_success ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-check font-medium-5">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <div class="font-medium text-historique text-white mt-6">Success</div>
            <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                {{ $sum_success ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-x-circle font-medium-5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <div class="font-medium text-historique mt-6">Echec</div>
            <div class="text-slate-500 mt-3">
                {{ $sum_error ?? null }}
            </div>
        </div>

    </div>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">


        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @if (firm())
                <button wire:click="$emit('show-reload-under-companie-modal')" class="btn btn-primary shadow-md mr-2"> <svg
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>Recharger</button>
            @endif


            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>


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
        <div class="intro-y col-span-12  lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom & prenom</th>
                        <th class="text-center whitespace-nowrap">Email</th>
                        <th class="text-center whitespace-nowrap">Pays/Ville</th>
                        <th class="text-center whitespace-nowrap">Status compte </th>
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

                                    <span
                                        class="alert alert-secondary mt-2  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        Wallet : {{ $item->wallet->wallet_id ?? null }}
                                    </span>

                                </td>
                                <td>
                                    <div class="text-lg text-gray-800 font-semibold">
                                        <span
                                            class="alert alert-secondary inline-flex items-center justify-center px-4 py-2 text-indigo-500 bg-indigo-600 rounded">
                                            {{ $item->email ?? 'N/A' }}
                                        </span>
                                    </div>

                                </td>

                                <td>
                                    <h3 class="mb-3">
                                        Localisation : {{ $item->country->name ?? null }} -
                                        {{ $item->city->name ?? null }} - {{ $item->zone ?? null }}
                                    </h3>
                                    <h3>
                                        Numero : {{ $item->phone ?? null }}
                                    </h3>
                                </td>

                                <td class="table-report__action w-35">

                                    @if ($item->company_id == firm()->company->id)
                                        <div
                                            class="text-center  border-t border-slate-200/60 dark:border-darkmode-400">

                                            @if ($item->is_accepted_companie == '1')
                                                <div class="flex items-center justify-center text-success">
                                                    <a class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                        href="javascript:;" wire:loading.attr="disabled"
                                                        title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                Déja attribuer
                                                            </div>
                                                        </div>
                                                    </a>


                                                </div>
                                                <div class="flex items-center justify-center text-pending mt-3">
                                                    <a wire:click="edit({{ $item->wallet->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                        class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                        href="javascript:;" wire:loading.attr="disabled"
                                                        title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                        Rétirer l'employer
                                                    </a>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center text-danger">
                                                    <a class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                        href="javascript:;"
                                                        title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                Attribution en attente
                                                            </div>
                                                        </div>

                                                    </a>

                                                </div>
                                            @endif

                                        </div>
                                    @endif
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        <a class="mr-1 mb-2"
                                            href="{{ route('underdistributors.companies.show', ['id' => $item->id]) }}">
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
    @include('livewire.modals.firms.disabledEmployers')
    @include('livewire.modals.firms.reloadUnderDistributors')

</div>
