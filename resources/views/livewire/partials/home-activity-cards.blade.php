        <div class="grid grid-cols-12 gap-6 mt-6">

            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" style="display:flex;">
                <div class="report-box zoom-in" style="width:100%;height:100%;">
                    <div class="box p-wallet style_facture_home3" style="height:100%;">


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
                        <div class="flex flex-wrap gap-2 mb-transactions">
                            <div class="report-box__indicator bg-success tooltip cursor-pointer">
                                Succès Mobile Zone {{ $pixel_mobile_zone_success ?? 0 }}
                            </div>

                            <div class="report-box__indicator bg-pending tooltip cursor-pointer">
                                Attente Mobile Zone {{ $pixel_mobile_zone_pendding ?? 0 }}
                            </div>

                            <div class="report-box__indicator bg-danger tooltip cursor-pointer">
                                Echec Mobile Zone {{ $pixel_mobile_zone_error ?? 0 }}
                            </div>

                            <div class="report-box__indicator bg-warning tooltip cursor-pointer">
                                Remboursement Mobile Zone {{ $pixel_mobile_zone_refund ?? 0 }}
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
                                <div class="text-xs text-slate-500">{{ $count_transaction_moov_days ?? 0 }} transaction(s)</div>
                            </div>
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_wave_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Wave {{ $user_currencie }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $count_transaction_wave_days ?? 0 }} transaction(s)</div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_orange_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Orange {{ $user_currencie }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $count_transaction_orange_days ?? 0 }} transaction(s)</div>
                            </div>

                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_mtn_days ?? null }}
                                </div>
                                <div class="text-base text-slate-500">Mtn {{ $user_currencie }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $count_transaction_mtn_days ?? 0 }} transaction(s)</div>
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

                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $count_transaction_direct_days ?? 0 }}
                                </div>
                                <div class="text-base text-slate-500">Nombre de transactions directes {{ $periodLabel ?? 'du jour' }}
                                </div>
                            </div>
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_transaction_direct_days ?? 0 }}
                                </div>
                                <div class="text-base text-slate-500">Montant total direct (Orange, Mtn, Moov, Wave)
                                    {{ $user_currencie }}</div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" style="display:flex;">
                <div class="report-box zoom-in" style="width:100%;height:100%;">
                    <div class="box p-wallet style_table4" style="height:100%;">


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

            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" style="display:flex;">
                <div class="report-box zoom-in" style="width:100%;height:100%;">
                    <div class="box p-wallet style_table2" style="height:100%;">


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

            <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y info-paiement-show" style="display:flex;">
                <div class="report-box zoom-in" style="width:100%;height:100%;">
                    <div class="box p-wallet style_facture_home2" style="height:100%;">


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
                        <div class="flex flex-wrap items-center gap-4 mt-3">
                            <div class="flex flex-col items-start">
                                <div class="text-3xl font-medium leading-8">
                                    {{ $amount_pixel_mobile_zone_days ?? 0 }}
                                </div>
                                <div class="text-base text-slate-500">Mobile Zone {{ $user_currencie }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
