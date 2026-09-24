@extends('layouts.master_web')

@section('title', 'Rechargement ROBUS')

@push('styles')
<style>
    /* ===== THEME ROBUS ===== */
    [data-txn-type="robus"] {
        --txn-page-bg:
            radial-gradient(circle at 12% 15%, rgba(99, 102, 241, .10), transparent 30%),
            radial-gradient(circle at 88% 0%, rgba(139, 92, 246, .08), transparent 28%),
            linear-gradient(180deg, #f5f3ff 0%, #ffffff 54%, #f8fafc 100%);
        --txn-head-gradient: linear-gradient(135deg, #1e1b4b 0%, #3730a3 58%, #818cf8 100%);
        --txn-submit-gradient: linear-gradient(135deg, #1e1b4b 0%, #3730a3 60%, #6366f1 100%);
        --txn-submit-shadow: rgba(99, 102, 241, .22);
        --txn-field-bg: #f5f3ff;
        --txn-field-focus-border: rgba(99, 102, 241, .55);
        --txn-field-focus-shadow: rgba(99, 102, 241, .12);
        --txn-section-title-color: #1e1b4b;
        --txn-icon-color: #6366f1;
        --txn-icon-bg: rgba(99, 102, 241, .10);
        --txn-amount-note-bg: rgba(99, 102, 241, .08);
        --txn-amount-note-color: #4338ca;
    }

    /* ===== SHARED txn-* STYLES ===== */
    .txn-page { background: var(--txn-page-bg); padding: 64px 0 76px; }
    .txn-shell { align-items: flex-start; }

    .txn-form-panel {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 24px;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .10);
        overflow: hidden;
    }

    .txn-form-head {
        display: flex; gap: 18px; align-items: center;
        justify-content: space-between;
        background: var(--txn-head-gradient);
        color: #ffffff; padding: 28px;
    }

    .txn-form-head h2 { color: #ffffff; font-size: 1.35rem; font-weight: 800; line-height: 1.25; margin: 0; }
    .txn-form-head p { color: rgba(255,255,255,.82); margin: 8px 0 0; }

    .txn-head-icon {
        width: 58px; height: 58px; border-radius: 18px;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.20);
        flex: 0 0 auto;
    }
    .txn-head-icon i { color: #ffffff; font-size: 1.55rem; }

    .txn-logo-box {
        width: 72px; height: 72px; border-radius: 20px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #ffffff; border: 1px solid rgba(255,255,255,.24);
        box-shadow: 0 18px 38px rgba(30,27,75,.22);
        flex: 0 0 auto; padding: 12px;
    }
    .txn-logo-box img { max-width: 100%; max-height: 48px; object-fit: contain; }

    .txn-trust-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
    .txn-trust-chip {
        display: inline-flex; align-items: center; gap: 7px;
        min-height: 34px; padding: 8px 12px; border-radius: 999px;
        background: rgba(255,255,255,.13); color: rgba(255,255,255,.94);
        font-size: .82rem; font-weight: 700;
    }

    .txn-form-body { padding: 28px; }

    .txn-section-title {
        display: flex; align-items: center; gap: 10px;
        color: var(--txn-section-title-color);
        font-size: .95rem; font-weight: 800; margin-bottom: 16px;
    }
    .txn-section-title span {
        width: 32px; height: 32px; display: inline-flex;
        align-items: center; justify-content: center;
        border-radius: 11px;
        color: var(--txn-icon-color); background: var(--txn-icon-bg);
    }

    .txn-field {
        height: 100%; padding: 14px;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 18px; background: var(--txn-field-bg);
    }
    .txn-field label {
        display: flex; align-items: center; gap: 8px;
        color: #334155; font-size: .84rem; font-weight: 800; margin-bottom: 10px;
    }
    .txn-field .form-control,
    .txn-field .form-select {
        min-height: 48px;
        border: 1px solid rgba(15,23,42,.10) !important;
        border-radius: 14px !important;
        background-color: #ffffff; color: #0f172a;
        font-weight: 700; box-shadow: none;
    }
    .txn-field .form-control:focus,
    .txn-field .form-select:focus {
        border-color: var(--txn-field-focus-border) !important;
        box-shadow: 0 0 0 .2rem var(--txn-field-focus-shadow);
    }
    .txn-field .form-control[readonly] {
        background-color: #f8fafc;
        color: #64748b;
        cursor: default;
    }
    .txn-field-hint {
        margin-top: 8px; font-size: .80rem;
        color: #94a3b8; font-style: italic;
    }

    .txn-amount-note {
        display: flex; align-items: center; gap: 10px;
        margin-top: 12px; padding: 11px 12px; border-radius: 14px;
        background: var(--txn-amount-note-bg);
        color: var(--txn-amount-note-color);
        font-size: .84rem; font-weight: 700;
    }

    .txn-submit-wrap { display: flex; justify-content: center; }
    .txn-submit-wrap .txn-submit-btn { width: min(100%, 420px); }

    .txn-submit-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 8px; min-height: 58px; height: auto;
        padding-right: 18px !important; padding-left: 18px !important;
        border: 0; border-radius: 18px;
        background: var(--txn-submit-gradient);
        box-shadow: 0 16px 30px var(--txn-submit-shadow);
        line-height: 1.25; white-space: normal;
        overflow-wrap: anywhere; text-align: center;
    }
    .txn-submit-btn:disabled { background: #a8b3c7; box-shadow: none; cursor: not-allowed; }
    .txn-submit-btn .submit-text { display: inline-flex; }
    .txn-submit-btn .loading-spinner { flex-wrap: wrap; }
    .txn-submit-btn i { flex: 0 0 auto; }
    .txn-submit-btn .submit-text,
    .txn-submit-btn .loading-spinner {
        align-items: center; justify-content: center;
        max-width: 100%; min-width: 0; line-height: 1.25;
    }

    .txn-side-note {
        margin-top: 16px; padding: 18px; border-radius: 20px;
        background: #ffffff; border: 1px solid rgba(15,23,42,.08);
        box-shadow: 0 14px 34px rgba(15,23,42,.07);
    }
    .txn-side-note strong { display: block; color: #0f172a; font-size: .95rem; margin-bottom: 6px; }
    .txn-side-note p { color: #64748b; font-size: .88rem; margin: 0; }

    .txn-page .card { border-radius: 22px !important; }

    @media (max-width: 991.98px) {
        .txn-page { padding: 46px 0 58px; }
        .txn-form-head { align-items: flex-start; flex-direction: column; }
    }
    @media (max-width: 575.98px) {
        .txn-form-head, .txn-form-body { padding: 22px; }
        .txn-submit-btn {
            min-height: 54px; padding: 13px 14px !important;
            border-radius: 15px; font-size: .94rem;
        }
        .txn-submit-btn .submit-text,
        .txn-submit-btn .loading-spinner { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')
    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/transactions.jpeg',
        'title' => 'Réchargement par ROBUS',
        'subtitle' => 'Rechargez votre compte ROBUS en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])

    {{-- @if ($appStatusResult['success']) --}}
        <section class="txn-page contact-info-section style_percent" data-txn-type="robus">
            <div class="container">
                <div class="row txn-shell g-4 justify-content-center">
                    <div class="col-lg-8 col-md-11 col-sm-12">
                        <div class="txn-form-panel">

                            <div class="txn-form-head">
                                <div>
                                    <h2>Formulaire de rechargement ROBUS</h2>
                                    <p>Renseignez votre identifiant ROBUS, votre numéro de téléphone et le montant.
                                        Le nom du titulaire s'affiche automatiquement.</p>
                                    <div class="txn-trust-list">
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-shield-check"></i> Transaction sécurisée
                                        </span>
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-wallet2"></i> Compte ROBUS
                                        </span>
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-receipt"></i> Demande suivie
                                        </span>
                                    </div>
                                </div>
                                <div class="txn-logo-box">
                                    <img src="https://robus.home.ci/public/dist/providers/robus.png" alt="ROBUS">
                                </div>
                            </div>

                            <form method="POST" action="{{ route('web.transactions_robus_store.stores') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="txn-form-body">
                                    <div class="txn-section-title">
                                        <span><i class="bi bi-person-vcard"></i></span>
                                        Informations du compte ROBUS
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="walletId">
                                                    <i class="bi bi-person-badge text-primary"></i>
                                                    ID du compte ROBUS <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="num_robus" id="walletId"
                                                    class="form-control" placeholder="Ex: CIZW7480" required>
                                                <div class="invalid-feedback">Veuillez saisir l'ID du compte ROBUS</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="resultSearch">
                                                    <i class="bi bi-person-circle text-primary"></i>
                                                    Nom et prénom ROBUS
                                                </label>
                                                <input type="text" id="resultSearch"
                                                    class="form-control" placeholder="Nom et prénom" readonly>
                                                <p class="txn-field-hint">
                                                    <i class="bi bi-arrow-left-short"></i>
                                                    S'affiche automatiquement après saisie de l'ID
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="my_phone">
                                                    <i class="bi bi-phone text-primary"></i>
                                                    Numéro de téléphone <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="my_phone" maxlength="10"
                                                    class="form-control" id="my_phone"
                                                    placeholder="Ex: 0101010101" required>
                                                <div class="invalid-feedback">Veuillez saisir votre numéro de téléphone</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="montant_robus">
                                                    <i class="bi bi-cash-stack text-primary"></i>
                                                    Montant (FCFA) <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control" id="montant_robus"
                                                    name="amount" min="2000" max="500000"
                                                    placeholder="Ex: 2400" required>
                                                <div class="invalid-feedback">
                                                    Le montant doit être entre 2.000 et 500.000 FCFA
                                                </div>
                                                <div class="txn-amount-note">
                                                    <i class="bi bi-info-circle"></i>
                                                    <small id="montantInfoRobus">Min: 2.000 FCFA / Max: 500.000 FCFA</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            @include('layouts.shared_web.frais', [
                                                'total_percent'  => $total_percent ?? 0,
                                                'calcul_valeurs' => $calcul_valeurs ?? 0,
                                            ])
                                        </div>

                                        <div class="col-12 txn-submit-wrap">
                                            <button type="submit"
                                                class="btn btn-primary btn-lg py-3 fw-bold txn-submit-btn"
                                                id="submitButtonRobus" disabled>
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
                            <p>Vérifiez l'ID ROBUS et le nom affiché automatiquement. Une erreur d'identifiant
                                peut empêcher le traitement du rechargement.</p>
                        </div>
                    </div>

                    @include('layouts.shared_web.info-open-robus')
                </div>
            </div>
        </section>
    {{-- @else
        <section class="contact-info-section style_percent">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-10">
                        @include('layouts.shared_web.close_app')
                    </div>
                </div>
            </div>
        </section>
    @endif --}}
@endsection

@push('scripts')
<script>
    const walletInput  = document.querySelector('input[name="num_robus"]');
    const resultSearch = document.getElementById('resultSearch');

    resultSearch.value = 'Entrez un ID de wallet pour afficher le nom';

    walletInput.addEventListener('input', function () {
        searchResult();
    });

    function searchResult() {
        const walletSearch = walletInput.value;
        if (walletSearch.trim() !== '') {
            fetch('{{ route('search.wallets') }}?num_robus=' + walletSearch)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur');
                    return response.json();
                })
                .then(data => {
                    resultSearch.value = data.user_firstname
                        ? `${data.user_firstname} ${data.user_lastname}`
                        : 'Aucun utilisateur trouvé';
                })
                .catch(() => {
                    resultSearch.value = 'Numéro wallet incorrect';
                });
        } else {
            resultSearch.value = 'Saisissez l\'ID du wallet';
        }
    }
</script>
@endpush
