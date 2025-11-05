<div>



    <div class="intro-y box px-5 pt-1 mt-1 ">
        <div class="grid grid-cols-12 gap-4 mt-6">
            <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                <div class="amount-label text-sm text-gray-500">Solde actuel</div>
                <div class="amount-balance">
                    <div class="flex items-baseline justify-between">
                        <span class="amount-balance-value text-xl font-semibold">
                            {{ $walletSolde ?? 0 }}
                        </span>
                        <span class="amount-balance-currency text-white">
                            {{ $usersCountry ?? 'CFA' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                <div class="amount-label text-sm text-gray-500">Compte des commissions total</div>
                <div class="amount-balance">
                    <div class="flex items-baseline justify-between">
                        <span class="amount-balance-value text-xl font-semibold">
                            {{ $walletCommissions ?? 0 }}
                        </span>
                        <span class="amount-balance-currency text-white">
                            {{ $usersCountry ?? 'CFA' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                <div class="amount-label text-sm text-gray-500">Compte des transactions </div>
                <div class="amount-balance">
                    <div class="flex items-baseline justify-between">
                        <span class="amount-balance-value text-xl font-semibold">
                            {{ $walletAdmin ?? 0 }}
                        </span>
                        <span class="amount-balance-currency text-white">
                            {{ $usersCountry ?? 'CFA' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" id="info-paie-show-1">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_cartes">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $reload_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $reload_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $reload_error }}

                                </div>

                            </div>

                        </div>
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
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $reload_success }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $reload_pendding }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $reload_error }}

                                </div>

                            </div>

                        </div>
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
                        <div class="text-base text-slate-500 mt-1">Rechargements {{ $usersCountry }} </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="intro-y block sm:flex items-center h-10 mt-6">
            <h2 class="text-lg font-medium truncate mr-5"> Les Activites des rechargements</h2>
            <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                    href="{{ route('reloads.historical.reporting_reload_historical') }}" style="color: white;">
                    <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
                </a>
            </div>
        </div>

        <div class="mt-5">

            @if (Auth::guard('web')->user()->hasPermissionTo('rechargement-compte'))
                <button class="btn btn-primary w-22 mr-1 mb-1"
                    wire:click="$emit('modal-reloadWallet-show' , 'create-reloadWallet-modal')">Rechargement</button>
            @endif

            @if (Auth::guard('web')->user()->hasPermissionTo('rechargement-commissions'))
                <button class="btn bg-warning w-22 mr-1 mb-1 "
                    wire:click="$emit('modal-reloadWalletCommission-show' , 'create-reloadWalletCommission-modal')">Réchargement
                    des comptes commissions</button>
            @endif

        </div>

        <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center mt-3"
            role="tablist">
            <li id="reload-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center active" data-tw-target="#reload"
                    aria-controls="reload" aria-selected="true" role="tab"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>Réchargements</a>
            </li>
            <li id="commission-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#commission"
                    aria-controls="commission" aria-selected="true" role="tab"><svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>Rechargements des comptes commissions</a>
            </li>
        </ul>

        <div class="tab-content mt-6">

            <div id="reload" class="tab-pane active" role="tabpanel" aria-labelledby="reload-tab">



                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;   ">
                        <div class="w-56 relative text-slate-500">

                            <input type="text" name="table_search" wire:model.debounce.250ms="searchRealod"
                                class="form-control  w-56 box pr-10" style="height: 3rem; background-color: #eff3f7;"
                                placeholder="Rechercher">
                            {{-- <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                                data-feather="searchTransaction"></i> --}}
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6  mt-6">
                    <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                        <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Liste des rechargements
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
                                                    Info transaction
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
                                            @if ($reaload_uv->count() > 0)
                                                @foreach ($reaload_uv as $item)
                                                    <tr class="intro-x">
                                                        <td class="text-center w-40">
                                                            <div
                                                                class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                <div class="fc-event-title-container"
                                                                    style="color: white;">
                                                                    <div class="fc-event-title fc-sticky">
                                                                        {{ $item->provider->name ?? null }}

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td class="user-info-card">
                                                            @include('components.infoUser')

                                                        </td>
                                                        @php

                                                            $valeur_amount_receveid =
                                                                (float) ($item->amount_received ?? 0);

                                                            $senderCurrencyValue =
                                                                optional(optional(sabdy()->country)->currencie)
                                                                    ->value ?? 1;
                                                            $receiverCurrencyValue =
                                                                optional(
                                                                    optional(optional($item->wallet)->sidUser)->country,
                                                                )->currencie->value ?? 1;
                                                            $countryId = optional(sabdy()->country)->id;

                                                            if ($countryId == 107) {
                                                                // Conversion de montant
                                                                if ($senderCurrencyValue > $receiverCurrencyValue) {
                                                                    // Conversion en CFA
                                                                    $valeur_amount_receveid =
                                                                        (float) $item->amount_received *
                                                                        (float) $senderCurrencyValue;
                                                                } elseif (
                                                                    $senderCurrencyValue == $receiverCurrencyValue
                                                                ) {
                                                                    // Montant inchangé
                                                                    $valeur_amount_receveid =
                                                                        (float) $item->amount_received;
                                                                } elseif (
                                                                    $senderCurrencyValue < $receiverCurrencyValue
                                                                ) {
                                                                    // Conversion vers la devise du pays d’envoi
                                                                    $valeur_amount_receveid =
                                                                        (float) $item->amount_received *
                                                                        (float) $receiverCurrencyValue;
                                                                }
                                                            } else {
                                                                // Pas de conversion spécifique
                                                                $valeur_amount_receveid =
                                                                    (float) $item->amount_received;
                                                            }
                                                        @endphp
                                                        <td>
                                                            @include('components.walletBalanceConvertion')


                                                        </td>
                                                        <td class="w-40">
                                                            <div class="mb-3">
                                                                @include('components.info_treatment')
                                                            </div>

                                                            <div class="user-name">
                                                                {{ $item->account_wallet ?? ($item->phone ?? null) }}
                                                            </div>
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
                                {{ $reaload_uv->links('vendor.livewire.tailwind-reload_compte') }}

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
    @include('livewire.commissions.modals.transactions.updateReload')
    @include('livewire.commissions.modals.transactions.addReload')
    @include('livewire.commissions.modals.transactions.addCommission')

</div>

</div>
