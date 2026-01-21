<div class="amount-section">
    <div class="amount-label">Commissions</div>
    <div class="amount-balance">
        <div class="flex items-baseline justify-between">
            <span class="amount-balance-value">
                {{ number_format($item->commissions, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-balance-currency">
                {{ $item->wallet->sidUser->country->currencie->code ?? "FCFA" }}

            </span>
        </div>
    </div>
</div>
