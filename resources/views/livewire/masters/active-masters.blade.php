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
                    <table id="table_orders" class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Date de
                                    creation
                                </th>
                                <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                    Prénom(s) & nom
                                </th>
                                <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                    Email & télépone
                                </th>
                                <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                    Statut compte
                                </th>
                                <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                    Solde
                                </th>

                                <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                    Statut liaison
                                </th>

                                <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->count() > 0)
                                @foreach ($users as $key => $item)
                                    <tr class="intro-x">
                                        <td>
                                            <div class="text-slate-500 text-xs  mt-0.5">
                                                {{ $item->created_at }}</div>


                                        </td>
                                        <td class="w-50">
                                            <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                <div class="text-slate-300 text-xs  mt-0.5">
                                                    {{ $item->firstname }}
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
                                        <td>
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                {{ $item->phone }}
                                            </div>
                                            <div class="text-slate-500 text-xs whitespace-nowrap mt-3">
                                                {{ $item->email }}
                                            </div>
                                        </td>
                                        <td class="table-report__action w-35">
                                            @if ($item->status)
                                                @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                                                    <div class="flex items-center justify-center text-success">

                                                        <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                            class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                            href="javascript:;"
                                                            title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                            <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                            Activé
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
                                        <td>
                                            @php
                                                $solde = $item->wallet()->sum('amount');
                                            @endphp

                                            @if ($solde >= 100000)
                                                <div
                                                    class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet">

                                                    <div class="font-medium text-primary text-xl color-wallet"
                                                        style="color: white;">
                                                        {{ number_format($solde, 2, ',', ' ') ?? null }}
                                                        {{ $item->country->currencie->code ?? null }}
                                                    </div>

                                                </div>
                                            @elseif($solde >= 50000 && $solde <= 99999)
                                                <div
                                                    class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet2">
                                                    <div class="text-center rounded-md  py-3"
                                                        style="color: white; font-size: 16px;">
                                                        <div class="font-medium text-lg mb-3">Solde
                                                        </div>

                                                        <div class="font-medium text-primary text-xl"
                                                            style="color: white;">
                                                            {{ number_format($solde, 2, ',', ' ') ?? null }}
                                                            {{ $item->country->currencie->code ?? null }}</div>
                                                    </div>

                                                </div>
                                            @elseif($solde >= 0 && $solde <= 49999)
                                                <div class="font-medium  mb-3 color-wallet3 ">
                                                    {{ number_format($solde, 2, ',', ' ') ?? null }}
                                                    {{ $item->country->currencie->code ?? null }}
                                                </div>
                                            @endif
                                            <div
                                                class="flex font-medium  items-center justify-center lg:justify-start text-slate-500 mt-5">
                                                Code wallet : {{ $item->wallet->wallet_id ?? null }}
                                            </div>
                                        </td>
                                        <td class="w-40">
                                            <div  class="text-center lg:text-right p-5 border-t border-slate-200/60 dark:border-darkmode-400">

                                                @if ($item->is_accepted)
                                                    @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                        <div class="flex items-center justify-center text-success">

                                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                                class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                                href="javascript:;"
                                                                wire:loading.attr="disabled"
                                                                title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                Lié 

                                                            </a>

                                                        </div>
                                                    @endif
                                                @else
                                                    @if (Auth::guard('web')->user()->hasPermissionTo('liaison-master'))
                                                        <div class="flex items-center justify-center text-danger">
                                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                                class="flex items-center mr-3 @if ($item->is_accepted == 1) @else @endif"
                                                                href="javascript:;"
                                                                title="@if ($item->is_accepted == 1) Désactiver @else Activer @endif">
                                                                En-cours 

                                                            </a>

                                                        </div>
                                                    @endif
                                                @endif



                                            </div>
                                        </td>
                                        <td class="table-report__action w-30">
                                            <div class="flex justify-center items-center">

                                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur'))
                                                    <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                                        href="{{ route('admin.ShowProfil', ['id' => $item->id]) }}">
                                                        <i data-feather="eye" class="h-15 mr-1"></i>
                                                        Voir
                                                    </a>
                                                @endif


                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Aucune donnée pour le moment
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
        <nav class="w-full sm:w-auto sm:mr-auto">
            <ul class="pagination">
                {{ $users->links('vendor.pagination.custum') }}
            </ul>
        </nav>
    </div>
    <!-- END: Pagination -->
    @include('livewire.modals.masters.disabledadmin')
</div>
