<div>

    <div class="intro-y block sm:flex items-center h-10 mt-10">
        <h2 class="text-lg font-medium truncate mr-5">
            Liste des classements par Services
        </h2>
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

                <a style="color: white;" href="{{ route('relaods.historical') }}"
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
                <a style="color: white;" href="{{ route('paiements.historical') }}"
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
                <a style="color: white;" href="{{ route('virements.historical') }}"
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
                <a style="color: white;" href="{{ route('inters.historical') }}"
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
                <a style="color: white;" href="{{ route('cartes.historical') }}"
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
                <a href="{{ route('phones.historical') }}"
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
