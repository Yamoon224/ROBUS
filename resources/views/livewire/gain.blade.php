<div>

    <div class="intro-y block sm:flex items-center h-10 mt-3">
        <h2 class="text-lg font-medium truncate mr-5"> Liste des gains de l'adminmistrateur</h2>
        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

            <a class="btn btn-primary shadow-md" href="{{ route('admin.browse_gain_distributer') }}">Validation
                distributeur </a>

        </div>
    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">

        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in bg-success">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6 text-white">Montant journalier admin</div>
            <div class="text-slate-500 mt-3 text-white">
                {{ number_format($sum_gain_days, 2, ',', ' ') ?? null }} FCFA
            </div>
        </div>
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_3">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Paiements sous distributeur et retraits</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_gain_drawal_days, 2, ',', ' ') ?? null }} FCFA
            </div>
        </div>
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_gains, 2, ',', ' ') ?? null }} FCFA
            </div>
        </div>

        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_facture_home2 ">

            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                </svg>

            </div>
            <div class="font-medium text-historique mt-6">Montant Banque journalier</div>
            <div class="text-slate-500 mt-3">
                {{ $sum_gain_banques ?? null }} FCFA
            </div>
        </div>
    </div>
    <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
            <div class="w-56 relative text-slate-500 mt-3">
                <label for="">Date de debut</label>

                <input type="date" class="form-control  w-56 box pr-10 mt -1" value="{{ $startDate }}"
                    wire:model="startDate" placeholder="Rechercher par date les commandes" id="start_date"
                    aria-label="Date">

            </div>
        </div>
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
            <div class="w-56 relative text-slate-500 mt-3">
                <label for="">Date de fin</label>
                <input type="date" class="form-control  w-56 box pr-10 mt-1" value="{{ $endDate }}"
                    wire:model="endDate" placeholder="Rechercher par date" id="end_date" aria-label="Date">

            </div>

        </div>
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
            <label for="countriesFilter">Filtrer par status</label>
            <select id="statusTransferFilter" wire:model="statusTransferFilter"
                class="form-select py-3 px-4 box w-full
                dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                aria-placeholder="Rechercher par pays">
                <option selected value="!= null">Status</option>
                <option value="SUCCESS">Success</option>
                <option value="PENDING">En attente</option>
            </select>
        </div>

        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 " style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500 mt-3">
                <input type="text" name="table_search" wire:model.debounce.250ms="search"
                    class="form-control w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                {{-- <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6  ml-10" data-feather="search"></i> --}}
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">

        <div class="intro-y col-span-12  lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="text-center whitespace-nowrap">Montant</th>
                        <th class="text-center whitespace-nowrap">Type </th>
                        <th class="text-center whitespace-nowrap">Statut </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($gains->count() > 0)
                        @foreach ($gains as $item)
                            @php
                                $types = [
                                    'wallet_bank_id' => [
                                        'label' => 'Virements bancaires',
                                        'relation' => 'walletBank',
                                    ],
                                    'wallet_transaction_id' => [
                                        'label' => 'Transactions',
                                        'relation' => 'walletTransaction',
                                    ],
                                    'wallet_deposit_id' => [
                                        'label' => 'Rechargement de carte',
                                        'relation' => 'walletDeposit',
                                    ],
                                    'wallet_inter_id' => [
                                        'label' => 'International',
                                        'relation' => 'walletInter',
                                    ],
                                    'wallet_payment_id' => [
                                        'label' => 'Paiements',
                                        'relation' => 'walletPayment',
                                    ],
                                    'wallet_phone_credit_id' => [
                                        'label' => 'Crédit téléphonique',
                                        'relation' => 'walletPhoneCredit',
                                    ],
                                    'wallet_reload_id' => [
                                        'label' => 'Rechargement de carte',
                                        'relation' => 'walletReload',
                                    ],
                                    'bank_card_id' => [
                                        'label' => 'Commande de carte',
                                        'relation' => 'orderCard',
                                    ],
                                    'wallet_xbet_id' => [
                                        'label' => 'Transactions 1Xbet',
                                        'relation' => 'walletXbet',
                                    ],
                                    'wallet_wave_id' => [
                                        'label' => 'Transactions Wave',
                                        'relation' => 'walletWave',
                                    ],
                                    'wallet_push_id' => [
                                        'label' => 'Transactions Push',
                                        'relation' => 'walletPush',
                                    ],
                                    'wallet_djamo_id' => [
                                        'label' => 'Transactions Djamo',
                                        'relation' => 'walletDjamo',
                                    ],
                                    'wallet_tresort_money_id' => [
                                        'label' => 'Transactions Tresort Money',
                                        'relation' => 'walletTresort',
                                    ],
                                    'wallet_versement_id' => [
                                        'label' => 'Transactions Versements bancaires',
                                        'relation' => 'walletVersement',
                                    ],
                                    'wallet_bank_send_id' => [
                                        'label' => 'Transactions Bank Send',
                                        'relation' => 'walletBankSend',
                                    ],
                                ];

                                $current = null;

                                foreach ($types as $column => $config) {
                                    if (!is_null($item->{$column})) {
                                        $current = $config;
                                        $relationData = $item->{$config['relation']};
                                        break;
                                    }
                                }
                            @endphp
                            <tr class="intro-x">

                                <td class="w-40">
                                    <div class="mb-3">
                                        @include('components.info')
                                    </div>
                                </td>
                                <td class="w-56">

                                    <div class="amount-section">
                                        <div class="amount-label">Montant reçu</div>
                                        <div class="amount-received">
                                            <div class="flex items-baseline justify-between">
                                                <span class="amount-received-value">
                                                    {{ number_format($item->amount, 2, ',', ' ') ?? '0,00' }} Frcfa
                                                </span>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-slate-500  mt-0.5">
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->description ?? null }}
                                        </span>
                                    </div>

                                </td>
                                <td class="w-40">


                                    @if ($current && $relationData)
                                        <div class="user-badges-container">
                                            @if ($relationData->wallet->sidUser->is_under_distributer == 1)
                                                <div class="user-badge user-badge--distributor">
                                                    Sous-distributeur
                                                </div>
                                            @elseif ($relationData->wallet->sidUser->is_custumers == 1)
                                                <div class="user-badge user-badge--customer">
                                                    Client
                                                </div>
                                            @elseif ($relationData->wallet->sidUser->is_company == 1)
                                                <div class="user-badge user-badge--compagnie">
                                                    Société
                                                </div>
                                            @elseif ($relationData->wallet->sidUser->is_api == 1)
                                                <div class="user-badge user-badge--compagnie">
                                                    Integrateur
                                                </div>
                                            @else
                                                <div class="user-badge user-badge--admin">
                                                    Administrateur
                                                </div>
                                            @endif
                                        </div>

                                        <div class="transaction-badges-container">
                                            <div
                                                class="transaction-badge transaction-badge--distributor text-white text-center">
                                                {{ $current['label'] }}
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="px-3 py-2 bg-blue-900 text-white rounded font-medium">
                                                {{ $relationData->wallet->country->name ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="amount-section">
                                            <div class="amount-label">Montant</div>
                                            <div class="amount-balance">
                                                <div class="flex items-baseline justify-between">
                                                    <span class="amount-balance-value">
                                                        {{ number_format($relationData->costs_amount ?? 0, 2, ',', ' ') }}
                                                    </span>
                                                    <span class="amount-balance-currency">
                                                        {{ $relationData->wallet->currencie->code ?? '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td class="w-40">
                                    @include('components.info_status')

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
                    {{ $gains->links() }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>

</div>
