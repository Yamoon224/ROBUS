<div class="amount-section">
    <div class="amount-label">Montant envoyé</div>
    <div class="flex items-baseline">
        <span class="amount-value">
            {{ number_format($item->amount, 2, ',', ' ') ?? '0,00' }}
        </span>
        <span class="amount-currency">
            {{ $item->wallet->currencie->code ?? 'CFA' }}
        </span>
    </div>
</div>
<!-- Montant reçu -->
<div class="amount-section">

    @if ($item->type_transaction == 'Retrait')
        <div class="amount-label">Montant à retiré</div>
    @else
        <div class="amount-label">Montant reçu</div>
    @endif

    <div class="amount-received">
        <div class="flex items-baseline justify-between">
            <span class="amount-received-value">
                {{ number_format($item->amount_received, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-received-currency">
                {{ $item->wallet->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>

<!-- Montant en CFA -->
<div class="amount-section">
    <div class="amount-cfa">
        <div class="amount-cfa-label">Équivalent en CFA</div>
        <div class="amount-cfa-value">
            {{ number_format($valeur_amount_receveid ?? 0, 2, ',', ' ') }} CFA
        </div>
    </div>
</div>

<!-- Solde -->
<div class="amount-section">
    <div class="amount-label">Solde actuel</div>
    <div class="amount-balance">
        <div class="flex items-baseline justify-between">
            <span class="amount-balance-value">
                {{ number_format($item->amount_solde, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-balance-currency">
                {{ $item->wallet->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>
