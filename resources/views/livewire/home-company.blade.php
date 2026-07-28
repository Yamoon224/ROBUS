<div>

    <div class="intro-y col-span-12 lg:col-span-12">


        <div class="intro-y block sm:flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Les Activites du compte Aujourd'hui
            </h2>
        </div>
    @include('components.solde_firm')

        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
                <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-trending-down font-medium-5">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                </div>
                <div class="font-medium text-historique mt-6">Montant total</div>
                <div class="text-slate-500 mt-3">
                    {{ number_format($sum_amount, 2, ',', ' ') ?? null }} {{ $user_currencie }}
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>

                </div>
                <div class="font-medium text-historique mt-6">Clients</div>
                <div class="text-slate-500 mt-3">
                    {{ $employers ?? null }}
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_user1">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>

                </div>
                <div class="font-medium text-historique mt-6">Sous distributeurs</div>
                <div class="text-slate-500 mt-3">
                    {{ $undersDistributors ?? null }}
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
                    {{ $pending_success ?? null }}
                </div>
            </div>
            <div
                class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-check font-medium-5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div class="font-medium text-historique text-white mt-6">Success</div>
                <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                    {{ $sum_success ?? null }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-x-circle font-medium-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Echec</div>
                <div class="text-slate-500 mt-3">
                    {{ $sum_error ?? null }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show" id="info-paie-show-1">
                <div class="report-box zoom-in">
                    <div class="box p-wallet">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_reload }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_reload }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_reload }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($amount_reload_days, 2, ',', ' ') ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Rechargements {{ $user_currencie }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_factures">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_phone_credit ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_phone_credit ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_phone_credit ?? null }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $amount_phone_credit_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Credit télephonique {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y " id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table2">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_inter ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_inter ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_inter ?? null }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $amount_inter_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">International {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table1">

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $company_gain ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Gain Total {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y info-paiement-show" id="info-paie-show-1">
                <div class="report-box zoom-in ">
                    <div class="box p-wallet style_table4">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_payment }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_payment }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_payment }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ number_format($amount_payment_days, 2, ',', ' ') ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Paiements {{ $user_currencie }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_factures">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_transaction ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_transaction ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_transaction ?? null }}

                                </div>

                            </div>

                        </div>
                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $amount_transaction_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Transactions {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y " id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table2">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_bank ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_bank ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_bank ?? null }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $amount_bank_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Transactions Banque {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_factures">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_deposit ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_transaction ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $pendding_deposit ?? null }}

                                </div>

                            </div>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                            class="lucide lucide-credit-card report-box__icon text-secondary">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <div class="text-3xl font-medium leading-8 mt-transactions">
                            {{ $amount_deposit_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Rechargements carte {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
            Gains des transactions
        </div>

        <div class="intro-y grid grid-cols-12 gap-5 mt-5">


            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-pendd">
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
                <div class="font-medium text-historique mt-6">Transactions</div>

                <div class="text-slate-500 mt-3">
                    {{ number_format($gain_transaction_days, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>

            </div>
            <div
                class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="font-medium text-historique text-white mt-6">Paiements</div>


                <div class="text-slate-500 mt-3">
                    {{ number_format($gain_payment_days, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Rechargements</div>


                <div class="text-slate-500 mt-3">
                    {{ number_format($gain_deposit_days, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_virements">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Credits</div>
                <div class="text-slate-500 mt-3"> {{ number_format($gain_phone_credit_days, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>


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
                <div class="font-medium text-historique mt-6">Inter</div>
                <div class="text-slate-500 mt-3">
                    {{ number_format($gain_inter_days, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
        </div>


        <div class="col-span-12 xl:col-span-6 mt-6">
            <div class="intro-y block sm:flex items-center h-10">
                <h2 class="text-lg font-medium truncate mr-5">
                    Les 10 derniers clients
                </h2>
            </div>
            <!-- BEGIN: Weekly Top Products -->
            <div class="intro-y col-span-12  lg:overflow-visible mt-3">
                <table class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap">Nom & prenom</th>
                            <th class="text-center whitespace-nowrap">Email</th>
                            <th class="text-center whitespace-nowrap">Pays/Ville</th>
                            <th class="text-center whitespace-nowrap">Catégorie</th>
                            <th class="text-center whitespace-nowrap">Utilisateur</th>
                            <th class="text-center whitespace-nowrap">Status compte </th>
                            <th class="text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->count() > 0)
                            @foreach ($users as $item)
                                <tr class="intro-x">
                                    <td>
                                        {{ $item->firstname }}
                                        {{ $item->lastname }}

                                        <span
                                            class="alert alert-secondary mt-2  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            Wallet : {{ $item->wallet->wallet_id ?? null }}
                                        </span>

                                    </td>
                                    <td>
                                        <div class="text-lg text-gray-800 font-semibold">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-4 py-2 text-indigo-500 bg-indigo-600 rounded">
                                                {{ $item->email ?? 'N/A' }}
                                            </span>
                                        </div>

                                    </td>

                                    <td>
                                        <h3 class="mb-3">
                                            Localisation : {{ $item->country->name ?? null }} -
                                            {{ $item->city->name ?? null }} - {{ $item->zone ?? null }}
                                        </h3>
                                        <h3>
                                            Numero : {{ $item->phone ?? null }}
                                        </h3>
                                    </td>
                                    <td>
                                        <span
                                            class="alert alert-secondary text-center inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $item->categorie->name ?? null }}
                                        </span>

                                    </td>
                                    <td>
                                        @if ($item->is_under_distributer)
                                            <div class="text-slate-500  mt-0.5">
                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    Sous-distributeur
                                                </span>
                                            </div>
                                        @else
                                            <div class="text-slate-500  mt-0.5">
                                                <span
                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    Client
                                                </span>
                                            </div>
                                        @endif

                                    </td>

                                    <td class="table-report__action w-35">

                                        @if ($item->company_id == firm()->company->id)
                                            <div
                                                class="text-center  border-t border-slate-200/60 dark:border-darkmode-400">

                                                @if ($item->is_accepted_companie == '1')
                                                    <div class="flex items-center justify-center text-success">
                                                        <a class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                            href="javascript:;" wire:loading.attr="disabled"
                                                            title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                            <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                                <div class="text-slate-300 text-xs  mt-0.5">
                                                                    Déja attribuer
                                                                </div>
                                                            </div>
                                                        </a>


                                                    </div>
                                                    <div class="flex items-center justify-center text-pending mt-3">
                                                        <a wire:click="edit({{ $item->wallet->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                            class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                            href="javascript:;" wire:loading.attr="disabled"
                                                            title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                            Rétirer l'employer
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center text-danger">
                                                        <a class="flex items-center mr-3 @if ($item->company_id == firm()->id) @else @endif"
                                                            href="javascript:;"
                                                            title="@if ($item->company_id == firm()->id) Désactiver @else Activer @endif">
                                                            <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                                <div class="text-slate-300 text-xs  mt-0.5">
                                                                    Attribution en attente
                                                                </div>
                                                            </div>

                                                        </a>

                                                    </div>
                                                @endif

                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">

                                            <a class="btn btn-primary  mr-1 mb-2 btn-sm"
                                                href="{{ route('employes.show', ['id' => $item->id]) }}">
                                                Voir
                                            </a>
                                        </div>
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
            <!-- END: Weekly Top Products -->
        </div>

        <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
            Statistique  des transactions sous distributeurs
        </div>
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="grid grid-cols-12 gap-6">

                <div class="col-span-12 2xl:col-span-6">
                    <div class="col-span-12 2xl:col-span-9">
                        <!-- BEGIN: Sales Report -->
                        <div class="col-span-12 sm:col-span-12 lg:col-span-8 mt-8">
                            <div class="intro-y block sm:flex items-center h-10">
                                <h2 class="text-lg font-medium truncate mr-5">
                                    Activités du jour {{ $user_currencie }}
                                </h2>
                            </div>
                            <div class="intro-y box p-5 mt-12 sm:mt-5">
                                <canvas id="sum" virement="{{ json_encode($amount_bank_days) }}"
                                    deposit="{{ json_encode($amount_deposit_days) }}"
                                    payment="{{ json_encode($amount_payment_days) }}"
                                    reload="{{ json_encode($amount_reload_days) }}"
                                    transaction="{{ json_encode($amount_transaction_days) }}"
                                    inter="{{ json_encode($amount_inter_days) }}"
                                    phone="{{ json_encode($amount_phone_credit_days) }}"></canvas>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-span-12 2xl:col-span-6">
                    <div class="col-span-12 2xl:col-span-9">
                        <!-- BEGIN: Sales Report -->
                        <div class="col-span-12 sm:col-span-12 lg:col-span-8 mt-8">
                            <div class="intro-y block sm:flex items-center h-10">
                                <h2 class="text-lg font-medium truncate mr-5">
                                    Activités du mois {{ $user_currencie }}
                                </h2>
                            </div>
                            <div class="intro-y box p-5 mt-12 sm:mt-5">
                                <canvas id="sum_month" virement_month="{{ json_encode($amount_bank_month) }}"
                                    deposit_month="{{ json_encode($amount_deposit_month) }}"
                                    payment_month="{{ json_encode($amount_payment_month) }}"
                                    reload_month="{{ json_encode($amount_reload_month) }}"
                                    transaction_month="{{ json_encode($amount_transaction_month_c) }}"
                                    inter_month="{{ json_encode($amount_inter_month) }}"
                                    phone_month="{{ json_encode($amount_phone_credit_month) }}"></canvas>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
            Statistique des paiements des employers
        </div>
    
        <div class="grid grid-cols-12 gap-6 mt-12">

            <div class="col-span-12 2xl:col-span-6">

                <!-- END: Sales Report -->
                <!-- BEGIN: Sales Report -->
                <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Les statistiques de l'année en paiement employés {{ $user_currencie }}
                        </h2>
                    </div>
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        <canvas id="reload" reload="{{ json_encode($realods) }}"></canvas>
                    </div>
                </div>
                <!-- END: Sales Report -->
            </div>

            <div class="col-span-12 2xl:col-span-6">


                <!-- END: Sales Report -->
                <!-- BEGIN: Sales Report -->
                <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Statistique des gains rechargement semaine {{ $user_currencie }}
                        </h2>
                    </div>
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        <canvas id="sum_paiement" sum_paiement="{{ json_encode($gainReloadEmployers) }}"></canvas>
                    </div>
                </div>
                <!-- END: Sales Report -->
            </div>

        </div>



    </div>

    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        setInterval(function() {
            const boxes = document.querySelectorAll('.style_app');
            boxes.forEach((box, index) => {
                const colors = ['red', 'blue', 'green',
                    '#6C0277', '#34C924', '#6600FF', '#F9429E'
                ]; // Ajoutez d'autres couleurs si nécessaire
                const color = colors[index % colors.length];
                box.style.backgroundColor = color;

            });
        }, 1000);
    </script>

    @include('components.statistique_home_all')


</div>
@include('livewire.modals.firms.updatemployers')
@include('livewire.modals.firms.disabledEmployers')
