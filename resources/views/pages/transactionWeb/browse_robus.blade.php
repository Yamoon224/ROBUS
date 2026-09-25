@extends('layouts.master_web')

@section('title', 'Achat UV — ROBUS')

@push('styles')
<link rel="stylesheet" href="{{ asset('web/css/achat-uv.css') }}">
@endpush

@section('content')
    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/transactions.jpeg',
        'title'           => 'Achat UV — ROBUS',
        'subtitle'        => 'Identifiez votre compte, choisissez votre opérateur et procédez au paiement',
        'badgeText'       => 'Transaction 100% sécurisée',
        'badgeIcon'       => 'bi-currency-exchange',
    ])

    @php
        $acvFlags = [
            'CI' => '🇨🇮', 'SN' => '🇸🇳', 'ML' => '🇲🇱', 'BF' => '🇧🇫',
            'BJ' => '🇧🇯', 'TG' => '🇹🇬', 'CM' => '🇨🇲', 'GM' => '🇬🇲',
            'GN' => '🇬🇳', 'CD' => '🇨🇩', 'MG' => '🇲🇬', 'GA' => '🇬🇦',
            'CG' => '🇨🇬',
        ];
        $acvCountries = getMobileTransferServicesGrouped()
            ->filter(fn($c) => collect($c['cash_out_providers'])->isNotEmpty())
            ->values();
    @endphp

    <section class="acv-page contact-info-section style_percent" data-txn-type="robus">
        <div class="container">
            <div class="row acv-shell g-4 justify-content-center">
                <div class="col-lg-8 col-md-11 col-sm-12">
                    <div class="acv-form-panel">

                        {{-- ── En-tête ── --}}
                        <div class="acv-form-head">
                            <div class="acv-form-head-content">
                                <h2>Achat UV — Rechargement ROBUS</h2>
                                <p>Vérifiez votre compte ROBUS, sélectionnez votre opérateur mobile et effectuez votre paiement.</p>
                                <div class="acv-trust-list">
                                    <span class="acv-trust-chip"><i class="bi bi-shield-check"></i> Sécurisé</span>
                                    <span class="acv-trust-chip"><i class="bi bi-wallet2"></i> Compte ROBUS</span>
                                    <span class="acv-trust-chip"><i class="bi bi-phone"></i> Mobile Money</span>
                                </div>
                            </div>
                            <div class="acv-head-icon">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>

                        {{-- ── Barre de progression ── --}}
                        <div class="acv-progress">
                            <div class="acv-progress-step active" id="acvStep1Ind">
                                <div class="acv-progress-num">1</div>
                                <div class="acv-progress-label">Compte ROBUS</div>
                            </div>
                            <div class="acv-progress-connector" id="acvProgressLine1"></div>
                            <div class="acv-progress-step" id="acvStep2Ind">
                                <div class="acv-progress-num">2</div>
                                <div class="acv-progress-label">Opérateur</div>
                            </div>
                            <div class="acv-progress-connector" id="acvProgressLine2"></div>
                            <div class="acv-progress-step" id="acvStep3Ind">
                                <div class="acv-progress-num">3</div>
                                <div class="acv-progress-label">Montant & envoi</div>
                            </div>
                        </div>

                        {{-- ── SECTION 1 : ID ROBUS ── --}}
                        <div class="acv-section" id="acvSection1" style="padding-bottom:24px">

                            <div style="margin-bottom:14px">
                                <div style="font-size:.96rem;font-weight:900;color:#1e1b4b;margin-bottom:4px">
                                    Votre identifiant ROBUS
                                </div>
                                <div style="font-size:.78rem;color:#64748b;font-weight:600">
                                    Saisissez votre ID — le titulaire du compte s'affiche automatiquement
                                </div>
                            </div>

                            <div class="acv-id-field">
                                <i class="bi bi-person-badge acv-id-icon"></i>
                                <input type="text" id="acvRobusIdInput" placeholder="Ex : CIZW7480"
                                       autocomplete="off" oninput="acvOnIdInput(this.value)">
                                <span id="acvVerifyIcon" class="acv-verify-icon"></span>
                            </div>

                            <div class="acv-name-card" id="acvNameCard" style="display:none">
                                <i class="bi bi-person-check-fill" style="color:#4338ca;font-size:1.1rem;flex:0 0 auto"></i>
                                <div>
                                    <div class="acv-name-lbl">Titulaire du compte</div>
                                    <div class="acv-name-val" id="acvRobusNameVal"></div>
                                </div>
                            </div>

                            <button type="button" class="acv-proceed-btn" id="acvStep1Btn"
                                    onclick="acvContinueToPayment()" disabled>
                                <i class="bi bi-check2-circle"></i> Continuer — Choisir l'opérateur
                            </button>
                        </div>

                        {{-- ── SECTION 2 : Pays + Opérateur ── --}}
                        <div id="acvSection2" class="d-none" style="padding-bottom:4px">

                            <div class="acv-divider">
                                <div class="acv-divider-line"></div>
                                <span class="acv-divider-badge">
                                    <span class="acv-divider-num">2</span>
                                    Mode de paiement
                                </span>
                                <div class="acv-divider-line"></div>
                            </div>

                            <div class="acv-section mb-3">
                                {{-- Bouton retour --}}
                                <div style="margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                                    <div>
                                        <div style="font-size:.96rem;font-weight:900;color:#1e1b4b" id="acvSection2Title">
                                            Choisissez un pays
                                        </div>
                                        <div style="font-size:.78rem;color:#64748b;font-weight:600">
                                            Puis sélectionnez votre opérateur mobile
                                        </div>
                                    </div>
                                    <button type="button" class="acv-back-btn" onclick="acvBackToStep1()">
                                        <i class="bi bi-arrow-left"></i> Retour
                                    </button>
                                </div>

                                {{-- Étape A : grille des pays --}}
                                <div id="acvCountryStep">
                                    <div class="acv-country-grid">
                                        @forelse ($acvCountries as $country)
                                            @php
                                                $iso2 = $country['country_iso2'] ?? '';
                                                $flag = $acvFlags[$iso2] ?? '🌍';
                                                $cnt  = collect($country['cash_out_providers'])->count();
                                            @endphp
                                            <div class="acv-ctry-card"
                                                 data-country-id="{{ $country['country_id'] }}"
                                                 data-country-name="{{ $country['country'] ?? $iso2 }}"
                                                 data-country-flag="{{ $flag }}"
                                                 onclick="acvPickCountry(this)">
                                                <span class="acv-ctry-flag">{{ $flag }}</span>
                                                <span class="acv-ctry-name">{{ $country['country'] ?? $iso2 }}</span>
                                                <span class="acv-ctry-badge">{{ $cnt }} op.</span>
                                            </div>
                                        @empty
                                            <div class="acv-empty">
                                                <i class="bi bi-wifi-off"></i>Aucun pays disponible
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Étape B : grille des opérateurs --}}
                                <div id="acvOpStep" class="d-none acv-op-step">

                                    <div class="acv-back-row">
                                        <button type="button" class="acv-back-btn" onclick="acvBackToCountry()">
                                            <i class="bi bi-arrow-left"></i> Pays
                                        </button>
                                        <span class="acv-back-row-flag" id="acvBackFlag"></span>
                                        <span class="acv-back-row-country" id="acvBackLabel"></span>
                                    </div>

                                    <div style="font-size:.76rem;font-weight:700;color:#64748b;
                                                text-transform:uppercase;letter-spacing:.05em;
                                                padding:4px 0 8px">
                                        Choisissez l'opérateur
                                    </div>

                                    @foreach ($acvCountries as $country)
                                        <div class="acv-op-group d-none" data-country-id="{{ $country['country_id'] }}">
                                            <div class="acv-operators">
                                                @foreach (collect($country['cash_out_providers']) as $op)
                                                    <div class="acv-op-card"
                                                         data-provider-id="{{ $op['provider_id'] }}"
                                                         data-service-id="{{ $op['service_id'] }}"
                                                         data-op-name="{{ $op['name'] }}"
                                                         data-op-image="{{ $op['image'] ?? '' }}"
                                                         data-country="{{ $country['country'] ?? '' }}"
                                                         data-country-iso2="{{ $country['country_iso2'] ?? '' }}"
                                                         data-country-indicatif="{{ $country['country_indicatif'] ?? '' }}"
                                                         onclick="acvSelectOp(this)">
                                                        <div class="acv-op-logo">
                                                            @if (!empty($op['image']))
                                                                <img src="{{ $op['image'] }}" alt="{{ $op['name'] }}"
                                                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                                                <i class="bi bi-phone-fill" style="display:none"></i>
                                                            @else
                                                                <i class="bi bi-phone-fill"></i>
                                                            @endif
                                                        </div>
                                                        <div class="acv-op-name">{{ $op['name'] }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Barre opérateur sélectionné --}}
                                <div class="acv-op-bar d-none" id="acvOpBar">
                                    <div class="acv-op-bar-info">
                                        <div class="acv-op-bar-avatar" id="acvOpBarAvatar">
                                            <i class="bi bi-phone-fill"></i>
                                        </div>
                                        <div style="flex:1;min-width:0">
                                            <div class="acv-op-bar-lbl">Opérateur sélectionné</div>
                                            <div class="acv-op-bar-val" id="acvOpBarName">—</div>
                                            <div style="font-size:.72rem;color:#64748b;font-weight:600" id="acvOpBarCountry"></div>
                                        </div>
                                    </div>
                                    <button type="button" class="acv-proceed-btn"
                                            id="acvSection2Btn" onclick="acvProceed()" disabled
                                            style="margin-top:0;flex:0 0 auto;width:auto;padding:10px 20px;font-size:.86rem">
                                        <i class="bi bi-check2-circle"></i> Continuer
                                    </button>
                                </div>

                            </div>{{-- /acv-section --}}
                        </div>{{-- /acvSection2 --}}

                        {{-- ── SECTION 3 : Formulaire AJAX ── --}}
                        <div class="acv-form-reveal" id="acvFormReveal">

                            <div class="acv-divider">
                                <div class="acv-divider-line"></div>
                                <span class="acv-divider-badge">
                                    <span class="acv-divider-num">3</span>
                                    Montant & envoi
                                </span>
                                <div class="acv-divider-line"></div>
                            </div>

                            <div class="acv-form-body">

                                {{-- Carte opérateur en lecture seule --}}
                                <div class="acv-info-card">
                                    <div class="acv-info-card-avatar" id="acvFormOpAvatar">
                                        <i class="bi bi-phone-fill"></i>
                                    </div>
                                    <div class="acv-info-card-body">
                                        <div class="acv-info-card-lbl">Opérateur de paiement</div>
                                        <div class="acv-info-card-val" id="acvFormOpName">—</div>
                                        <div class="acv-info-card-sub" id="acvFormOpCountry"></div>
                                    </div>
                                    <i class="bi bi-lock-fill" style="color:#94a3b8;font-size:.85rem;flex:0 0 auto"></i>
                                </div>

                                {{-- Bandeau USSD (conditionnel) --}}
                                <div id="acvUssdBanner" style="display:none;margin-bottom:14px"></div>

                                {{-- Code OTP (conditionnel — Orange Money) --}}
                                <div class="d-none" id="acvOtpFieldWrap" style="margin-bottom:14px">
                                    <div class="acv-compact-field" style="border-color:rgba(249,115,22,.35);background:#fff7ed">
                                        <i class="bi bi-lock-fill" style="color:#ea580c;flex:0 0 auto"></i>
                                        <input type="text" id="acvOtp" inputmode="numeric" maxlength="10"
                                               placeholder="Code OTP (après composition du USSD)">
                                    </div>
                                </div>

                                {{-- Numéro de téléphone --}}
                                <div class="acv-compact-field" id="acvPhoneField" style="margin-bottom:14px">
                                    <i class="bi bi-telephone-fill acv-cf-icon"></i>
                                    <input type="text" id="acvPhone" maxlength="15"
                                           placeholder="Votre numéro mobile"
                                           oninput="acvClearInvalid('acvPhoneField')">
                                </div>

                                {{-- ID ROBUS (confirmation lecture seule) --}}
                                <div class="acv-compact-field" style="margin-bottom:14px;background:#f8fafc;border-color:rgba(99,102,241,.18)">
                                    <i class="bi bi-person-badge acv-cf-icon"></i>
                                    <input type="text" id="acvRobusDisplay" readonly
                                           style="color:#64748b;font-size:.88rem"
                                           placeholder="ID ROBUS">
                                </div>

                                {{-- Montant --}}
                                <div class="acv-amount-field">
                                    <div class="acv-af-icon"><i class="bi bi-cash-stack"></i></div>
                                    <input type="number" id="acvAmount" min="2000" max="500000"
                                           placeholder="Montant (FCFA)"
                                           oninput="acvOnAmountChange(this.value)">
                                    <span class="acv-af-badge">FCFA</span>
                                </div>

                                {{-- Calcul dynamique des frais --}}
                                <div id="acvFeesWrap" style="display:none;margin-top:12px;margin-bottom:14px">
                                    <div id="acvFeesPreview"></div>
                                </div>

                                {{-- Note min/max --}}
                                <div class="acv-fees-note" id="acvFeesNote" style="margin-top:10px;margin-bottom:16px">
                                    <i class="bi bi-info-circle-fill" style="flex:0 0 auto"></i>
                                    <span>
                                        Frais : <strong>{{ $total_percent ?? 0 }}%</strong>
                                        — Min : <strong>2 000 FCFA</strong> / Max : <strong>500 000 FCFA</strong>
                                    </span>
                                </div>

                                {{-- Erreur --}}
                                <div class="acv-error-box" id="acvErrorBox" style="display:none"></div>

                                {{-- Bouton --}}
                                <button type="button" class="acv-submit-btn" id="acvSubmitBtn"
                                        onclick="acvSubmit()">
                                    <span class="acv-submit-text">
                                        <i class="bi bi-send-fill me-2"></i>Procéder au paiement
                                    </span>
                                    <span class="acv-loading-spinner d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Traitement en cours…
                                    </span>
                                </button>

                                {{-- Lien retour --}}
                                <div class="text-center mt-2">
                                    <button type="button" class="btn btn-link btn-sm text-muted p-0"
                                            onclick="acvBackToStep2()">
                                        <i class="bi bi-pencil me-1"></i>Modifier l'opérateur
                                    </button>
                                </div>

                            </div>
                        </div>{{-- /acvFormReveal --}}

                    </div>{{-- /acv-form-panel --}}

                    <div class="acv-side-note">
                        <strong>Avant d'envoyer votre demande</strong>
                        <p>Vérifiez l'ID ROBUS, le nom affiché et le numéro de téléphone. Une erreur d'identifiant peut empêcher le traitement du rechargement.</p>
                    </div>
                </div>

                @include('layouts.shared_web.info-open-robus')
            </div>
        </div>
    </section>

    {{-- ── Modal Confirmation ── --}}
    <div class="modal fade" id="acvConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:0">
                <div class="modal-header border-0 px-4 pt-4 pb-2"
                     style="background:var(--acv-head,linear-gradient(135deg,#1e1b4b,#3730a3))">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="bi bi-wallet2 me-2"></i>Confirmer le paiement
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="acvConfirmBody"></div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-3 fw-bold"
                            data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Annuler
                    </button>
                    <button type="button" class="btn btn-primary fw-bold rounded-3 px-4"
                            onclick="acvDoInitialize()">
                        <i class="bi bi-check2-circle me-1"></i> Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modal Résultat ── --}}
    <div class="modal fade" id="acvResultModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:0">
                <div class="modal-header border-0 px-4 pt-4 pb-0">
                    <h5 class="modal-title fw-bold" id="acvResultTitle"></h5>
                </div>
                <div class="modal-body px-4 py-3" id="acvResultBody"></div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-primary fw-bold rounded-3 w-100"
                            data-bs-dismiss="modal" onclick="acvAfterResult()">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
window._ACV = {
    searchUrl: "{{ route('search.wallets') }}",
    calcUrl:   "{{ route('web.pixelInnov.mobile-transfer.pixelInnov.reload.calculate') }}",
    initUrl:   "{{ route('web.pixelInnov.mobile-transfer.pixelInnov.reload.initialize') }}",
    statusUrl: "{{ route('web.pixelInnov.mobile-transfer.status') }}",
    csrf: document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
};
</script>
<script src="{{ asset('web/js/achat-uv.js') }}"></script>
@endpush
