@extends('layouts.master_web')

@section('title', 'Rechargement 1XBET')

@push('styles')
<style>
    /* ===== THEME 1XBET ===== */
    [data-txn-type="xbet"] {
        --txn-page-bg:
            radial-gradient(circle at 10% 10%, rgba(220, 38, 38, .10), transparent 30%),
            radial-gradient(circle at 90% 5%, rgba(249, 115, 22, .10), transparent 28%),
            linear-gradient(180deg, #fff7f7 0%, #ffffff 52%, #f8fafc 100%);
        --txn-head-gradient: linear-gradient(135deg, #7f1d1d 0%, #dc2626 58%, #f97316 100%);
        --txn-submit-gradient: linear-gradient(135deg, #7f1d1d 0%, #dc2626 60%, #ea580c 100%);
        --txn-submit-shadow: rgba(220, 38, 38, .22);
        --txn-field-bg: #fff7f7;
        --txn-field-focus-border: rgba(220, 38, 38, .55);
        --txn-field-focus-shadow: rgba(220, 38, 38, .12);
        --txn-section-title-color: #7f1d1d;
        --txn-icon-color: #dc2626;
        --txn-icon-bg: rgba(220, 38, 38, .10);
        --txn-amount-note-bg: rgba(249, 115, 22, .12);
        --txn-amount-note-color: #9a3412;
    }

    /* ===== SHARED txn-* STYLES ===== */
    .txn-page { background: var(--txn-page-bg); padding: 64px 0 76px; }
    .txn-shell { align-items: flex-start; }

    .txn-form-panel {
        background: #ffffff;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 24px;
        box-shadow: 0 24px 70px rgba(15,23,42,.10);
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

    .txn-logo-box {
        width: 72px; height: 72px; border-radius: 20px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #ffffff; border: 1px solid rgba(255,255,255,.24);
        box-shadow: 0 18px 38px rgba(127,29,29,.22);
        flex: 0 0 auto; padding: 10px;
    }
    .txn-logo-box img { max-width: 100%; max-height: 52px; object-fit: contain; }

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
        .txn-form-head { align-items: flex-start; flex-direction: column-reverse; }
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
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/reload_acompte.jpg',
        'title' => 'Réchargement par 1XBet',
        'subtitle' => 'Rechargez votre compte 1XBet en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])

    {{-- @if ($appStatusResult['success']) --}}
        <section class="txn-page contact-info-section style_percent" data-txn-type="xbet">
            <div class="container">
                <div class="row txn-shell g-4 justify-content-center">
                    <div class="col-lg-8 col-md-11 col-sm-12">
                        <div class="txn-form-panel">

                            <div class="txn-form-head">
                                <div>
                                    <h2>Formulaire de rechargement 1XBET</h2>
                                    <p>Renseignez votre identifiant 1XBET, le nom du titulaire et le montant.
                                        Les frais sont calculés automatiquement avant l'envoi.</p>
                                    <div class="txn-trust-list">
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-shield-check"></i> Transaction sécurisée
                                        </span>
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-dice-5"></i> Compte 1XBET
                                        </span>
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-receipt"></i> Demande suivie
                                        </span>
                                    </div>
                                </div>
                                <div class="txn-logo-box">
                                    <img src="https://robus.home.ci/public/dist/providers/xbet.png" alt="1XBET">
                                </div>
                            </div>

                            <form method="POST" action="{{ route('web.transactions_xbets_store.stores') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="txn-form-body">
                                    <div class="txn-section-title">
                                        <span><i class="bi bi-person-vcard"></i></span>
                                        Informations du compte 1XBET
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="num_xbet">
                                                    <i class="bi bi-person-badge text-primary"></i>
                                                    ID du compte 1XBET <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="num_xbet" class="form-control"
                                                    id="num_xbet" placeholder="Ex: CIZW7480" required>
                                                <div class="invalid-feedback">Veuillez saisir l'ID du compte 1XBET</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="all_name">
                                                    <i class="bi bi-person text-primary"></i>
                                                    Nom et prénom 1XBET <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="all_name" class="form-control"
                                                    id="all_name" placeholder="Ex: KONAN Franck" required>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir le nom et prénom sur le compte 1XBET
                                                </div>
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
                                                <label for="montant_fixe">
                                                    <i class="bi bi-cash-stack text-primary"></i>
                                                    Montant (FCFA) <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control" id="montant_fixe"
                                                    name="amount" min="500" max="500000"
                                                    placeholder="Ex: 2400" required>
                                                <div class="invalid-feedback">
                                                    Le montant doit être entre 500 et 500.000 FCFA
                                                </div>
                                                <div class="txn-amount-note">
                                                    <i class="bi bi-info-circle"></i>
                                                    <small id="montantInfo">Min: 500 FCFA / Max: 500.000 FCFA</small>
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
                                                id="submitButton" disabled>
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
                            <p>Vérifiez l'ID 1XBET et le nom du titulaire. Ces informations sont indispensables
                                pour que le rechargement soit traité correctement.</p>
                        </div>
                    </div>

                    @include('layouts.shared_web.info-open-xbet')
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
@endpush
