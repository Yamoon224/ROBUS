
<!-- Montant reçu -->
<div class="amount-section">
    <div class="amount-label">Commission rémontée</div>
    <div class="amount-commission-balance">
        <div class="flex items-baseline justify-between">
            <span class="amount-received-value">
                {{ number_format($item->amount_with_draw, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-received-currency">
                {{ $item->sidUser->wallet->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>

<div class="amount-section">
    <div class="amount-label">Ancien solde</div>
    <div class="amount-received">
        <div class="flex items-baseline justify-between">
            <span class="amount-received-value">
                {{ number_format($item->old_balance, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-received-currency">
                {{ $item->sidUser->wallet->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>


<!-- Solde -->
<div class="amount-section">
    <div class="amount-label">Nouveau solde</div>
    <div class="amount-balance">
        <div class="flex items-baseline justify-between">
            <span class="amount-balance-value">
                {{ number_format($item->amount_solde, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-balance-currency">
                {{ $item->sidUser->wallet->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>

