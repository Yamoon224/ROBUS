<div>


    @if ($stepInter === 1)
        <div id="loader" style="display:none;">
            <!-- Ici, vous pouvez mettre votre animation de chargement -->
            <p>Chargement en cours...</p>
        </div>
        <div
            class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                <a href="#step-1"
                    class="w-10 h-10 rounded-full btn btn-circle {{ $stepInter != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations requises
                </div>
            </div>

        </div>

        @if ($showTransacInterButton)
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 ">

                <div class="col-span-12 mt-space">
                    <label for="country_inter_out_civ" class="form-label">Pays<span class="text-danger">
                            *</span></label>
                    <select wire:model.debounce.10ms="country_inter_out_civ" wire:change="updateProviderTransaction"
                        id="country_inter_out_civ" class="form-control">
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
                        $countrie_indicative = App\Models\Custumers\Country::where(
                            'id',
                            $country_inter_out_civ,
                        )->first();
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
                    <div class="col-span-12 mt-3">
                        <label for="provider_inter_out_civ" class="form-label">Canal du paiement money<span
                                class="text-danger">
                                *</span></label>
                        <select wire:model.debounce.10ms="provider_inter_out_civ" id="provider_inter_out_civ"
                            class="form-control">
                            <option value="null" disabled>Selectionnez le canal</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_inter_out_civ')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            @endif

            @if (!is_null($country_inter_out_civ) && !is_null($provider_inter_out_civ))

                @if (!is_null($provider_inter_out_civ))
                    @php

                        $providerData = [
                            49 => ['name' => 'Orange money', 'image' => 'orange.png'],
                            50 => ['name' => 'Moov money', 'image' => 'moov.png'],
                            48 => ['name' => 'Mtn money', 'image' => 'mtn.png'],
                            55 => ['name' => 'Wave money', 'image' => 'wave.png'],
                            71 => ['name' => 'Airtel Money', 'image' => 'airTel.png'],
                            72 => ['name' => 'Libertis money', 'image' => 'libertis.png'],
                            73 => ['name' => 'Sarifacom money', 'image' => 'sarifacom.png'],
                            74 => ['name' => 'Africell money', 'image' => 'africel.png'],
                            75 => ['name' => 'Sierratel money', 'image' => 'sierratel.jpg'],
                            76 => ['name' => 'M-PESA money', 'image' => 'm_pesa.png'],
                            77 => ['name' => 't-kash money', 'image' => 'tkash.png'],
                            51 => ['name' => 'Robus', 'image' => 'robus.png'],
                            78 => ['name' => 'Telecel Money', 'image' => 'telecel_money.png'],
                            93 => ['name' => 'Vodafone', 'image' => 'Vodafone.png'],
                            94 => ['name' => 'Ethio', 'image' => 'ethio.png'],
                            95 => ['name' => 'Zantel', 'image' => 'zantel.png'],
                            96 => ['name' => 'Tigo', 'image' => 'tigo.png'],
                            97 => ['name' => 'TogoCell', 'image' => 'togo_cell.png'],
                            98 => ['name' => 'Cash money', 'image' => 'Cash_Money_log.png'],
                            99 => ['name' => 'Remittance Money', 'image' => 'logo_remuttance.jpeg'],
                            100 => ['name' => 'MAURITEL', 'image' => 'mauritel.png'],
                            101 => ['name' => 'MATTEL', 'image' => 'mattel.png'],
                            102 => ['name' => 'CHINGUITTELy', 'image' => 'hinguittel.jpeg'],
                        ];

                        $provider = $providerData[$provider_inter_out_civ] ?? null;
                        $countrie_indicative = App\Models\Custumers\Country::where(
                            'id',
                            $country_inter_out_civ,
                        )->first();
                        $wallet_name = $wallet_name ?? '';

                    @endphp

                    @if ($provider)
                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/' . $provider['image']) }}"
                                    alt="{{ $provider['name'] }}">
                            </div>
                        </div>

                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            @if ($provider['name'] == 'Robus')
                                @include('components.robus-wallet-inter-input', [
                                    'compte_number_robus' => $compte_inter_number_robus,
                                    'wallet_name' => $wallet_name,
                                ])

                                @include(
                                    'ccountrie_inter_ivoiresomponents.transaction-inter-amount-input',
                                    [
                                        'id' => 'transaction_inter_amount_money',
                                        'model' => 'transaction_inter_amount_money',
                                        'label' => 'Montant',
                                        'error' => 'transaction_inter_amount_money',
                                    ]
                                )
                            @else
                                @if (sabdy()->is_under_distributer == 1)

                                    @if ($provider['name'] == 'Remittance Money')
                                        @include('components.transaction-custumer-input', [
                                            'id' => 'name_sender',
                                            'model' => 'name_sender',
                                            'label' => 'Nom & prenom du bénéficiaire',
                                            'error' => 'name_sender',
                                        ])

                                        <div class="col-span-6 mt-3">
                                            <label for="phone_inter_out_civ" class="form-label">Numero de telephone du
                                                bénéficiaire<span class="text-danger">*</span></label>

                                            <div class="flex">
                                                <div
                                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                    {{ $countrie_indicative->indecative ?? null }}
                                                </div>
                                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ"
                                                    type="number" class="form-control" placeholder="Numero"
                                                    value="" minlength="6" maxlength="15">
                                                @error('phone_inter_out_civ')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @include('components.transaction-custumer-input', [
                                            'id' => 'name',
                                            'model' => 'name',
                                            'label' => 'Nom de la banque',
                                            'error' => 'name',
                                        ])

                                        @include('components.transaction-custumer-input', [
                                            'id' => 'account_number',
                                            'model' => 'account_number',
                                            'label' => 'Numero du compte du bénéficiaire',
                                            'error' => 'account_number',
                                        ])

                                        @include('components.transaction-custumer-input', [
                                            'id' => 'name_custumer',
                                            'model' => 'name_custumer',
                                            'label' => 'Nom & prenom de l\'expediteur',
                                            'error' => 'name_custumer',
                                        ])

                                        @include('components.transaction-custumer-input', [
                                            'id' => 'num_piece_custumer',
                                            'model' => 'num_piece_custumer',
                                            'label' => 'Numero piece',
                                            'error' => 'num_piece_custumer',
                                        ])

                                        @include('components.transaction-date-input', [
                                            'id' => 'date_piece',
                                            'model' => 'date_piece',
                                            'label' => 'Date piece',
                                            'error' => 'date_piece',
                                        ])
                                    @else
                                        @include('components.transaction-custumer-input', [
                                            'id' => 'name_custumer',
                                            'model' => 'name_custumer',
                                            'label' => 'Nom & prenom',
                                            'error' => 'name_custumer',
                                        ])

                                        @include('components.transaction-custumer-input', [
                                            'id' => 'num_piece_custumer',
                                            'model' => 'num_piece_custumer',
                                            'label' => 'Numero piece',
                                            'error' => 'num_piece_custumer',
                                        ])



                                        <div class="col-span-12 mt-3">
                                            <label for="date_piece" class="form-label">Date piece<span
                                                    class="text-danger">*</span></label>

                                            <div class="flex">

                                                <input wire:model.defer="date_piece" id="date_piece" type="date"
                                                    class="form-control" placeholder="Date">
                                                @error('date_piece')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-span-6 mt-3">
                                            <label for="phone_inter_out_civ" class="form-label">Numero de telephone<span
                                                    class="text-danger">*</span></label>

                                            <div class="flex">
                                                <div
                                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                    {{ $countrie_indicative->indecative ?? null }}
                                                </div>
                                                <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ"
                                                    type="number" class="form-control" placeholder="Numero"
                                                    value="" minlength="6" maxlength="15">
                                                @error('phone_inter_out_civ')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="phone_inter_out_civ" class="form-label">Numero de telephone<span
                                                class="text-danger">*</span></label>

                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>
                                            <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ"
                                                type="number" class="form-control" placeholder="Numero" value=""
                                                minlength="6" maxlength="15">
                                            @error('phone_inter_out_civ')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif


                                @include('components.transaction-inter-amount-input', [
                                    'id' => 'transaction_inter_amount_money',
                                    'model' => 'transaction_inter_amount_money',
                                    'label' => 'Montant',
                                    'error' => 'transaction_inter_amount_money',
                                ])
                            @endif
                        </div>

                        @if ($transaction_inter_amount_money)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                {{-- Commission --}}
                                @php
                                    $countrie_indicative = App\Models\Custumers\Country::where(
                                        'id',
                                        $country_inter_out_civ,
                                    )->first();
                                    $amount_to_pay = $this->commission_transaction_val_inter_pay;
                                    $amount_to_receive = $this->commission_transaction_val_inter_convertion_send;
                                    $commission = $this->commission_transaction_val_inter_pay;
                                    $frais = $this->commission_transaction_val_percent_pay;
                                @endphp

                                @if (sabdy()->is_under_distributer == 1)

                                    @if ($showRemonterInterButton)
                                        <x-transaction-inter-detail title="Commission"
                                            value="{{ $commission ?? null }}" country="{{ $usersCountry }}" />
                                    @else
                                        <div class="col-span-6 mt-3">
                                            <label class="form-label">Pas de commission<span class="text-danger">
                                                    *</span></label>
                                            <div class="px-5 py-3 flex-1 zoom-in alert alert-secondary relative font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">

                                            </div>
                                        </div>
                                    @endif


                                    <x-transaction-inter-detail title="Montant à recevoir"
                                        value="{{ $amount_to_receive ?? null }}"
                                        country=" {{ $countrie_indicative->currencie->code ?? null }} " />
                                    <x-transaction-send title="Frais d'envoie" value="{{ $frais ?? null }}"
                                        country="{{ $usersCountry }}" />
                                @else
                                    <x-transaction-inter-detail title="Montant à payer"
                                        value="{{ $amount_to_pay ?? null }}" country="{{ $usersCountry }}" />

                                    <x-transaction-inter-detail title="Montant à recevoir"
                                        value="{{ $amount_to_receive ?? null }}"
                                        country=" {{ $countrie_indicative->currencie->code ?? null }} " />
                                @endif

                                {{-- Montant à recevoir --}}

                            </div>
                        @endif


                    @endif
                @endif


                <div class="modal-footer style-foo">
                    <button wire:click="cancelInterOutCivField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="addInterOut" type="button" wire:loading.attr="disabled"
                        wire:loading.remove="" class="btn btn-primary w-50">Envoyer
                        la
                        demande</button>
                    <div wire:loading wire:target="addInterOut">
                        Traitement en cours...
                    </div>
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            @endif
        @endif
    @elseif ($stepInter === 2)
        <div
            class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                <a href="#step-2"
                    class="w-10 h-10 rounded-full btn btn-circle {{ $stepInter != 2 ? 'btn-default' : 'btn-secondary' }}">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validation </div>
            </div>

        </div>
        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 mt-3">

            {{-- Other Providers Inputs --}}
            @php

                $countrie_indicative = App\Models\Custumers\Country::where('id', $country_inter_out_civ)->first();

            @endphp

            @if ($provider_inter_out_civ == 51)
                <div class="col-span-6 mt-space-padding">
                    <label for="number_phone_credit_edit" class="form-label">Montant de la transaction Robus<span
                            class="text-danger">
                            *</span></label>

                    <div class="flex">

                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $transaction_inter_amount_money ?? null }} {{ $usersCountry ?? null }}
                        </div>
                    </div>

                </div>
                <div class="col-span-6 mt-space-padding">
                    <label for="amount_phone_credit_edit" class="form-label">Numéro du compte Robus<span
                            class="text-danger">
                            *</span></label>
                    <div class="flex">

                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $compte_inter_number_robus ?? null }}
                        </div>
                    </div>

                </div>
            @else
                <div class="col-span-6 mt-space-padding">
                    <label class="form-label">Montant de la transaction<span class="text-danger">
                            *</span></label>

                    <div class="flex">

                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $transaction_inter_amount_money ?? null }} {{ $usersCountry }}
                        </div>
                    </div>


                </div>
                <div class="col-span-6 mt-space-padding">
                    <label class="form-label">Numéro du bénéficiaire<span class="text-danger">
                            *</span></label>
                    <div class="flex">


                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $countrie_indicative->indecative ?? null }} {{ $phone_inter_out_civ ?? null }}
                        </div>
                    </div>

                </div>
            @endif

            <div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="validatedTransaction">
                <x-password-input />
            </div>

        </div>
        <div class="modal-footer">

            <button wire:click="cancelInterOutCivField" type="button" class="btn btn-outline-secondary w-20 mr-1"
                wire:loading.class="hidden" wire:target="validatedTransaction">
                Annuler
            </button>

            @if ($isPasswordValid)
                <button wire:click="validatedTransaction" type="button" wire:loading.attr="disabled"
                    class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                    Validez la demande
                </button>
            @else
                <p class="text-danger mt-2">
                    Veuillez entrer un mot de passe valide pour activer le bouton de validation.
                </p>
            @endif
            <div wire:loading.flex wire:target="validatedTransaction" class="flex items-center justify-center">
                <div class="loader"></div> <!-- Ajoutez votre loader ici -->
                <style>
                    .loader {
                        border: 4px solid rgba(0, 0, 0, 0.1);
                        border-left-color: #09f;
                        border-radius: 50%;
                        width: 40px;
                        height: 40px;
                        animation: spin 1s linear infinite;
                    }

                    @keyframes spin {
                        to {
                            transform: rotate(360deg);
                        }
                    }

                    .hidden {
                        display: none;
                    }
                </style>
                <p>Traitement en cours...</p>
            </div>


        </div>
    @endif
</div>
