<div class="amount-section">
        <div class="amount-label">Montant envoyé</div>
        <div class="flex items-baseline">
            <span class="amount-value">
                {{ number_format($item->pay_amount, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-currency">
                {{ $item->sidUser->wallet->currencie->code ?? 'CFA' }}
            </span>
        </div>
    </div>
     <!-- Montant reçu -->
    <div class="amount-section">
        <div class="amount-label">Montant reçu</div>
        <div class="amount-received">
            <div class="flex items-baseline justify-between">
                <span class="amount-received-value">
                    {{ number_format($item->amount_received, 2, ',', ' ') ?? '0,00' }}
                </span>
                <span class="amount-received-currency">
                    {{ $item->sidUser->wallet->currencie->code ?? 'EUR' }}
                </span>
            </div>
        </div>
    </div>


    <!-- Solde -->
    <div class="amount-section">
        <div class="amount-label">Solde envoyeur</div>
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