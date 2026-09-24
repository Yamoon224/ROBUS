@once
@push('styles')
<style>
    .info-inter-panel {
        position: sticky; top: 24px;
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid rgba(15,23,42,.08);
        box-shadow: 0 24px 60px rgba(15,23,42,.10);
        background: #ffffff;
    }

    .info-inter-head {
        background: var(--txn-head-gradient, linear-gradient(135deg, #0c4a6e 0%, #0284c7 58%, #38bdf8 100%));
        padding: 22px 22px 18px;
        color: #ffffff;
    }
    .info-inter-head-icon {
        width: 42px; height: 42px; border-radius: 13px;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.22);
        flex: 0 0 auto;
    }
    .info-inter-head-icon i { font-size: 1.2rem; color: #ffffff; }
    .info-inter-head h6 { color: #ffffff; font-size: 1rem; font-weight: 800; margin: 0; }
    .info-inter-head small { color: rgba(255,255,255,.78); font-size: .78rem; }

    .info-inter-body { padding: 20px; }

    .info-inter-block {
        border-radius: 14px;
        border: 1px solid rgba(15,23,42,.07);
        background: #f8fafc;
        padding: 14px;
        margin-bottom: 14px;
    }
    .info-inter-block:last-child { margin-bottom: 0; }

    .info-inter-block-title {
        display: flex; align-items: center; gap: 8px;
        font-size: .82rem; font-weight: 800; color: #334155;
        margin-bottom: 12px;
    }
    .info-inter-block-title span {
        width: 26px; height: 26px; border-radius: 9px;
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--txn-icon-bg, rgba(2,132,199,.10));
        color: var(--txn-icon-color, #0284c7);
        font-size: .85rem; flex: 0 0 auto;
    }

    .info-inter-schedule-badge {
        display: inline-flex; align-items: center;
        padding: 6px 14px; border-radius: 999px;
        font-size: .82rem; font-weight: 800;
        background: rgba(2,132,199,.10); color: #0284c7;
        border: 1px solid rgba(2,132,199,.20);
    }

    .info-inter-time-row {
        display: flex; align-items: center; gap: 8px;
        flex-wrap: wrap; margin-top: 10px;
    }
    .info-inter-time-badge {
        padding: 5px 12px; border-radius: 10px;
        font-size: .82rem; font-weight: 800;
        background: #ffffff; color: #0f172a;
        border: 1px solid rgba(15,23,42,.12);
    }
    .info-inter-time-sep { font-size: .78rem; color: #94a3b8; font-weight: 600; }

    .info-inter-fee-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 10px; border-radius: 10px;
        background: #ffffff; border: 1px solid rgba(15,23,42,.07);
        margin-bottom: 8px;
    }
    .info-inter-fee-row:last-child { margin-bottom: 0; }
    .info-inter-fee-label { font-size: .82rem; color: #64748b; font-weight: 600; }
    .info-inter-fee-val {
        font-size: .82rem; font-weight: 800; color: #0284c7;
        min-width: 56px; text-align: right;
    }

    .info-inter-services {
        display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    }
    .info-inter-service-chip {
        display: flex; align-items: center; gap: 7px;
        padding: 8px 10px; border-radius: 11px;
        background: #ffffff; border: 1px solid rgba(15,23,42,.07);
        font-size: .78rem; font-weight: 700; color: #334155;
    }
    .info-inter-service-chip i {
        font-size: .88rem; color: var(--txn-icon-color, #0284c7);
        flex: 0 0 auto;
    }

    .info-inter-footer {
        margin: 20px 20px 20px;
        padding: 12px 14px; border-radius: 12px;
        background: rgba(2,132,199,.07);
        border: 1px solid rgba(2,132,199,.15);
        display: flex; align-items: center; gap: 10px;
    }
    .info-inter-footer i { color: #0284c7; font-size: 1rem; flex: 0 0 auto; }
    .info-inter-footer small { font-size: .80rem; font-weight: 700; color: #075985; line-height: 1.4; }
</style>
@endpush
@endonce

<div class="col-lg-4 col-md-4 col-sm-12">
    <div class="info-inter-panel">

        <div class="info-inter-head">
            <div class="d-flex align-items-center gap-3">
                <div class="info-inter-head-icon">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div>
                    <h6>Informations du service</h6>
                    <small>Détails et horaires d'ouverture</small>
                </div>
            </div>
        </div>

        <div class="info-inter-body">

            <!-- Horaires -->
            <div class="info-inter-block">
                <div class="info-inter-block-title">
                    <span><i class="bi bi-clock"></i></span>
                    Horaires d'ouverture
                </div>
                <span class="info-inter-schedule-badge">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ getAppCloseWeb()->day_of_week ?? 'N/A' }}
                </span>
                <div class="info-inter-time-row">
                    <span class="info-inter-time-sep">De</span>
                    <span class="info-inter-time-badge">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? '00:00:00')->format('H:i') }}
                    </span>
                    <span class="info-inter-time-sep">à</span>
                    <span class="info-inter-time-badge">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? '00:00:00')->format('H:i') }}
                    </span>
                </div>
            </div>

            <!-- Frais -->
            <div class="info-inter-block">
                <div class="info-inter-block-title">
                    <span><i class="bi bi-percent"></i></span>
                    Structure des frais
                </div>
                <div class="info-inter-fee-row">
                    <span class="info-inter-fee-label">Frais de paiement</span>
                    <span class="info-inter-fee-val" id="total-percent-wave">—</span>
                </div>
                <div class="info-inter-fee-row">
                    <span class="info-inter-fee-label">Frais de transaction</span>
                    <span class="info-inter-fee-val" id="total-percent-robus">—</span>
                </div>
            </div>

            <!-- Services -->
            <div class="info-inter-block">
                <div class="info-inter-block-title">
                    <span><i class="bi bi-grid"></i></span>
                    Services disponibles
                </div>
                <div class="info-inter-services">
                    <div class="info-inter-service-chip">
                        <i class="bi bi-phone"></i> Cartes prépayées
                    </div>
                    <div class="info-inter-service-chip">
                        <i class="bi bi-wallet2"></i> Comptes money
                    </div>
                    <div class="info-inter-service-chip">
                        <i class="bi bi-sim"></i> Crédits télécom
                    </div>
                    <div class="info-inter-service-chip">
                        <i class="bi bi-globe2"></i> International
                    </div>
                    <div class="info-inter-service-chip">
                        <i class="bi bi-dice-5"></i> Paiement 1XBET
                    </div>
                    <div class="info-inter-service-chip">
                        <i class="bi bi-lightning"></i> Paiement en ligne
                    </div>
                </div>
            </div>

        </div>

        <div class="info-inter-footer">
            <i class="bi bi-shield-check"></i>
            <small>Service 100% sécurisé et instantané</small>
        </div>

    </div>
</div>
