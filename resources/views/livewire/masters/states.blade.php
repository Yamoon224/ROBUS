<div>


    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
            Gains des transactions d'aujourd'hui en {{ $user_currencie }}
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
                            {{ $sum_gain ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Gain Total {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet">
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
                            {{ $gain_transaction_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Transactions {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table4">
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
                            {{ $gain_phone_credit_days ?? null }}
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
                            {{ $gain_inter_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">International {{ $user_currencie }} </div>
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
                                    {{ $success_ordercard ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_ordercard ?? null }}

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
                            {{ $gain_ordercard_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Commande carte {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>


            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_cartes">
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
                            {{ $gain_bank_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Banque {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
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
                                    {{ $pendding_deposit ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_deposit ?? null }}

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
                            {{ $gain_deposit_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Rechargements {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y info-paiement-show" id="info-paie-show-2">
                <div class="report-box zoom-in">
                    <div class="box p-wallet style_table_credits">
                        <div class="flex mb-transactions">
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                    Success
                                    {{ $success_payment ?? null }}

                                </div>
                            </div>
                            <div class="ml-auto">

                                <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                    Attente
                                    {{ $pendding_payment ?? null }}

                                </div>

                            </div>
                            <div class="ml-auto">
                                <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                    Echec
                                    {{ $error_payment ?? null }}

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
                            {{ $gain_payment_days ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Paiements {{ $user_currencie }} </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
            Gains des transactions du mois
        </div>

        <div class="intro-y grid grid-cols-12 gap-5 mt-5">


            <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in style-pendd">
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
                    {{ number_format($gain_transaction_month, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>

            </div>
            <div
                class="col-span-12 sm:col-span-4 2xl:col-span-4 box bg-primary p-5 cursor-pointer zoom-in style-success">
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
                    {{ number_format($gain_payment_month, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-4 box p-5 cursor-pointer zoom-in style-error ">
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
                    {{ number_format($gain_deposit_month, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Banque</div>


                <div class="text-slate-500 mt-3">
                    {{ number_format($gain_bank_month, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_virements">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Credits</div>
                <div class="text-slate-500 mt-3"> {{ number_format($gain_phone_credit_month, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>


            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in">
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
                    {{ number_format($gain_inter_month, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 box p-5 cursor-pointer zoom-in">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" icon-name="credit-card" data-lucide="credit-card"
                    class="lucide lucide-credit-card report-box__icon text-secondary">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2">
                    </rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Commande</div>
                <div class="text-slate-500 mt-3">
                    {{ number_format($gain_ordercard_month, 2, ',', ' ') ?? null }}
                    {{ $user_currencie }}</div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-6">

            <!-- BEGIN: Sales Report -->
            <div class="intro-y col-span-12 lg:col-span-6">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les gains d'aujourd'hui en {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        <canvas id="gaindays" virement_gain="{{ json_encode($gain_bank_days_state) }}"
                            deposit_gain="{{ json_encode($gain_deposit_days_state) }}"
                            payment_gain="{{ json_encode($gain_payment_days_state) }}"
                            transaction_gain="{{ json_encode($gain_transaction_days_state) }}"
                            inter_gain="{{ json_encode($gain_inter_days_state) }}"
                            ordercard_gain="{{ json_encode($gain_ordercard_days_state) }}"
                            phone_gain="{{ json_encode($gain_phone_credit_days_state) }}"></canvas>
                    </div>
                </div>
            </div>
            <!-- END: Sales Report -->
            <div class="col-span-12 sm:col-span-12 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les gain du mois en {{ $user_currencie }}
                    </h2>
                </div>

                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum_month" virement_month="{{ json_encode($gain_bank_month_state) }}"
                        deposit_month="{{ json_encode($gain_deposit_month_state) }}"
                        payment_month="{{ json_encode($gain_payment_month_state) }}"
                        transaction_month="{{ json_encode($gain_transaction_month_state) }}"
                        inter_month="{{ json_encode($gain_inter_month_state) }}"
                        order_month="{{ json_encode($gain_ordercard_month_state) }}"
                        phone_month="{{ json_encode($gain_phone_credit_month_state) }}"></canvas>
                </div>
            </div>
            <!-- END: Sales Report -->
        </div>
        {{-- <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
            Statistique Gain de la semaine
        </div>

        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de la semaine paiements facture {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="weeks" weeks="{{ json_encode($gain_payment_month_state) }}"></canvas>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de la semaine transactions {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="weeks" weeks="{{ json_encode($gain_transaction_days_state) }}"></canvas>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de la semaine credits telephonique {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="weeks" weeks="{{ json_encode($gain_phone_credit_days_state) }}"></canvas>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de la semaine virements {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="weeks" weeks="{{ json_encode($gain_bank_days_state) }}"></canvas>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de la semaine réchargement de carte {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="weeks" weeks="{{ json_encode($gain_deposit_days_state) }}"></canvas>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de la semaine International {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="weeks" weeks="{{ json_encode($gain_inter_days_state) }}"></canvas>
                </div>
            </div>
        </div> --}}
        <div class="alert alert-dark   show mb-2 ml-6 mr-6 text-center mt-6" role="alert">
            Statistique Gain de l'année
        </div>

        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de l'année paiements facture {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="yearstatef" yearpaiement="{{ json_encode($gain_payment_month_state) }}"></canvas>

                </div>
            </div>
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de l'année transactions {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="yearstatet" yeartransaction="{{ json_encode($gain_transaction_month_state) }}"></canvas>

                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de l'année credits telephonique {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="yearstatec" yearcredit="{{ json_encode($gain_phone_credit_month_state) }}"></canvas>

                </div>
            </div>
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de l'année virements {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="yearstatev" yearvirement="{{ json_encode($gain_bank_month_state) }}"></canvas>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de l'année réchargement de carte {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="yearstater" yearrechargment="{{ json_encode($gain_deposit_month_state) }}"></canvas>

                </div>
            </div>
            <div class="col-span-12 sm:col-span-8 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de l'année International {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="yearstatei" yearinter="{{ json_encode($gain_inter_month_state) }}"></canvas>

                </div>
            </div>

            <div class="col-span-12 sm:col-span-8 lg:col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Gain de l'année commande de carte {{ $user_currencie }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="yearstateo" yearorder="{{ json_encode($gain_ordercard_month_state) }}"></canvas>

                </div>
            </div>
        </div>

    </div>
    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        // days month
        var sum_v_month = JSON.parse($('#sum_month').attr('virement_month'));
        var sum_d_month = JSON.parse($('#sum_month').attr('deposit_month'));
        var sum_p_month = JSON.parse($('#sum_month').attr('payment_month'));
        var sum_order_month = JSON.parse($('#sum_month').attr('order_month'));
        var sum_t_month = JSON.parse($('#sum_month').attr('transaction_month'));
        var sum_i_month = JSON.parse($('#sum_month').attr('inter_month'));
        var sum_phone_month = JSON.parse($('#sum_month').attr('phone_month'));

        // days gain
        var sumVirementGain = JSON.parse($('#gaindays').attr('virement_gain'));
        var sumDepositGain = JSON.parse($('#gaindays').attr('deposit_gain'));
        var sumPaymentGain = JSON.parse($('#gaindays').attr('payment_gain'));
        var sumTransactionGain = JSON.parse($('#gaindays').attr('transaction_gain'));
        var sumInterGain = JSON.parse($('#gaindays').attr('inter_gain'));
        var sumPhoneGain = JSON.parse($('#gaindays').attr('phone_gain'));
        var sumOrderCardGain = JSON.parse($('#gaindays').attr('ordercard_gain'));

        // Year
        var state_year = JSON.parse($('#yearstatev').attr('yearvirement'));
        var state_year_f = JSON.parse($('#yearstatef').attr('yearpaiement'));
        var state_year_t = JSON.parse($('#yearstatet').attr('yeartransaction'));
        var state_year_c = JSON.parse($('#yearstatec').attr('yearcredit'));
        var state_year_r = JSON.parse($('#yearstater').attr('yearrechargment'));
        var state_year_i = JSON.parse($('#yearstatei').attr('yearinter'));
        var state_year_o = JSON.parse($('#yearstateo').attr('yearorder'));

        // Déclaration des couleurs de fond et des bordures
        const COLORS = [
            'rgba(255, 99, 132,1)',
            'rgba(255, 159, 64,1)',
            'rgba(255, 205, 86,1)',
            'rgba(75, 192, 192,1)',
            'rgba(54, 162, 235,1)',
            'rgba(153, 102, 255,1)',
            'rgba(201, 203, 207,1)',
            'rgba(255, 0, 132,1)',
            'rgba(255, 159, 64,1)',
            'rgba(255, 205, 86,1)',
            'rgba(75, 192, 192,1)',
            'rgba(54, 162, 235,1)',
        ];

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                labels: {
                    render: 'percentage',
                    fontColor: ['white', 'white'],
                }
            },
        };

        var _ctx3 = $('#yearstatev');
        var _ctx4 = $('#yearstatef');
        var _ctx5 = $('#yearstatet');
        var _ctx6 = $('#yearstatec');
        var _ctx7 = $('#yearstater');
        var _ctx8 = $('#yearstatei');
        var _ctx9 = $('#yearstateo');
        var _ctx2 = $('#gaindays');
        var _ctx10 = $('#sum_month');

        new Chart(_ctx10, {
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
                    'Commande carte'
                ],
                datasets: [{

                    label: 'Montant statistique de gains ',
                    data: [sum_v_month, sum_d_month, sum_p_month, sum_t_month,
                    sum_i_month,
                    sum_phone_month, sum_order_month
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
                    ],
                    borderWidth: 4,
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
                    'Commande carte'
                ],
                datasets: [{

                    label: 'Montant statistique de gains ',
                    data: [sumVirementGain, sumDepositGain, sumPaymentGain, sumTransactionGain,
                        sumInterGain,
                        sumPhoneGain, sumOrderCardGain
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
                    ],
                    borderWidth: 4,
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

        new Chart(_ctx3, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Gain de la transactions',
                    data: state_year,
                    backgroundColor: COLORS,
                    borderColor: COLORS,
                    borderWidth: 1,
                }]
            },
            options: chartOptions
        });

        new Chart(_ctx4, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Gain de la transactions',
                    data: state_year_f,
                    backgroundColor: COLORS,
                    borderColor: COLORS,
                    borderWidth: 1,
                }]
            },
            options: chartOptions
        });

        new Chart(_ctx5, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Gain de la transactions',
                    data: state_year_t,
                    backgroundColor: COLORS,
                    borderColor: COLORS,
                    borderWidth: 1,
                }]
            },
            options: chartOptions
        });

        new Chart(_ctx6, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Gain de la transactions',
                    data: state_year_c,
                    backgroundColor: COLORS,
                    borderColor: COLORS,
                    borderWidth: 1,
                }]
            },
            options: chartOptions
        });

        new Chart(_ctx7, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Gain de la transactions',
                    data: state_year_r,
                    backgroundColor: COLORS,
                    borderColor: COLORS,
                    borderWidth: 1,
                }]
            },
            options: chartOptions
        });
        new Chart(_ctx8, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Gain de la transactions',
                    data: state_year_i,
                    backgroundColor: COLORS,
                    borderColor: COLORS,
                    borderWidth: 1,
                }]
            },
            options: chartOptions
        });

        new Chart(_ctx9, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Gain de la transactions',
                    data: state_year_o,
                    backgroundColor: COLORS,
                    borderColor: COLORS,
                    borderWidth: 1,
                }]
            },
            options: chartOptions
        });
    </script>
</div>
