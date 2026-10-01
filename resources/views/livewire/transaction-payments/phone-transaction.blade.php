<div>

    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
        <div class="col-span-12">
            <label for="provider_phone_credit_inter" class="form-label">Canal du paiement de credit
                telephonique<span class="text-danger">
                    *</span></label>
            <select wire:model="provider_phone_credit_inter" id="provider_phone_credit_inter" class="form-control">
                <option value="null" selected>Selectionnez le canal</option>
                @foreach ($provider_credits_inter as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('provider_phone_credit_inter')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @if (!is_null($provider_phone_credit_inter))
        @if ($provider_phone_credit_inter == 56)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/orange.png') }}" alt="Orange money">
                </div>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-12">
                        <label for="country_inter_robus_inter" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus_inter" id="country_inter_robus_inter"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>
                        </select>
                        @error('country_inter_robus_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-12">
                        <label for="country_inter_robus_inter" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model="country_inter_robus_inter" id="country_inter_robus_inter"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('country_inter_robus_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                @endif
            </div>
            @if (!is_null($country_inter_robus_inter))
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @php
                        $countrie_indicative = App\Models\Custumers\Country::whereId($country_inter_robus_inter)->first();
                    @endphp
                    <div class="col-span-6 mt-space-padding">
                        <label for="number_phone_credit_inter" class="form-label">Numero<span class="text-danger">
                                *</span></label>

                        <div class="flex">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                {{ $countrie_indicative->indecative ?? null }}
                            </div>

                            <input wire:model.defer="number_phone_credit_inter" id="number_phone_credit_inter"
                                type="number" class="form-control" placeholder="Montant" value="" maxlength="15">
                        </div>

                        @error('number_phone_credit_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-6 mt-space-padding">
                        <label for="amount_phone_credit_inter" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="amount_phone_credit_inter" id="amount_phone_credit"
                            type="number" class="form-control" placeholder="Montant">
                        @error('amount_phone_credit_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @if (!is_null($amount_phone_credit_inter))
                        @if (sabdy()->is_under_distributer == 1)
                            <div class="col-span-12 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Commission<span
                                        class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_received_credit ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @else
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
        
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        @elseif($provider_phone_credit_inter == 57)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/moov.png') }}" alt="Moov money">
                </div>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-12">
                        <label for="country_inter_robus_inter" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus_inter" id="country_inter_robus_inter"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>
                        </select>
                        @error('country_inter_robus_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-12">
                        <label for="country_inter_robus_inter" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus_inter" id="country_inter_robus_inter"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('country_inter_robus_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                @endif

            </div>
            @if (!is_null($country_inter_robus_inter))

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @php
                        $countrie_indicative = App\Models\Custumers\Country::whereId($country_inter_robus_inter)->first();
                    @endphp
                    <div class="col-span-6 mt-space-padding">
                        <label for="number_phone_credit_inter" class="form-label">Numero<span class="text-danger">
                                *</span></label>

                        <div class="flex">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                {{ $countrie_indicative->indecative ?? null }}
                            </div>

                            <input wire:model.defer="number_phone_credit_inter" id="number_phone_credit_inter"
                                type="number" class="form-control" placeholder="Montant" value=""
                                maxlength="15">
                        </div>

                        @error('number_phone_credit_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-6 mt-space-padding">
                        <label for="amount_phone_credit_inter" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="amount_phone_credit_inter" id="amount_phone_credit"
                            type="number" class="form-control" placeholder="Montant">
                        @error('amount_phone_credit_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @if (!is_null($amount_phone_credit_inter))
                        @if (sabdy()->is_under_distributer == 1)
                            <div class="col-span-12 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Commission<span
                                        class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_received_credit ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @else
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
        
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        @elseif($provider_phone_credit_inter == 58)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/mtn.png') }}" alt="Orange money">
                </div>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-12">
                        <label for="country_inter_robus_inter" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus_inter" id="country_inter_robus_inter"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-12">
                        <label for="country_inter_robus_inter" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus_inter" id="country_inter_robus_inter"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('country_inter_robus_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                @endif


            </div>
            @if (!is_null($country_inter_robus_inter))

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @php
                        $countrie_indicative = App\Models\Custumers\Country::whereId($country_inter_robus_inter)->first();
                    @endphp
                    <div class="col-span-6 mt-space-padding">
                        <label for="number_phone_credit_inter" class="form-label">Numero<span class="text-danger">
                                *</span></label>

                        <div class="flex">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                {{ $countrie_indicative->indecative ?? null }}
                            </div>

                            <input wire:model.defer="number_phone_credit_inter" id="number_phone_credit_inter"
                                type="number" class="form-control" placeholder="Montant" value=""
                                maxlength="15">
                        </div>

                        @error('number_phone_credit_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-6 mt-space-padding">
                        <label for="amount_phone_credit_inter" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="amount_phone_credit_inter" id="amount_phone_credit"
                            type="number" class="form-control" placeholder="Montant">
                        @error('amount_phone_credit_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @if (!is_null($amount_phone_credit_inter))
                        @if (sabdy()->is_under_distributer == 1)
                            <div class="col-span-12 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Commission<span
                                        class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_received_credit ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @else
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>
        
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
        
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_phone_credit_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_phone_credit_val_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        @endif
     
    @endif
    <div class="modal-footer style-foo">
        <button wire:click="cancelInterPhoneField" type="button"
            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
        <button wire:click="addInterPhone" type="button" wire:loading.remove=""
            class="btn btn-primary w-50">Envoyer la demande</button>
        <div wire:loading wire:target="addInterPhone">
            Traitement en cours...
        </div>
    </div>

</div>
