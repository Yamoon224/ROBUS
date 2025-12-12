@extends('layouts.master')

@section('title', 'Details pays')

@section('content')
    <div class="intro-y  px-5 pt-1 mt-1">

        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">
                <h2 class="font-medium text-base mr-auto mt-3">
                    <a href="{{ URL::previous() }}" class="btn btn-sm">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                </h2>
            </div>
            <div class="col-span-12 sm:col-span-8 xl:col-span-8 intro-y">
                <div class="alert alert-secondary show mb-2 font-medium text-lg text-center" role="alert">Les
                    paiements
                    entrants en/au {{ $countrie->name ?? null }} en {{ $usersCountry ?? null }}</div>
            </div>
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">

            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-5">

            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table2">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">

                            {{ $sum_wallet_custumers ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde cliens
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $sum_wallet_supervisors ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde superviseurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $sum_wallet_companies ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde Compagnie
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $sum_wallet_under_distributors ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde Sous distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in ">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $sum_wallet_distributors ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $sum_wallet_masters ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde masters
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table_cartes">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $sum_wallet_comZones ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde COM ZONES
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <form method="POST"
                    action="{{ route('remonte.remonteAllCountryCommission.unders', ['id' => $countrie->id]) }}">
                    @csrf
                    <div class="report-box zoom-in">
                        <div class="box p-5 style_table3 flex flex-col justify-between h-full">

                            <div>
                                <div class="flex">
                                    <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">
                                    {{ $sumTotalCommission ?? '0.00' }}
                                </div>
                                <div class="text-base text-slate-500 mt-1 mb-4">
                                    Solde commission SD
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-darkmode-400 h-16 flex justify-end">
                                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Tout Remonter">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                                    </svg>
                                    Remonter toute les commissions
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <form method="POST"
                    action="{{ route('remonte.remonteAllCountryCompanies.unders', ['id' => $countrie->id]) }}">
                    @csrf
                    <div class="report-box zoom-in">
                        <div class="box p-5 style_table3 flex flex-col justify-between h-full">

                            <div>
                                <div class="flex">
                                    <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">
                                    {{ $sumCTotalCommission ?? '0.00' }}
                                </div>
                                <div class="text-base text-slate-500 mt-1 mb-4">
                                   Solde commission Compagnies
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-darkmode-400 h-16 flex justify-end">
                                <button type="submit" class="btn btn-warning  mr-1 mb-2 btn-sm" title="Tout Remonter">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                                    </svg>
                                    Remonter toute les commissions
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_user1">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $custumers_countrie_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Clients
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_2">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_supervisor_count ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Superviseurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_user2">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_company_count ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1"> Compagnie
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_user3">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $users_distri_Countrie_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_under_distributor_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1"> Sous distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table_factures">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_master_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Masters
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 ">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_comzone_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">COM ZONES
                        </div>
                    </div>
                </div>
            </div>

        </div>


        @livewire('user-countrie', ['countrie' => $countrie->id])


        <div class="grid grid-cols-12 gap-6 mt-6">

            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 lg:col-span-6 mt-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Activités d'aujourd'hui en {{ $usersCountry }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum_todays" virement_todays="{{ json_encode($sum_bank_todays) }}"
                        deposit_todays="{{ json_encode($sum_deposit_todays) }}"
                        payment_todays="{{ json_encode($sum_payment_todays) }}"
                        reload_todays="{{ json_encode($sum_reload_todays) }}"
                        transaction_todays="{{ json_encode($sum_transaction_todays) }}"
                        inter_todays="{{ json_encode($sum_inter_todays) }}"
                        phone_todays="{{ json_encode($sum_phoneCredit_todays) }}"></canvas>

                </div>
            </div>
            <!-- END: Sales Report -->
            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les activités du mois en {{ $usersCountry }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum" virement="{{ json_encode($sum_bank_month) }}"
                        deposit="{{ json_encode($sum_deposit_month) }}" payment="{{ json_encode($sum_payment_month) }}"
                        reload="{{ json_encode($sum_reload_month) }}"
                        transaction="{{ json_encode($sum_transaction_month) }}"
                        inter="{{ json_encode($sum_inter_month) }}"
                        phone="{{ json_encode($sum_phoneCredit_month) }}"></canvas>
                </div>
            </div>
            <!-- END: Sales Report -->
        </div>

    </div>

    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var sumVirement = JSON.parse($('#sum').attr('virement'));
        var sumDeposit = JSON.parse($('#sum').attr('deposit'));
        var sumPayment = JSON.parse($('#sum').attr('payment'));
        var sumReload = JSON.parse($('#sum').attr('reload'));
        var sumTransaction = JSON.parse($('#sum').attr('transaction'));
        var sumInter = JSON.parse($('#sum').attr('inter'));
        var sumPhone = JSON.parse($('#sum').attr('phone'));
        var _ctx = $('#sum');

        var sumVirement_todays = JSON.parse($('#sum_todays').attr('virement_todays'));
        var sumDeposit_todays = JSON.parse($('#sum_todays').attr('deposit_todays'));
        var sumPayment_todays = JSON.parse($('#sum_todays').attr('payment_todays'));
        var sumReload_todays = JSON.parse($('#sum_todays').attr('reload_todays'));
        var sumTransaction_todays = JSON.parse($('#sum_todays').attr('transaction_todays'));
        var sumInter_todays = JSON.parse($('#sum_todays').attr('inter_todays'));
        var sumPhone_todays = JSON.parse($('#sum_todays').attr('phone_todays'));
        var _ctx2 = $('#sum_todays ');

        new Chart(_ctx, {
            type: 'bar',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits'
                ],
                datasets: [{

                    label: 'Activites',
                    data: [sumVirement, sumDeposit, sumPayment, sumReload, sumTransaction, sumInter,
                        sumPhone
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['white', 'white'],
                    }
                },
            }

        });

        new Chart(_ctx2, {
            type: 'bar',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits'
                ],
                datasets: [{

                    label: 'Activites',
                    data: [sumVirement_todays, sumDeposit_todays, sumPayment_todays, sumReload_todays,
                        sumTransaction_todays, sumInter_todays,
                        sumPhone_todays
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['white', 'white'],
                    }
                },
            }


        });
    </script>
    <script type="text/javascript">
        function sort_status(el) {
            $('#sort_status').submit();
        }
    </script>
@endsection
