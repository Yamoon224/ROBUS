<div>

    <!-- inject:js -->
    {{-- <script src="{{ asset_url('/js/app.js') }}"></script> --}}
    <script src="{{ asset_url('/css/app.css') }}"></script>
    <script src="{{ asset_url('/css/appModal.css') }}"></script>

    <div class="intro-y col-span-12 lg:col-span-12">
        @if (sabdyHomeVerify())
            <div class="grid grid-cols-12 gap-6">

                <div class="col-span-12 2xl:col-span-12">

                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2 intro-y style_user1">
                            <div class="report-box zoom-in style_user1">
                                <div class="box p-5 style_user1">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>

                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6">
                                        {{ $is_custumers ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500 mt-1">Clients</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-2 intro-y style_2">
                            <div class="report-box zoom-in style_2">
                                <div class="box p-5 style_2">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6">
                                        {{ $is_distributors ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500 mt-1">Operatrices</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2 intro-y style_user2">
                            <div class="report-box zoom-in style_user2">
                                <div class="box p-5 style_user2">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6">
                                        {{ $is_underdistributors }}
                                    </div>
                                    <div class="text-base text-slate-500 mt-1">Sous-distributeurs</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2 intro-y ">
                            <div class="report-box zoom-in ">
                                <div class="box p-5 ">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6">
                                        {{ $is_compagnies }}
                                    </div>
                                    <div class="text-base text-slate-500 mt-1">Compagnies</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 xl:col-span-2 intro-y style_user3">
                            <div class="report-box zoom-in  style_user3">
                                <div class="box p-5 style_user3">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6">
                                        {{ $is_masters ?? null }}</div>
                                    <div class="text-base text-slate-500 mt-1">Masters</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6 lg:col-span-2 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                        </svg>

                                    </div>
                                    <div class="text-3xl font-medium leading-8 mt-6">
                                        {{ $is_supervisors ?? null }}
                                    </div>
                                    <div class="text-base text-slate-500 mt-1">Superviseurs</div>
                                </div>
                            </div>
                        </div>



                    </div>

                </div>
            </div>
        @endif

        <div class="intro-y block sm:flex items-center h-10 mt-6">
            <h2 class="text-lg font-medium truncate mr-5">
                Les Activites du compte Aujourdh'ui
            </h2>
        </div>
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

            @if (Auth::guard('web')->user()->hasPermissionTo('voir-commission-admin'))
                <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">
                    <div class="amount-label text-sm text-gray-500">Compte des commissions SD total</div>
                    <div class="amount-commission-balance">
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
                    <div class="amount-label text-sm text-gray-500">Compte des commissions Admin </div>
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
            @endif

        </div>

        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_facture_home3">


                        <div class="flex flex-wrap gap-2 mb-transactions">
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès transferts {{ $success_transaction }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente transferts {{ $pendding_transaction }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec transferts {{ $error_transaction }}
                            </div>

                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès crédits {{ $success_phone_credit }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente crédits {{ $pendding_phone_credit }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec crédits {{ $error_phone_credit }}
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-transactions">
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Cartes {{ $success_bank }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Cartes {{ $pendding_bank }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Cartes {{ $error_phone_credit }}
                            </div>
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Versements {{ $versment_success }}
                            </div>

                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Versements {{ $versment_padding }}
                            </div>

                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Versements {{ $versment_error }}
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>

                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_robus_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Robus {{ $user_currencie }}</div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_moov_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Moov
                                    {{ $user_currencie }}</div>
                            </div>
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_wave_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Wave {{ $user_currencie }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_orange_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Orange {{ $user_currencie }}
                                </div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_mtn_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Mtn {{ $user_currencie }}
                                </div>
                            </div>
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Total {{ $user_currencie }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-4 mt-3">

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Montant Total {{ $user_currencie }}
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table4">


                        <div class="flex flex-wrap gap-2 mb-transactions">
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès paiements {{ $success_payment }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente paiements {{ $pendding_payment }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec paiements {{ $error_payment }}
                            </div>

                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès réchargements {{ $success_deposit }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente réchargements {{ $pendding_transaction }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec réchargements {{ $pendding_deposit }}
                            </div>

                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès crédits {{ $success_phone_credit }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente crédits {{ $pendding_phone_credit }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec crédits {{ $error_phone_credit }}
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-transactions">
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Xbets {{ $success_xbet }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Xbets {{ $pendding_xbet }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Xbets {{ $error_xbet }}
                            </div>
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Cartes {{ $success_bank }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Cartes {{ $pendding_bank }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Cartes {{ $error_phone_credit }}
                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>

                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_xbet_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Achat 1Xbets {{ $user_currencie }}</div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_deposit_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Réchargement cartes
                                    {{ $user_currencie }}</div>
                            </div>

                        </div>
                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_payment_cie_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">CIE {{ $user_currencie }}</div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_payment_canal_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">CANAL
                                    {{ $user_currencie }}</div>
                            </div>
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_payment_sodeci_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">SODECI {{ $user_currencie }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 mt-3">

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_payment_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Montant Total Paiements {{ $user_currencie }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table2">


                        <div class="flex flex-wrap gap-2 mb-transactions">
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès International {{ $success_inter }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente International {{ $pendding_inter }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec International {{ $error_inter }}
                            </div>

                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès crédits {{ $success_phone_credit }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente crédits {{ $pendding_phone_credit }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec crédits {{ $error_phone_credit }}
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>

                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_inter_moneyRobus ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Mobiles {{ $user_currencie }}</div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_inter_cie ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Factures
                                    {{ $user_currencie }}</div>
                            </div>
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_inter_credit ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Crédits {{ $user_currencie }}
                                </div>
                            </div>
                        </div>


                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_inter_virement ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Virements {{ $user_currencie }}</div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_inter_rechargement ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Rechargements
                                    {{ $user_currencie }}</div>
                            </div>

                        </div>

                        <div class="flex flex-wrap items-center gap-4 mt-3">

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_inter_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Montant Total International
                                    {{ $user_currencie }}
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_facture_home2">


                        <div class="flex flex-wrap gap-2 mb-transactions">
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Push {{ $push_success }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Push {{ $push_pendding }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Push {{ $push_error }}
                            </div>

                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Djamo {{ $djamo_success }}
                            </div>
                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Djamo {{ $djamo_padding }}
                            </div>
                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Djamo {{ $djamo_error }}
                            </div>
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Versements {{ $versment_success }}
                            </div>

                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Versements {{ $versment_padding }}
                            </div>

                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Versements {{ $versment_error }}
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>

                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_push_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Push {{ $user_currencie }}</div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_djamo_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Djamo
                                    {{ $user_currencie }}</div>
                            </div>

                        </div>


                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_versment_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Versements {{ $user_currencie }}
                                </div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_tresortMoney_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Tresort Money
                                    {{ $user_currencie }}</div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="menu-header mt-3">
            <h3 class="menu-title">Cliquez pour voir les services Financiers</h3>
            <div class="menu-badge">Robus</div>
        </div>
        <div class="intro-y grid grid-cols-12 gap-5 mt-6">

            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                        </rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_transaction_days ?? null }}</div>
                <div class="font-medium text-historique mt-6">

                    <a href="{{ route('transactions.browse') }}">
                        Transferts d'argents</a>

                </div>

            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_facture_home2">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                        </rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_payment_days ?? null }}</div>
                <div class="font-medium text-historique mt-6">
                    <a href="{{ route('transactions.browse') }}">
                        Paiement factures</a>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_bank_days ?? null }}</div>

                <div class="font-medium text-historique  mt-6">

                    <a href="{{ route('transactions.browse') }}"> Virements bancaires</a>

                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_facture_home ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_inter_days ?? null }}</div>

                <div class="font-medium text-historique mt-6">

                    <a href="{{ route('transactions.browse') }}"> Paiements internationaux</a>

                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_deposit_days ?? null }}</div>

                <div class="font-medium text-historique mt-6">
                    <a href="{{ route('transactions.browse') }}"> Réchargements de carte</a>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_facture_home3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_reload_days ?? null }}</div>

                <div class="font-medium text-historique mt-6">
                    <a href="{{ route('transactions.uv.browse') }}"> Réchargements</a>


                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_facture_home">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                        </rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_djamo_days ?? null }}</div>
                <div class="font-medium text-historique mt-6">
                    <a href="{{ route('transactions.browse') }}">
                        Transaction Djamo</a>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_facture_home2">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                        </rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_push_days ?? null }}</div>
                <div class="font-medium text-historique mt-6">
                    <a href="{{ route('transactions.browse') }}">
                        Transaction Push</a>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_facture_home3">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                        </rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_versment_days ?? null }}</div>
                <div class="font-medium text-historique mt-6">
                    <a href="{{ route('transactions.browse') }}">
                        Transaction Versement</a>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table2">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                        class="lucide lucide-credit-card report-box__icon text-secondary">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                        </rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div class="text-3xl font-medium leading-8 mt-6">{{ $account_tresort_days ?? null }}</div>
                <div class="font-medium text-historique mt-6">
                    <a href="{{ route('transactions.browse') }}">
                        Transaction Tresort Money</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-6">

            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 sm:col-span-8 lg:col-span-4 mt-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Activités d'aujourd'hui en {{ $usersCountry }}
                    </h2>
                </div>

                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum" virement="{{ json_encode($sum_bank) }}"
                        deposit="{{ json_encode($sum_deposit) }}" payment="{{ json_encode($sum_payment) }}"
                        reload="{{ json_encode($sum_reload) }}" transaction="{{ json_encode($sum_transaction) }}"
                        inter="{{ json_encode($sum_inter) }}" phone="{{ json_encode($sum_phoneCredit) }}"
                        xbet="{{ json_encode($sum_phoneCredit) }}" push="{{ json_encode($sum_push) }}"
                        djamo="{{ json_encode($sum_djamo) }}" versment="{{ json_encode($sum_versment) }}"
                        tresort="{{ json_encode($sum_tresortMoney) }}">
                    </canvas>

                </div>
            </div>
            <!-- END: Sales Report -->
            <!-- BEGIN: Sales Report -->

            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 sm:col-span-8 lg:col-span-8 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les activités des 30 derniers jours en {{ $usersCountry }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum_month" virement_month="{{ json_encode($sum_bank_month) }}"
                        deposit_month="{{ json_encode($sum_deposit_month) }}"
                        payment_month="{{ json_encode($sum_payment_month) }}"
                        reload_month="{{ json_encode($sum_reload_month) }}"
                        transaction_month="{{ json_encode($sum_transaction_month) }}"
                        inter_month="{{ json_encode($sum_inter_month) }}"
                        phone_month="{{ json_encode($sum_phoneCredit_month) }}"
                        xbet_month="{{ json_encode($sum_xbet_month) }}"
                        push_month="{{ json_encode($sum_push_month) }}"
                        djamo_month="{{ json_encode($sum_djamo_month) }}"
                        versment_month="{{ json_encode($sum_versment_month) }}"
                        tresort_month="{{ json_encode($sum_tresort_month) }}"></canvas>
                </div>
            </div>
            <!-- END: Sales Report -->
            <!-- END: Sales Report -->
        </div>

    </div>
    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        setInterval(function() {
            const boxes = document.querySelectorAll('.style_app');
            boxes.forEach((box, index) => {
                const colors = ['grey', 'blue', 'green',
                    '#6C0277', '#34C924', '#6600FF', '#F9429E'
                ]; // Ajoutez d'autres couleurs si nécessaire
                const color = colors[index % colors.length];
                box.style.backgroundColor = color;

            });
        }, 1000);
    </script>
    <script>
        var sumVirement = JSON.parse($('#sum').attr('virement'));
        var sumDeposit = JSON.parse($('#sum').attr('deposit'));
        var sumPayment = JSON.parse($('#sum').attr('payment'));
        var sumReload = JSON.parse($('#sum').attr('reload'));
        var sumTransaction = JSON.parse($('#sum').attr('transaction'));
        var sumInter = JSON.parse($('#sum').attr('inter'));
        var sumPhone = JSON.parse($('#sum').attr('phone'));
        var sumXbet = JSON.parse($('#sum').attr('xbet'));
        var sumPush = JSON.parse($('#sum').attr('push'));
        var sumDjamo = JSON.parse($('#sum').attr('djamo'));
        var sumVersment = JSON.parse($('#sum').attr('versment'));
        var sumTresort = JSON.parse($('#sum').attr('tresort'));

        var sum_v_month = JSON.parse($('#sum_month').attr('virement_month'));
        var sum_d_month = JSON.parse($('#sum_month').attr('deposit_month'));
        var sum_p_month = JSON.parse($('#sum_month').attr('payment_month'));
        var sum_r_month = JSON.parse($('#sum_month').attr('reload_month'));
        var sum_t_month = JSON.parse($('#sum_month').attr('transaction_month'));
        var sum_i_month = JSON.parse($('#sum_month').attr('inter_month'));
        var sum_phone_month = JSON.parse($('#sum_month').attr('phone_month'));
        var sum_1xbetw_month = JSON.parse($('#sum_month').attr('xbet_month'));
        var sum_push_month = JSON.parse($('#sum_month').attr('push_month'));
        var sum_djamo_month = JSON.parse($('#sum_month').attr('djamo_month'));
        var sum_versment_month = JSON.parse($('#sum_month').attr('versment_month'));
        var sum_tresortMoney_month = JSON.parse($('#sum_month').attr('tresort_month'));

        var _ctx = $('#sum');
        var _ctx2 = $('#sum_month');

        new Chart(_ctx, {
            type: 'pie',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits',
                    '1Xbet',
                    'Push',
                    'Djamo',
                    'Versement',
                    'Tresort',
                ],
                datasets: [{

                    label: 'Activites du jours',
                    data: [sumVirement, sumDeposit, sumPayment, sumReload,
                        sumTransaction, sumInter, sumPhone, sumXbet, sumPush, sumDjamo, sumVersment,
                        sumTresort
                    ],
                    backgroundColor: [
                        '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel
                    ],

                    borderColor: [
                        '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel

                    ],
                    borderWidth: 2,
                }]
            },
            options: {
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
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
                    'Credits',
                    '1Xbet',
                    'Push',
                    'Djamo',
                    'Versement',
                    'Tresort',
                ],
                datasets: [{

                    label: 'Activites du mois',
                    data: [sum_v_month, sum_d_month,
                        sum_p_month, sum_r_month, sum_t_month, sum_i_month, sum_phone_month,
                        sum_1xbetw_month, sum_push_month, sum_djamo_month, sum_versment_month,
                        sum_tresortMoney_month
                    ],
                    backgroundColor: [
                        '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel

                    ],
                    borderColor: [
                        '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel

                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },
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
</div>
