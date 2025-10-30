<div>

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

        <div class="grid grid-cols-12 gap-6 mt-6">
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
                            {{ $walletSolde ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde {{ $user_currencie }} </div>
                    </div>
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
                        <div class="text-base text-slate-500 mt-1">Réchargements {{ $user_currencie }}</div>
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
                        <div class="text-base text-slate-500 mt-1">Crédits téléphoniques {{ $user_currencie }} </div>
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
                        <div class="text-base text-slate-500 mt-1">Transactions Banques {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="col-span-12 sm:col-span-6 lg:col-span-4 intro-y info-paiement-show" id="info-paie-show-1">
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
                        <div class="text-base text-slate-500 mt-1">
                            CIE : {{ $amount_payment_cie_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            CANAL : {{ $amount_payment_canal_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            SODECI : {{ $amount_payment_sodeci_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Total {{ $amount_payment_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Paiements {{ $user_currencie }}</div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y info-paiement-show" id="info-paie-show-2">
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
                        <div class="text-base text-slate-500 mt-1">
                            Robus : {{ $amount_transaction_robus_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Moov : {{ $amount_transaction_moov_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Wave : {{ $amount_transaction_wave_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Orange : {{ $amount_transaction_orange_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Mtn : {{ $amount_transaction_mtn_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Total {{ $amount_transaction_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Transactions {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y " id="info-paie-show-2">
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
                        <div class="text-base text-slate-500 mt-1">
                            Mobiles : {{ $amount_inter_moneyRobus ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Factures : {{ $amount_inter_cie ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Crédits : {{ $amount_inter_credit ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Virements : {{ $amount_inter_virement ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Rechargements : {{ $amount_inter_rechargement ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">
                            Total : {{ $amount_inter_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">International {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>


            <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet">
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
                        <div class="text-base text-slate-500 mt-1">Réchargement cartes {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_user3">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_xbet ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_xbet ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_xbet ?? null }}

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
                            {{ $amount_xbet_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Achat 1Xbets{{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-4 intro-y info-paiement-show" id="info-paie-show-2">
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
                        <div class="text-base text-slate-500 mt-1">Réchargement cartes {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">Cliquez pour vous rendre
            sur pages
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
                        xbet="{{ json_encode($sum_phoneCredit) }}"></canvas>

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
                        xbet_month="{{ json_encode($sum_xbet_month) }}"></canvas>
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
        var sum_v_month = JSON.parse($('#sum_month').attr('virement_month'));
        var sum_d_month = JSON.parse($('#sum_month').attr('deposit_month'));
        var sum_p_month = JSON.parse($('#sum_month').attr('payment_month'));
        var sum_r_month = JSON.parse($('#sum_month').attr('reload_month'));
        var sum_t_month = JSON.parse($('#sum_month').attr('transaction_month'));
        var sum_i_month = JSON.parse($('#sum_month').attr('inter_month'));
        var sum_phone_month = JSON.parse($('#sum_month').attr('phone_month'));
        var sum_1xbetw_month = JSON.parse($('#sum_month').attr('xbet_month'));
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
                ],
                datasets: [{

                    label: 'Activites du jours',
                    data: [sumVirement, sumDeposit, sumPayment, sumReload,
                        sumTransaction, sumInter,
                        sumPhone, sumXbet
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00',
                        '#0000FF',
                        '#ff0000'


                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00',
                        '#0000FF',
                        '#ff0000'

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
                    '1Xbet',
                    'Inters',
                ],
                datasets: [{

                    label: 'Activites du mois',
                    data: [sum_v_month, sum_d_month,
                        sum_p_month, sum_r_month, sum_t_month, sum_i_month, sum_phone_month,
                        sum_1xbetw_month
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00',
                        '#0000FF',
                        '#ff0000'

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00',
                        '#0000FF',
                        '#ff6384'

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
