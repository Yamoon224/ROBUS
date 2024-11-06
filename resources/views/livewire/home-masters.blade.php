<div>

    <div class="intro-y col-span-12 lg:col-span-12">

        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12 2xl:col-span-12">
                @php
                    $currentDayOfWeek = Illuminate\Support\Carbon::now()->dayOfWeek;
                @endphp
                @foreach ($open_apps as $index => $open)
                    <div class="col-span-12 xl:col-span-6 ml-3 mr-3 mb-3">
                        @if ($currentDayOfWeek == $open->id)
                            <h2 class="text-lg font-medium truncate items-center text-xs mt-3 mr-6 ml-6 text-center whitespace-nowrap blinking style_app text-white"
                                id="box_{{ $index }}">
                                Robus est ouvert ce {{ $open->day_of_week ?? null }} de :
                                {{ $open->time_start ?? null }} a {{ $open->time_end ?? null }}
                            </h2>
                        @endif
                    </div>
                @endforeach
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y style_table4">
                        <div class="report-box zoom-in style_table4">
                            <div class="box p-5 style_table4">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>

                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">
                                    {{ $custumers_counts ?? null }}
                                </div>
                                <div class="text-base text-slate-500 mt-1">Clients</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y style_table_cartes  ">
                        <div class="report-box zoom-in style_table_cartes   ">
                            <div class="box p-5 style_table_cartes  ">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>

                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">
                                    {{ $under_counts ?? null }}
                                </div>
                                <div class="text-base text-slate-500 mt-1">Sous distributeurs</div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
        <div class="intro-y block sm:flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Les Activites du compte Aujourdh'ui
            </h2>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">
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
                            {{ $sum_gain ?? null }}
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

        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="grid grid-cols-12 gap-6">

                <div class="col-span-12 2xl:col-span-9">
                    <div class="col-span-12 2xl:col-span-9">
                        <!-- BEGIN: Sales Report -->
                        <div class="col-span-12 sm:col-span-12 lg:col-span-8 mt-8">
                            <div class="intro-y block sm:flex items-center h-10">
                                <h2 class="text-lg font-medium truncate mr-5">
                                    Activités d'aujourd'hui en {{ $user_currencie }}
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
                    <div class="col-span-12 sm:col-span-8 lg:col-span-9 mt-8">
                        <div class="intro-y flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Les activités du mois en {{ $user_currencie }}
                            </h2>
                        </div>
                        <div class="intro-y box p-5 mt-12 sm:mt-5">
                            <div class="intro-y box p-5 mt-12 sm:mt-5">
                                <canvas id="sum_month" virement_month="{{ json_encode($amount_bank_month) }}"
                                    deposit_month="{{ json_encode($amount_deposit_month) }}"
                                    payment_month="{{ json_encode($amount_payment_month) }}"
                                    reload_month="{{ json_encode($amount_reload_month) }}"
                                    transaction_month="{{ json_encode($amount_transaction_month) }}"
                                    inter_month="{{ json_encode($amount_inter_month) }}"
                                    phone_month="{{ json_encode($amount_phone_credit_month) }}"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-span-12 2xl:col-span-3">
                    <div class="col-span-12 sm:col-span-12 lg:col-span-4 mt-8">
                        <div class="intro-y block sm:flex items-center h-10">
                            <h2 class="text-lg font-medium truncate mr-5">
                                Les jours d'ouverture et fermeture de ROBUS
                            </h2>
                        </div>

                        @php
                            $currentDayOfWeek = Illuminate\Support\Carbon::now()->dayOfWeek;
                        @endphp
                        @foreach ($open_apps as $index => $open)
                            <div class="col-span-12 xl:col-span-6 ml-3 mr-3 mb-3">
                                @if ($currentDayOfWeek == $open->id)
                                    <div class="box blinking style_app" id="box_{{ $index }}">
                                    @else
                                        <div class="box style_app" id="box_{{ $index }}">
                                @endif
                                <div class="flex flex-col items-center p-3 border-b border-slate-200/60 dark:border-darkmode-400"
                                    style="overflow-x: auto;">
                                    <h2 class="text-lg font-medium truncate mr-5 text-white">
                                        {{ $open->day_of_week ?? null }}
                                    </h2>
                                </div>
                                <div class="flex text-slate-500 text-xs mt-3 mr-6 ml-6">
                                    <div class="mr-auto text-white">Ouverture : {{ $open->time_start ?? null }}
                                    </div>
                                </div>
                                <div class="flex text-slate-500 text-xs mt-3 mr-6 ml-6 mb-3">
                                    <div class="mr-auto text-white">Fermeture : {{ $open->time_end ?? null }}
                                    </div>
                                </div>
                            </div>
                    </div>
                    @endforeach

                </div>
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
