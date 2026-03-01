<div>
    @php

        $is_under = sabdy()->is_under_distributer;
        $is_distributer = sabdy()->is_distributer;

    @endphp

    @if ($stepInter === 1)
        <div id="loader" style="display:none;">
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

        <!-- Label -->
        @include('components.modal-header', [
            'title' => 'Canal de paiement a l\'internationnal',
            'icon' => 'fas fa-check-circle',
            'service' => 'Paiement internationnal',
            'serviceIcon' => 'fas fa-dice',
            'onClose' => 'cancelInterOutCivField',
        ])
        @if ($is_under)
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
                                    {{ $item->name }}

                                </option>
                            @endforeach
                        </select>
                        @error('country_inter_out_civ')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
        @else
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 ">
                <div class="col-span-12 mt-space">
                    <label for="country_inter_out_civ" class="form-label">Aucune données disponible pour vous !!!<span
                            class="text-danger">
                        </span></label>
                </div>
            </div>
        @endif

        @if (!is_null($country_inter_out_civ))
            <div class="modal-body mt-1">
                @php
                    $countrie_indicative = App\Models\Custumers\Country::where('id', $country_inter_out_civ)->first();
                    $region = match ($countrie_indicative->name_groupe) {
                        'AFL' => '🌍 Afrique de L\'Ouest',
                        'AFC' => '🌍 Afrique Centrale',
                        'MAG' => '🌍 Maghreb',
                        'URP' => '🌍 Europe',
                        'USA' => '🇺🇸 États-Unis',
                        'CIV' => '🇨🇮 Côte d\'Ivoire',
                        'ASIE' => '🌏 Asie',
                        'AFE' => '🌍 Afrique de l\'Est',
                        default => '🌍 Autre',
                    };
                @endphp
                <div class="col-span-12 text-center">
                    <span
                        class="alert alert-primary inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                        {{ $region }}
                    </span>
                </div>

                <input type="hidden" wire:init="loadChoose3">
                <div class="col-span-12 mt-6">


                    @if (!empty($providers) && is_array($providers))

                        <div class="grid grid-cols-12 gap-6 mt-4">

                            @foreach ($providers as $mainCategory => $subCategories)
                                @foreach ($subCategories as $categoryName => $categoryProviders)
                                    @if (!empty($categoryProviders))
                                        <!-- COL-SPAN-6 -->
                                        <div class="col-span-12 md:col-span-6">

                                            <div
                                                class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 
                                                     hover:shadow-md transition duration-300 h-full">

                                                <!-- Category Title -->
                                                <div class="amount-section">
                                                    <div class="amount-label"> Canal de transaction</div>
                                                    <div class="amount-received">
                                                        <div class="flex items-baseline justify-between">
                                                            <span class="amount-received-value">
                                                                {{ $categoryName }}

                                                            </span>

                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Select -->
                                                <div class="relative">
                                                    <select wire:model.debounce.300ms="provider_inter_out_civ"
                                                        id="provider_inter_out_civ_{{ Str::slug($categoryName) }}"
                                                        class="w-full appearance-none bg-gray-50 border border-gray-300 
                                                        text-gray-700 text-sm rounded-lg px-4 py-3
                                                        focus:ring-2 focus:ring-indigo-500 
                                                        focus:border-indigo-500
                                                        transition duration-200 outline-none">

                                                        <option value="">
                                                            Sélectionnez une {{ $categoryName }}
                                                        </option>

                                                        @foreach ($categoryProviders as $provider)
                                                            @if (is_object($provider) && property_exists($provider, 'id'))
                                                                <option value="{{ $provider->id }}">
                                                                    {{ $provider->name }}
                                                                </option>
                                                            @elseif(is_array($provider) && isset($provider['id']))
                                                                <option value="{{ $provider['id'] }}">
                                                                    {{ $provider['name'] }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                    <!-- Custom Arrow -->
                                                    <div
                                                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach

                        </div>
                    @else
                        <div
                            class="bg-red-50 border border-red-200 text-red-600 
                    text-sm rounded-lg px-4 py-3">
                            Aucun service disponible
                        </div>
                    @endif

                    <!-- Error -->
                    @error('provider_inter_out_civ')
                        <p class="mt-2 text-sm text-red-600 font-medium">
                            {{ $message }}
                        </p>
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
                        104 => ['name' => 'Djamo', 'image' => 'djamo.png'],
                        105 => ['name' => 'Push', 'image' => 'push.png'],
                        106 => ['name' => 'Tresort Money', 'image' => 'tresorMoney.png'],
                        124 => ['name' => 'Bank send', 'image' => 'bank_send.png'],
                        // New Service sender
                        52 => ['name' => 'CANAL', 'image' => 'bank_send.png'],
                        53 => ['name' => 'CIE', 'image' => 'bank_send.png'],
                        54 => ['name' => 'SODECI', 'image' => 'bank_send.png'],
                        56 => ['name' => 'Orange', 'image' => 'bank_send.png'],
                        57 => ['name' => 'Moov', 'image' => 'bank_send.png'],
                        58 => ['name' => 'Mtn', 'image' => 'bank_send.png'],

                    ];

                    $provider = $providerData[$provider_inter_out_civ] ?? null;
                    $countrie_indicative = App\Models\Custumers\Country::where('id', $country_inter_out_civ)->first();
                    $wallet_name = $wallet_name ?? '';
52
                @endphp

                @if ($provider)
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset_url('dist/providers/' . $provider['image']) }}"
                                alt="{{ $provider['name'] }}">
                        </div>
                    </div>

                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        @if ($provider['name'] == 'Robus')
                            @include('components.robus-wallet-inter-input', [
                                'compte_number_robus' => $compte_inter_number_robus,
                                'wallet_name' => $wallet_name,
                            ])

                            @include('components.transaction-inter-amount-input', [
                                'id' => 'transaction_inter_amount_money',
                                'model' => 'transaction_inter_amount_money',
                                'label' => 'Montant',
                                'error' => 'transaction_inter_amount_money',
                            ])
                        @else
                            @if (sabdy()->is_under_distributer == 1)

                                @if ($provider['name'] == 'Remittance Money')
                                    @include('components.partials.remittance-fields')
                                @elseif ($provider_inter_out_civ == 124)
                                    @include('components.partials.provider-hunders-fields')
                                @else
                                    @include('components.partials.authers-fields')
                                @endif
                            @else
                                <div class="field-group">

                                    <div class="field-full">
                                        <label class="form-label required">Aucune données pour vous </label>
                                        <div class="field-value">
                                            <i class="fas fa-network-wired"></i>
                                            <span id="provider_push"> Verifiez a l'administration</span>
                                        </div>
                                    </div>
                                </div>

                            @endif

                            @if ($provider_inter_out_civ != 124)
                                @include('components.transaction-inter-amount-input', [
                                    'id' => 'transaction_inter_amount_money',
                                    'model' => 'transaction_inter_amount_money',
                                    'label' => 'Montant',
                                    'error' => 'transaction_inter_amount_money',
                                ])
                            @endif


                        @endif
                    </div>


                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
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
                                <x-transaction-inter-detail title="Commission" value="{{ $commission ?? null }}"
                                    country="{{ $usersCountry }}" />
                            @else
                                <div class="col-span-6 mt-3">
                                    <label class="form-label">Pas de commission<span class="text-danger">
                                            *</span></label>
                                    <div class="px-5 py-3 flex-1 zoom-in alert alert-secondary relative font-medium leading-6 pl-3 2xl:pl-4"
                                        style="font-size: 25px; color:white;">

                                    </div>
                                </div>
                            @endif

                            <div class="col-span-6 mt-3">
                                <label for="transaction_inter_amount_money_receved" class="form-label">Montant
                                    à recevoir<span class="text-danger">*</span></label>

                                <div class="flex">

                                    <input wire:model.debounce.500ms="transaction_inter_amount_money_receved" readonly
                                        id="transaction_inter_amount_money_receved" type="number" class="form-control"
                                        placeholder="Montant à recevoir">
                                    <div
                                        class="px-5 py-3 flex-1 zoom-in alert alert-primary relative font-medium leading-6 pl-3 2xl:pl-4 style-devise">
                                        {{ $countrie_indicative->currencie->code ?? null }}
                                    </div>
                                    @error('transaction_inter_amount_money_receved')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <x-transaction-send title="Frais d'envoie" value="{{ $frais ?? null }}"
                                country="{{ $usersCountry }}" />
                        @else
                            <x-transaction-inter-detail title="Montant à payer" value="{{ $amount_to_pay ?? null }}"
                                country="{{ $usersCountry }}" />



                            <div class="col-span-6 mt-3">
                                <label for="transaction_inter_amount_money_receved" class="form-label">Montant
                                    à recevoir<span class="text-danger">*</span></label>

                                <div class="flex">

                                    <input wire:model.debounce.500ms="transaction_inter_amount_money_receved" readonly
                                        id="transaction_inter_amount_money_receved" type="number"
                                        class="form-control" placeholder="Montant à recevoir">
                                    <div
                                        class="px-5 py-3 flex-1 zoom-in alert alert-primary relative font-medium leading-6 pl-3 2xl:pl-4 style-devise">
                                        {{ $countrie_indicative->currencie->code ?? null }}
                                    </div>
                                    @error('transaction_inter_amount_money_receved')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif


                    </div>

                    {{-- @endif --}}

                @endif
            @endif

            <div class="modal-footer style-foo">
                <button wire:click="cancelInterOutCivField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="addInterOut" type="button" wire:loading.attr="disabled" wire:loading.remove=""
                    class="btn btn-primary w-50">Envoyer
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
                            {{ $transaction_inter_amount_money ?? $transaction_bank_amount }}
                            {{ $usersCountry ?? null }}
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
                @if ($provider_inter_out_civ == 124)
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Nom & prenom du bénéficiaire<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $name_sender ?? null }}
                            </div>
                        </div>

                    </div>

                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Numero de telephone du
                            bénéficiaire<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $countrie_indicative->indecative . $phone_inter_out_civ ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Numero du compte du bénéficiaire<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $account_number ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Numero piece<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $num_piece_custumer ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Date piece<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $date_piece ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Nom de la banque<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $name ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Code swift/IFSC/Bic Code<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $swit_code ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Nom & prenom de l\'expediteur<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $name_custumer ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Numero de telephone de
                            l'expediteur<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $phone_inter_expe ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-space-padding mt-3">
                        <label class="form-label">Montant de la transaction<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $transaction_bank_amount ?? null }} {{ $usersCountry }}
                            </div>
                        </div>

                    </div>
                @endif
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
