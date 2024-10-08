<div>
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 ">

        <div class="col-span-12 mt-space">
            <label for="country_inter_out_civ" class="form-label">Pays<span class="text-danger">
                    *</span></label>
            <select wire:model.debounce.10ms="country_inter_out_civ" id="country_inter_out_civ" class="form-control">
                <option value="null" selected>Selectionnez le pays</option>
                @foreach ($countrie_inter_ivoires as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }}</option>
                @endforeach
            </select>
            @error('country_inter_out_civ')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

 
    @if (!is_null($country_inter_out_civ))
        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
            @php
                $countrie_indicative = App\Models\Custumers\Country::where('id', $country_inter_out_civ)->first();
            @endphp
            <div class="col-span-12 text-center">
                <span
                    class="alert alert-primary inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                    @if ($countrie_indicative->name_groupe == 'AFL')
                        Afrique de L'Ouest
                    @elseif ($countrie_indicative->name_groupe == 'AFC')
                        Afrique de Centrale
                    @elseif ($countrie_indicative->name_groupe == 'MAG')
                        Maghreb
                    @elseif ($countrie_indicative->name_groupe == 'URP')
                        Europe
                    @elseif ($countrie_indicative->name_groupe == 'USA')
                        Etat Unis
                    @elseif ($countrie_indicative->name_groupe == 'CIV')
                        Côte d'Ivoire
                    @elseif ($countrie_indicative->name_groupe == 'ASIE')
                        Asie
                    @elseif ($countrie_indicative->name_groupe == 'AFE')
                        Afrique de l'EST
                    @endif
                </span>
            </div>

            <input type="hidden" wire:init="loadChoose3">
            <div class="col-span-6 mt-3">
                <label for="provider_inter_out_civ" class="form-label">Canal du paiement money<span class="text-danger"> *</span></label>
                <select wire:model="provider_inter_out_civ" id="provider_inter_out_civ" class="form-control">
                    <option value="null" disabled>Selectionnez le canal</option>
                    @foreach ($providers as $provider)
                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                    @endforeach
                </select>
                @error('provider_inter_out_civ')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-span-6 mt-3">
                <label for="amount_inter_out_civ" class="form-label">Montant<span class="text-danger">*</span></label>
                <input wire:model.debounce.10ms="amount_inter_out_civ" id="amount_inter_out_civ" type="number"
                    class="form-control" placeholder="Montant">
                @error('amount_inter_out_civ')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>
    @endif
      
    @if (!is_null($country_inter_out_civ) && !is_null($provider_inter_out_civ) && !is_null($amount_inter_out_civ))

        @if (!is_null($provider_inter_out_civ))
            @php
                $providerData = [
                    49 => ['name' => 'Orange money', 'image' => 'orange.png'],
                    50 => ['name' => 'Moov money', 'image' => 'moov.png'],
                    48 => ['name' => 'Mtn money', 'image' => 'mtn.png'],
                    55 => ['name' => 'Wave money', 'image' => 'wave.png'],
                    71 => ['name' => 'AirTel money', 'image' => 'airtel.png'],
                    72 => ['name' => 'Libertis money', 'image' => 'libertis.jpg'],
                    73 => ['name' => 'Sarifacom money', 'image' => 'sarifacom.jpg'],
                    74 => ['name' => 'Africell money', 'image' => 'africell.png'],
                    75 => ['name' => 'Sierratel money', 'image' => 'sierratel.jpg'],
                    76 => ['name' => 'M-PESA money', 'image' => 'M-PESA.png'],
                    77 => ['name' => 't-kash money', 'image' => 't-kash.png'],
                    51 => ['name' => 'Robus', 'image' => 'robus.png'],
                    78 => ['name' => 'Telecel Money', 'image' => 'telecel_money.png'],
                ];

                $provider = $providerData[$provider_inter_out_civ] ?? null;
                $countrie_indicative = App\Models\Custumers\Country::where('id', $country_inter_out_civ)->first();
            @endphp

            @if ($provider)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/' . $provider['image']) }}"
                            alt="{{ $provider['name'] }}">
                    </div>
                </div>
                @if ($provider['name'] == 'Robus')
                    <div class="col-span-12">
                        <label for="phone_inter_robus_civ" class="form-label">Numero wallet<span class="text-danger">
                                *</span></label>

                        <input wire:model.debounce.10ms="phone_inter_robus_civ" id="phone_inter_robus_civ" type="text"
                            class="form-control" value="" maxlength="9" placeholder="Numero wallet">
                        <div wire:loading>Recherche wallet...</div>
                        <div wire:loading.remove></div>

                        @if ($phone_inter_robus_civ == '')
                            <div class="text-gray-500 text-sm">
                                Entrer numero wallet
                            </div>
                        @else
                            @php

                                $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $phone_inter_robus_civ)
                                    ->where('country_id', $countrie_indicative->id)
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


                        @error('phone_inter_out_civ')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de telephone<span
                                    class="text-danger">*</span></label>

                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" minlength="6" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

            @endif
        @endif

        @if (!is_null($amount_inter_out_civ))
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                @php
                    $countrie_indicative = App\Models\Custumers\Country::where('id', $country_inter_out_civ)->first();
                @endphp

                @if (sabdy()->is_under_distributer == 1)
                    <div class="col-span-12 mt-3">
                        <label for="amount_inter_out_civ" class="form-label">Commission<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-pending   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ $amount_transaction_nat_val_pay ?? null }}
                            {{ $usersCountry }}
                        </div>
                    </div>
                    <div class="col-span-6 mt-3">
                        <label for="amount_inter_out_civ" class="form-label">Montant à
                            payer<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ $amount_transaction_nat_commission_val_pay ?? null }}
                            {{ $usersCountry }}
                        </div>
                    </div>

                    <div class="col-span-6 mt-2">
                        <label for="amount_inter_out_civ" class="form-label">Montant à
                            recevoir<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ $amount_transaction_nat_val_receive ?? null }}
                            {{ $countrie_indicative->currencie->code ?? null }}
                        </div>
                    </div>
                @else
                    <div class="col-span-6 mt-3">
                        <label for="amount_inter_out_civ" class="form-label">Montant à
                            payer<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ $amount_transaction_nat_commission_val_pay ?? null }}
                            {{ $usersCountry }}
                        </div>
                    </div>

                    <div class="col-span-6 mt-2">
                        <label for="amount_inter_out_civ" class="form-label">Montant à
                            recevoir<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ $amount_transaction_nat_val_receive ?? null }}
                            {{ $countrie_indicative->currencie->code ?? null }}
                        </div>
                    </div>
                @endif
            </div>
        @endif
        <div class="modal-footer style-foo">
            <button wire:click="cancelInterOutCivField" type="button"
                class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
            <button wire:click="addInterOutCiv" type="button" wire:loading.remove="" class="btn btn-primary w-50">Envoyer
                la
                demande</button>
            <div wire:loading wire:target="addInterOutCiv">
                Traitement en cours...
            </div>
        </div>
    @endif
   
</div>
