<div>
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
        <div class="col-span-12">
            <label for="modal-form-1" class="form-label">Canal transfert d'argent <span class="text-danger">
                    *</span></label>
            <select wire:model="provider_transaction" id="modal-form-1" class="form-control">
                <option value="null" disabled>Selectionnez le canal</option>
                @foreach ($provide_usa as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('provider_transaction')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>
    </div>
    @if (!is_null($provider_transaction))
        @if ($provider_transaction == 9)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/robus.png') }}" alt="image"wire:model="image">
                </div>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                <div class="col-span-6">
                    <label for="compte_number_robus" class="form-label">Numero wallet<span class="text-danger">
                            *</span></label>

                    <input wire:model.debounce.10ms="compte_number_robus" id="compte_number_robus" type="text"
                        class="form-control" value="" maxlength="9" placeholder="Numero wallet">
                    <div wire:loading>Recherche wallet...</div>
                    <div wire:loading.remove></div>

                    @if ($compte_number_robus == '')
                        <div class="text-gray-500 text-sm">
                            Entrer numero wallet
                        </div>
                    @else
                        @php
                            $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $compte_number_robus)
                                // ->where('country_id', sabdy()->country->id)
                                ->first();
                            
                            if ($wallet_search != null) {
                                $wallet_name = $wallet_search->sidUser->firstname . ' ' . $wallet_search->sidUser->lastname;
                            } else {
                                $wallet_name = 'Numero introuvable';
                            }
                            
                        @endphp

                        <div class="text-lg text-gray-900 text-bold text-secondary ">
                            <span
                                class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                {{ $wallet_name ?? null }}
                            </span>

                        </div>
                    @endif


                    @error('compte_number_robus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-6">
                    <label for="transaction_amount_robus" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="transaction_amount_robus" id="transaction_amount_robus"
                        min="1" max="5" type="number" class="form-control" placeholder="Montant">
                    @error('transaction_amount_robus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (!is_null($transaction_amount_robus))
                    @if (sabdy()->is_under_distributer == 1)

                        <div class="col-span-6 mt-3">
                            <label for="transaction_amount_robus" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>
                            @if ($transaction_amount_robus == '')
                                <div class="text-gray-500 text-sm">
                                    Entrer le montant
                                </div>
                            @else
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_transaction_nat_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="transaction_amount_robus" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>
                            @if ($transaction_amount_robus == '')
                                <div class="text-gray-500 text-sm">
                                    Entrer le montant
                                </div>
                            @else
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_transaction_nat_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="col-span-6 mt-3">
                        <label for="transaction_amount_robus" class="form-label">Montant à
                            recevoir<span class="text-danger">
                                *</span></label>
                        @if ($transaction_amount_robus == '')
                            <div class="text-gray-500 text-sm">
                                Entrer le montant
                            </div>
                        @else
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_transaction_nat_val_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        
        @endif
    @endif
    <div class="modal-footer">
        <button wire:click="cancelInterOutCivField" type="button"
            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
        <button wire:click="addTransactionUsa" type="button" wire:loading.remove=""
            class="btn btn-primary w-50">Envoyer la demande</button>
        <div wire:loading wire:target="addTransactionUsa">
            Traitement en cours...
        </div>

    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        Echo.private('wallet-transfers-created.').listen(
            '.Created_wallet_transfers',
            (e) => {
                window.livewire.emit('notifyNewTransfers:' + e.id);
            },
        )

    });
</script>
