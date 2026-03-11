@php
    $is_under = sabdy()->is_under_distributer;
@endphp


<ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center mb-3" role="tablist">

    @if (sabdy()->country->code == 'CI')

        {{-- Sous-agents & distributeurs $is_under  --}}
        @if ($is_under || $is_distributer)

            {{-- New Updated  --}}

            @if ($showDepotButton && sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-400 bg-white text-gray-600 
              hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-500 hover:border-3 
              transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center"
                        id="transfers-nav" data-tw-target="#transfers" aria-controls="transfers" aria-selected="false"
                        role="tab">
                        <svg class="w-5 h-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="nav-label"> Transferts d'argent
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $transactions->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showPaiementButton && sabdy()->hasPermissionTo('paiement-factures'))
                <li id="paiement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2  border-gray-400 bg-white text-gray-600 
                      hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-500 hover:border-3 
                       transition-all duration-200 ease-in-out shadow-sm nav-link nav-link-green py-4 items-center"
                        id="paiement-nav" data-tw-target="#paiement" aria-controls="paiement" aria-selected="false"
                        role="tab">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="nav-label"> Paiements
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $paiements->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if ($showVirementButton && sabdy()->hasPermissionTo('virement-compte'))
                <li id="virement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="virement-nav" data-tw-target="#virement" aria-controls="virement" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="nav-label"> Virements
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $virements->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showRechargementButton && sabdy()->hasPermissionTo('rechargement-carte'))
                <li id="carte-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="carte-nav" data-tw-target="#carte" aria-controls="carte" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="nav-label"> Réchargement carte
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $reloadCards->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showCreditButton && sabdy()->hasPermissionTo('rechargement-credit'))
                <li id="phone-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="phone-nav" data-tw-target="#phone" aria-controls="phone" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="nav-label"> Crédit téléphonique
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $phoneCredits->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            {{-- @if ($showTransacInterButton && sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="inter-nav" data-tw-target="#inter" aria-controls="inter" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                        </svg>
                        <span class="nav-label"> International
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $Internationals->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif --}}

            @if ($showXbetButton && sabdy()->hasPermissionTo('rechargement-xbets'))
                <li id="xbet-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="xbet-nav" data-tw-target="#xbet" aria-controls="xbet" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="nav-label"> 1Xbets
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $xbets->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showVersementButton && sabdy()->hasPermissionTo('rechargement-versements'))
                <li id="versment-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="versment-nav" data-tw-target="#versment" aria-controls="versment" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        <span class="nav-label"> Versements
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $versements->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showBankSendButton && sabdy()->hasPermissionTo('rechargement-bank-sends'))
                <li id="bank-send-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="bank-send-nav" data-tw-target="#bank-send" aria-controls="bank-send"
                        aria-selected="false" role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="nav-label"> Bank send
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $bankSends->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showPushButton && sabdy()->hasPermissionTo('rechargement-push'))
                <li id="push-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="push-nav" data-tw-target="#push" aria-controls="push" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <span class="nav-label"> Push
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $pushs->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showDjamoButton && sabdy()->hasPermissionTo('rechargement-tresort'))
                <li id="tresort-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="tresort-nav" data-tw-target="#tresort" aria-controls="tresort" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="nav-label">
                            Trésort Money
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $tresortMoneys->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showDjamoButton && sabdy()->hasPermissionTo('rechargement-djamo'))
                <li id="djamo-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="djamo-nav" data-tw-target="#djamo" aria-controls="djamo" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="nav-label"> Djamo
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $djamos->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif
        @else
            {{-- Admin --}}
            @if (sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="transfers-nav" data-tw-target="#transfers" aria-controls="transfers"
                        aria-selected="false" role="tab">
                        <svg class="w-5 h-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="nav-label font-medium"> Transferts d'argent
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $transactions->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('paiement-factures'))
                <li id="paiement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="paiement-nav" data-tw-target="#paiement" aria-controls="paiement" aria-selected="false"
                        role="tab">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="nav-label"> Paiements
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $paiements->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('virement-compte'))
                <li id="virement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="virement-nav" data-tw-target="#virement" aria-controls="virement" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="nav-label"> Virements
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $virements->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-carte'))
                <li id="carte-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="carte-nav" data-tw-target="#carte" aria-controls="carte" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="nav-label"> Réchargement carte
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $reloadCards->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-credit'))
                <li id="phone-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="phone-nav" data-tw-target="#phone" aria-controls="phone" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="nav-label"> Crédit téléphonique
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $phoneCredits->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="inter-nav" data-tw-target="#inter" aria-controls="inter" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                        </svg>
                        <span class="nav-label"> International
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $Internationals->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-xbets'))
                <li id="xbet-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="xbet-nav" data-tw-target="#xbet" aria-controls="xbet" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="nav-label"> 1Xbets
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $xbets->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-versements'))
                <li id="versment-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="versment-nav" data-tw-target="#versment" aria-controls="versment" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        <span class="nav-label"> Versements
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $versements->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-bank-sends'))
                <li id="bank-send-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="bank-send-nav" data-tw-target="#bank-send" aria-controls="bank-send"
                        aria-selected="false" role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="nav-label"> Bank send
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $bankSends->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-push'))
                <li id="push-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="push-nav" data-tw-target="#push" aria-controls="push" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        <span class="nav-label"> Push
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $pushs->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-tresort'))
                <li id="tresort-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="tresort-nav" data-tw-target="#tresort" aria-controls="tresort" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="nav-label">
                            Trésort Money
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $tresortMoneys->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-djamo'))
                <li id="djamo-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="djamo-nav" data-tw-target="#djamo" aria-controls="djamo" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="nav-label"> Djamo
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $djamos->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

        @endif
    @else
        {{-- Autres pays $is_under --}}
        @if ($is_under || $is_distributer)
            @if ($showDepotButton && sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="transfers-nav" data-tw-target="#transfers" aria-controls="transfers"
                        aria-selected="false" role="tab">
                        <svg class="w-5 h-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="nav-label"> Transferts d'argent
                        </span>
                          <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $transactions->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif

            {{-- @if ($showTransacInterButton && sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="inter-nav" data-tw-target="#inter" aria-controls="inter" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                        </svg>
                        <span class="nav-label"> International
                        </span>
                         <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $Internationals->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif --}}
        @else
            {{-- Admin autres pays --}}
            @if (sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="transfers-nav" data-tw-target="#transfers" aria-controls="transfers"
                        aria-selected="false" role="tab">
                        <svg class="w-5 h-5 " fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        <span class="nav-label"> Transferts d'argent
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $transactions->count() }} éléments
                        </span>
                    </a>
                </li>
            @endif
            {{-- @if (sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;"
                        class="flex items-center px-4 py-2 mt-3 rounded-lg border-2 border-gray-500 bg-white text-gray-700 
                        hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-500 hover:border-3
                        active:border-indigo-600 active:border-3 active:ring-2 active:ring-indigo-200
                        transition-all duration-200 ease-in-out shadow-md nav-link nav-link-blue py-4 items-center
                        focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:border-indigo-600"
                        id="inter-nav" data-tw-target="#inter" aria-controls="inter" aria-selected="false"
                        role="tab">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                        </svg>
                        <span class="nav-label"> International
                        </span>
                        <span
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{ $Internationals->count() }} éléments
                        </span>

                    </a>
                </li>
            @endif --}}
        @endif
    @endif
</ul>
