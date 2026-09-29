@props([
'type', // 'reload' | 'push'
'formAction',
'cards' => null, // collection (reload) | single model (push)
'totalPercent' => 0,
'calculValeurs' => 0,
])

@once
@push('styles')
<style>
    /* ===== THEMING ===== */
    [data-txn-type="reload"] {
        --txn-page-bg:
            radial-gradient(circle at 12% 15%, rgba(63, 81, 181, .10), transparent 30%),
            radial-gradient(circle at 88% 0%, rgba(0, 188, 212, .10), transparent 28%),
            linear-gradient(180deg, #f7fbff 0%, #ffffff 54%, #f8fafc 100%);
        --txn-head-gradient: linear-gradient(135deg, #221f70 0%, #1f6fb2 58%, #19a6b6 100%);
        --txn-submit-gradient: linear-gradient(135deg, #221f70 0%, #1f6fb2 60%, #16a3b8 100%);
        --txn-submit-shadow: rgba(31, 111, 178, .22);
        --txn-field-bg: #f8fbff;
        --txn-field-focus-border: rgba(31, 111, 178, .55);
        --txn-field-focus-shadow: rgba(31, 111, 178, .12);
        --txn-section-title-color: #172554;
        --txn-icon-color: #1f6fb2;
        --txn-icon-bg: rgba(31, 111, 178, .10);
        --txn-amount-note-bg: rgba(13, 202, 240, .10);
        --txn-amount-note-color: #0f6c80;
    }

    [data-txn-type="push"] {
        --txn-page-bg:
            radial-gradient(circle at 10% 10%, rgba(248, 162, 31, .12), transparent 30%),
            radial-gradient(circle at 90% 5%, rgba(163, 0, 113, .10), transparent 28%),
            linear-gradient(180deg, #fff9f3 0%, #ffffff 52%, #f8fafc 100%);
        --txn-head-gradient: linear-gradient(135deg, #5c0f58 0%, #a30071 58%, #f8a21f 100%);
        --txn-submit-gradient: linear-gradient(135deg, #5c0f58 0%, #a30071 58%, #f8a21f 100%);
        --txn-submit-shadow: rgba(163, 0, 113, .22);
        --txn-field-bg: #fffaf6;
        --txn-field-focus-border: rgba(163, 0, 113, .48);
        --txn-field-focus-shadow: rgba(163, 0, 113, .11);
        --txn-section-title-color: #3b1740;
        --txn-icon-color: #a30071;
        --txn-icon-bg: rgba(163, 0, 113, .10);
        --txn-amount-note-bg: rgba(248, 162, 31, .14);
        --txn-amount-note-color: #8a4a00;
    }

    /* ===== PAGE ===== */
    .txn-page {
        background: var(--txn-page-bg);
        padding: 64px 0 76px;
    }

    .txn-shell {
        align-items: flex-start;
    }

    /* ===== PANEL ===== */
    .txn-form-panel {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 24px;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .10);
        overflow: hidden;
    }

    /* ===== HEADER ===== */
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

    /* Icon (reload) */
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

    .txn-head-icon i {
        color: #ffffff;
        font-size: 1.55rem;
    }

    /* Logo box (push) */
    .txn-logo-box {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        border: 1px solid rgba(255, 255, 255, .24);
        box-shadow: 0 18px 38px rgba(55, 7, 52, .22);
        flex: 0 0 auto;
        padding: 12px;
    }

    .txn-logo-box img {
        max-width: 100%;
        max-height: 48px;
        object-fit: contain;
    }

    /* ===== TRUST CHIPS ===== */
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

    /* ===== CARD STRIP (reload) ===== */
    .txn-card-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(104px, 1fr));
        gap: 12px;
        padding: 22px 28px 0;
    }

    .txn-card-logo {
        min-height: 82px;
        border: 1px solid rgba(31, 111, 178, .12);
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #f6fbff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .txn-card-logo:hover {
        border-color: rgba(31, 111, 178, .28);
        box-shadow: 0 14px 28px rgba(31, 111, 178, .10);
        transform: translateY(-2px);
    }

    .txn-card-logo img {
        max-width: 86px;
        max-height: 48px;
        object-fit: contain;
    }

    /* ===== SELECTED CARD (push) ===== */
    .txn-selected-card {
        display: flex;
        align-items: center;
        gap: 16px;
        margin: 24px 28px 0;
        padding: 16px;
        border: 1px solid rgba(163, 0, 113, .13);
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(163, 0, 113, .06), rgba(248, 162, 31, .10));
    }

    .txn-selected-card img {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        object-fit: contain;
        background: #ffffff;
        padding: 10px;
        box-shadow: 0 12px 26px rgba(15, 23, 42, .10);
    }

    .txn-selected-card small {
        display: block;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .txn-selected-card strong {
        color: #5c0f58;
        font-size: 1rem;
    }

    /* ===== FORM BODY ===== */
    .txn-form-body {
        padding: 28px;
    }

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

    /* ===== FIELDS ===== */
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

    /* ===== AMOUNT NOTE ===== */
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

    /* ===== SUBMIT BUTTON ===== */
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

    .txn-submit-btn .submit-text,
    .txn-submit-btn .loading-spinner {
        align-items: center;
        justify-content: center;
        max-width: 100%;
        min-width: 0;
        line-height: 1.25;
    }

    .txn-submit-btn .submit-text {
        display: inline-flex;
    }

    .txn-submit-btn .loading-spinner {
        flex-wrap: wrap;
    }

    .txn-submit-btn i {
        flex: 0 0 auto;
    }

    /* ===== SIDE NOTE ===== */
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

    .txn-page .card {
        border-radius: 22px !important;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 991.98px) {
        .txn-page {
            padding: 46px 0 58px;
        }

        [data-txn-type="reload"] .txn-form-head {
            align-items: flex-start;
            flex-direction: column;
        }

        [data-txn-type="push"] .txn-form-head {
            align-items: flex-start;
            flex-direction: column-reverse;
        }
    }

    @media (max-width: 575.98px) {

        .txn-form-head,
        .txn-form-body {
            padding: 22px;
        }

        .txn-card-strip {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            padding: 18px 22px 0;
        }

        .txn-selected-card {
            align-items: flex-start;
            margin: 18px 22px 0;
        }

        .txn-submit-btn {
            min-height: 54px;
            padding: 13px 14px !important;
            border-radius: 15px;
            font-size: .94rem;
        }

        .txn-submit-btn .submit-text,
        .txn-submit-btn .loading-spinner {
            flex-wrap: wrap;
        }
    }
</style>
@endpush
@endonce

<section class="txn-page contact-info-section style_percent" data-txn-type="{{ $type }}">
    <div class="container">
        <div class="row txn-shell g-4 justify-content-center">
            <div class="col-lg-8 col-md-11 col-sm-12">
                <div class="txn-form-panel">

                    {{-- En-tête --}}
                    <div class="txn-form-head">
                        <div>
                            @if ($type === 'reload')
                            <h2>Formulaire de rechargement carte</h2>
                            <p>Renseignez les informations de votre carte et le montant souhaité. Les frais sont
                                calculés automatiquement avant l'envoi.</p>
                            <div class="txn-trust-list">
                                <span class="txn-trust-chip">
                                    <i class="bi bi-shield-check"></i> Paiement sécurisé
                                </span>
                                <span class="txn-trust-chip">
                                    <i class="bi bi-credit-card-2-front"></i> VISA / Mastercard
                                </span>
                                <span class="txn-trust-chip">
                                    <i class="bi bi-clock-history"></i> Suivi rapide
                                </span>
                            </div>
                            @else
                            <h2>Formulaire de rechargement PUSH</h2>
                            <p>Renseignez le numéro PUSH, le nom du titulaire et le montant à recharger. Les frais
                                sont calculés automatiquement.</p>
                            <div class="txn-trust-list">
                                <span class="txn-trust-chip">
                                    <i class="bi bi-shield-check"></i> Transaction sécurisée
                                </span>
                                <span class="txn-trust-chip">
                                    <i class="bi bi-phone"></i> Compte PUSH
                                </span>
                                <span class="txn-trust-chip">
                                    <i class="bi bi-receipt"></i> Demande suivie
                                </span>
                            </div>
                            @endif
                        </div>

                        @if ($type === 'reload')
                        <div class="txn-head-icon">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        @else
                        <div class="txn-logo-box">
                            <img src="https://robus.home.ci/public/dist/providers/push.png" alt="Carte PUSH">
                        </div>
                        @endif
                    </div>

                    {{-- Logos des cartes (reload uniquement) --}}
                    @if ($type === 'reload')
                    <div class="txn-card-strip">
                        @foreach (getReloadMasterCards() as $masterCard)
                        <div class="txn-card-logo">
                            <img src="{{ $masterCard->image ?? null }}"
                                alt="{{ $masterCard->name ?? 'Carte disponible' }}">
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Carte sélectionnée (push uniquement) --}}
                        @if ($type === 'push')
                        <div class="txn-selected-card">
                            <img src="https://robus.home.ci/public/dist/providers/push.png" alt="Carte PUSH">
                            <div>
                                <small>Carte sélectionnée</small>
                                <strong>{{ $cards->name ?? 'Carte PUSH' }}</strong>
                            </div>
                        </div>
                        @endif

                        <div class="txn-form-body">
                            <div class="txn-section-title">
                                <span><i class="bi bi-person-vcard"></i></span>
                                {{ $type === 'reload' ? 'Informations de la carte' : 'Informations du compte PUSH' }}
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="num_carte">
                                            @if ($type === 'reload')
                                            <i class="bi bi-credit-card text-primary"></i>
                                            Identifiant ou numéro de la carte
                                            @else
                                            <i class="bi bi-phone text-primary"></i>
                                            N° Téléphone PUSH
                                            @endif
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="num_carte" @if ($type==='push' ) maxlength="10" @endif
                                            class="form-control" id="num_carte"
                                            placeholder="{{ $type === 'reload' ? 'Ex: 12345566776' : 'Ex: 0707077777' }}"
                                            required>
                                        <div class="invalid-feedback">
                                            {{ $type === 'reload' ? "Veuillez saisir l'identifiant ou numéro de la
                                            carte" : 'Veuillez saisir le numéro de téléphone PUSH' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="all_name">
                                            <i class="bi bi-person text-primary"></i>
                                            {{ $type === 'reload' ? 'Nom et prénom sur la carte' : 'Nom complet sur le
                                            compte' }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="all_name" class="form-control" id="all_name"
                                            placeholder="Ex: KONAN Franck" required>
                                        <div class="invalid-feedback">
                                            {{ $type === 'reload' ? 'Veuillez saisir le nom et prénom sur la carte' :
                                            'Veuillez saisir le nom complet' }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Champ 4 derniers chiffres (reload uniquement) --}}
                                @if ($type === 'reload')
                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="num_digits">
                                            <i class="bi bi-123 text-primary"></i>
                                            4 derniers chiffres du recto <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="num_digits" maxlength="4" class="form-control"
                                            id="num_digits" placeholder="Ex: 1234" required>
                                        <div class="invalid-feedback">Veuillez saisir les 4 derniers chiffres</div>
                                    </div>
                                </div>
                                @endif

                                @if ($type === 'reload')
                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="my_phone">
                                            <i
                                                class="bi bi-{{ $type === 'push' ? 'telephone' : 'phone' }} text-primary"></i>
                                            {{ $type === 'reload' ? 'Numéro de téléphone' : 'Votre numéro personnel' }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="my_phone" maxlength="10" class="form-control"
                                            id="my_phone" placeholder="Ex: 0101010101" required>
                                        <div class="invalid-feedback">
                                            {{ $type === 'reload' ? 'Veuillez saisir votre numéro de téléphone' :
                                            'Veuillez saisir votre numéro personnel' }}
                                        </div>
                                    </div>
                                </div>


                                @else
                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="my_phone">
                                            <i
                                                class="bi bi-{{ $type === 'push' ? 'telephone' : 'phone' }} text-primary"></i>
                                            {{ $type === 'reload' ? 'Numéro de téléphone' : 'Votre numéro personnel' }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="my_phone" maxlength="10" class="form-control"
                                            id="my_phone" placeholder="Ex: 0101010101" required>
                                        <div class="invalid-feedback">
                                            {{ $type === 'reload' ? 'Veuillez saisir votre numéro de téléphone' :
                                            'Veuillez saisir votre numéro personnel' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="montant_txn">
                                            <i class="bi bi-cash-stack text-primary"></i>
                                            Montant (FCFA) <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control"
                                            id="{{ $type === 'reload' ? 'montant_card' : 'montant_push' }}"
                                            name="amount" min="2000" max="500000" placeholder="Ex: 2400" required>
                                        <div class="invalid-feedback">Le montant doit être entre 2.000 et 500.000 FCFA
                                        </div>
                                        <div class="txn-amount-note">
                                            <i class="bi bi-info-circle"></i>
                                            <small
                                                id="{{ $type === 'reload' ? 'montantInfoCard' : 'montantInfoPush' }}">Min:
                                                2.000 FCFA / Max: 500.000 FCFA</small>
                                        </div>
                                    </div>
                                </div>

                                @endif

                            </div>

                            @if ($type === 'reload')
                            <div class="txn-section-title mt-4">
                                <span><i class="bi bi-cash-coin"></i></span>
                                Montant et frais
                            </div>
                            @endif


                            <div class="row g-3">
                                {{-- Sélection de carte (reload uniquement) --}}
                                @if ($type === 'reload')
                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="provider">
                                            <i class="bi bi-wallet text-primary"></i>
                                            Carte à recharger <span class="text-danger">*</span>
                                        </label>
                                        <select required id="provider" name="provider"
                                            class="form-select @error('provider') is-invalid @enderror">
                                            <option disabled selected>Selectionnez la carte</option>
                                            @foreach ($cards as $card)
                                            <option value="{{ $card->id }}">{{ $card->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Veuillez sélectionner une carte</div>
                                    </div>
                                </div>
                                @endif

                                @if ($type === 'reload')
                                <div class="col-md-6">
                                    <div class="txn-field">
                                        <label for="montant_txn">
                                            <i class="bi bi-cash-stack text-primary"></i>
                                            Montant (FCFA) <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control"
                                            id="{{ $type === 'reload' ? 'montant_card' : 'montant_push' }}"
                                            name="amount" min="2000" max="500000" placeholder="Ex: 2400" required>
                                        <div class="invalid-feedback">Le montant doit être entre 2.000 et 500.000 FCFA
                                        </div>
                                        <div class="txn-amount-note">
                                            <i class="bi bi-info-circle"></i>
                                            <small
                                                id="{{ $type === 'reload' ? 'montantInfoCard' : 'montantInfoPush' }}">Min:
                                                2.000 FCFA / Max: 500.000 FCFA</small>
                                        </div>
                                    </div>
                                </div>
                                @endif


                                <div class="col-12">
                                    @include('layouts.shared_web.frais', [
                                    'total_percent' => $totalPercent,
                                    'calcul_valeurs' => $calculValeurs,
                                    ])
                                </div>
                                <div class="col-12 txn-submit-wrap">
                                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold txn-submit-btn"
                                        id="{{ $type === 'reload' ? 'submitButtonCard' : 'submitButtonPush' }}"
                                        disabled>
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
                    @if ($type === 'reload')
                    <p>Vérifiez le nom inscrit sur la carte, les 4 derniers chiffres et le montant. Une erreur peut
                        ralentir le traitement du rechargement.</p>
                    @else
                    <p>Vérifiez le numéro PUSH, le nom du titulaire et votre numéro personnel. Ces informations
                        facilitent le traitement du rechargement.</p>
                    @endif
                </div>
            </div>

            @include('layouts.shared_web.info-open')
        </div>
    </div>
</section>
