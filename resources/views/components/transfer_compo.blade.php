    <div id="transfers" class="tab-pane {{ $activeTab === 'transfers' ? 'active show' : '' }}" role="tabpanel"
        aria-labelledby="transfers-tab">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>

            <div class="hidden md:block mx-auto text-slate-500"></div>

            <div class="input-group input-group-md hidden" style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500">

                    <input type="text" name="table_search" class="form-control  w-56 box pr-10"
                        style="height: 3rem; background-color: #eff3f7;" placeholder="Rechercher">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                        data-feather="searchTransaction"></i>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="intro-y box col-span-12" style="background-color: #eff3f7;">

                <div
                    class="flex items-center  border-b border-slate-200/60 dark:border-darkmode-400 bg-white dark:bg-darkmode-700">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-8 bg-orange-500 rounded-r-full"></div>
                        <div>
                            <h2 class="font-medium text-base mr-auto text-slate-900 dark:text-white text-xl">
                                Liste des transferts d'argent
                            </h2>
                            <div class="flex items-center gap-4 mt-2 mb-2 ">
                                <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                                    Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}
                                </span>
                                <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
                                <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                                    {{ $transactions->count() }} éléments
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="new-products" class="tiny-slider py-5">
                    <div class="px-5">
                        <div class="overflow-x-auto">
                            <table id="table_orders" class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                        </th>
                                        <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                        </th>
                                        <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                            Montant à recevoir
                                        </th>

                                        <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                            Info Transactions
                                        </th>
                                        <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                            Statut </th>
                                        @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                            <th
                                                class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($transactions->count() > 0)
                                        @foreach ($transactions as $key => $item)
                                            <tr class="intro-x">
                                                <td class="text-center w-40 ">

                                                    @include('components.infoTypeTransaction')

                                                    <div
                                                        class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                        <div class="fc-event-title-container" style="color: white;">
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

                                                    if (sabdy()->country->id == 107) {
                                                        //  Convertion of amount valeur
                                                        if (
                                                            sabdy()->country->currencie->value >
                                                            $item->wallet->sidUser->country->currencie->value
                                                        ) {
                                                            //   Convertion  in CFA
                                                            $valeur_amount_receveid =
                                                                (float) $item->amount_received *
                                                                (float) sabdy()->country->currencie->value;
                                                        } elseif (
                                                            sabdy()->country->currencie->value ==
                                                            $item->wallet->sidUser->country->currencie->value
                                                        ) {
                                                            $valeur_amount_receveid = $item->amount_received;
                                                        } elseif (
                                                            sabdy()->country->currencie->value <
                                                            $item->wallet->sidUser->country->currencie->value
                                                        ) {
                                                            //   Convertion in Country send
                                                            $valeur_amount_receveid =
                                                                (float) $item->amount_received *
                                                                (float) $item->wallet->sidUser->country->currencie
                                                                    ->value;
                                                        }
                                                    } else {
                                                        //  Convertion of amount valeur
                                                        $valeur_amount_receveid = (float) $item->amount_received;
                                                    }
                                                @endphp
                                                <td>
                                                    @include('components.walletBalanceTransactionConvertion')

                                                </td>

                                                <td class="w-60">
                                                    <div class="mb-3">
                                                        @include('components.info')
                                                    </div>


                                                    @if ($item->updated_by == null)
                                                        <div class="user-name">
                                                            <span
                                                                class="alert alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                Indicatif :
                                                                {{ $item->wallet->sidUser->country->indecative ?? null }}

                                                            </span>
                                                            <p class="font-medium whitespace-nowrap">
                                                                {{ substr($item->phone ?? ($item->num_wallet ?? null), 0, 6) }}
                                                            </p>

                                                            @if ($item->provider->name == 'Moov' && $item->type_transaction == 'Retrait')
                                                                <p class="font-medium whitespace-nowrap">
                                                                    Référence :
                                                                    {{ substr($item->reference ?? ($item->reference ?? null), 0, 6) }}
                                                                </p>
                                                            @endif

                                                            <p class="font-medium whitespace-nowrap">
                                                                Nom& Prenom(S) :
                                                                {{ $item->name_complet ?? $item->wallet->sidUser->firstname }}

                                                            </p>
                                                            <p class="font-medium whitespace-nowrap mt-1">
                                                                Numéro piece : {{ $item->num_piece ?? null }}
                                                            </p>
                                                            <p class="font-medium whitespace-nowrap">
                                                                Date validité :
                                                                {{ $item->validated_date ?? null }}
                                                            </p>
                                                        </div>

                                                        <div wire:key="action-transfert-{{ $item->id }}"
                                                            class="flex items-center justify-center text-success mt-3">
                                                            @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                @if ($item->type_transaction == 'Retrait')
                                                                    <x-take-action-button
                                                                        action="ChooseUpdateTransaction"
                                                                        :itemId="$item->id" label="Je confirme"
                                                                        speed="ultra-fast" color="warning" />
                                                                @else
                                                                    <x-take-action-button
                                                                        action="ChooseUpdateTransaction"
                                                                        :itemId="$item->id" label="Je prends en charge"
                                                                        speed="ultra-fast" color="warning" />
                                                                @endif
                                                            @endif

                                                        </div>
                                                    @else
                                                        @if ($item->type_transaction == 'Retrait' && ($item->provider->name == 'Moov' || $item->provider->name == 'MOOV'))
                                                            <div
                                                                class="transaction-badge transaction-badge--customer text-white text-center">
                                                                Verifier la transaction
                                                            </div>
                                                        @endif
                                                        <div class="user-name">
                                                            <span
                                                                class="alert alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                Indicatif :
                                                                {{ $item->wallet->sidUser->country->indecative ?? null }}

                                                            </span>
                                                            <p class="font-medium whitespace-nowrap">
                                                                {{ $item->phone ?? null }}
                                                                {{ $item->num_wallet ?? null }}
                                                            </p>
                                                            @if ($item->provider->name == 'Moov' && $item->type_transaction == 'Retrait')
                                                                <p class="font-medium whitespace-nowrap">
                                                                    Référence : {{ $item->reference ?? null }}
                                                                </p>
                                                            @endif
                                                            <p class="font-medium whitespace-nowrap">
                                                                Nom& Prenom(S) :
                                                                {{ $item->name_complet ?? $item->wallet->sidUser->firstname }}

                                                            </p>
                                                            <p class="font-medium whitespace-nowrap mt-1">
                                                                Numéro piece : {{ $item->num_piece ?? null }}
                                                            </p>
                                                            <p class="font-medium whitespace-nowrap">
                                                                Date validité :
                                                                {{ $item->validated_date ?? null }}
                                                            </p>
                                                        </div>

                                                        @if ($item->type_transaction === 'Retrait')
                                                            <div wire:key="action-transfert-{{ $item->id }}"
                                                                class="flex items-center justify-center text-success mt-3">
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <x-taked-action-button
                                                                        action="ChooseUpdateDrawalTransaction"
                                                                        :itemId="$item->id"
                                                                        label="Je confirme pour le retrait"
                                                                        speed="ultra-fast" color="purple" />
                                                                @endif

                                                            </div>
                                                        @endif
                                                    @endif

                                                </td>

                                                <td class="w-40">
                                                    @include('components.info_status')

                                                </td>
                                                <td class="w-40">
                                                    <div class="flex justify-center items-center">
                                                        @if ($item->updated_by != null)
                                                            @if (
                                                                ($item->status == 'PENDING' && $item->updated_by == sabdy()->id) ||
                                                                    ($item->status === 'PROCESSING' && $item->updated_by == sabdy()->id))
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a wire:click="editTransaction({{ $item->id }}, 'update-transfersUV-modal')"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        <svg class="w-4 h-4 mr-1" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                        @if ($item->type_transaction == 'Retrait')
                                                                            Reversement
                                                                        @else
                                                                            Traité maintenant
                                                                        @endif
                                                                    </a>
                                                                @endif
                                                            @elseif ($item->status == 'SUCCESS' && $item->updated_by == sabdy()->id)
                                                                <a target="_bank"
                                                                    href="{{ route('transfers.invoice.print', $item->id) }}"
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
                                                        @else
                                                            @if ($item->choose_transaction == 1)
                                                                <div
                                                                    class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-warring">
                                                                        <span
                                                                            class="alert alert-dark show mb-2 inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                icon-name="alert-octagon"
                                                                                data-lucide="alert-octagon"
                                                                                class="lucide lucide-alert-octagon w-6 h-6 mr-2">
                                                                                <polygon
                                                                                    points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                                                </polygon>
                                                                                <line x1="12" y1="8"
                                                                                    x2="12" y2="12">
                                                                                </line>
                                                                                <line x1="12" y1="16"
                                                                                    x2="12.01" y2="16">
                                                                                </line>
                                                                            </svg>
                                                                            Pas encore choisir
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-warring">
                                                                        <span
                                                                            class="alert alert-dark show mb-2 inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                icon-name="alert-octagon"
                                                                                data-lucide="alert-octagon"
                                                                                class="lucide lucide-alert-octagon w-6 h-6 mr-2">
                                                                                <polygon
                                                                                    points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                                                </polygon>
                                                                                <line x1="12" y1="8"
                                                                                    x2="12" y2="12">
                                                                                </line>
                                                                                <line x1="12" y1="16"
                                                                                    x2="12.01" y2="16">
                                                                                </line>
                                                                            </svg>
                                                                            Reversement
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
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
                        {{ $transactions->links('vendor.livewire.tailwind-transactions') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
