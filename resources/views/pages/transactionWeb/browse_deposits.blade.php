@extends('layouts.master_web')

@section('title', 'Transfert Mobile Zone')

@push('styles')
<link rel="stylesheet" href="{{ asset('public/web/css/mzt-deposits.css') }}">
@endpush

@section('content')
    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/transaction_acoumpte.jpg',
        'title'           => 'Transfert Mobile Zone',
        'subtitle'        => 'Sélectionnez vos réseaux et procédez au transfert',
        'badgeText'       => 'Transaction 100% sécurisée',
        'badgeIcon'       => 'bi-currency-exchange',
    ])

    @php
        $mztFlags = [
            'CI' => '🇨🇮', 'SN' => '🇸🇳', 'ML' => '🇲🇱', 'BF' => '🇧🇫',
            'BJ' => '🇧🇯', 'TG' => '🇹🇬', 'CM' => '🇨🇲', 'GM' => '🇬🇲',
            'GN' => '🇬🇳', 'CD' => '🇨🇩', 'MG' => '🇲🇬', 'GA' => '🇬🇦',
            'CG' => '🇨🇬',
        ];
        $mztCountries          = getMobileTransferServicesGrouped();
        $mztSenderCountries    = $mztCountries->filter(fn($c) => collect($c['cash_out_providers'])->isNotEmpty())->values();
        $mztRecipientCountries = $mztCountries->filter(fn($c) => collect($c['cash_in_providers'])->isNotEmpty())->values();
    @endphp


    <section class="txn-page contact-info-section style_percent" data-txn-type="deposit">
        <div class="container">
            <div class="row txn-shell g-4 justify-content-center">
                <div class="col-lg-8 col-md-11 col-sm-12">
                    <div class="txn-form-panel">

                        {{-- ── En-tête ── --}}
                        <div class="txn-form-head">
                            <div class="txn-form-head-content">
                                <h2>Transfert Mobile Zone</h2>
                                <p>Choisissez le pays et l'opérateur de chaque côté pour commencer.</p>
                                <div class="txn-trust-list">
                                    <span class="txn-trust-chip"><i class="bi bi-shield-check"></i> Sécurisé</span>
                                    <span class="txn-trust-chip"><i class="bi bi-phone"></i> Mobile Money</span>
                                    <span class="txn-trust-chip"><i class="bi bi-receipt"></i> Suivi en temps réel</span>
                                </div>
                            </div>
                            <div class="txn-head-icon">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                        </div>

                        {{-- ── Barre de progression ── --}}
                        <div class="mzt-progress">
                            <div class="mzt-progress-step active" id="mztStep1Indicator">
                                <div class="mzt-progress-num">1</div>
                                <div class="mzt-progress-label">Sélection des réseaux</div>
                            </div>
                            <div class="mzt-progress-connector" id="mztProgressLine"></div>
                            <div class="mzt-progress-step" id="mztStep2Indicator">
                                <div class="mzt-progress-num">2</div>
                                <div class="mzt-progress-label">Informations & envoi</div>
                            </div>
                        </div>

                        {{-- ── ÉTAPE 1 : Sélecteur ── --}}
                        <div class="mzt-selector" id="mztStep1">
                            <div class="mzt-selector-header">
                                <span class="mzt-selector-step-num">1</span>
                                <div>
                                    <div class="mzt-selector-title">Sélectionnez les réseaux</div>
                                    <div class="mzt-selector-sub">Pays puis opérateur — expéditeur à gauche, destinataire à droite</div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">

                                {{-- ── Colonne Expéditeur (Cash Out) ── --}}
                                <div class="col-md-6">
                                    <div class="mzt-side mzt-side-sender mb-3" id="mztSideSender">
                                        <div class="mzt-side-head">
                                            <div class="mzt-side-icon"><i class="bi bi-box-arrow-up-right"></i></div>
                                            <div>
                                                <div class="mzt-side-label">Expéditeur</div>
                                                <div class="mzt-side-sublabel">Votre opérateur mobile</div>
                                            </div>
                                        </div>
                                        <div class="mzt-side-body">

                                            {{-- Étape A : choix du pays --}}
                                            <div id="mztSenderCountryStep">
                                                <div class="mzt-step-hint">Choisissez un pays</div>
                                                <div class="mzt-country-grid">
                                                    @forelse ($mztSenderCountries as $country)
                                                        @php
                                                            $iso2 = $country['country_iso2'] ?? '';
                                                            $flag = $mztFlags[$iso2] ?? '🌍';
                                                            $cnt  = collect($country['cash_out_providers'])->count();
                                                        @endphp
                                                        <div class="mzt-ctry-card"
                                                             data-country-id="{{ $country['country_id'] }}"
                                                             data-country-name="{{ $country['country'] ?? $iso2 }}"
                                                             data-country-flag="{{ $flag }}"
                                                             data-side="sender"
                                                             onclick="mztPickCountry(this,'sender')">
                                                            <span class="mzt-ctry-flag">{{ $flag }}</span>
                                                            <span class="mzt-ctry-name">{{ $country['country'] ?? $iso2 }}</span>
                                                            <span class="mzt-ctry-badge">{{ $cnt }} op.</span>
                                                        </div>
                                                    @empty
                                                        <div class="mzt-empty" style="grid-column:1/-1">
                                                            <i class="bi bi-wifi-off"></i>
                                                            Aucun pays disponible
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>

                                            {{-- Étape B : choix de l'opérateur (masqué jusqu'à sélection du pays) --}}
                                            <div id="mztSenderProviderStep" class="d-none mzt-provider-step">
                                                <div class="mzt-back-row">
                                                    <button type="button" class="mzt-back-btn" onclick="mztBackToCountry('sender')">
                                                        <i class="bi bi-arrow-left"></i> Changer
                                                    </button>
                                                    <div class="mzt-back-row-country">
                                                        <span class="mzt-back-row-flag" id="mztSenderCountryFlag"></span>
                                                        <span id="mztSenderCountryLabel"></span>
                                                    </div>
                                                </div>
                                                <div class="mzt-step-hint" style="padding-top:10px">Choisissez l'opérateur</div>
                                                @foreach ($mztSenderCountries as $country)
                                                    <div class="mzt-op-group d-none" data-country-id="{{ $country['country_id'] }}">
                                                        <div class="mzt-operators">
                                                            @foreach (collect($country['cash_out_providers']) as $op)
                                                                <div class="mzt-op-card"
                                                                     data-side="sender"
                                                                     data-provider-id="{{ $op['provider_id'] }}"
                                                                     data-service-id="{{ $op['service_id'] }}"
                                                                     data-op-name="{{ $op['name'] }}"
                                                                     data-op-image="{{ $op['image'] ?? '' }}"
                                                                     data-country="{{ $country['country'] ?? '' }}"
                                                                     data-country-iso2="{{ $country['country_iso2'] ?? '' }}"
                                                                     data-country-indicatif="{{ $country['country_indicatif'] ?? '' }}"
                                                                     onclick="mztSelectOp(this,'sender')">
                                                                    <div class="mzt-op-logo">
                                                                        @if (!empty($op['image']))
                                                                            <img src="{{ $op['image'] }}" alt="{{ $op['name'] }}"
                                                                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                                                            <i class="bi bi-phone-fill" style="display:none"></i>
                                                                        @else
                                                                            <i class="bi bi-phone-fill"></i>
                                                                        @endif
                                                                    </div>
                                                                    <div class="mzt-op-name">{{ $op['name'] }}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- ── Colonne Destinataire (Cash In) ── --}}
                                <div class="col-md-6">
                                    <div class="mzt-side mzt-side-recipient" id="mztSideRecipient">
                                        <div class="mzt-side-head">
                                            <div class="mzt-side-icon"><i class="bi bi-box-arrow-in-down-right"></i></div>
                                            <div>
                                                <div class="mzt-side-label">Destinataire</div>
                                                <div class="mzt-side-sublabel">Réseau du bénéficiaire</div>
                                            </div>
                                        </div>
                                        <div class="mzt-side-body">

                                            {{-- Étape A : choix du pays --}}
                                            <div id="mztRecipientCountryStep">
                                                <div class="mzt-step-hint">Choisissez un pays</div>
                                                <div class="mzt-country-grid">
                                                    @forelse ($mztRecipientCountries as $country)
                                                        @php
                                                            $iso2 = $country['country_iso2'] ?? '';
                                                            $flag = $mztFlags[$iso2] ?? '🌍';
                                                            $cnt  = collect($country['cash_in_providers'])->count();
                                                        @endphp
                                                        <div class="mzt-ctry-card"
                                                             data-country-id="{{ $country['country_id'] }}"
                                                             data-country-name="{{ $country['country'] ?? $iso2 }}"
                                                             data-country-flag="{{ $flag }}"
                                                             data-side="recipient"
                                                             onclick="mztPickCountry(this,'recipient')">
                                                            <span class="mzt-ctry-flag">{{ $flag }}</span>
                                                            <span class="mzt-ctry-name">{{ $country['country'] ?? $iso2 }}</span>
                                                            <span class="mzt-ctry-badge">{{ $cnt }} op.</span>
                                                        </div>
                                                    @empty
                                                        <div class="mzt-empty" style="grid-column:1/-1">
                                                            <i class="bi bi-wifi-off"></i>
                                                            Aucun pays disponible
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>

                                            {{-- Étape B : choix de l'opérateur --}}
                                            <div id="mztRecipientProviderStep" class="d-none mzt-provider-step">
                                                <div class="mzt-back-row">
                                                    <button type="button" class="mzt-back-btn" onclick="mztBackToCountry('recipient')">
                                                        <i class="bi bi-arrow-left"></i> Changer
                                                    </button>
                                                    <div class="mzt-back-row-country">
                                                        <span class="mzt-back-row-flag" id="mztRecipientCountryFlag"></span>
                                                        <span id="mztRecipientCountryLabel"></span>
                                                    </div>
                                                </div>
                                                <div class="mzt-step-hint" style="padding-top:10px">Choisissez l'opérateur</div>
                                                @foreach ($mztRecipientCountries as $country)
                                                    <div class="mzt-op-group d-none" data-country-id="{{ $country['country_id'] }}">
                                                        <div class="mzt-operators">
                                                            @foreach (collect($country['cash_in_providers']) as $op)
                                                                <div class="mzt-op-card"
                                                                     data-side="recipient"
                                                                     data-provider-id="{{ $op['provider_id'] }}"
                                                                     data-service-id="{{ $op['service_id'] }}"
                                                                     data-op-name="{{ $op['name'] }}"
                                                                     data-op-image="{{ $op['image'] ?? '' }}"
                                                                     data-country="{{ $country['country'] ?? '' }}"
                                                                     data-country-iso2="{{ $country['country_iso2'] ?? '' }}"
                                                                     data-country-indicatif="{{ $country['country_indicatif'] ?? '' }}"
                                                                     onclick="mztSelectOp(this,'recipient')">
                                                                    <div class="mzt-op-logo">
                                                                        @if (!empty($op['image']))
                                                                            <img src="{{ $op['image'] }}" alt="{{ $op['name'] }}"
                                                                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                                                            <i class="bi bi-phone-fill" style="display:none"></i>
                                                                        @else
                                                                            <i class="bi bi-phone-fill"></i>
                                                                        @endif
                                                                    </div>
                                                                    <div class="mzt-op-name">{{ $op['name'] }}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>{{-- /row --}}

                            {{-- Route bar --}}
                            <div class="mzt-route-bar d-none" id="mztRouteBar">
                                <div class="mzt-route-op">
                                    <div class="mzt-route-op-avatar" id="mztRouteSenderAvatar">
                                        <i class="bi bi-phone-fill"></i>
                                    </div>
                                    <div class="mzt-route-op-info">
                                        <div class="mzt-route-op-lbl">Expéditeur</div>
                                        <div class="mzt-route-op-val" id="mztRouteSenderName">—</div>
                                    </div>
                                </div>
                                <div class="mzt-route-arrow-wrap">
                                    <div class="mzt-route-arrow"><i class="bi bi-arrow-right"></i></div>
                                </div>
                                <div class="mzt-route-op">
                                    <div class="mzt-route-op-avatar" id="mztRouteRecipientAvatar">
                                        <i class="bi bi-phone-fill"></i>
                                    </div>
                                    <div class="mzt-route-op-info">
                                        <div class="mzt-route-op-lbl">Destinataire</div>
                                        <div class="mzt-route-op-val" id="mztRouteRecipientName">—</div>
                                    </div>
                                </div>
                                <button type="button" class="mzt-route-proceed" onclick="mztProceed()">
                                    <i class="bi bi-check2-circle"></i> Continuer
                                </button>
                            </div>

                        </div>{{-- /mztStep1 --}}

                        {{-- ── ÉTAPE 2 : Formulaire ── --}}
                        <div class="mzt-form-reveal" id="mztFormReveal">

                            <div class="mzt-step-divider">
                                <div class="mzt-step-divider-line"></div>
                                <span class="mzt-step-divider-badge">
                                    <span class="mzt-step-divider-num">2</span>
                                    Informations du transfert
                                </span>
                                <div class="mzt-step-divider-line"></div>
                            </div>

                            <form id="mztTransferForm" novalidate onsubmit="mztSubmit(event)">
                                @csrf
                                <input type="hidden" id="inputSenderServiceId">
                                <input type="hidden" id="inputRecipientServiceId">

                                <div class="txn-form-body">

                                    {{-- ── Ligne 1 : Expéditeur | Destinataire ── --}}
                                    <div class="row g-3 mb-3">

                                        {{-- Expéditeur --}}
                                        <div class="col-12 col-sm-6">
                                            <div class="mzt-compact-panel">
                                                <div class="mzt-compact-panel-head sender">
                                                    <i class="bi bi-box-arrow-up-right"></i> Expéditeur
                                                </div>

                                                {{-- Opérateur (lecture seule) --}}
                                                <div class="mzt-info-card" id="mztFormSenderCard" style="padding:10px 12px">
                                                    <div class="mzt-info-card-avatar" id="mztFormSenderAvatar" style="width:34px;height:34px;border-radius:10px">
                                                        <i class="bi bi-phone-fill"></i>
                                                    </div>
                                                    <div class="mzt-info-card-body">
                                                        <div class="mzt-info-card-val" id="mztFormSenderOp" style="font-size:.82rem">—</div>
                                                        <div class="mzt-info-card-country" id="mztFormSenderCountry"></div>
                                                    </div>
                                                    <i class="bi bi-lock-fill mzt-info-lock"></i>
                                                </div>

                                                {{-- Votre numéro --}}
                                                <div class="mzt-compact-field mt-2" id="mztSenderPhoneField">
                                                    <i class="bi bi-telephone-fill mzt-cf-icon"></i>
                                                    <input type="text" name="my_phone" id="my_phone"
                                                           maxlength="15" placeholder="Votre n°" required
                                                           oninvalid="mztMarkInvalid('mztSenderPhoneField')"
                                                           oninput="mztClearInvalid('mztSenderPhoneField')">
                                                </div>
                                                <div class="invalid-feedback d-block" id="mztSenderPhoneErr" style="display:none!important;font-size:.75rem;padding:2px 4px"></div>
                                            </div>
                                        </div>

                                        {{-- Destinataire --}}
                                        <div class="col-12 col-sm-6">
                                            <div class="mzt-compact-panel recip-panel">
                                                <div class="mzt-compact-panel-head recip">
                                                    <i class="bi bi-box-arrow-in-down-right"></i> Destinataire
                                                </div>

                                                {{-- Opérateur (lecture seule) --}}
                                                <div class="mzt-info-card mzt-info-recip" id="mztFormRecipCard" style="padding:10px 12px">
                                                    <div class="mzt-info-card-avatar" id="mztFormRecipAvatar" style="width:34px;height:34px;border-radius:10px">
                                                        <i class="bi bi-phone-fill"></i>
                                                    </div>
                                                    <div class="mzt-info-card-body">
                                                        <div class="mzt-info-card-val" id="mztFormRecipOp" style="font-size:.82rem">—</div>
                                                        <div class="mzt-info-card-country" id="mztFormRecipCountry"></div>
                                                    </div>
                                                    <i class="bi bi-lock-fill mzt-info-lock"></i>
                                                </div>

                                                {{-- Numéro bénéficiaire --}}
                                                <div class="mzt-compact-field mt-2" id="mztRecipPhoneField"
                                                     style="border-color:rgba(5,150,105,.22)">
                                                    <i class="bi bi-telephone-fill mzt-cf-icon" style="color:var(--mzt-recip-color)"></i>
                                                    <input type="text" name="phone_benef" id="phone_benef"
                                                           maxlength="15" placeholder="N° bénéficiaire" required
                                                           oninvalid="mztMarkInvalid('mztRecipPhoneField')"
                                                           oninput="mztClearInvalid('mztRecipPhoneField')">
                                                </div>
                                                <div class="invalid-feedback d-block" id="mztRecipPhoneErr" style="display:none!important;font-size:.75rem;padding:2px 4px"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ── Bandeau USSD (pleine largeur, conditionnel) ── --}}
                                    <div id="mztUssdBannerWrap" style="display:none;margin-bottom:12px"></div>

                                    {{-- ── Code OTP (conditionnel) ── --}}
                                    <div class="d-none mb-3" id="mztOtpFieldWrap">
                                        <div class="mzt-compact-field" style="border-color:rgba(249,115,22,.35);background:#fff7ed">
                                            <i class="bi bi-lock-fill" style="color:#ea580c;flex:0 0 auto"></i>
                                            <input type="text" name="om_otp" id="om_otp"
                                                   inputmode="numeric" maxlength="10"
                                                   placeholder="Code OTP (après composition du USSD)">
                                        </div>
                                    </div>

                                    {{-- ── Montant ── --}}
                                    <div class="mzt-amount-field mb-3">
                                        <div class="mzt-af-icon"><i class="bi bi-cash-stack"></i></div>
                                        <input type="number" id="mztAmount" min="1"
                                               placeholder="Montant à envoyer (FCFA)" required
                                               oninput="mztOnAmountChange(this.value)">
                                        <span class="mzt-af-badge">FCFA</span>
                                    </div>

                                    {{-- Frais dynamiques --}}
                                    <div id="mztFeesWrap" style="display:none;margin-bottom:14px">
                                        <div id="mztFeesPreview"></div>
                                    </div>

                                    {{-- Bouton envoyer --}}
                                    <button type="button"
                                        class="btn btn-primary fw-bold txn-submit-btn"
                                        id="mztSubmitBtn" onclick="mztSubmit()">
                                        <span class="submit-text">
                                            <i class="bi bi-send-fill me-2"></i>Envoyer maintenant
                                        </span>
                                        <span class="loading-spinner d-none">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            Traitement en cours...
                                        </span>
                                    </button>

                                    {{-- Lien Modifier --}}
                                    <div class="text-center mt-2">
                                        <button type="button" class="btn btn-link btn-sm text-muted p-0" onclick="mztBack()">
                                            <i class="bi bi-pencil me-1"></i>Modifier les réseaux
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>{{-- /mztFormReveal --}}

                    </div>{{-- /txn-form-panel --}}

                    <div class="txn-side-note">
                        <strong>Avant d'envoyer votre demande</strong>
                        <p>Vérifiez le numéro du bénéficiaire, le réseau et le type d'opération. Une erreur peut retarder le traitement.</p>
                    </div>
                </div>

                @include('layouts.shared_web.info-open-transfert')
            </div>
        </div>
    </section>

    {{-- ── Modal Confirmation ── --}}
    <div class="modal fade" id="mztConfirmModal" tabindex="-1" aria-labelledby="mztConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:0">
                <div class="modal-header" style="background:var(--txn-head-gradient);border:0;padding:20px 24px">
                    <h5 class="modal-title text-white fw-bold" id="mztConfirmModalLabel">
                        <i class="bi bi-arrow-left-right me-2"></i>Confirmer le transfert
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="mztConfirmBody"></div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 fw-bold" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Annuler
                    </button>
                    <button type="button" class="btn btn-primary fw-bold rounded-3 px-4" id="mztConfirmBtn" onclick="mztDoInitialize()">
                        <i class="bi bi-check2-circle me-1"></i> Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Résultat ── --}}
    <div class="modal fade" id="mztResultModal" tabindex="-1" aria-labelledby="mztResultModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:0">
                <div class="modal-header border-0 px-4 pt-4 pb-0">
                    <h5 class="modal-title fw-bold" id="mztResultModalLabel"></h5>
                </div>
                <div class="modal-body px-4 py-3" id="mztResultBody"></div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-primary fw-bold rounded-3 w-100"
                            data-bs-dismiss="modal" onclick="mztAfterResult()">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
window._MZT = {
    calcUrl:   "{{ route('web.pixelInnov.mobile-transfer.calculate') }}",
    initUrl:   "{{ route('web.pixelInnov.mobile-transfer.initialize') }}",
    statusUrl: "{{ route('web.pixelInnov.mobile-transfer.status') }}",
    csrf: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
};
</script>
<script src="{{ asset('web/js/mzt-deposits.js') }}"></script>
@endpush
