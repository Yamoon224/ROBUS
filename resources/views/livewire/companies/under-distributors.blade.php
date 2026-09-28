<div>
    
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>


        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">
                <input type="text" name="table_search" wire:model="search" class="form-control  w-56 box pr-10"
                    style="height: 3rem;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>


    </div>
    <div class="grid grid-cols-12 gap-6 mt-6">
        <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
            <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Liste des sous distributeurs
                </h2>
            </div>
            <div id="new-products" class="tiny-slider py-5">
                <div class="px-5">
                    <div class="overflow-x-auto">
                        <table class="table table-bordered table-hover -mt-2 -intro-x ">
                            <thead class="table-dark">
                                <tr>
                                    <th class="whitespace-nowrap">Date de création</th>
                                    <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                                    <th class="text-center whitespace-nowrap">Email & télépone</th>
                                    <th class="text-center whitespace-nowrap">Type</th>
                                    <th class="text-center whitespace-nowrap">Attribution</th>
                                </tr>
                            </thead>
                            @foreach ($underCompaniesDistributors as $key => $item)
                                <tbody>

                                    <tr class="intro-x">
                                        <td class="w-35">
                                            <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}
                                            </div>

                                        </td>
                                        <td class="w-50">
                                            <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                <div class="text-slate-300 text-xs  mt-0.5">{{ $item->firstname }}
                                                    {{ $item->lastname }}</div>
                                            </div>
                                            <div class="text-slate-500  mt-0.5">
                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    {{ $item->country->name ?? null }} ville :
                                                    {{ $item->city->name ?? null }}
                                                </span>
                                            </div>

                                        </td>
                                        <td class="max-w-xs">
                                            <div class="text-slate-500 text-xs mt-0.5">
                                                {{ $item->phone }}
                                            </div>
                                            <div class="text-slate-500 text-xs break-all mt-3">
                                                {{ $item->email }}
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

                                        <td class="table-report__action w-35">

                                            @if ($item->company_id != null && $item->company_id != 1)
                                                <div  class="text-center  border-t border-slate-200/60 dark:border-darkmode-400">

                                                    @if ($item->is_accepted_companie == '1')
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                            <div class="flex items-center justify-center text-success">
                                                                <a wire:click="editConnectedCompanie({{ $item->id }}, 'show-connected-company-under-modal'); $emit('show-connected-company-under-modal')"
                                                                    class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                                    href="javascript:;" wire:loading.attr="disabled"
                                                                    title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                                    <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                                        <div class="text-slate-300 text-xs  mt-0.5">
                                                                            Déja attribuer
                                                                        </div>
                                                                    </div>
                                                                </a>

                                                            </div>
                                                        @endif
                                                    @else
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                            <div class="flex items-center justify-center text-danger">
                                                                <a wire:click="editConnectedCompanie({{ $item->id }}, 'show-connected-company-under-modal'); $emit('show-connected-company-under-modal')"
                                                                    class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                                    href="javascript:;"
                                                                    title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">
                                                                    <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                                        <div class="text-slate-300 text-xs  mt-0.5">
                                                                            Attribution en attente
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </a>

                                                            </div>
                                                        @endif
                                                    @endif

                                                </div>
                                            @else
                                                @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                    <div class="flex items-center justify-center text-primary ">
                                                        <a wire:click="addConnectedCompanie({{ $item->id }}, 'show-connected-company-under-modal'); $emit('show-connected-company-under-modal')"
                                                            class="flex items-center mr-3 @if ($item->is_accepted_companie == 1) @else @endif"
                                                            href="javascript:;" wire:loading.attr="disabled"
                                                            title="@if ($item->is_accepted_companie == 1) Désactiver @else Activer @endif">

                                                            <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                                <div class="text-slate-300 text-xs  mt-0.5">
                                                                    Lié a une compagnie

                                                                </div>
                                                            </div>
                                                        </a>

                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $underCompaniesDistributors->links('vendor.pagination.custum') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->

    </div>

    @include('livewire.modals.unsignUnderDistributors')

</div>
