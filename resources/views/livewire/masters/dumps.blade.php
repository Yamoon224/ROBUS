<div>

    <div class="intro-y box px-5 pt-1 mt-1 ">

        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" id="info-paie-show-1">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table2">
                       
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($walletSolde, 2, ',', ' ') ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde wallet {{ $usersCountry }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_factures">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $WalletReloads ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Déchargements {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="intro-y block sm:flex items-center h-10 mt-6">
            <h2 class="text-lg font-medium truncate mr-5"> Les Activites des déchargements</h2>
            <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                    href="{{ route('reloads.historical.reporting_reload_historical') }}" style="color: white;">
                    <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
                </a>
            </div>
        </div>

        <div class="mt-5">

            <button class="btn btn-primary w-22 mr-1 mb-1"
                wire:click="$emit('modal-unlock-show' , 'create-unlock-modal')">Déchargement</button>
        </div>

        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="reload-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center active" data-tw-target="#reload"
                    aria-controls="reload" aria-selected="true" role="tab"><svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>Déchargements</a>
            </li>
        </ul>

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>

            
        </div>
        <div class="tab-content mt-6">

            <div id="reload" class="tab-pane active" role="tabpanel" aria-labelledby="reload-tab">

                <div class="grid grid-cols-12 gap-6  mt-6">
                    <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                        <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Liste des déchargements
                            </h2>
                        </div>
                        <div id="new-products" class="tiny-slider py-5">
                            <div class="px-5">
                                <div class="overflow-x-auto">
                                    <table id="table_orders" class="table table-bordered">
                                        <thead class="table-dark">
                                            <tr>

                                                <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                    Opérateur
                                                </th>
                                                <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                    Expéditeur
                                                </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Montant demandé
                                                </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Envoyer le
                                                </th>

                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Numero
                                                </th>

                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Statut </th>
                                                <th
                                                    class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($unlocks->count() > 0)
                                                @foreach ($unlocks as $item)
                                                    <tr class="intro-x">
                                                        <td>
                                                            <p class="font-medium whitespace-nowrap">
                                                                {{ $item->provider->name ?? null }}
                                                            </p>

                                                        </td>
                                                        <td class="w-50">
                                                            <div class=" mt-0.5 mb-2">
                                                                <div class=" mt-0.5">
                                                                    {{ $item->wallet->sidUser->firstname ?? null }}
                                                                    {{ $item->wallet->sidUser->lastname ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="  mt-0.5">
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ $item->wallet->sidUser->country->name ?? null }}
                                                                    ville :
                                                                    {{ $item->wallet->sidUser->city->name ?? null }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="w-40">
                                                            @if ($item->wallet->wallet_id != $item->num_wallet)
                                                                <span
                                                                    class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                             text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Transfers d'argent
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                                 text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Depot d'argent
                                                                </span>
                                                            @endif
                                                            <p class="font-medium whitespace-nowrap">
                                                                {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                                {{ $item->wallet->currencie->code ?? null }}

                                                            </p>
                                                        </td>
                                                        <td class="w-40">
                                                            <p class="font-medium whitespace-nowrap">
                                                                {{ $item->created_at ?? null }}</p>
                                                        </td>

                                                        <td class="w-40">

                                                            @php
                                                                $wallet_info = \App\Models\Wallets\Wallet::where('wallet_id' , $item->num_wallet)->first();
                                                            @endphp
                                                           
                                                            <p class="font-medium whitespace-nowrap">
                                                                    {{ $wallet_info->sidUser->firstname ?? null }}  {{ $wallet_info->sidUser->lastname ?? null }}
                                                            </p>
                                                            <p class="font-medium whitespace-nowrap mt-3">
                                                                {{ $item->num_wallet ?? null }}</p>
                                                        </td>

                                                        <td class="w-40">
                                                            @if ($item->status == 'PENDING')
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        En-attente
                                                                    </span>

                                                                </div>
                                                            @elseif ($item->status == 'ERROR')
                                                                <div
                                                                    class="flex items-center justify-center text-danger">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                            Refusé
                                                                        </span>

                                                                    </div>

                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Traiter
                                                                    </span>

                                                                </div>
                                                            @else
                                                                <div
                                                                    class="flex items-center justify-center text-success">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Accepté
                                                                        </span>


                                                                    </div>

                                                                </div>
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Traiter
                                                                    </span>

                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="w-56">
                                                            <div class="flex justify-center items-center">

                                                                @if ($item->status == 'PENDING')
                                                                    @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                        <a wire:click="editReload({{ $item->id }}, 'update-reloadWallet-modal')"
                                                                            class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                            href="javascript:;" title="Modifier">
                                                                            Modifier
                                                                        </a>
                                                                    @endif
                                                                @elseif ($item->status == 'SUCCESS')
                                                                    <a target="_bank"
                                                                        href="{{ route('reaload.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                @else
                                                                    <div
                                                                        class="flex items-center justify-center text-dark">
                                                                        <div
                                                                            class="flex items-center justify-center text-pending">
                                                                            <span
                                                                                class="alert  alert-warning inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                                style="color: white;">
                                                                                Déjà choisir
                                                                            </span>

                                                                        </div>
                                                                    </div>
                                                                @endif

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">Aucune donnée pour le
                                                        moment</td>
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
                                {{ $unlocks->links('vendor.livewire.tailwind-reload_compte') }}

                            </ul>
                        </nav>
                    </div>
                    <!-- END: Pagination -->

                </div>
            </div>
        </div>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                Echo.channel('update_all_wallet').listen(
                    '.wallet_update_all',
                    (e) => {
                        window.livewire.emit('notifyNewReload:' + e.id);
                    },
                )
            });
        </script>
    </div>
    @include('livewire.commissions.modals.transactions.masters.adddumps')
    {{-- Modal update virement --}}

</div>

</div>
