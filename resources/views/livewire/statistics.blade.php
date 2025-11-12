    <div>


        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <!-- BEGIN: Ticket -->
            <div class="col-span-12 lg:col-span-4">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Activites d'aujourd'hui
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        {{-- <div class="report-chart"> --}}
                        <canvas id="sum" virement="{{ json_encode($sum_bank) }}"
                            deposit="{{ json_encode($sum_deposit) }}" payment="{{ json_encode($sum_payment) }}"
                            transaction="{{ json_encode($sum_transaction) }}" inter="{{ json_encode($sum_inter) }}"
                            phone="{{ json_encode($sum_phoneCredit) }}" xbet="{{ json_encode($sum_phoneCredit) }}"
                            push="{{ json_encode($sum_push) }}" djamo="{{ json_encode($sum_djamo) }}"
                            versment="{{ json_encode($sum_versment) }}"
                            tresort="{{ json_encode($sum_tresort) }}"></canvas>


                        {{-- </div> --}}
                    </div>
                </div>
            </div>
            <!-- END: Ticket -->
            <!-- BEGIN: Item List -->
            <div class="intro-y col-span-12 lg:col-span-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les gains d'aujourd'hui en {{ $usersCountry }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div class="intro-y box p-5 mt-12 sm:mt-5">
                        <canvas id="gain" virement_gain="{{ json_encode($sum_bank_gain) }}"
                            deposit_gain="{{ json_encode($sum_deposit_gain) }}"
                            payment_gain="{{ json_encode($sum_payment_gain) }}"
                            transaction_gain="{{ json_encode($sum_transaction_gain) }}"
                            inter_gain="{{ json_encode($sum_inter_gain) }}"
                            phone_gain="{{ json_encode($sum_phoneCredit_gain) }}"
                            xbet_gain="{{ json_encode($sum_xbet_gain) }}"
                            push_gain="{{ json_encode($sum_push_gain) }}"
                            djamo_gain="{{ json_encode($sum_djamo_gain) }}"
                            versment_gain="{{ json_encode($sum_versment_gain) }}"
                            tresort_gain="{{ json_encode($sum_tresort_gain) }}"></canvas>

                    </div>
                </div>
            </div>

        </div>
        <!-- END: Item List -->
        <div class="intro-y block sm:flex items-center h-10 text-center">
            <h2 class="text-lg font-medium truncate mr-5 text-center">
                Les statistiques par activites
            </h2>
        </div>
        <div class="intro-y grid grid-cols-12 gap-5 mt-5">


            <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #ff0000; color:white;">

                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #ff0000; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Rechargement</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Reloads_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('relaods.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #fe6383; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #fe6383; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Transfers d'argent</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Transactions_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('transfers.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="arrow-right" data-lucide="arrow-right"
                                class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 ">
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Paiement de facture</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Payments_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('paiements.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #84cc17; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #84cc17; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Versement bancaire</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($wallet_Banks_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('virements.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #b5048f; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #b5048f; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Transfert Internationnal</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Inters_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('inters.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #f97417; color:white;">
                <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #f97417; color:white;">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Rechargement de carte</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_Deposits_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('cartes.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1">
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                        <div>Credit telephonique</div>
                        <div class="ml-auto">Date de creation</div>
                    </div>
                    @foreach ($Wallet_PhoneCredits_last as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $reload->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $reload->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a href="{{ route('phones.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300 dark:border-darkmode-300 border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 style_user1">
                <div class="intro-y box p-5 mt-12 sm:mt-5 style_user1">
                    <div
                        class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                        <div>Service 1xbets</div>
                        <div class="ml-auto">Date</div>
                    </div>
                    @foreach ($Wallet_xbet_last as $xbet)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center">
                                <div>{{ $xbet->provider->name ?? null }}</div>

                            </div>
                            <div class="ml-auto">{{ $xbet->created_at ?? null }}</div>
                        </div>
                    @endforeach
                    <a style="color: white;" href="{{ route('xbets.states') }}"
                        class="btn btn-outline-secondary w-full border-slate-300  border-dashed relative justify-start mb-2">
                        <span class="truncate mr-5">Voir plus</span>
                        <span
                            class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>

                 <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 style_table_cartes">
                    <div class="intro-y box p-5 mt-12 sm:mt-5 style_table_cartes">
                        <div
                            class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                            <div>Service Versements</div>
                            <div class="ml-auto">Date</div>
                        </div>
                        @foreach ($Wallet_virement_last as $virements)
                            <div class="flex items-center mb-5">
                                <div class="flex items-center">
                                    <div>{{ $virements->provider->name ?? null }}</div>

                                </div>
                                <div class="ml-auto">{{ $virements->created_at ?? null }}</div>
                            </div>
                        @endforeach
                        <a style="color: white;" href="{{ route('versments.states') }}"
                            class="btn btn-outline-secondary w-full border-slate-300  border-dashed relative justify-start mb-2">
                            <span class="truncate mr-5">Voir plus</span>
                            <span
                                class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                    data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 style_table_transfers">
                    <div class="intro-y box p-5 mt-12 sm:mt-5 style_table_transfers">
                        <div
                            class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                            <div>Service Push</div>
                            <div class="ml-auto">Date</div>
                        </div>
                        @foreach ($Wallet_push_last as $push)
                            <div class="flex items-center mb-5">
                                <div class="flex items-center">
                                    <div>{{ $push->provider->name ?? null }}</div>

                                </div>
                                <div class="ml-auto">{{ $push->created_at ?? null }}</div>
                            </div>
                        @endforeach
                        <a style="color: white;" href="{{ route('pushs.states') }}"
                            class="btn btn-outline-secondary w-full border-slate-300  border-dashed relative justify-start mb-2">
                            <span class="truncate mr-5">Voir plus</span>
                            <span
                                class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                    data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 style_table3">
                    <div class="intro-y box p-5 mt-12 sm:mt-5 style_table3">
                        <div
                            class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                            <div>Service Trésort Money</div>
                            <div class="ml-auto">Date</div>
                        </div>
                        @foreach ($Wallet_tresort_last as $tresorts)
                            <div class="flex items-center mb-5">
                                <div class="flex items-center">
                                    <div>{{ $tresorts->provider->name ?? null }}</div>

                                </div>
                                <div class="ml-auto">{{ $tresorts->created_at ?? null }}</div>
                            </div>
                        @endforeach
                        <a style="color: white;" href="{{ route('tresorts.states') }}"
                            class="btn btn-outline-secondary w-full border-slate-300  border-dashed relative justify-start mb-2">
                            <span class="truncate mr-5">Voir plus</span>
                            <span
                                class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                    data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1">
                    <div class="intro-y box p-5 mt-12 sm:mt-5 ">
                        <div
                            class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                            <div>Service Djamo</div>
                            <div class="ml-auto">Date</div>
                        </div>
                        @foreach ($Wallet_djamo_last as $djamo)
                            <div class="flex items-center mb-5">
                                <div class="flex items-center">
                                    <div>{{ $djamo->provider->name ?? null }}</div>

                                </div>
                                <div class="ml-auto">{{ $djamo->created_at ?? null }}</div>
                            </div>
                        @endforeach
                        <a href="{{ route('djamos.states') }}"
                            class="btn btn-outline-secondary text-black w-full border-slate-300  border-dashed relative justify-start mb-2">
                            <span class="truncate mr-5 text-black">Voir plus</span>
                            <span
                                class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" icon-name="arrow-right"
                                    data-lucide="arrow-right" class="lucide lucide-arrow-right w-4 h-4">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
        </div>
        @stack('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var sumVirement = JSON.parse($('#sum').attr('virement'));
            var sumDeposit = JSON.parse($('#sum').attr('deposit'));
            var sumPayment = JSON.parse($('#sum').attr('payment'));
            var sumTransaction = JSON.parse($('#sum').attr('transaction'));
            var sumInter = JSON.parse($('#sum').attr('inter'));
            var sumPhone = JSON.parse($('#sum').attr('phone'));
            var sumXbet = JSON.parse($('#sum').attr('xbet'));
            var sumPush = JSON.parse($('#sum').attr('push'));
            var sumDjamo = JSON.parse($('#sum').attr('djamo'));
            var sumVersment = JSON.parse($('#sum').attr('versment'));
            var sumTresort = JSON.parse($('#sum').attr('tresort'));

            var sumVirementGain = JSON.parse($('#gain').attr('virement_gain'));
            var sumDepositGain = JSON.parse($('#gain').attr('deposit_gain'));
            var sumPaymentGain = JSON.parse($('#gain').attr('payment_gain'));
            var sumTransactionGain = JSON.parse($('#gain').attr('transaction_gain'));
            var sumInterGain = JSON.parse($('#gain').attr('inter_gain'));
            var sumPhoneGain = JSON.parse($('#gain').attr('phone_gain'));
            var sumDjamoGain = JSON.parse($('#gain').attr('djamo_gain'));
            var sumPushGain = JSON.parse($('#gain').attr('push_gain'));
            var sumXbetGain = JSON.parse($('#gain').attr('xbet_gain'));
            var sumVermentGain = JSON.parse($('#gain').attr('versment_gain'));
            var sumTresortGain = JSON.parse($('#gain').attr('tresort_gain'));

            var _ctx2 = $('#gain');
            var _ctx = $('#sum');
            new Chart(_ctx, {
                type: 'pie',
                data: {
                    labels: [
                        'Virements',
                        'Cartes',
                        'Paiements',
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

                        label: 'Activites',
                        data: [sumVirement, sumDeposit, sumPayment,
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
                        label: 'Statisque des gains d\'aujourd\'hui',
                        data: [sumVirementGain, sumDepositGain, sumPaymentGain,
                            sumTransactionGain, sumInterGain,
                            sumPhoneGain, sumDjamoGain, sumPushGain, sumXbetGain,
                            sumVermentGain, sumTresortGain
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
