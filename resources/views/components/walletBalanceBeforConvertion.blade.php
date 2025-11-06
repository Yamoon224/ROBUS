<!-- Montant reçu -->
<div class="amount-section">
    <div class="amount-label">Ancien Solde</div>
    <div class="amount-befor">
        <div class="flex items-baseline justify-between">
            <span class="amount-received-value">
                {{ number_format($item->amount_solde_befor, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-received-currency">
                {{ $item->sidUser->wallet->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>


<!-- Solde -->
<div class="amount-section">
    <div class="amount-label">Nouveau Solde</div>
    <div class="amount-after">
        <div class="flex items-baseline justify-between">
            <span class="amount-balance-value">
                {{ number_format($item->amount_solde_after, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-balance-currency">
                {{ $item->sidUser->wallet->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>
