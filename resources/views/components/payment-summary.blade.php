@if ($showCommission)
    <div class="col-span-6">
        <label class="form-label">Commission</label>
        <div class="px-5 py-3 alert alert-primary font-medium text-white text-2xl">
            {{ formatDisplayAmount($commission ?? '-') }} {{ $currency }}
        </div>
    </div>
@elseif(!$showCommission && $showFee)
    <div class="col-span-6">
        <label class="form-label">Sans commission</label>
        <div class="px-5 py-3 alert alert-secondary font-medium text-white text-2xl"></div>
    </div>
@endif

@if ($showFee)
    <div class="col-span-6">
        <label class="form-label">Frais</label>
        <div class="px-5 py-3 alert alert-success font-medium text-white text-2xl">
            {{ formatDisplayAmount($fee ?? '-') }} {{ $currency }}
        </div>
    </div>
@endif

@if ($showTotal)
    <div class="col-span-12">
        <label class="form-label">Montant à traiter</label>
        <div class="px-5 py-3 alert alert-success font-medium text-white text-2xl">
            {{ formatDisplayAmount($total ?? '-') }} {{ $currency }}
        </div>
    </div>
@endif
