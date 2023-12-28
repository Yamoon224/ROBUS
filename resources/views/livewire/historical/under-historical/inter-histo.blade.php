<div>

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">

            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5"> Les Activites des paiements internationnaux</h2>
                <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                    <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                        href="{{ route('inters.historical.reporting_inter_historical') }}" style="color: white;">
                        <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
                    </a>
                </div>
            </div>
            <div class="intro-y grid grid-cols-12 gap-5 mt-5">
                <div
                    class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
                    <div>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                            <polyline points="17 18 23 18 23 12"></polyline>
                        </svg>
                    </div>
                    <div class="font-medium text-historique mt-6">Total Internationnal</div>
                    <div class="text-slate-500 mt-3"> {{ $user->wallet->walletPayments->sum('amount') ?? null }}
                        {{ $usersCountry }}
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-alert-circle font-medium-5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div class="font-medium text-historique mt-6">En attente</div>
                    <div class="text-slate-500 mt-3">
                        {{ $inter_historicals->where('status', 'PENDING')->count() ?? null }}
                    </div>
                </div>
                <div
                    class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-check font-medium-5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <div class="font-medium text-historique text-white mt-6">Success</div>
                    <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                        {{ $inter_historicals->where('status', 'SUCCESS')->count() ?? null }}
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x-circle font-medium-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <div class="font-medium text-historique mt-6">Echec</div>
                    <div class="text-slate-500 mt-3">
                        {{ $inter_historicals->where('status', 'ERROR')->count() ?? null }}</div>
                </div>

                <div
                    class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2"
                            ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    <div class="font-medium text-historique mt-6">Gains</div>
                    <div class="text-slate-500 mt-3"> {{ $sum_commissions }} {{ $usersCountry }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

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
                <select id="statusInterFilter" wire:model="statusInterFilter"
                    class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                    aria-placeholder="Rechercher par status">
                    <option selected disabled>Status</option>
                    <option value="SUCCESS">Success</option>
                    <option value="ERROR">Erreur</option>
                    <option value="PENDING">En attente</option>
                </select>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 " style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500 mt-3">
                    <input type="text" name="table_search" wire:model.debounce.250ms="searchInterHistorical"
                        class="form-control w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                    {{-- <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6  ml-10" data-feather="search"></i> --}}
                </div>
            </div>
        </div>
        <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des paiements internationnaux
                        </h2>
                    </div>
                    <div class="tiny-slider py-5">
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
                                                Traité le
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
                                        @if ($inter_historicals->count() > 0)
                                            @foreach ($inter_historicals as $item)
                                                <tr class="intro-x">
                                                    <td class="text-center w-40">
                                                        @if ($item->provider_type == 1)
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                       text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Paiement Mooney
                                                            </span>
                                                        @elseif ($item->provider_type == 5)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1
                                                       mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Paiement Facture
                                                            </span>
                                                        @elseif ($item->provider_type == 6)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 
                                                      mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Credit telephonique
                                                            </span>
                                                        @elseif ($item->provider_type == 7)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Versement bancaire
                                                            </span>
                                                        @elseif($item->provider_type == 8)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Rechargement carte
                                                            </span>
                                                        @endif
                                                        {{ $item->provider->name ?? null }}
      
                                                    </td>
                                                    <td class="w-50">
                                                        <div class="mt-0.5 mb-2">
                                                            <div class="mt-0.5">
                                                                @if ($item->wallet->sidUser->is_under_distributer == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Sous-distributeur</div>
                                                                        </div>
                                                                    </div>
                                                                @elseif($item->wallet->sidUser->is_custumers == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">Client</div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">Admin</div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}
                                                            </div>
                                                        </div>
                                                        <div class="mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>
      
                                                    </td>
      
                                                    @php
                                                        if ($item->provider_type == 1) {
                                                            //  Convertion of amount valeur
                                                            if ($item->wallet->currencie->code == 'EUR' || $item->wallet->currencie->code == 'DH' || $item->wallet->currencie->code == 'Dt' || $item->wallet->currencie->code == 'CEDI' || $item->wallet->currencie->code == 'USD') {
                                                                $valeur_amount_receveid = $item->amount_received * $item->wallet->currencie->value;
                                                            } elseif ($item->wallet->currencie->code == 'GN') {
                                                                // Montant Update  if Diff country or Auth is <  a send client
                                                                $valeur_amount_receveid = $item->amount_received / $item->wallet->currencie->value;
                                                            } elseif ($item->wallet->currencie->code == 'CFA') {
                                                                $valeur_amount_receveid = $item->amount_received ?? null;
                                                            }
                                                        } else {
                                                            //  Convertion of amount valeur
                                                            if ($item->wallet->currencie->code == 'EUR' || $item->wallet->currencie->code == 'DH' || $item->wallet->currencie->code == 'Dt' || $item->wallet->currencie->code == 'CEDI' || $item->wallet->currencie->code == 'USD') {
                                                                $valeur_amount = $item->amount * $item->wallet->currencie->value;
                                                            } elseif ($item->wallet->currencie->code == 'GN') {
                                                                // Montant Update  if Diff country or Auth is <  a send client
                                                                $valeur_amount = $item->amount / $item->wallet->currencie->value;
                                                            } elseif ($item->wallet->currencie->code == 'CFA') {
                                                                $valeur_amount = $item->amount ?? null;
                                                            }
                                                        }
                                                        
                                                    @endphp
                                                    <td class="w-40">
      
                                                        @if ($item->provider_type == 1)
                                                            <p class="mb-3">
                                                                Montant envoyé :
                                                            </p>
                                                            <div class="mt-0.5 mb-2">
                                                                <div class="mt-0.5">
                                                                    {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                                    {{ $item->wallet->currencie->code ?? null }}
                                                                </div>
                                                            </div>
                                                            <p>
                                                                Montant reçu
                                                            </p>
                                                            <div class="mt-0.5 mb-2">
                                                                <div class="mt-0.5">
                                                                    {{ number_format($item->amount_received, 2, ',', ' ') ?? null }}
                                                                    {{ $item->wallet->currencie->code ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="mt-0.5">
                                                                <span class="font-bold ">
                                                                    Montant en {{ $usersCountry ?? null }} : <br>
                                                                    {{ number_format($valeur_amount_receveid, 2, ',', ' ') ?? null }}
                                                                    {{ $usersCountry ?? null }}
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="mt-0.5 mb-2">
                                                                <div class="mt-0.5">
                                                                    {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                                    {{ $item->wallet->currencie->code ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="mt-0.5">
                                                                <span class="font-bold ">
                                                                    Montant en {{ $usersCountry ?? null }} : <br>
                                                                    {{ number_format($valeur_amount, 2, ',', ' ') ?? null }}
                                                                    {{ $usersCountry ?? null }}
                                                                </span>
                                                            </div>
                                                        @endif
      
                                                    </td>
                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->transactionInter->created_at ?? null }}</p>
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
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                                                    <span
                                                                        class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        En attente
                                                                    </span>
                                                                @endif
                                                            @elseif ($item->status == 'SUCCESS'  || $item->status == 'REFUND' )
                                                                <a target="_bank"
                                                                    href="{{ route('inters.invoice.print', $item->id) }}"
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
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
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
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6 mb-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $inter_historicals->links() }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>


        </div>
    </div>
    {{-- <script type="text/javascript">
        function sort_date(el) {
            $('#sort_date').submit();
        }
    </script> --}}
</div>
