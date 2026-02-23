<div>



    <!-- Version améliorée avec design moderne -->
    <div class="text-center mb-10 py-8 px-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl border border-blue-100 shadow-sm">
        <!-- Badge d'information -->
        <div
            class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full border border-blue-200 shadow-sm mb-6">
            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
            <span class="text-sm font-medium text-blue-700">Classement en temps réel</span>
        </div>

        <!-- Titre principal avec effet dégradé -->
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
            <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 bg-clip-text text-transparent">
                🏆 Classement des Sous-Distributeurs
            </span>
        </h1>

        <!-- Description améliorée -->
        <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed mb-6">
            Découvrez le <span class="font-bold text-blue-600">Top </span> des sous-distributeurs les plus performants
            selon le volume du montant et leur impact global.
        </p>

        <!-- Statistiques rapides -->
        <div class="flex flex-wrap justify-center gap-6 mb-6">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm font-medium text-gray-700">Mise à jour quotidienne</span>
            </div>
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Données certifiées</span>
            </div>
        </div>

    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">


        <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #ff0000; color:white;">

            <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #ff0000; color:white;">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Transfert d'argent</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_transactions as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse

                <a style="color: white;" href="{{ route('rankings.browse_transactions') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 style_user1">
            <div class="intro-y box p-5 mt-12 sm:mt-5 style_user1">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Paiement de facture</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_paiements as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_paiments') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #84cc17; color:white;">
            <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #84cc17; color:white;">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Virements bancaire</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_virements as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_virments') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #b5048f; color:white;">
            <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #b5048f; color:white;">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Transfert Internationnal</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_Inters as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_inters') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #f97417; color:white;">
            <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #f97417; color:white;">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Rechargement de carte</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_reloadCards as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_reloads_cards') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1">
            <div class="intro-y box p-5 mt-12 sm:mt-5">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Credit telephonique</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_creditPhone as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a href="{{ route('rankings.browse_phone_credits') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1 style_user2">
            <div class="intro-y box p-5 mt-12 sm:mt-5 style_user2">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3 v">
                    <div>Service Bank send</div>
                    <div class="ml-auto">Date</div>
                </div>
                @forelse ($classment_bank_sends as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_bank_sends') }}"
                    class="btn btn-outline-secondary w-full border-slate-300  border-dashed relative justify-start mb-2">
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

        {{-- New transactions --}}

          <div class="col-span-12 md:col-span-6 lg:col-span-4 " style="background-color: #ff0000; color:white;">

            <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #ff0000; color:white;">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Transfert Push</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_pushs as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse

                <a style="color: white;" href="{{ route('rankings.browse_transaction_push') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 style_user1">
            <div class="intro-y box p-5 mt-12 sm:mt-5 style_user1">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Transaction tresort Money</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_tresortMoneys as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_transaction_tresorts') }}"
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
        <div class="col-span-12 md:col-span-6 lg:col-span-4 mt-1" style="background-color: #84cc17; color:white;">
            <div class="intro-y box p-5 mt-12 sm:mt-5" style="background-color: #84cc17; color:white;">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Transaction Djamo</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_djamos as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_transaction_djamos') }}"
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
         <div class="col-span-12 md:col-span-6 lg:col-span-4 style_user1">
            <div class="intro-y box p-5 mt-12 sm:mt-5 style_user1">
                <div
                    class="flex text-slate-500 border-b border-slate-200 dark:border-darkmode-300 border-dashed pb-3 mb-3">
                    <div>Transaction 1Xbets</div>
                    <div class="ml-auto">Date de creation</div>
                </div>
                @forelse ($classment_xbets as $country => $subDistributors)
                    <h3 class="text-white font-semibold mb-2">{{ $country }}</h3>

                    @foreach ($subDistributors as $reload)
                        <div class="flex items-center mb-5">
                            <div class="flex items-center space-x-2">
                                <div class="font-medium">
                                    {{ $reload['provider'] ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                {{ $reload['last_transaction_date'] ?? 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-4 text-white">
                        Aucun rechargement disponible pour le moment.
                    </div>
                @endforelse
                <a style="color: white;" href="{{ route('rankings.browse_transaction_xbets') }}"
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
    </div>
    @stack('scripts')
</div>
