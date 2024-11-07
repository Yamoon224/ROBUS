<div class="col-span-6 mt-3">
    <label for="compte_number_robus" class="form-label">Numero wallet<span class="text-danger">*</span></label>

    <input wire:model.debounce.300ms="compte_number_robus" id="compte_number_robus" type="text" class="form-control"
        maxlength="9" placeholder="Numero wallet">

    {{-- Affichage du message de chargement pendant la recherche --}}
    <div wire:loading>Recherche wallet...</div>

    @php
        $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $compte_number_robus)
            ->first();

        if ($wallet_search) {
           $wallet_name = $wallet_search->sidUser->firstname . ' ' . $wallet_search->sidUser->lastname;
        } else {
            $wallet_name = 'Numero introuvable ou n\' est pas client';
        }

    @endphp

    {{-- Affichage du nom ou du message d'erreur --}}
    @if ($compte_number_robus == '')
        <div class="text-gray-500 text-sm">Entrer numero wallet</div>
    @else
        @if ($wallet_name)
            <div class="text-lg text-gray-900 text-bold text-secondary">
                <span
                    class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                    {{ $wallet_name }}
                </span>
            </div>
        @endif
    @endif

    @error('compte_number_robus')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>