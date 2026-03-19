@if (Auth::guard('web')->user()->hasPermissionTo('voir-couleurs'))
    @php
        $solde = $item->wallet->amount ?? 0.0;
        $senderCurrency = sabdy()->country?->currencie?->value;
        $currencyUser = $item->wallet?->sidUser?->country?->currencie?->value;

        // Calcul du solde (votre logique existante)
        if ($senderCurrency > $currencyUser) {
            $amount_solde = (float) $solde * (float) $senderCurrency;
        } elseif ($senderCurrency == $currencyUser) {
            $amount_solde = $solde;
        } elseif ($senderCurrency < $currencyUser) {
            $amount_solde = (float) $solde * (float) $currencyUser;
        }

        // Déterminer le niveau pour la barre de progression (max 100000)
        $percentage = min(($amount_solde / 100000) * 100, 100);

        // Déterminer la couleur et le message
        if ($amount_solde >= 100000) {
            $color = '#fbbf24';
            $bgColor = '#fef3c7';
            $textColor = '#92400e';
            $level = 'Elite'; // 💡 changé
            $icon = '🏆';
        } elseif ($amount_solde >= 50000) {
            $color = '#3b82f6';
            $bgColor = '#dbeafe';
            $textColor = '#1e40af';
            $level = 'Premium';
            $icon = '💳';
        } elseif ($amount_solde > 0) {
            $color = '#ef4444';
            $bgColor = '#fee2e2';
            $textColor = '#991b1b';
            $level = 'Standard';
            $icon = '📊';
        } else {
            $color = '#9ca3af';
            $bgColor = '#f3f4f6';
            $textColor = '#4b5563';
            $level = 'Dormant';
            $icon = '⚪';
        }
    @endphp

    <div class="wallet-status mb-4">
        <!-- Indicateur de niveau -->
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium" style="color: {{ $textColor }};">
                {{ $icon }} Solde {{ $level }}
            </span>

        </div>

        <!-- Barre de progression moderne -->
        <div class="progress-container"
            style="
            height: 24px;
            background-color: {{ $bgColor }};
            border-radius: 12px;
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        ">
            <div class="progress-bar"
                style="
                height: 100%;
                width: {{ $percentage }}%;
                background: linear-gradient(90deg, {{ $color }} 0%, {{ $color }}dd 100%);
                border-radius: 12px;
                transition: width 0.3s ease;
                position: relative;
                box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            ">
                @if ($percentage > 15)
                    <span
                        style="
                        position: absolute;
                        right: 8px;
                        top: 2px;
                        color: white;
                        font-size: 12px;
                        font-weight: bold;
                        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
                    ">
                        {{ number_format($percentage, 0) }}%
                    </span>
                @endif
            </div>
        </div>

    </div>
@endif


<!-- Email avec style amélioré -->
<div class="user-contact mt-4 p-3 rounded-lg"
    style="
    background-color: #f8fafc;
    border-left: 4px solid {{ $color ?? '#3b82f6' }};
">
    <div class="flex items-center">
        <svg class="w-4 h-4 mr-2" style="color: {{ $color ?? '#3b82f6' }};" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
            </path>
        </svg>
        <span class="text-sm text-gray-700 font-medium">
            {{ $item->email ?? 'Email non renseigné' }}
        </span>
    </div>
</div>
