@php
    $is_under = sabdy()->is_under_distributer;
@endphp


<ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center mb-3" role="tablist">

    @if (sabdy()->country->code == 'CI')

        {{-- Sous-agents & distributeurs $is_under  --}}
        @if ($is_under || $is_distributer)

            {{-- New Updated  --}}
            @if ($showDepotButton && sabdy()->hasPermissionTo('transfers-argent'))
                <li wire:key="nav-transfers-admin" id="transfers-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('transfers')"
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
                        <span x-data="{ count: {{ $transactions->count() }} }" x-on:update-transfers-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showPaiementButton && sabdy()->hasPermissionTo('paiement-factures'))
                <li wire:key="nav-paiement-admin" id="paiement-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('paiement')"
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
                        <span x-data="{ count: {{ $paiements->count() }} }" x-on:update-paiement-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if ($showVirementButton && sabdy()->hasPermissionTo('virement-compte'))
                <li wire:key="nav-virement-admin" id="virement-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('virement')"
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
                        <span x-data="{ count: {{ $virements->count() }} }" x-on:update-virement-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showRechargementButton && sabdy()->hasPermissionTo('rechargement-carte'))
                <li wire:key="nav-carte-admin" id="carte-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('carte')"
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
                        <span x-data="{ count: {{ $reloadCards->count() }} }" x-on:update-reloadCard-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showCreditButton && sabdy()->hasPermissionTo('rechargement-credit'))
                <li  wire:key="nav-phone-admin" id="phone-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('phone')"
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
                        <span x-data="{ count: {{ $phoneCredits->count() }} }"
                            x-on:update-phoneCredit-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if ($showTransacInterButton && sabdy()->hasPermissionTo('rechargement-inters'))
                <li wire:key="nav-inter-admin" id="inter-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('inter')"
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
                        <span x-data="{ count: {{ $Internationals->count() }} }"
                            x-on:update-international-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showXbetButton && sabdy()->hasPermissionTo('rechargement-xbets'))
                <li wire:key="nav-xbet-admin" id="xbet-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('xbet')"

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
                        <span x-data="{ count: {{ $xbets->count() }} }" x-on:update-xbets-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if ($showVersementButton && sabdy()->hasPermissionTo('rechargement-versements'))
                <li wire:key="nav-verment-admin" id="versment-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('versment')"

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
                        <span x-data="{ count: {{ $versements->count() }} }" x-on:update-versment-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showBankSendButton && sabdy()->hasPermissionTo('rechargement-bank-sends'))
                <li wire:key="nav-bank-send-admin" id="bank-send-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('bank-send')"

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
                       <span x-data="{ count: {{ $bankSends->count() }} }" x-on:update-bankSend-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showPushButton && sabdy()->hasPermissionTo('rechargement-push'))
                <li wire:key="nav-push-admin" id="push-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('push')"
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
                         <span x-data="{ count: {{ $pushs->count() }} }" x-on:update-push-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showDjamoButton && sabdy()->hasPermissionTo('rechargement-tresort'))
                <li  wire:key="nav-tresort-admin" id="tresort-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('tresort')"

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
                         <span x-data="{ count: {{ $tresortMoneys->count() }} }" x-on:update-tresort-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showDjamoButton && sabdy()->hasPermissionTo('rechargement-djamo'))
                <li wire:key="nav-djamo-admin" id="djamo-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('djamo')"

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
                         <span x-data="{ count: {{ $djamos->count() }} }" x-on:update-djamo-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                       
                    </a>
                </li>
            @endif
        @else
            {{-- Admin --}}
            @if (sabdy()->hasPermissionTo('transfers-argent'))
                <li wire:key="nav-transfers-admin" id="transfers-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('transfers')"

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

                        <span x-data="{ count: {{ $transactions->count() }} }" x-on:update-transfers-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('paiement-factures'))
                <li  wire:key="nav-paiement-admin" id="paiement-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('paiement')"

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
                        <span x-data="{ count: {{ $paiements->count() }} }" x-on:update-paiement-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('virement-compte'))
                <li wire:key="nav-virement-admin" id="virement-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('virement')"

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
                        <span x-data="{ count: {{ $virements->count() }} }" x-on:update-virement-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-carte'))
                <li  wire:key="nav-carte-admin" id="carte-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('carte')"

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
                        <span x-data="{ count: {{ $reloadCards->count() }} }"
                            x-on:update-reloadCard-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-credit'))
                <li  wire:key="nav-phone-admin" id="phone-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('phone')"

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
                        <span x-data="{ count: {{ $phoneCredits->count() }} }"
                            x-on:update-phoneCredit-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-inters'))
                <li wire:key="nav-inter-admin" id="inter-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('inter')"

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
                        <span x-data="{ count: {{ $Internationals->count() }} }"
                            x-on:update-international-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-xbets'))
                <li wire:key="nav-xbet-admin" id="xbet-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('xbet')"

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
                        <span x-data="{ count: {{ $xbets->count() }} }" x-on:update-xbets-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-versements'))
                <li wire:key="nav-versment-admin" id="versment-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('versment')"

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
                       <span x-data="{ count: {{ $versements->count() }} }" x-on:update-versment-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-bank-sends'))
                <li wire:key="nav-bank-send-admin" id="bank-send-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('bank-send')"

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

                        <span x-data="{ count: {{ $bankSends->count() }} }" x-on:update-bankSend-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-push'))
                <li wire:key="nav-push-admin" id="push-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('push')"
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
                        <span x-data="{ count: {{ $pushs->count() }} }" x-on:update-push-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-tresort'))
                <li wire:key="nav-tresort-admin" id="tresort-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('tresort')"

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
                        <span x-data="{ count: {{ $tresortMoneys->count() }} }" x-on:update-tresort-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-djamo'))
                <li wire:key="nav-djamo-admin"  id="djamo-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('djamo')"

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
                         <span x-data="{ count: {{ $djamos->count() }} }" x-on:update-djamo-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

        @endif
    @else
        {{-- Autres pays $is_under --}}
        @if ($is_under || $is_distributer)
            @if ($showDepotButton && sabdy()->hasPermissionTo('transfers-argent'))
                <li wire:key="nav-transfers-admin" id="transfers-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('transfers')"

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
                        <span x-data="{ count: {{ $transactions->count() }} }" x-on:update-transfers-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>
                    </a>
                </li>
            @endif

            @if ($showTransacInterButton && sabdy()->hasPermissionTo('rechargement-inters'))
                <li wire:key="nav-inter-admin" id="inter-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('inter')"

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
                        <span x-data="{ count: {{ $Internationals->count() }} }"
                            x-on:update-international-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
                        </span>

                    </a>
                </li>
            @endif
        @else
            {{-- Admin autres pays --}}
            @if (sabdy()->hasPermissionTo('transfers-argent'))
                <li wire:key="nav-transfers-admin" id="transfers-tab" class="nav-item" role="presentation" wire:ignore>
                    <a href="javascript:;"
                         wire:click="setActiveTab('transfers')"

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
                         <span x-data="{ count: {{ $transactions->count() }} }" x-on:update-transfers-count.window="count = $event.detail.count"
                            class="inline-flex ml-3  items-center justify-center min-w-[24px] h-6 px-2 text-xs font-bold text-red-600 bg-white border-2 border-red-600 rounded-full shadow-sm transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                            {{-- Le texte est mis à jour en JS sans toucher au reste du menu --}}
                            <b x-text="count"></b>&nbsp;éléments
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
