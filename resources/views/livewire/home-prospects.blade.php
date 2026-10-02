<div>


    <div class="intro-y col-span-12 lg:col-span-12">


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
            <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show">

            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y ">
                <div class="amount-label text-sm text-gray-500">Status de votre dossiers</div>
                @if ($integrationClient->status === 'Validé')
                    <span
                        class="inline-flex status-badge bg-success items-center px-4 py-2 text-base font-semibold text-white bg-green-600 rounded-lg shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Compte validé
                    </span>
                @elseif ($integrationClient->status === 'Refusé')
                    <div class="mb-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full animate-pulse"
                                style="width: 75%"></div>
                        </div>
                        <p class="text-amber-600 text-xs text-center mt-2">
                            <span
                                class="inline-flex status-badge bg-danger  items-center px-4 py-2 text-base font-semibold text-white bg-red-600 rounded-lg shadow-sm">
                                <i class="fas fa-times-circle mr-2"></i>
                                Refusé
                            </span>
                        </p>
                    </div>
                @else
                    <div class="text-center">
                        <div class="w-48 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full animate-pulse"
                                style="width: 75%"></div>
                        </div>
                        <p class="text-amber-600 text-xs mt-2">
                            <i class="fas fa-sync-alt animate-spin mr-1"></i>En cours de vérification
                        </p>
                    </div>
                @endif
            </div>

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

                    <a href="#">
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
                    <a href="#">
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

                    <a href="#"> Virements bancaires</a>

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

                    <a href="#"> Paiements internationaux</a>

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
                    <a href="#"> Réchargements de carte</a>
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
                    <a href="#">
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
                    <a href="#">
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
                    <a href="#">
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
                    <a href="#">
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
    @include('components.statistics')
</div>
