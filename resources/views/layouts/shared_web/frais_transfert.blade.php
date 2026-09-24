@once
@push('styles')
<style>
    .frais-card {
        border-radius: 18px; overflow: hidden;
        border: 1px solid rgba(15,23,42,.08);
        box-shadow: 0 12px 32px rgba(15,23,42,.08);
        background: #ffffff;
    }
    .frais-card-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; flex-wrap: wrap;
        background: var(--txn-head-gradient, linear-gradient(135deg, #1e40af 0%, #3b82f6 100%));
        padding: 14px 18px;
    }
    .frais-card-head-label {
        display: flex; align-items: center; gap: 8px;
        color: #ffffff; font-size: .88rem; font-weight: 800;
    }
    .frais-card-head-label i { font-size: 1rem; opacity: .9; }
    .frais-card-total-badge {
        display: inline-flex; align-items: center;
        padding: 5px 14px; border-radius: 999px;
        background: rgba(255,255,255,.20);
        border: 1px solid rgba(255,255,255,.30);
        color: #ffffff; font-size: .82rem; font-weight: 800;
        white-space: nowrap;
    }
    .frais-card-body {
        padding: 20px 18px;
        background: var(--txn-field-bg, #f8fafc);
    }
    .frais-card-result {
        display: flex; flex-direction: column; align-items: center;
        padding: 18px 16px; border-radius: 14px;
        background: #ffffff; border: 1px solid rgba(15,23,42,.07);
        box-shadow: 0 4px 14px rgba(15,23,42,.05);
        text-align: center;
    }
    .frais-card-result small {
        font-size: .78rem; font-weight: 600;
        color: #94a3b8; margin-bottom: 8px;
    }
    .frais-card-result-amount {
        font-size: 1.6rem; font-weight: 900; line-height: 1;
        color: var(--txn-icon-color, #3b82f6);
        margin-bottom: 6px;
    }
    .frais-card-result-detail {
        font-size: .76rem; font-weight: 600; color: #94a3b8;
    }
    .frais-card-progress { margin-top: 14px; }
    .frais-card-progress-row {
        display: flex; justify-content: space-between; margin-bottom: 6px;
    }
    .frais-card-progress-row small { font-size: .76rem; font-weight: 700; color: #64748b; }
    .frais-card-progress .progress {
        height: 6px; border-radius: 999px; background: rgba(15,23,42,.08);
    }
    .frais-card-progress .progress-bar {
        border-radius: 999px;
        background: var(--txn-head-gradient, linear-gradient(135deg, #1e40af 0%, #3b82f6 100%));
        transition: width .4s ease;
    }
</style>
@endpush
@endonce

<div class="frais-card mt-3 d-none active_transfert">

    <div class="frais-card-head">
        <div class="frais-card-head-label">
            <i class="bi bi-receipt"></i>
            Détail des Frais — Transfert Mobile Money
        </div>
        <span class="frais-card-total-badge"
              id="total-percent-transfert"
              data-total-percent-trans="{{ $total_percent ?? 0 }}">
            <i class="bi bi-percent me-1" style="font-size:.72rem;"></i>
            Total : {{ $total_percent ?? '0' }}%
        </span>
    </div>

    <div class="frais-card-body">
        <div class="frais-card-result">
            <small>Montant des frais pour votre transaction Mobile Money</small>
            <div class="frais-card-result-amount" id="resultTransfert">0 FCFA</div>
            <div class="frais-card-result-detail">Basé sur le montant saisi</div>
        </div>
    </div>

</div>
