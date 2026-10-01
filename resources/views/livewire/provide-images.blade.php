<div>
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
                51 => ['name' => 'Robus money', 'image' => 'robus.png'],
            ];

            $provider = $providerData[$provider_inter_out_civ] ?? null;
            $countrie_indicative = App\Models\Custumers\Country::where('id', $country_inter_out_civ)->first();
        @endphp

        @if ($provider)
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/' . $provider['image']) }}" alt="{{ $provider['name'] }}">
                </div>
            </div>

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
                            class="form-control" placeholder="Numero" value="" maxlength="15">
                        @error('phone_inter_out_civ')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        @endif
    @endif

</div>
