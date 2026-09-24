@extends('layouts.master_web')

@section('title', 'Transfert & Crédit téléphonique')

@push('styles')
<style>
    /* ===== THEME DEPOSIT ===== */
    [data-txn-type="deposit"] {
        --txn-page-bg:
            radial-gradient(circle at 10% 15%, rgba(5, 150, 105, .10), transparent 30%),
            radial-gradient(circle at 88% 5%, rgba(6, 182, 212, .08), transparent 28%),
            linear-gradient(180deg, #f0fdf9 0%, #ffffff 54%, #f8fafc 100%);
        --txn-head-gradient: linear-gradient(135deg, #064e3b 0%, #059669 58%, #34d399 100%);
        --txn-submit-gradient: linear-gradient(135deg, #064e3b 0%, #059669 60%, #10b981 100%);
        --txn-submit-shadow: rgba(5, 150, 105, .22);
        --txn-field-bg: #f0fdf9;
        --txn-field-focus-border: rgba(5, 150, 105, .55);
        --txn-field-focus-shadow: rgba(5, 150, 105, .12);
        --txn-section-title-color: #064e3b;
        --txn-icon-color: #059669;
        --txn-icon-bg: rgba(5, 150, 105, .10);
        --txn-amount-note-bg: rgba(6, 182, 212, .10);
        --txn-amount-note-color: #0e7490;
    }

    /* ===== SHARED txn-* STYLES ===== */
    .txn-page {
        background: var(--txn-page-bg);
        padding: 64px 0 76px;
    }

    .txn-shell { align-items: flex-start; }

    .txn-form-panel {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 24px;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .10);
        overflow: hidden;
    }

    .txn-form-head {
        display: flex;
        gap: 18px;
        align-items: center;
        justify-content: space-between;
        background: var(--txn-head-gradient);
        color: #ffffff;
        padding: 28px;
    }

    .txn-form-head h2 {
        color: #ffffff;
        font-size: 1.35rem;
        font-weight: 800;
        line-height: 1.25;
        margin: 0;
    }

    .txn-form-head p {
        color: rgba(255, 255, 255, .82);
        margin: 8px 0 0;
    }

    .txn-head-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .20);
        flex: 0 0 auto;
    }

    .txn-head-icon i { color: #ffffff; font-size: 1.55rem; }

    .txn-trust-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .txn-trust-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 34px;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .13);
        color: rgba(255, 255, 255, .94);
        font-size: .82rem;
        font-weight: 700;
    }

    .txn-form-body { padding: 28px; }

    .txn-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--txn-section-title-color);
        font-size: .95rem;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .txn-section-title span {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        color: var(--txn-icon-color);
        background: var(--txn-icon-bg);
    }

    .txn-field {
        height: 100%;
        padding: 14px;
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 18px;
        background: var(--txn-field-bg);
    }

    .txn-field label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #334155;
        font-size: .84rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .txn-field .form-control,
    .txn-field .form-select {
        min-height: 48px;
        border: 1px solid rgba(15, 23, 42, .10) !important;
        border-radius: 14px !important;
        background-color: #ffffff;
        color: #0f172a;
        font-weight: 700;
        box-shadow: none;
    }

    .txn-field .form-control:focus,
    .txn-field .form-select:focus {
        border-color: var(--txn-field-focus-border) !important;
        box-shadow: 0 0 0 .2rem var(--txn-field-focus-shadow);
    }

    .txn-amount-note {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 12px;
        padding: 11px 12px;
        border-radius: 14px;
        background: var(--txn-amount-note-bg);
        color: var(--txn-amount-note-color);
        font-size: .84rem;
        font-weight: 700;
    }

    .txn-submit-wrap {
        display: flex;
        justify-content: center;
    }

    .txn-submit-wrap .txn-submit-btn {
        width: min(100%, 420px);
    }

    .txn-submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 58px;
        height: auto;
        padding-right: 18px !important;
        padding-left: 18px !important;
        border: 0;
        border-radius: 18px;
        background: var(--txn-submit-gradient);
        box-shadow: 0 16px 30px var(--txn-submit-shadow);
        line-height: 1.25;
        white-space: normal;
        overflow-wrap: anywhere;
        text-align: center;
    }

    .txn-submit-btn:disabled {
        background: #a8b3c7;
        box-shadow: none;
        cursor: not-allowed;
    }

    .txn-submit-btn .submit-text { display: inline-flex; }
    .txn-submit-btn .loading-spinner { flex-wrap: wrap; }
    .txn-submit-btn i { flex: 0 0 auto; }

    .txn-submit-btn .submit-text,
    .txn-submit-btn .loading-spinner {
        align-items: center;
        justify-content: center;
        max-width: 100%;
        min-width: 0;
        line-height: 1.25;
    }

    .txn-side-note {
        margin-top: 16px;
        padding: 18px;
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, .08);
        box-shadow: 0 14px 34px rgba(15, 23, 42, .07);
    }

    .txn-side-note strong {
        display: block;
        color: #0f172a;
        font-size: .95rem;
        margin-bottom: 6px;
    }

    .txn-side-note p {
        color: #64748b;
        font-size: .88rem;
        margin: 0;
    }

    .txn-page .card { border-radius: 22px !important; }

    /* ===== MOBILE ZONE TRANSFER STRIP ===== */
    .mzt-strip {
        padding: 22px 28px 0;
    }

    .mzt-strip-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .mzt-strip-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .92rem;
        font-weight: 800;
        color: #064e3b;
    }

    .mzt-strip-title i {
        width: 30px;
        height: 30px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(5, 150, 105, .10);
        color: #059669;
        font-size: .9rem;
    }

    .mzt-strip-hint {
        font-size: .78rem;
        color: #94a3b8;
        font-weight: 600;
    }

    .mzt-country {
        margin-bottom: 8px;
        border-radius: 14px;
        border: 1px solid rgba(15, 23, 42, .07);
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        overflow: hidden;
        transition: box-shadow .2s ease;
    }

    .mzt-country:hover {
        box-shadow: 0 6px 18px rgba(5, 150, 105, .10);
    }

    .mzt-country-toggle {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        transition: background .15s ease;
    }

    .mzt-country-toggle:hover {
        background: rgba(5, 150, 105, .04);
    }

    .mzt-country-flag {
        font-size: 1.5rem;
        flex: 0 0 auto;
        line-height: 1;
    }

    .mzt-country-name {
        flex: 1;
        font-size: .94rem;
        font-weight: 700;
        color: #0f172a;
        text-align: left;
    }

    .mzt-country-count {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 999px;
        background: rgba(5, 150, 105, .10);
        color: #059669;
        font-size: .74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .mzt-chevron {
        font-size: .9rem;
        color: #059669;
        transition: transform .25s ease;
        flex: 0 0 auto;
    }

    .mzt-chevron.rotated { transform: rotate(180deg); }

    .mzt-operators {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 12px 16px 14px;
        background: #f8fafc;
        border-top: 1px solid rgba(5, 150, 105, .08);
    }

    .mzt-op-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 8px;
        border-radius: 14px;
        border: 2px solid rgba(15, 23, 42, .06);
        background: #ffffff;
        cursor: pointer;
        transition: border-color .2s ease, box-shadow .2s ease, transform .15s ease;
        min-height: 90px;
    }

    .mzt-op-card:hover {
        border-color: rgba(5, 150, 105, .30);
        box-shadow: 0 8px 18px rgba(5, 150, 105, .10);
        transform: translateY(-1px);
    }

    .mzt-op-card.selected {
        border-color: #059669;
        background: rgba(5, 150, 105, .05);
        box-shadow: 0 6px 16px rgba(5, 150, 105, .18);
    }

    .mzt-op-logo {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f1f5f9;
    }

    .mzt-op-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .mzt-op-logo i {
        font-size: 1.3rem;
        color: #94a3b8;
    }

    .mzt-op-name {
        font-size: .75rem;
        font-weight: 700;
        color: #334155;
        text-align: center;
        line-height: 1.3;
    }

    .mzt-op-card.selected .mzt-op-name {
        color: #059669;
    }

    .mzt-selected-badge {
        display: none;
        align-items: center;
        gap: 8px;
        margin: 12px 28px 0;
        padding: 10px 14px;
        border-radius: 12px;
        background: rgba(5, 150, 105, .08);
        border: 1px solid rgba(5, 150, 105, .18);
        color: #064e3b;
        font-size: .82rem;
        font-weight: 700;
    }

    .mzt-selected-badge.visible { display: flex; }

    @media (min-width: 576px) {
        .mzt-operators {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 991.98px) {
        .txn-page { padding: 46px 0 58px; }
        .txn-form-head { align-items: flex-start; flex-direction: column; }
    }

    @media (max-width: 575.98px) {
        .txn-form-head,
        .txn-form-body { padding: 22px; }

        .mzt-strip { padding: 18px 22px 0; }

        .mzt-selected-badge { margin: 10px 22px 0; }

        .txn-submit-btn {
            min-height: 54px;
            padding: 13px 14px !important;
            border-radius: 15px;
            font-size: .94rem;
        }

        .txn-submit-btn .submit-text,
        .txn-submit-btn .loading-spinner { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')
    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/transaction_acoumpte.jpg',
        'title' => 'Transfert Mooney',
        'subtitle' => 'Rechargez en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])

    <section class="txn-page contact-info-section style_percent" data-txn-type="deposit">
        <div class="container">
            <div class="row txn-shell g-4 justify-content-center">
                <div class="col-lg-8 col-md-11 col-sm-12">
                    <div class="txn-form-panel">

                        {{-- En-tête --}}
                        <div class="txn-form-head">
                            <div>
                                <h2>Formulaire de Transfert</h2>
                                <p>Renseignez les informations du bénéficiaire, sélectionnez le réseau et le type
                                    d'opération. Les frais sont calculés automatiquement.</p>
                                <div class="txn-trust-list">
                                    <span class="txn-trust-chip">
                                        <i class="bi bi-shield-check"></i> Transaction sécurisée
                                    </span>
                                    <span class="txn-trust-chip">
                                        <i class="bi bi-phone"></i> Mobile Money
                                    </span>
                                    <span class="txn-trust-chip">
                                        <i class="bi bi-receipt"></i> Demande suivie
                                    </span>
                                </div>
                            </div>
                            <div class="txn-head-icon">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                        </div>

                        {{-- Réseaux — Transfert Mobile Zone style --}}
                        @php
                            $mztFlags = [
                                'CI' => '🇨🇮', 'SN' => '🇸🇳', 'ML' => '🇲🇱', 'BF' => '🇧🇫',
                                'BJ' => '🇧🇯', 'TG' => '🇹🇬', 'CM' => '🇨🇲', 'GM' => '🇬🇲',
                                'GN' => '🇬🇳', 'CD' => '🇨🇩', 'MG' => '🇲🇬', 'GA' => '🇬🇦',
                                'CG' => '🇨🇬',
                            ];
                            $mztCountries = getMobileTransferServicesGrouped();
                        @endphp

                        <div class="mzt-strip">
                            <div class="mzt-strip-header">
                                <div class="mzt-strip-title">
                                    <i class="bi bi-globe2"></i>
                                    Réseaux disponibles
                                </div>
                                <span class="mzt-strip-hint">Cliquez pour sélectionner un opérateur</span>
                            </div>

                            @forelse ($mztCountries as $mztCountry)
                                @php
                                    $iso2     = $mztCountry['country_iso2'] ?? '';
                                    $flag     = $mztFlags[$iso2] ?? '🌍';
                                    $opCount  = count($mztCountry['providers']);
                                    $countryId = $mztCountry['country_id'];
                                @endphp
                                <div class="mzt-country" data-mzt-country-id="{{ $countryId }}">
                                    <button class="mzt-country-toggle" type="button"
                                            onclick="mztToggle(this)">
                                        <span class="mzt-country-flag">{{ $flag }}</span>
                                        <span class="mzt-country-name">{{ $mztCountry['country'] ?? $iso2 }}</span>
                                        <span class="mzt-country-count">
                                            {{ $opCount }} opérateur{{ $opCount > 1 ? 's' : '' }}
                                        </span>
                                        <i class="bi bi-chevron-down mzt-chevron"></i>
                                    </button>
                                    <div class="mzt-operators d-none">
                                        @foreach ($mztCountry['providers'] as $mztOp)
                                            <div class="mzt-op-card"
                                                 data-provider-id="{{ $mztOp['provider_id'] }}"
                                                 data-service-id="{{ $mztOp['service_id'] }}"
                                                 data-op-name="{{ $mztOp['name'] }}"
                                                 onclick="mztSelectOperator(this)">
                                                <div class="mzt-op-logo">
                                                    @if (!empty($mztOp['image']))
                                                        <img src="{{ $mztOp['image'] }}" alt="{{ $mztOp['name'] }}"
                                                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                                        <i class="bi bi-phone-fill" style="display:none"></i>
                                                    @else
                                                        <i class="bi bi-phone-fill"></i>
                                                    @endif
                                                </div>
                                                <div class="mzt-op-name">{{ $mztOp['name'] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted small px-2 py-3 mb-0">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Aucun réseau disponible pour le moment.
                                </p>
                            @endforelse
                        </div>

                        {{-- Opérateur sélectionné --}}
                        <div class="mzt-selected-badge" id="mztSelectedBadge">
                            <i class="bi bi-check-circle-fill text-success"></i>
                            <span>Opérateur sélectionné : <strong id="mztSelectedName">—</strong></span>
                        </div>

                        <form method="POST" action="{{ route('web.transactions_deposits_store.stores') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="txn-form-body">
                                <div class="txn-section-title">
                                    <span><i class="bi bi-person-vcard"></i></span>
                                    Informations de l'expéditeur & bénéficiaire
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="txn-field">
                                            <label for="name_expeditor">
                                                <i class="bi bi-person text-primary"></i>
                                                Nom et prénom expéditeur <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name_expeditor" maxlength="80"
                                                class="form-control" id="name_expeditor"
                                                placeholder="Ex: KONAN Franck" required>
                                            <div class="invalid-feedback">
                                                Veuillez saisir le nom et prénom de l'expéditeur
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="txn-field">
                                            <label for="phone_benef">
                                                <i class="bi bi-phone text-primary"></i>
                                                N° Téléphone bénéficiaire <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" maxlength="10" name="phone_benef"
                                                class="form-control" id="phone_benef"
                                                placeholder="Ex: 0909090909" required>
                                            <div class="invalid-feedback">
                                                Veuillez saisir le numéro du bénéficiaire
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="txn-field">
                                            <label for="my_phone">
                                                <i class="bi bi-telephone text-primary"></i>
                                                Votre numéro téléphone <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="my_phone" maxlength="10"
                                                class="form-control" id="my_phone"
                                                placeholder="Ex: 0101010101" required>
                                            <div class="invalid-feedback">
                                                Veuillez saisir votre numéro de téléphone
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="txn-field">
                                            <label for="provider">
                                                <i class="bi bi-wifi text-primary"></i>
                                                Réseau <span class="text-danger">*</span>
                                            </label>
                                            <select required id="provider" name="provider"
                                                class="form-select @error('provider') is-invalid @enderror">
                                                <option disabled selected>Sélectionnez le réseau</option>
                                                @foreach ($providers as $provider)
                                                    <option value="{{ $provider->id }}">
                                                        {{ $provider->name ?? null }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">Veuillez sélectionner un réseau</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="txn-section-title mt-4">
                                    <span><i class="bi bi-cash-coin"></i></span>
                                    Type d'opération & montant
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="txn-field">
                                            <label for="operators">
                                                <i class="bi bi-gear text-primary"></i>
                                                Type d'opération <span class="text-danger">*</span>
                                            </label>
                                            <select required name="operators" id="operators"
                                                class="form-select @error('operators') is-invalid @enderror">
                                                <option disabled selected>Sélectionnez le type d'opération</option>
                                                <option value="1">Transfert mobile money</option>
                                                <option value="2">Rechargement téléphonique</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Veuillez sélectionner le type d'opération
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="txn-field">
                                            <label for="montant_deposit">
                                                <i class="bi bi-cash-stack text-primary"></i>
                                                Montant (FCFA) <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control" id="montant_deposit"
                                                name="amount" min="500" max="500000"
                                                placeholder="Ex: 2400" required>
                                            <div class="invalid-feedback">
                                                Le montant doit être entre 500 et 500.000 FCFA
                                            </div>
                                            <div class="txn-amount-note">
                                                <i class="bi bi-info-circle"></i>
                                                <small id="montantInfoDeposit">Min: 500 FCFA / Max: 500.000 FCFA</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        @include('layouts.shared_web.frais_transfert', [
                                            'total_percent' => $total_percent ?? 0,
                                        ])
                                        @include('layouts.shared_web.frais_credit', [
                                            'total_percent_phone' => $total_percent_phone ?? 0,
                                        ])
                                    </div>

                                    <div class="col-12 txn-submit-wrap">
                                        <button type="submit"
                                            class="btn btn-primary btn-lg py-3 fw-bold txn-submit-btn"
                                            id="submitButtonDeposit" disabled>
                                            <span class="submit-text">
                                                <i class="bi bi-send me-2"></i>
                                                Procéder à la demande
                                            </span>
                                            <span class="loading-spinner" style="display: none;">
                                                <i class="bi bi-arrow-repeat me-2"></i>
                                                Traitement en cours...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="txn-side-note">
                        <strong>Avant d'envoyer votre demande</strong>
                        <p>Vérifiez le numéro du bénéficiaire, le réseau sélectionné et le type d'opération. Une
                            erreur peut retarder le traitement.</p>
                    </div>
                </div>

                @include('layouts.shared_web.info-open-transfert')
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // ── Opérateur toggle de type d'opération ──────────────────────────────────
    const selectElement  = document.querySelector('select[name="operators"]');
    const elementToToggle  = document.querySelector('.active_phone');
    const elementToToggle1 = document.querySelector('.active_phone1');
    const elementToToggle2 = document.querySelector('.active_transfert');
    const elementToToggle3 = document.querySelector('.active_transfert2');

    selectElement.addEventListener('change', function () {
        const selectedValue = selectElement.value;
        if (selectedValue === '1') {
            elementToToggle2.classList.remove('d-none');
            elementToToggle3.classList.remove('d-none');
            elementToToggle.classList.add('d-none');
            elementToToggle1.classList.add('d-none');
        } else if (selectedValue === '2') {
            elementToToggle.classList.remove('d-none');
            elementToToggle1.classList.remove('d-none');
            elementToToggle2.classList.add('d-none');
            elementToToggle3.classList.add('d-none');
        } else {
            elementToToggle.classList.add('d-none');
            elementToToggle1.classList.add('d-none');
            elementToToggle2.classList.add('d-none');
            elementToToggle3.classList.add('d-none');
        }
    });

    // ── Mobile Zone Transfer — accordion et sélection opérateur ──────────────
    function mztToggle(btn) {
        const section   = btn.closest('.mzt-country');
        const operators = section.querySelector('.mzt-operators');
        const chevron   = btn.querySelector('.mzt-chevron');
        operators.classList.toggle('d-none');
        chevron.classList.toggle('rotated');
    }

    function mztSelectOperator(card) {
        document.querySelectorAll('.mzt-op-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');

        const providerId = card.dataset.providerId;
        const opName     = card.dataset.opName;

        // Mise à jour du select réseau si la valeur correspond
        const providerSelect = document.getElementById('provider');
        if (providerSelect && providerId) {
            const match = Array.from(providerSelect.options).find(o => o.value == providerId);
            if (match) {
                providerSelect.value = providerId;
            }
        }

        // Badge opérateur sélectionné
        const badge     = document.getElementById('mztSelectedBadge');
        const nameLbl   = document.getElementById('mztSelectedName');
        if (badge && nameLbl) {
            nameLbl.textContent = opName || '—';
            badge.classList.add('visible');
        }
    }
</script>
@endpush
