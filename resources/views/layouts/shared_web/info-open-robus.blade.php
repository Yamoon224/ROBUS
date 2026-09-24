@once
@push('styles')
<style>
    .info-panel {
        position: sticky; top: 24px;
        border-radius: 22px; overflow: hidden;
        border: 1px solid rgba(15,23,42,.08);
        box-shadow: 0 24px 60px rgba(15,23,42,.10);
        background: #ffffff;
    }
    .info-panel-head {
        background: var(--txn-head-gradient, linear-gradient(135deg, #1e40af 0%, #3b82f6 100%));
        padding: 22px 22px 18px; color: #ffffff;
    }
    .info-panel-head-icon {
        width: 42px; height: 42px; border-radius: 13px;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.22);
        flex: 0 0 auto;
    }
    .info-panel-head-icon i { font-size: 1.2rem; color: #ffffff; }
    .info-panel-head h6 { color: #ffffff; font-size: 1rem; font-weight: 800; margin: 0; }
    .info-panel-head small { color: rgba(255,255,255,.78); font-size: .78rem; }
    .info-panel-body { padding: 20px; }
    .info-panel-block {
        border-radius: 14px; border: 1px solid rgba(15,23,42,.07);
        background: #f8fafc; padding: 14px; margin-bottom: 14px;
    }
    .info-panel-block:last-child { margin-bottom: 0; }
    .info-panel-block-title {
        display: flex; align-items: center; gap: 8px;
        font-size: .82rem; font-weight: 800; color: #334155; margin-bottom: 12px;
    }
    .info-panel-block-title span {
        width: 26px; height: 26px; border-radius: 9px;
        display: inline-flex; align-items: center; justify-content: center;
        background: var(--txn-icon-bg, rgba(59,130,246,.10));
        color: var(--txn-icon-color, #3b82f6);
        font-size: .85rem; flex: 0 0 auto;
    }
    .info-panel-schedule-badge {
        display: inline-flex; align-items: center;
        padding: 6px 14px; border-radius: 999px;
        font-size: .82rem; font-weight: 800;
        background: var(--txn-icon-bg, rgba(59,130,246,.10));
        color: var(--txn-icon-color, #3b82f6);
        border: 1px solid rgba(15,23,42,.08);
    }
    .info-panel-time-row {
        display: flex; align-items: center; gap: 8px;
        flex-wrap: wrap; margin-top: 10px;
    }
    .info-panel-time-badge {
        padding: 5px 12px; border-radius: 10px;
        font-size: .82rem; font-weight: 800;
        background: #ffffff; color: #0f172a;
        border: 1px solid rgba(15,23,42,.12);
    }
    .info-panel-time-sep { font-size: .78rem; color: #94a3b8; font-weight: 600; }
    .info-panel-fee-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 10px; border-radius: 10px;
        background: #ffffff; border: 1px solid rgba(15,23,42,.07);
        margin-bottom: 8px;
    }
    .info-panel-fee-row:last-child { margin-bottom: 0; }
    .info-panel-fee-label { font-size: .82rem; color: #64748b; font-weight: 600; }
    .info-panel-fee-val {
        font-size: .82rem; font-weight: 800;
        color: var(--txn-icon-color, #3b82f6);
        min-width: 56px; text-align: right;
    }
    .info-panel-fee-total {
        display: flex; justify-content: space-between; align-items: center;
        padding: 9px 10px; border-radius: 10px; margin-top: 6px;
        background: var(--txn-icon-bg, rgba(59,130,246,.08));
        border: 1px solid rgba(15,23,42,.07);
    }
    .info-panel-fee-total span { font-size: .82rem; font-weight: 800; color: #0f172a; }
    .info-panel-fee-total strong {
        font-size: .88rem; font-weight: 800;
        color: var(--txn-icon-color, #3b82f6);
    }
    .info-panel-services {
        display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    }
    .info-panel-service-chip {
        display: flex; align-items: center; gap: 7px;
        padding: 8px 10px; border-radius: 11px;
        background: #ffffff; border: 1px solid rgba(15,23,42,.07);
        font-size: .78rem; font-weight: 700; color: #334155;
    }
    .info-panel-service-chip i {
        font-size: .88rem;
        color: var(--txn-icon-color, #3b82f6);
        flex: 0 0 auto;
    }
    .info-panel-footer {
        margin: 0 20px 20px;
        padding: 12px 14px; border-radius: 12px;
        background: var(--txn-icon-bg, rgba(59,130,246,.07));
        border: 1px solid rgba(15,23,42,.08);
        display: flex; align-items: center; gap: 10px;
    }
    .info-panel-footer i {
        color: var(--txn-icon-color, #3b82f6);
        font-size: 1rem; flex: 0 0 auto;
    }
    .info-panel-footer small { font-size: .80rem; font-weight: 700; color: #334155; line-height: 1.4; }
</style>
@endpush
@endonce

<div class="col-lg-4 col-md-4 col-sm-12">
    <div class="info-panel">

        <div class="info-panel-head">
            <div class="d-flex align-items-center gap-3">
                <div class="info-panel-head-icon">
                    <i class="bi bi-info-circle"></i>
                </div>
                <div>
                    <h6>Informations du service</h6>
                    <small>Détails et horaires d'ouverture</small>
                </div>
            </div>
        </div>

        <div class="info-panel-body">

            <!-- Horaires -->
            <div class="info-panel-block">
                <div class="info-panel-block-title">
                    <span><i class="bi bi-clock"></i></span>
                    Horaires d'ouverture
                </div>
                <span class="info-panel-schedule-badge">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ getAppCloseWeb()->day_of_week ?? 'N/A' }}
                </span>
                <div class="info-panel-time-row">
                    <span class="info-panel-time-sep">De</span>
                    <span class="info-panel-time-badge">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? '00:00:00')->format('H:i') }}
                    </span>
                    <span class="info-panel-time-sep">à</span>
                    <span class="info-panel-time-badge">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? '00:00:00')->format('H:i') }}
                    </span>
                </div>
            </div>

            <!-- Frais -->
            <div class="info-panel-block">
                <div class="info-panel-block-title">
                    <span><i class="bi bi-percent"></i></span>
                    Structure des frais
                </div>
                <div class="info-panel-fee-row">
                    <span class="info-panel-fee-label">Frais de transfert ROBUS</span>
                    <span class="info-panel-fee-val">{{ $transaction_percent ?? '0' }}%</span>
                </div>
                <div class="info-panel-fee-row">
                    <span class="info-panel-fee-label">Frais opérateur (ROBUS)</span>
                    <span class="info-panel-fee-val">{{ $robus_percent ?? '0' }}%</span>
                </div>
                <div class="info-panel-fee-total">
                    <span>Total des frais</span>
                    <strong>{{ ($transaction_percent ?? 0) + ($robus_percent ?? 0) }}%</strong>
                </div>
            </div>

            <!-- Services -->
            <div class="info-panel-block">
                <div class="info-panel-block-title">
                    <span><i class="bi bi-grid"></i></span>
                    Services disponibles
                </div>
                <div class="info-panel-services">
                    <div class="info-panel-service-chip">
                        <i class="bi bi-wallet2"></i> Compte ROBUS
                    </div>
                    <div class="info-panel-service-chip">
                        <i class="bi bi-phone"></i> Cartes prépayées
                    </div>
                    <div class="info-panel-service-chip">
                        <i class="bi bi-arrow-left-right"></i> Transferts
                    </div>
                    <div class="info-panel-service-chip">
                        <i class="bi bi-sim"></i> Crédits télécom
                    </div>
                    <div class="info-panel-service-chip">
                        <i class="bi bi-globe2"></i> International
                    </div>
                    <div class="info-panel-service-chip">
                        <i class="bi bi-receipt"></i> Suivi demande
                    </div>
                </div>
            </div>

        </div>

        <div class="info-panel-footer">
            <i class="bi bi-shield-check"></i>
            <small>Service 100% sécurisé et instantané</small>
        </div>

    </div>
</div>
