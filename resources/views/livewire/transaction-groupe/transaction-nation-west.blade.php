<div>
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
        <div class="col-span-12">
            <label for="provider_transaction" id="provider_transaction" class="form-label">Canal transfert d'argent <span
                    class="text-danger">
                    *</span>
            </label>
            <select wire:model="provider_transaction" id="provider_transaction" class="form-control">
                <option value="null" disabled>Selectionnez le canal</option>
                @php
                    $provideOptions = [];
                    if (sabdy()->country->id == 39) {
                        $provideOptions = $provide_cameroun;
                    } elseif (sabdy()->country->id == 79) {
                        $provideOptions = $provide_gabon;
                    } elseif (sabdy()->country->id == 50) {
                        $provideOptions = $provide_congo;
                    } elseif (sabdy()->country->id == 57) {
                        $provideOptions = $provide_rdc;
                    } elseif (sabdy()->country->id == 122) {
                        $provideOptions = $provide_liberia;
                    } elseif (sabdy()->country->id == 43) {
                        $provideOptions = $provide_tchad;
                    } elseif (sabdy()->country->id == 42) {
                        $provideOptions = $provide_centre_afrique;
                    } elseif (sabdy()->country->id == 25) {
                        $provideOptions = $provide_west_benin;
                    } elseif (sabdy()->country->id == 36) {
                        $provideOptions = $provide_west_bf;
                    } elseif (sabdy()->country->id == 133) {
                        $provideOptions = $provide_west_mali;
                    } elseif (sabdy()->country->id == 214) {
                        $provideOptions = $provide_west_togo;
                    } elseif (sabdy()->country->id == 188) {
                        $provideOptions = $provide_west_senegal;
                    } elseif (sabdy()->country->id == 83) {
                        $provideOptions = $provide_west_ghana;
                    } elseif (sabdy()->country->id == 157) {
                        $provideOptions = $provide_west_niger;
                    } elseif (sabdy()->country->id == 91) {
                        $provideOptions = $provide_west_guinne_kona;
                    }
                @endphp
                @foreach ($provideOptions as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>

            @error('provider_transaction')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>
    </div>
    @if (!is_null($provider_transaction))

        @php
            $providerData = [
                4 => ['name' => 'Orange money', 'image' => 'orange.png'],
                3 => ['name' => 'Moov money', 'image' => 'moov.png'],
                1 => ['name' => 'Mtn money', 'image' => 'mtn.png'],
                6 => ['name' => 'Wave money', 'image' => 'wave.png'],
                71 => ['name' => 'AirTel money', 'image' => 'airtel.png'],
                72 => ['name' => 'Libertis money', 'image' => 'libertis.jpg'],
                73 => ['name' => 'Sarifacom money', 'image' => 'sarifacom.jpg'],
                74 => ['name' => 'Africell money', 'image' => 'africell.png'],
                75 => ['name' => 'Sierratel money', 'image' => 'sierratel.jpg'],
                76 => ['name' => 'M-PESA money', 'image' => 'M-PESA.png'],
                77 => ['name' => 't-kash money', 'image' => 't-kash.png'],
                9 => ['name' => 'Robus', 'image' => 'robus.png'],
                78 => ['name' => 'Telecel Money', 'image' => 'telecel_money.png'],
            ];

            $provider = $providerData[$provider_transaction] ?? null;
            $countrie_indicative = countryById($country_inter_out_civ);
        @endphp
        @if ($provider)
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/' . $provider['image']) }}" alt="{{ $provider['name'] }}">
                </div>
            </div>
            @if ($provider['name'] == 'Robus')

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

                            if ($wallet_search) {
                                if ($wallet_search->sidUser->is_custumers) {
                                    $wallet_name = $wallet_search->sidUser->firstname . ' ' . $wallet_search->sidUser->lastname;
                                } else {
                                    $wallet_name = 'Numero introuvable ou n\' est pas client';
                                }
                            } else {
                                $wallet_name = 'Numero introuvable ou n\' est pas client';
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
                    <div class="col-span-6 mt-3">
                        <label for="transaction_amount_robus" class="form-label">Commission<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ formatDisplayAmount($amount_transaction_nat_val_pay ?? null) }}
                            {{ $usersCountry }}
                        </div>

                    </div>
                    <div class="col-span-6 mt-3">
                        <label for="transaction_amount_robus" class="form-label">Montant à
                            recevoir<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ formatDisplayAmount($amount_transaction_nat_val_receive ?? null) }}
                            {{ $usersCountry }}
                        </div>
                    </div>
                @endif
            @else
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3  mt-3">
                    <div class="col-span-6 mt-1">
                        <label for="transaction_phone" class="form-label">Numero de telephone<span
                                class="text-danger">*</span></label>

                        <div class="flex">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                {{ $countrie_indicative->indecative ?? null }}
                            </div>
                            <input wire:model.defer="transaction_phone" id="transaction_phone" type="number"
                                class="form-control" placeholder="Numero" value="" maxlength="15">
                            @error('transaction_phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-span-6 mt-1">
                        <label for="name_custumers" class="form-label">Nom & Prénom(S)<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="name_custumers" id="name_custumers" min="1"
                            max="5" type="text" class="form-control" placeholder="Montant">
                        @error('name_custumers')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="col-span-12 mt-1">
                        <label class="form-label">Nature de la piece(CNI, Passeport, PC ou Autres)<span
                                class="text-danger">
                                *</span></label>
                    </div>
                    <div class="col-span-6">
                        <label for="num_piece_custumers" class="form-label">No de la piece<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="num_piece_custumers" id="num_piece_custumers" min="1"
                            max="5" type="text" class="form-control" placeholder="Montant">
                        @error('num_piece_custumers')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="col-span-6">
                        <label for="date_piece" class="form-label">Date de validité<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="date_piece" id="date_piece" min="1" max="5"
                            type="date" class="form-control" placeholder="Montant">
                        @error('date_piece')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                </div>
            @endif

        @endif

    @endif
    <div class="modal-footer">
        <button wire:click="cancelInterOutCivField" type="button"
            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
        <button wire:click="addTransactionWestWest" type="button" wire:loading.remove=""
            class="btn btn-primary w-50">Envoyer la demande</button>
        <div wire:loading wire:target="addTransactionWestWest">
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
