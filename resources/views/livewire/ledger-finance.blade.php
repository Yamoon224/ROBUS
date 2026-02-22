<div class="p-6 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mt-6">
            Grand Livre
        </h1>
        <p class="text-gray-500 text-sm">
            Journal des mouvements financiers (Debit / Credit)
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-12 gap-4 mt-6">
        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Total Commissions Admin</div>
            <div class="amount-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($sum_gains_admin, 2) }} FCFA

                    </span>
                    <span class="amount-balance-currency text-white">
                        FCFA
                    </span>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Commissions Aujourd'hui</div>
            <div class="amount-commission-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($sum_gain_admin_days, 2) }} FCFA

                    </span>
                    <span class="amount-balance-currency text-white">
                        FCFA
                    </span>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Total Commissions Banque</div>
            <div class="amount-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($sum_gains_commission, 2) }}

                    </span>
                    <span class="amount-balance-currency text-white">
                        FCFA
                    </span>

                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y info-paiement-show">
            <div class="amount-label text-sm text-gray-500">Commissions Banque Aujourd'hui</div>
            <div class="amount-commission-balance">
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($sum_gain_commission_days, 2) }}

                    </span>
                    <span class="amount-balance-currency text-white">
                        FCFA
                    </span>

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
                        wire:model="startDate" placeholder="Rechercher par date" id="start_date" aria-label="Date">

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
                <label for="countriesFilter">Filtrer par types</label>
                <select id="typeFilter" wire:model="typeFilter"
                    class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                    aria-placeholder="Rechercher par pays">
                    <option value="credit">Credit</option>
                    <option value="debit">Debit</option>
                </select>
            </div>
        </div>
        <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Journal des mouvements financiers (Debit / Credit)

                        </h2>
                    </div>
                    <div class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Transaction
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Description
                                            </th>
                                            <th
                                                class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Debit
                                            </th>

                                            <th
                                                class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Credit
                                            </th>
                                            <th
                                                class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Date
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($gains->count() > 0)
                                            @foreach ($gains as $key => $item)
                                                @php
                                                    $type = $item->adminCom->type ?? 'UNKNOWN';
                                                @endphp
                                                <tr class="intro-x">
                                                    
                                                    <td class="text-center w-50 ">

                                                        <div
                                                            class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    {{ $item->transaction_id ?? null }}

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>

                                                    <td class="text-center w-40">
                                                        {{ $item->description }}

                                                    </td>
                                                    <td class="w-40">
                                                        <div class="amount-section">
                                                            <div class="amount-label">Montant debité</div>
                                                            <div class="amount-received">
                                                                <div class="flex items-baseline justify-between">
                                                                    <span class="amount-received-value">
                                                                        {{ $item->debit > 0 ? '-' . number_format($item->debit, 2) . ' FCFA' : '-' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">


                                                        <div class="amount-section">
                                                            <div class="amount-label">Montant credité</div>
                                                            <div class="amount-balance">
                                                                <div class="flex items-baseline justify-between">
                                                                    <span class="amount-balance-value">
                                                                        {{ $item->credit > 0 ? '+' . number_format($item->credit, 2) . ' FCFA' : '-' }}

                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>


                                                    <td class="w-56">
                                                        @include('components.info_treatment')

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
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6 mb-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $gains->links() }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>

        </div>
    </div>
</div>
