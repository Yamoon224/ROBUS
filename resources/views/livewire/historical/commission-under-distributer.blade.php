<div>

    <div class="grid grid-cols-12 gap-4 mt-6">
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Solde actuel</div>
            <div class="amount-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ formatDisplayAmount($walletSolde ?? 0) }}
                    </span>
                    <span class="amount-balance-currency text-white">
                        {{ $usersCountry ?? 'CFA' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Compte des commissions total</div>
            <div class="amount-commission-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ formatDisplayAmount($walletCommissions ?? 0) }}
                    </span>
                    <span class="amount-balance-currency text-white">
                        {{ $usersCountry ?? 'CFA' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Compte des transactions </div>
            <div class="amount-admin-balance">
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

        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Total remonté Sous distributeur aujourd'hui </div>
            <div class="amount-remonte-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ formatDisplayAmount($sum_commissions ?? 0) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5"> Les Activites des commissions remontées</h2>
                <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                    <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                        href="{{ route('payments.historical.reporting_payment_historical') }}" style="color: white;">
                        <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
                    </a>
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


            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 " style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500 mt-3">
                    <input type="text" name="table_search" wire:model.debounce.250ms="searchDrawersHistorical"
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
                            Liste des commission remontées
                        </h2>
                    </div>
                    <div class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Date
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Nom & Prénom(s)
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Pays & ville
                                            </th>
                                            <th
                                                class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant
                                            </th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($payment_drawers->count() > 0)
                                            @foreach ($payment_drawers as $key => $item)
                                                <tr class="intro-x">
                                                    <td class="w-30">
                                                        <div
                                                            class="px-3 py-2 text-dark btn-rose dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    {{ $item->type_transaction ?? null }}

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            @include('components.info_admin_treatment')
                                                        </div>
                                                    </td>

                                                    <td class="w-40">
                                                        @include('components.infoRemonteUser')


                                                    </td>


                                                    <td class="w-40">
                                                        @include('components.infoRemonteDetailsUser')

                                                    </td>
                                                    <td class="w-50">

                                                        @include('components.walletBalanceRemonte')

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
                            {{ $payment_drawers->links() }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>


        </div>
    </div>
</div>
