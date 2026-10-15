<div>

    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
        <div class="col-span-12">
            <label for="provider_Inter_virement" class="form-label">Canal du versement bancaire<span class="text-danger">
                    *</span></label>
            <select wire:model.debounce.10ms="provider_Inter_virement" id="provider_Inter_virement" class="form-control">
                <option value="null" disabled>Selectionnez le canal</option>
                @foreach ($provider_inter_virements as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('provider_Inter_virement')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @if (!is_null($provider_Inter_virement))
        @if ($provider_Inter_virement == 59)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/ecobank.png') }}" alt="ecobank money">
                </div>
            </div>


            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="firstname_user_inter" class="form-label">Nom client<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="firstname_user_inter" id="firstname_user_inter" type="text"
                        class="form-control" placeholder="Nom du client">
                    @error('firstname_user_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="lastname_user_inter" class="form-label">Prenom client<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="lastname_user_inter" id="lastname_user_inter" type="text"
                        class="form-control" placeholder="Prenom du client">
                    @error('lastname_user_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 mt-space-padding">
                <div class="col-span-6">
                    <label for="account_number_user_bank_inter" class="form-label">Numero
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_user_bank_inter" id="account_number_user_bank_inter"
                        type="number" class="form-control" placeholder="Numero du compte" />
                    @error('account_number_user_bank_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus" name=""
                            class="form-control">
                            <option value="null" selected> Selectionnez le pays</option>

                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6">

                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

            </div>
            @if (!is_null($country_inter_robus))

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 mt-space-padding">

                    @php
                        $countrie_indicative = countryById($country_inter_robus);
                    @endphp
                    <div class="col-span-6">
                        <label for="phone_user_inter" class="form-label">Numero<span class="text-danger">
                                *</span></label>

                        <div class="flex">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                {{ $countrie_indicative->indecative ?? null }}
                            </div>

                            <input wire:model.defer="phone_user_inter" id="phone_user_inter" type="number"
                                class="form-control" placeholder="Montant" value="" maxlength="15">
                        </div>

                        @error('phone_user_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="amount_virement_inter" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="amount_virement_inter" id="amount_virement_inter"
                            type="number" class="form-control" placeholder="Montant">
                        @error('amount_virement_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @if (!is_null($amount_virement_inter))
                        @if (sabdy()->is_under_distributer == 1)
                            <div class="col-span-12 mt-3">
                                <label for="amount_virement_inter" class="form-label">Commission<span
                                        class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ formatDisplayAmount($amount_received_credit ?? null) }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_virement_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ formatDisplayAmount($amount_virement_inter_val_pay ?? null) }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_virement_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ formatDisplayAmount($amount_virement_inter_val_receive ?? null) }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @else
                            <div class="col-span-6 mt-3">
                                <label for="amount_virement_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ formatDisplayAmount($amount_virement_inter_val_pay ?? null) }}
                                    {{ $usersCountry }}
                                </div>

                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_virement_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ formatDisplayAmount($amount_virement_inter_val_receive ?? null) }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        @elseif($provider_Inter_virement == 60)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/nsia.png') }}" alt="nsia money">
                </div>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            {{-- @foreach ($countries as $item) --}}
                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>
                            {{-- @endforeach --}}
                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6">

                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="col-span-6">
                    <label for="account_number_user_bank_inter" class="form-label">Numero
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_user_bank_inter" id="account_number_user_bank_inter"
                        type="number" class="form-control" placeholder="Numero du compte" />
                    @error('account_number_user_bank_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 mt-space-padding">
                <div class="col-span-6">
                    <label for="firstname_user_inter" class="form-label">Nom client<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="firstname_user_inter" id="firstname_user_inter" type="text"
                        class="form-control" placeholder="Nom du client">
                    @error('firstname_user_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="lastname_user_inter" class="form-label">Prenom client<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="lastname_user_inter" id="lastname_user_inter" type="text"
                        class="form-control" placeholder="Prenom du client">
                    @error('lastname_user_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @if (!is_null($country_inter_robus))
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 mt-space-padding">
                    @php
                        $countrie_indicative = countryById($country_inter_robus);
                    @endphp
                    <div class="col-span-6">
                        <label for="phone_user_inter" class="form-label">Numero<span class="text-danger">
                                *</span></label>

                        <div class="flex">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                {{ $countrie_indicative->indecative ?? null }}
                            </div>

                            <input wire:model.defer="phone_user_inter" id="phone_user_inter" type="number"
                                class="form-control" placeholder="Montant" value="" maxlength="15">
                        </div>

                        @error('phone_user_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="amount_virement_inter" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="amount_virement_inter" id="amount_virement_inter"
                            type="number" class="form-control" placeholder="Montant">
                        @error('amount_virement_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                @if (!is_null($amount_virement_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_virement_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ formatDisplayAmount($amount_received_credit ?? null) }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_virement_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ formatDisplayAmount($amount_virement_inter_val_pay ?? null) }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_virement_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ formatDisplayAmount($amount_virement_inter_val_receive ?? null) }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_virement_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ formatDisplayAmount($amount_virement_inter_val_pay ?? null) }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_virement_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ formatDisplayAmount($amount_virement_inter_val_receive ?? null) }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    @endif

    <div class="modal-footer  mt-space-padding">
        <button wire:click="cancelInterVirementField" type="button"
            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
        <button wire:click="addInterVirement" type="button" wire:loading.remove=""
            class="btn btn-primary w-50">Envoyer la demande</button>
        <div wire:loading wire:target="addInterVirement">
            Traitement en cours...
        </div>
    </div>
</div>
