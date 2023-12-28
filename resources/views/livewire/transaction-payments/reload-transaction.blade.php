<div>

    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
        <div class="col-span-12">
            <label for="provider_reloadcard_inter" class="form-label">Canal de rechargement de
                carte<span class="text-danger">
                    *</span></label>
            <select wire:model="provider_reloadcard_inter" id="provider_reloadcard_inter" class="form-control">
                <option value="null" selected>Selectionnez le canal</option>
                @foreach ($provider_reload_inters as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            @error('provider_reloadcard_inter')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @if (!is_null($provider_reloadcard_inter))
        @if ($provider_reloadcard_inter == 65)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/bsic.png') }}" alt="BSIC">
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reload_card_inter" id="account_number_reload_card_inter"
                        type="text" class="form-control" placeholder="Numero du compte" value=""
                        maxlength="10">
                    @error('account_number_reload_card_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6 mt-space">
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
                    <div class="col-span-6 mt-space">
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
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                        class="form-control" placeholder="Nom et prenom">
                    @error('name_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_inter" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                        type="number" class="form-control" placeholder="Montant">
                    @error('amount_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if (!is_null($amount_reloadcard_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Commission<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_credit ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @elseif($provider_reloadcard_inter == 64)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/ecobank.png') }}" alt="Ecobank">
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reload_card_inter" id="account_number_reload_card_inter"
                        type="text" class="form-control" placeholder="Numero du compte" value=""
                        maxlength="10">
                    @error('account_number_reload_card_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6 mt-space">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            name="" class="form-control">
                            <option value="null" selected> Selectionnez le pays</option>

                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6 mt-space">

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
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                        class="form-control" placeholder="Nom et prenom">
                    @error('name_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_inter" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                        type="number" class="form-control" placeholder="Montant">
                    @error('amount_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if (!is_null($amount_reloadcard_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_credit ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @elseif ($provider_reloadcard_inter == 63)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/uba.png') }}" alt="UBA BF">
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reload_card_inter" id="account_number_reload_card_inter"
                        type="text" class="form-control" placeholder="Numero du compte" value=""
                        maxlength="10">
                    @error('account_number_reload_card_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6 mt-space">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            name="" class="form-control">
                            <option value="null" selected> Selectionnez le pays</option>

                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6 mt-space">

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
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                        class="form-control" placeholder="Nom et prenom">
                    @error('name_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_inter" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                        type="number" class="form-control" placeholder="Montant">
                    @error('amount_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (!is_null($amount_reloadcard_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_credit ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @elseif ($provider_reloadcard_inter == 62)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/bni.png') }}" alt="Bni">
                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space ">
                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reload_card_inter" id="account_number_reload_card_inter"
                        type="text" class="form-control" placeholder="Numero du compte" value=""
                        maxlength="10">
                    @error('account_number_reload_card_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6 mt-space">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            name="" class="form-control">
                            <option value="null" selected> Selectionnez le pays</option>

                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6 mt-space">
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
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                        class="form-control" placeholder="Nom et prenom">
                    @error('name_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_inter" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                        type="number" class="form-control" placeholder="Montant">
                    @error('amount_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if (!is_null($amount_reloadcard_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_credit ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @elseif ($provider_reloadcard_inter == 61)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/uba.png') }}" alt="UBA CIV">
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reload_card_inter" id="account_number_reload_card_inter"
                        type="text" class="form-control" placeholder="Numero du compte" value=""
                        maxlength="10">
                    @error('account_number_reload_card_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6 mt-space">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            name="" class="form-control">
                            <option value="null" selected> Selectionnez le pays</option>

                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6 mt-space">

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
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                        class="form-control" placeholder="Nom et prenom">
                    @error('name_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_inter" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                        type="number" class="form-control" placeholder="Montant">
                    @error('amount_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if (!is_null($amount_reloadcard_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_credit ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @elseif ($provider_reloadcard_inter == 67)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/versus.png') }}" alt="versus CIV">
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reload_card_inter" id="account_number_reload_card_inter"
                        type="text" class="form-control" placeholder="Numero du compte" value=""
                        maxlength="10">
                    @error('account_number_reload_card_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6 mt-space">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            name="" class="form-control">
                            <option value="null" selected> Selectionnez le pays</option>

                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6 mt-space">

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
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                        class="form-control" placeholder="Nom et prenom">
                    @error('name_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_inter" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                        type="number" class="form-control" placeholder="Montant">
                    @error('amount_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if (!is_null($amount_reloadcard_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_credit ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @elseif ($provider_reloadcard_inter == 69)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/mastercard-mobile.png') }}" alt="Mastercard">
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                        compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reload_card_inter" id="account_number_reload_card_inter"
                        type="text" class="form-control" placeholder="Numero du compte" value=""
                        maxlength="10">
                    @error('account_number_reload_card_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6 mt-space">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="country_inter_robus" id="country_inter_robus"
                            name="" class="form-control">
                            <option value="null" selected> Selectionnez le pays</option>

                            <option value="{{ $countrie_inter_cf->id }}">
                                {{ $countrie_inter_cf->name }}</option>

                        </select>
                        @error('country_inter_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <div class="col-span-6 mt-space">

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
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                        class="form-control" placeholder="Nom et prenom">
                    @error('name_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_inter" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                        type="number" class="form-control" placeholder="Montant">
                    @error('amount_reloadcard_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if (!is_null($amount_reloadcard_inter))

                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_credit ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="amount_reloadcard_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_deposit_inter_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    @endif

    <div class="modal-footer style-foo">
        <button wire:click="cancelInterField" type="button"
            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
        <button wire:click="addReloadCard" type="button" wire:loading.remove=""
            class="btn btn-primary w-50">Envoyer la demande</button>
        <div wire:loading wire:target="addReloadCard">
            Traitement en cours...
        </div>
    </div>
</div>
