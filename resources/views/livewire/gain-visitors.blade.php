<div>

    <div class="intro-y block sm:flex items-center h-10 mt-3">
        <h2 class="text-lg font-medium truncate mr-5">   Liste des gains de l'adminmistrateur</h2>
        <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

            <a class="btn btn-primary shadow-md" href="{{ route('admin.browse_gain_distributer') }}">Validation
                distributeur </a>
           
        </div>
    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_3">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant journalier admin</div>
            <div class="text-slate-500 mt-3">
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

        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in ">
            
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                  </svg>
                  
            </div>
            <div class="font-medium text-historique mt-6">Montant Banque journalier</div>
            <div class="text-slate-500 mt-3">
                {{ $sum_gain_banques  ?? null }} FCFA
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
                            <tr class="intro-x">
                                <td class="w-40">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->created_at ?? null }}</p>
                                </td>
                                <td class="w-56">
                                    <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                        <div class="text-slate-300 text-xs  mt-0.5"> {{ $item->amount ?? null }} Frcfa
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
                                    @if ($item->wallet_bank_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                Virements bancaires
                                            </a>
                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->walletBank->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->walletBank->costs_amount ?? null }}FCFA
                                            </span>
                                        </div>
                                    @elseif($item->wallet_transaction_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                Transactions
                                            </a>

                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->walletTransaction->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->walletTransaction->costs_amount ?? 'Retrait' }}FCFA
                                            </span>
                                        </div>
                                    @elseif($item->wallet_deposit_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                Rechargement de carte
                                            </a>

                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->walletDeposit->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->walletDeposit->costs_amount ?? null }} FCFA
                                            </span>
                                        </div>
                                    @elseif($item->wallet_inter_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                International
                                            </a>

                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->walletInter->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->walletInter->costs_amount ?? null }}
                                            </span>
                                        </div>
                                    @elseif($item->wallet_payment_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                Paiements
                                            </a>

                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->walletPayment->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->walletPayment->costs_amount ?? null }}
                                            </span>
                                        </div>
                                    @elseif($item->wallet_phone_credit_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                Credit telephonique
                                            </a>

                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->walletPhoneCredit->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->walletPhoneCredit->costs_amount ?? null }}
                                            </span>
                                        </div>
                                    @elseif($item->wallet_reload_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                Rechargement de carte
                                            </a>

                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->walletReload->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->walletReload->costs_amount ?? null }} FCFA
                                            </span>
                                        </div>
                                    @elseif($item->bank_card_id != null)
                                        <div class="flex items-center justify-center text-success">

                                            <a class="flex items-center mr-3">
                                                Commande de carte
                                            </a>

                                        </div>
                                        <div class="text-slate-500 items-center mt-0.5">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->orderCard->wallet->country->name ?? null }} <br> <br>
                                                montant : {{ $item->orderCard->costs_amount ?? null }} FCFA
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="w-40">
                                    @if ($item->status == 'PENDING')
                                        <div class="flex items-center justify-center text-pending">
                                            <span
                                                class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                En-attente
                                            </span>

                                        </div>
                                    @elseif ($item->status == 'ERROR')
                                        <div class="flex items-center justify-center text-danger">
                                            <div
                                                class="flex items-center justify-center text-pending">
                                                <span
                                                    class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    Refusé
                                                </span>

                                            </div>

                                        </div>
                                        <div class="flex items-center justify-center text-pending">
                                            <span
                                                class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                style="color: white;">
                                                Traiter
                                            </span>

                                        </div>
                                    @elseif($item->status == 'REFUND')
                                        <div class="flex items-center justify-center text-primary">
                                            <div
                                                class="flex items-center justify-center text-pending">
                                                <span
                                                    class="alert alert-primary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    Remboursé
                                                </span>

                                            </div>
                                        </div>
                                        <div class="flex items-center justify-center text-pending">
                                            <span
                                                class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                style="color: white;">
                                                Traiter
                                            </span>

                                        </div>
                                    @else
                                        <div class="flex items-center justify-center text-success">
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
                                        <div class="flex items-center justify-center text-pending">
                                            <span
                                                class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                style="color: white;">
                                                Traiter
                                            </span>

                                        </div>
                                    @endif
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
