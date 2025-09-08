@php
    $is_under = sabdy()->is_under_distributer;
@endphp

<ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">

    @if (sabdy()->country->code == 'CI')

        {{-- Sous-agents & distributeurs --}}
        @if ($is_under || $is_distributer)

            @if ($showDepotButton && sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="transfers-nav"
                        data-tw-target="#transfers" aria-controls="transfers" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg> Transfers d'argent
                    </a>
                </li>
            @endif

            @if ($showPaiementButton && sabdy()->hasPermissionTo('paiement-factures'))
                <li id="paiement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="paiement-nav"
                        data-tw-target="#paiement" aria-controls="paiement" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Paiements
                    </a>
                </li>
            @endif

            @if ($showVirementButton && sabdy()->hasPermissionTo('virement-compte'))
                <li id="virement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="virement-nav"
                        data-tw-target="#virement" aria-controls="virement" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Virements
                    </a>
                </li>
            @endif

            @if ($showRechargementButton && sabdy()->hasPermissionTo('rechargement-carte'))
                <li id="carte-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="carte-nav"
                        data-tw-target="#carte" aria-controls="carte" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Rechargement carte
                    </a>
                </li>
            @endif

            @if ($showCreditButton && sabdy()->hasPermissionTo('rechargement-credit'))
                <li id="phone-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="phone-nav"
                        data-tw-target="#phone" aria-controls="phone" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Crédit téléphonique
                    </a>
                </li>
            @endif

            @if ($showTransacInterButton && sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="inter-nav"
                        data-tw-target="#inter" aria-controls="inter" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Internationnal
                    </a>
                </li>
            @endif
            @if ($showXbetButton && sabdy()->hasPermissionTo('rechargement-xbets'))
                <li id="xbet-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="xbet-nav"
                        data-tw-target="#xbet" aria-controls="xbet" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Xbets
                    </a>
                </li>
            @endif

             @if ($showVersementButton && sabdy()->hasPermissionTo('rechargement-versements'))
                <li id="versment-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="versment-nav"
                        data-tw-target="#versment" aria-controls="versment" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Versements
                    </a>
                </li>
            @endif

              @if ($showBankSendButton && sabdy()->hasPermissionTo('rechargement-bank-sends'))
                <li id="bank-send-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="bank-send-nav"
                        data-tw-target="#bank-send" aria-controls="bank-send" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Bank send
                    </a>
                </li>
            @endif

              @if ($showPushButton && sabdy()->hasPermissionTo('rechargement-push'))
                <li id="push-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="push-nav"
                        data-tw-target="#push" aria-controls="push" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Push
                    </a>
                </li>
            @endif
             @if ($showDjamoButton && sabdy()->hasPermissionTo('rechargement-djamo'))
                <li id="djamo-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="djamo-nav"
                        data-tw-target="#djamo" aria-controls="djamo" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Djamo
                    </a>
                </li>
            @endif

        @else
            {{-- Admin --}}
            @if (sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="transfers-nav"
                        data-tw-target="#transfers" aria-controls="transfers" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Transfers d'argent
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('paiement-factures'))
                <li id="paiement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="paiement-nav"
                        data-tw-target="#paiement" aria-controls="paiement" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Paiements
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('virement-compte'))
                <li id="virement-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="virement-nav"
                        data-tw-target="#virement" aria-controls="virement" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Virements
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-carte'))
                <li id="carte-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="carte-nav"
                        data-tw-target="#carte" aria-controls="carte" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Rechargement carte
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-credit'))
                <li id="phone-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="phone-nav"
                        data-tw-target="#phone" aria-controls="phone" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Crédit téléphonique
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="inter-nav"
                        data-tw-target="#inter" aria-controls="inter" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Internationnal
                    </a>
                </li>
            @endif

             @if (sabdy()->hasPermissionTo('rechargement-xbets'))
                <li id="xbet-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="xbet-nav"
                        data-tw-target="#xbet" aria-controls="xbet" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Xbets
                    </a>
                </li>
            @endif

             @if (sabdy()->hasPermissionTo('rechargement-versements'))
                <li id="versment-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="versment-nav"
                        data-tw-target="#versment" aria-controls="versment" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Versements
                    </a>
                </li>
            @endif

              @if (sabdy()->hasPermissionTo('rechargement-bank-sends'))
                <li id="bank-send-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="bank-send-nav"
                        data-tw-target="#bank-send" aria-controls="bank-send" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Bank send
                    </a>
                </li>
            @endif

              @if (sabdy()->hasPermissionTo('rechargement-push'))
                <li id="push-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="push-nav"
                        data-tw-target="#push" aria-controls="push" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Push
                    </a>
                </li>
            @endif
             @if ( sabdy()->hasPermissionTo('rechargement-djamo'))
                <li id="djamo-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="djamo-nav"
                        data-tw-target="#djamo" aria-controls="djamo" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Djamo
                    </a>
                </li>
            @endif
        @endif

    @else
        {{-- Même logique mais version pays != CI --}}
        @if ($is_under || $is_distributer)
            @if ($showMoneyButton && sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="transfers-nav"
                        data-tw-target="#transfers" aria-controls="transfers" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Transfers d'argent
                    </a>
                </li>
            @endif

            @if ($showInterButton && sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="inter-nav"
                        data-tw-target="#inter" aria-controls="inter" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Internationnal
                    </a>
                </li>
            @endif
        @else
            {{-- Admin --}}
            @if (sabdy()->hasPermissionTo('transfers-argent'))
                <li id="transfers-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="transfers-nav"
                        data-tw-target="#transfers" aria-controls="transfers" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Transfers d'argent
                    </a>
                </li>
            @endif

            @if (sabdy()->hasPermissionTo('rechargement-inters'))
                <li id="inter-tab" class="nav-item" role="presentation">
                    <a href="javascript:;" class="nav-link py-4 flex items-center" id="inter-nav"
                        data-tw-target="#inter" aria-controls="inter" aria-selected="false" role="tab">
                        <svg class="feather feather-shield w-4 h-4 mr-2" ...>...</svg> Internationnal
                    </a>
                </li>
            @endif
        @endif
    @endif
</ul>
