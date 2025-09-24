<!-- Affichage des montants calculés -->
@if (!is_null($amount_virement) && $amount_virement > 0)
    <div class="grid grid-cols-12 gap-4 gap-y-3" style="padding: 12px !important">
        @if ($user_auth->is_under_distributer == 1)
            @if ($showRemonterVirementButton)
                <div class="col-span-6">
                    <label for="amount_virement" class="form-label">Montant commission </label>
                    @if ($amount_virement == '')
                        <div class="text-gray-500 text-sm">Entrer le montant</div>
                    @else
                        <div class="px-5 py-3 alert alert-primary text-white text-2xl font-medium">
                            {{ $amount_virement_nation_pay ?? null }} {{ $usersCountry }}
                        </div>
                    @endif
                </div>
            @else
                <div class="col-span-6">
                    <label class="form-label">Pas de commission </label>
                    <div class="px-5 py-3 alert alert-secondary text-white text-2xl font-medium"></div>
                </div>
            @endif
        @else
            <div class="col-span-6">
                <label for="amount_virement" class="form-label">Montant à payer </label>
                @if ($amount_virement == '')
                    <div class="text-gray-500 text-sm">Entrer le montant</div>
                @else
                    <div class="px-5 py-3 alert alert-primary text-white text-2xl font-medium">
                        {{ $amount_virement_nation_pay ?? null }} {{ $usersCountry }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Montant à recevoir -->
        <div class="col-span-6">
            <label for="amount_virement" class="form-label">Montant à recevoir </label>
            @if ($amount_virement == '')
                <div class="text-gray-500 text-sm">Entrer le montant</div>
            @else
                <div class="px-5 py-3 alert alert-success text-white text-2xl font-medium">
                    {{ $amount_virement_nation_receive ?? null }} {{ $usersCountry }}
                </div>
            @endif
        </div>
    </div>
@endif
