<div>
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 ">

        <div class="col-span-12 mt-space">
            <label for="country_inter_out_civ" class="form-label">Pays<span class="text-danger">
                    *</span></label>
            <select wire:model.debounce.10ms="country_inter_out_civ" id="country_inter_out_civ" class="form-control">
                <option value="null" disabled>Selectionnez le canal</option>
                @foreach ($countrie_west_send_centre as $item)
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
                $countrie_indicative = countryById($country_inter_out_civ);
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
                    @endif
                </span>

            </div>

            <input type="hidden" wire:init="loadChoose3">
            @if ($country_inter_out_civ == 107)

                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>

                        @php
                            $provideOptions = [];
                            if ($country_inter_out_civ == 107) {
                                $provideOptions = $provide_civ;
                            } elseif ($country_inter_out_civ == 122) {
                                $provideOptions = $provide_liberia;
                            } elseif ($country_inter_out_civ == 43) {
                                $provideOptions = $provide_tchad;
                            } elseif ($country_inter_out_civ == 42) {
                                $provideOptions = $provide_centre_afrique;
                            } elseif ($country_inter_out_civ == 79) {
                                $provideOptions = $provide_gabon;
                            } elseif ($countrie_indicative->name_groupe == sabdy()->country->name_groupe) {
                                $provideOptions = $provide_europe;
                            } elseif ($country_inter_out_civ == 25) {
                                $provideOptions = $provide_west_benin;
                            } elseif ($country_inter_out_civ == 36) {
                                $provideOptions = $provide_west_bf;
                            } elseif ($country_inter_out_civ == 133) {
                                $provideOptions = $provide_west_mali;
                            } elseif ($country_inter_out_civ == 214) {
                                $provideOptions = $provide_west_togo;
                            } elseif ($country_inter_out_civ == 188) {
                                $provideOptions = $provide_west_senegal;
                            } elseif ($country_inter_out_civ == 83) {
                                $provideOptions = $provide_west_ghana;
                            } elseif ($country_inter_out_civ == 39) {
                                $provideOptions = $provide_cameroun;
                            } elseif ($country_inter_out_civ == 191) {
                                $provideOptions = $provide_serialionne;
                            } elseif ($country_inter_out_civ == 157) {
                                $provideOptions = $provide_west_niger;
                            } elseif ($country_inter_out_civ == 91) {
                                $provideOptions = $provide_west_guinne_kona;
                            } elseif ($country_inter_out_civ == 50) {
                                $provideOptions = $provide_congo;
                            } elseif ($country_inter_out_civ == 57) {
                                $provideOptions = $provide_rdc;
                            } elseif ($country_inter_out_civ == 122) {
                                $provideOptions = $provide_liberia;
                            } elseif ($country_inter_out_civ == 43) {
                                $provideOptions = $provide_tchad;
                            } elseif ($country_inter_out_civ == 42) {
                                $provideOptions = $provide_centre_afrique;
                            } elseif ($country_inter_out_civ == 79) {
                                $provideOptions = $provide_gabon;
                            }
                        @endphp
                        @foreach ($provideOptions as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
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
            @elseif($countrie_indicative->name_groupe == sabdy()->country->name_groupe)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_europe as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('provider_inter_out_civ')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            @elseif ($country_inter_out_civ == 25)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_benin as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 36)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_bf as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 133)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_mali as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 214)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_togo as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 188)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_senegal as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 83)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_ghana as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 39)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_cameroun as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 191)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_serialionne as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 157)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_niger as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 91)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_west_guinne_kona as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 50)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_congo as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 57)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_rdc as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 122)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_liberia as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 43)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_tchad as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 42)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_centre_afrique as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @elseif ($country_inter_out_civ == 79)
                <div class="col-span-6 mt-3">
                    <label for="provider_inter_out_civ" class="form-label">Canal du paiement
                        money<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provide_gabon as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            @endif
            @error('provider_inter_out_civ')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <div class="col-span-6 mt-3">
                <label for="amount_inter_out_civ" class="form-label">Montant<span class="text-danger">
                        *</span></label>
                <input wire:model.debounce.10ms="amount_inter_out_civ" id="amount_inter_out_civ" type="number"
                    class="form-control" placeholder="Montant">
                @error('amount_inter_out_civ')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

            </div>
        </div>
    @endif
    @if (!is_null($country_inter_out_civ))

        @if (!is_null($provider_inter_out_civ))

            @if ($provider_inter_out_civ == 49)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/orange.png') }}" alt="Orange money">
                    </div>
                </div>

                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>

                            <div class="flex">

                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 50)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/moov.png') }}" alt="Moov money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 48)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/mtn.png') }}" alt="Mtn money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 55)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/wave.png') }}" alt="wave money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 71)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/airTel.png') }}" alt="AirTel money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 72)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/libertis.jpg') }}" alt="lLbertis money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 73)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/sarifacom.jpg') }}" alt="Sarifacom money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 74)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/africell.png') }}" alt="Africell money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 75)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/sierratel.jpg') }}" alt="Sierratel money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 76)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/M-PESA.png') }}" alt="M-PESA money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 77)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/t-kash.png') }}" alt="t-kash money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12 mt-space">
                            <label for="phone_inter_out_civ" class="form-label">Numero de
                                telephone<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number"
                                    class="form-control" placeholder="Numero" value="" maxlength="15">
                                @error('phone_inter_out_civ')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            @elseif ($provider_inter_out_civ == 51)
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/robus.png') }}" alt="robus money">
                    </div>
                </div>
                @if (!is_null($country_inter_out_civ))
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                        @php
                            $countrie_indicative = countryById($country_inter_out_civ);
                        @endphp
                        <div class="col-span-12">
                            <label for="phone_inter_out_civ" class="form-label">Numero wallet<span
                                    class="text-danger">
                                    *</span></label>

                            <input wire:model.debounce.10ms="phone_inter_out_civ" id="phone_inter_out_civ"
                                type="text" class="form-control" value="" maxlength="9"
                                placeholder="Numero wallet">
                            <div wire:loading>Recherche wallet...</div>
                            <div wire:loading.remove></div>

                            @if ($phone_inter_out_civ == '')
                                <div class="text-gray-500 text-sm">
                                    Entrer numero wallet
                                </div>
                            @else
                                @php

                                    $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $phone_inter_out_civ)
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

                    </div>
                @endif
            @endif
        @endif

        @if (!is_null($amount_inter_out_civ))
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                @php
                    $countrie_indicative = countryById($country_inter_out_civ);
                @endphp

                @if (sabdy()->is_under_distributer == 1)
                    <div class="col-span-12 mt-3">
                        <label for="amount_inter_out_civ" class="form-label">Commission<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-pending   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ formatDisplayAmount($amount_transaction_nat_val_pay ?? null) }}
                            {{ $usersCountry }}
                        </div>
                    </div>
                    <div class="col-span-6 mt-3">
                        <label for="amount_inter_out_civ" class="form-label">Montant à
                            payer<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ formatDisplayAmount($amount_transaction_nat_commission_val_pay ?? null) }}
                            {{ $usersCountry }}
                        </div>
                    </div>

                    <div class="col-span-6 mt-2">
                        <label for="amount_inter_out_civ" class="form-label">Montant à
                            recevoir<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ formatDisplayAmount($amount_transaction_nat_val_receive ?? null) }}
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
                            {{ formatDisplayAmount($amount_transaction_nat_commission_val_pay ?? null) }}
                            {{ $usersCountry }}
                        </div>
                    </div>

                    <div class="col-span-6 mt-2">
                        <label for="amount_inter_out_civ" class="form-label">Montant à
                            recevoir<span class="text-danger">
                                *</span></label>

                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                            style="font-size: 25px; color:white;">
                            {{ formatDisplayAmount($amount_transaction_nat_val_receive ?? null) }}
                            {{ $countrie_indicative->currencie->code ?? null }}
                        </div>
                    </div>
                @endif
            </div>
        @endif
        <div class="modal-footer style-foo">
            <button wire:click="cancelInterOutCivField" type="button"
                class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
            <button wire:click="addInterOutCiv" type="button" wire:loading.remove=""
                class="btn btn-primary w-50">Envoyer la
                demande</button>
            <div wire:loading wire:target="addInterOutCiv">
                Traitement en cours...
            </div>
        </div>
    @endif
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
