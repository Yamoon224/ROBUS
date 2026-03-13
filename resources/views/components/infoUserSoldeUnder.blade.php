@php
    $wallet_users = \App\Models\Wallets\Wallet::Where('wallet_id', $item->account_wallet)->first();
@endphp

<span class="bg-success w-60 text-white fs-5 px-1 py-1 rounded">
    Informations sur le solde du bénéficiaire
</span>

<div class="amount-section mt-6">
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

<!-- Montant en CFA -->
<div class="amount-section">
    <div class="amount-cfa">
        <div class="amount-cfa-label">Solde avant operation</div>
        <div class="amount-cfa-value">
            {{ number_format($amount_solde_before_drawal ?? 0, 2, ',', ' ') }} CFA
        </div>
    </div>
</div>

<!-- Solde -->
<div class="amount-section">
    <div class="amount-label">Solde actuel</div>
    <div class="amount-balance">
        <div class="flex items-baseline justify-between">
            <span class="amount-balance-value">
                {{ number_format($item->amount_solde_drawal, 2, ',', ' ') ?? '0,00' }}
            </span>
            <span class="amount-balance-currency">
                {{ $wallet_users->currencie->code ?? 'EUR' }}
            </span>
        </div>
    </div>
</div>
