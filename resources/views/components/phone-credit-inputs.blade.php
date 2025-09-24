@props([
    'number' => null,
    'amount' => null,
    'countryCode' => null,
])

<div class="col-span-6 form-group required">
    <label for="number_phone_credit" class="form-label">Numéro </label>
    <div class="flex">
        <div class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
            {{ $countryCode ?? '' }}
        </div>
        <input wire:model.defer="number_phone_credit" id="number_phone_credit"
               type="tel" class="form-control" placeholder="Numéro"
               value="{{ $number ?? '' }}" maxlength="10" required>
    </div>
    @error('number_phone_credit')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="col-span-6 form-group required">
    <label for="amount_phone_credit" class="form-label">Montant </label>
    <input wire:model.debounce.10ms="amount_phone_credit" id="amount_phone_credit"
           type="number" class="form-control" placeholder="Montant"
           value="{{ $amount ?? '' }}" required>
    @error('amount_phone_credit')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
