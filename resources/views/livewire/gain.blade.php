<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des gains de l'adminmistrateur
    </h2>
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
        <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in">

            <div class="font-medium text-historique mt-6">Voir validations distributeur</div>
            <div class="text-slate-500 mt-3">
                <a class="btn btn-primary shadow-md" href="{{ route('admin.browse_gain_distributer') }}">Validation
                    distributeur </a>
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
                                                montant : {{ $item->walletBank->costs_amount ?? null }}
                                                {{ $item->walletBank->wallet->currencie->code ?? null }}
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
                                                montant : {{ $item->walletTransaction->costs_amount ?? 'Retrait' }}
                                                {{ $item->walletTransaction->wallet->currencie->code ?? null }}
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
                                                montant : {{ $item->walletDeposit->costs_amount ?? null }}
                                                {{ $item->walletDeposit->wallet->currencie->code ?? null }}
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
                                                {{ $item->walletInter->wallet->currencie->code ?? null }}
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
                                                {{ $item->walletPayment->wallet->currencie->code ?? null }}
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
                                                {{ $item->walletPhoneCredit->wallet->currencie->code ?? null }}
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
                                                montant : {{ $item->walletReload->costs_amount ?? null }}
                                                {{ $item->walletReload->wallet->currencie->code ?? null }}
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
                                                montant : {{ $item->orderCard->costs_amount ?? null }}
                                                {{ $item->orderCard->wallet->currencie->code ?? null }}
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
