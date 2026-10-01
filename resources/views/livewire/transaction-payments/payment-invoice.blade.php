<div>
    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
        <div class="col-span-12">
            <label for="provider_payment_inter" class="form-label">Canal du paiement de facture<span class="text-danger">
                    *</span></label>
            <select wire:model="provider_payment_inter" id="provider_payment_inter" class="form-control">
                <option value="null" disabled>Selectionnez le canal</option>
                @foreach ($provider_paiements_inter as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }}</option>
                @endforeach
            </select>
            @error('provider_payment_inter')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>
    </div>
    @if (!is_null($provider_payment_inter))

        @if ($provider_payment_inter == 52)

            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/canal.png') }}" alt="MTN money">
                </div>
            </div>
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <div class="alert alert-secondary show mb-2" role="alert">
                        Faire le choix entre les deux numero
                    </div>
                </div>
                <div class="col-span-12">
                    <label class="form-label" for="paiementChoix_phone">Le choix de numero<span class="text-danger">
                            *</span></label>
                    <select wire:model="paiementChoix_phone" id="paiementChoix_phone" class="form-control">
                        <option value="null" disabled>Selectionnez type numero</option>
                        <option value="1">Numero de telephone</option>
                        <option value="2">Numero du decodeur</option>
                    </select>
                </div>
            </div>
            @if (!is_null($paiementChoix_phone))

                @if ($paiementChoix_phone == 1)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">

                        @if (sabdy()->country->code != 'CI')
                            <div class="col-span-6">
                                <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                        *</span></label>
                                <select wire:model="country_inter_robus" id="country_inter_robus" class="form-control">
                                    <option value="null" selected>Selectionnez le pays</option>
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
                                <select wire:model="country_inter_robus" id="country_inter_robus" class="form-control">
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
                            @php
                                $countrie_indicative = App\Models\Custumers\Country::whereId(107)->first();
                            @endphp
                            <label for="paiement_phone_canal_inter" class="form-label">Numero du
                                telephone(10
                                chiffres)<span class="text-danger">
                                    *</span></label>
                            <div class="flex">

                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="paiement_phone_canal_inter" id="paiement_phone_canal_inter"
                                    type="text" class="form-control" value="" maxlength="10"
                                    placeholder="Numero du telephone">
                                @error('paiement_phone_canal_inter')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @elseif ($paiementChoix_phone == 2)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="paiement_decoder_canal_inter" class="form-label">Numero du
                                decodeur(14 chiffres)<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="paiement_decoder_canal_inter" id="paiement_decoder_canal_inter"
                                type="text" class="form-control" value="" maxlength="14"
                                placeholder="Numero du decodeur">
                            @error('paiement_decoder_canal_inter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (sabdy()->country->code != 'CI')
                            <div class="col-span-6">
                                <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                        *</span></label>
                                <select wire:model="country_inter_robus" id="country_inter_robus" class="form-control">
                                    <option value="null" selected>Selectionnez le pays</option>
                                    <option selected value="{{ $countrie_inter_cf->id }}">
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
                                <select wire:model="country_inter_robus" id="country_inter_robus" class="form-control">
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
                @endif
                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="offer_paiement_inter" class="form-label">Offre<span class="text-danger">
                                *</span></label>
                        <select wire:model="offer_paiement" class="select2 form-select">
                            <option value="null" disabled>Selectionnez offre</option>
                            @foreach ($offers_canal as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach

                        </select>
                        @error('offer_paiement')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @if (!is_null($offer_paiement))
                        @if (sabdy()->is_under_distributer == 1)
                            <div class="col-span-6 mt-3">
                                <label for="offer_paiement" class="form-label">Commission<span class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_received_canal ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @else
                            <div class="col-span-6 mt-3">

                                <label for="offer_paiement" class="form-label">Montant <span class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_paiement_invoice_canal_inter ?? null }}
                                    {{ $usersCountry }}
                                </div>


                            </div>
                        @endif
                        <div class="col-span-6 mt-3">

                            <label for="offer_paiement" class="form-label">Montant à payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_paiement_invoice_canal_inter ?? null }}
                                {{ $usersCountry }}
                            </div>


                        </div>

                    @endif

                </div>
            @endif
        @elseif($provider_payment_inter == 53)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/cie.png') }}" alt="MTN money">
                </div>
            </div>
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label class="form-label" for="facture_choix_type">Le choix de type de
                        facture<span class="text-danger">
                            *</span></label>
                    <select wire:model="facture_choix_type" id="facture_choix_type" class="form-control">
                        <option value="null" disabled>Choix type facture</option>
                        <option value="1">Facture prépayée</option>
                        <option value="2">Facture normale</option>
                    </select>
                </div>
            </div>
            @if (!is_null($facture_choix_type))
                @if ($facture_choix_type == 1)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="paiement_id_invoice_inter" class="form-label">Identifiant
                                facture(11
                                chiffres)<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="paiement_id_invoice_inter" id="paiement_id_invoice_inter"
                                type="text" class="form-control" value="" maxlength="11"
                                placeholder="Identifiant facture">
                            @error('paiement_id_invoice_inter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (sabdy()->country->code != 'CI')
                            <div class="col-span-6">
                                <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                        *</span></label>
                                <select wire:model.defer="country_inter_robus" id="country_inter_robus"
                                    class="form-control">
                                    <option value="null" selected>Selectionnez le pays</option>
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
                                <select wire:model.defer="country_inter_robus" id="country_inter_robus"
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
                @elseif ($facture_choix_type == 2)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="paiement_id_invoice_inter" class="form-label">Identifiant
                                facture(09
                                chiffres)<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="paiement_id_invoice_inter" id="paiement_id_invoice_inter"
                                type="text" class="form-control" value="" maxlength="9"
                                placeholder="Identifiant">
                            @error('c')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (sabdy()->country->code != 'CI')
                            <div class="col-span-6">
                                <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                        *</span></label>
                                <select wire:model.defer="country_inter_robus" id="country_inter_robus"
                                    class="form-control">
                                    <option value="null" selected>Selectionnez le pays</option>
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
                                <select wire:model.defer="country_inter_robus" id="country_inter_robus"
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

                @endif
                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6">
                        <label for="paiement_name_cie_inter" class="form-label">Nom de la
                            facture<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="paiement_name_cie_inter" id="paiement_name_cie_inter" type="text"
                            class="form-control" placeholder="Nom facture">
                        @error('paiement_name_cie_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-6">
                        <label for="pre_phone_invoice_inter" class="form-label">Numero </label>
                        <input wire:model.defer="pre_phone_invoice_inter" id="pre_phone_invoice_inter" type="number"
                            class="form-control" placeholder="Numero de telephone">
                        @error('pre_phone_invoice_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6">
                        <label for="pre_id_invoice_inter" class="form-label">Id facture<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="pre_id_invoice_inter" id="pre_id_invoice_inter" type="number"
                            class="form-control" placeholder="Montant total">
                        @error('pre_id_invoice_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-span-6">
                        <label for="paiement_amount_total_inter" class="form-label">Montant
                            total<span class="text-danger">
                                *</span></label>
                        <input wire:model.debounce.10ms="paiement_amount_total_inter" id="paiement_amount_total_inter"
                            type="number" class="form-control" placeholder="Montant total">
                        @error('paiement_amount_total_inter')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @if (!is_null($paiement_amount_total_inter))

                        @if (sabdy()->is_under_distributer == 1)
                            <div class="col-span-12 mt-3">
                                <label for="paiement_amount_total_inter" class="form-label">Commission<span
                                        class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_received_payment ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="paiement_amount_total_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_paiement_invoice_auther_inter_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="paiement_amount_total_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_paiement_invoice_auther_inter_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @else
                            <div class="col-span-6 mt-3">
                                <label for="paiement_amount_total_inter" class="form-label">Montant à
                                    payer<span class="text-danger">
                                        *</span></label>

                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_paiement_invoice_auther_inter_pay ?? null }}
                                    {{ $usersCountry }}
                                </div>

                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="paiement_amount_total_inter" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    {{ $amount_paiement_invoice_auther_inter_receive ?? null }}
                                    {{ $usersCountry }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

            @endif
        @elseif($provider_payment_inter == 54)
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/sodeci.png') }}" alt="MTN money">
                </div>
            </div>
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="paiement_name_sodecie_inter" class="form-label">Nom de la
                        facture<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="paiement_name_sodecie_inter" id="paiement_name_sodecie_inter"
                        type="text" class="form-control" placeholder="Nom facture">
                    @error('paiement_name_sodecie_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (sabdy()->country->code != 'CI')
                    <div class="col-span-6">
                        <label for="country_inter_robus" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <select wire:model.defer="country_inter_robus" id="country_inter_robus" class="form-control">
                            <option value="null" selected>Selectionnez le pays</option>
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
                        <select wire:model.defer="country_inter_robus" id="country_inter_robus" class="form-control">
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
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="paiement_num_contrat_inter" class="form-label">Numero du contrat (09
                        chiffres)<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="paiement_num_contrat_inter" id="paiement_num_contrat_inter"
                        type="text" class="form-control" placeholder="Numero contrat" value=""
                        maxlength="9">
                    @error('paiement_num_contrat_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="paiement_amount_t_sodecie_inter" class="form-label">Montant
                        total<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="paiement_amount_t_sodecie_inter"
                        id="paiement_amount_t_sodecie_inter" type="number" class="form-control"
                        placeholder="Montant total">
                    @error('paiement_amount_t_sodecie_inter')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (!is_null($paiement_amount_t_sodecie_inter))
                    @if (sabdy()->is_under_distributer == 1)
                        <div class="col-span-12 mt-3">
                            <label for="paiement_amount_t_sodecie_inter" class="form-label">Commission<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_received_payment ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="paiement_amount_t_sodecie_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_paiement_invoice_auther_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="paiement_amount_t_sodecie_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_paiement_invoice_auther_inter_receive ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                    @else
                        <div class="col-span-6 mt-3">
                            <label for="paiement_amount_t_sodecie_inter" class="form-label">Montant à
                                payer<span class="text-danger">
                                    *</span></label>

                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_paiement_invoice_auther_inter_pay ?? null }}
                                {{ $usersCountry }}
                            </div>
                        </div>
                        <div class="col-span-6 mt-3">
                            <label for="paiement_amount_t_sodecie_inter" class="form-label">Montant à
                                recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                style="font-size: 25px; color:white;">
                                {{ $amount_paiement_invoice_auther_inter_receive ?? null }}
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
        <button wire:click="addInterInvoice" type="button" wire:loading.remove=""
            class="btn btn-primary w-50">Envoyer la demande</button>
        <div wire:loading wire:target="addInterInvoice">
            Traitement en cours...
        </div>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        Echo.channel('wallet-bank-created').listen(
            '.Created_wallet_bank',
            (e) => {
                window.livewire.emit('notifyNewBank:' + e.id);
            },
        )

    });
</script>
