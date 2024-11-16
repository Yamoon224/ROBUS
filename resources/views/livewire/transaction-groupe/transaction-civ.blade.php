<div>
    @if ($step === 1)
        <div
            class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                <a href="#step-1"
                    class="w-10 h-10 rounded-full btn btn-circle {{ $step != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations requises
                </div>
            </div>

        </div>


        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
            <div class="col-span-12">
                <label for="modal-form-1" class="form-label">
                    Canal transfert d'argent
                    <span class="text-danger">*</span>
                </label>
                <select wire:model.debounce.300ms="provider_transaction" wire:change="updateProviderTransaction"
                    id="modal-form-1" class="form-control">
                    <option value="null" disabled selected>Selectionnez le canal</option>

                    {{-- Vérification des options disponibles --}}
                    @if ($showDepotButton && $showMoneyButton)
                        @foreach ($providersAlls as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    @elseif ($showDepotButton)
                        @foreach ($providersDepot as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    @elseif ($showMoneyButton)
                        @foreach ($providersMoney as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    @else
                        <option value="null" disabled>Pas disponible pour vous</option>
                    @endif
                </select>

                {{-- Affichage des erreurs --}}
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
                    9 => ['name' => 'Robus', 'image' => 'robus.png'],
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

                $provider = $providerData[$provider_transaction] ?? null;
                $countrie_indicative = App\Models\Custumers\Country::where('id', sabdy()->country->id)->first();
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
                    {{-- Robus Specific Inputs --}}

                    @if ($provider['name'] == 'Robus')
                        @include('components.robus-wallet-input', [
                            'compte_number_robus' => $compte_number_robus,
                            'wallet_name' => $wallet_name,
                        ])

                        @include('components.transaction-amount-input', [
                            'id' => 'transaction_amount_robus',
                            'model' => 'transaction_amount_robus',
                            'label' => 'Montant',
                            'error' => 'transaction_amount_robus',
                        ])
                    @else
                        {{-- @if (sabdy()->is_under_distributer == 1)
                        
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
                        @endif --}}
                        <div class="col-span-6 mt-3">
                            <label for="transaction_phone" class="form-label">Numero de telephone <span
                                    class="text-danger">*</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_indicative->indecative ?? null }}
                                </div>
                                <input wire:model.defer="transaction_phone" id="transaction_phone" type="number"
                                    class="form-control" placeholder="Numero" maxlength="15">
                            </div>
                            @error('transaction_phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @include('components.transaction-amount-input', [
                            'id' => 'transaction_amount_money',
                            'model' => 'transaction_amount_money',
                            'label' => 'Montant',
                            'error' => 'transaction_amount_money',
                        ])
                    @endif
                </div>

                @if ($transaction_amount_robus || $transaction_amount_money)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        {{-- Commission --}}
                        @php
                            $amount_to_pay = $transaction_amount_robus
                                ? $amount_transaction_nat_val_percent
                                : $amount_transaction_nat_money_val_percent;
                            $amount_to_receive = $transaction_amount_robus
                                ? $amount_transaction_nat_val_receive
                                : $amount_transaction_nat_money_val_receive;
                            $commission = $transaction_amount_robus
                                ? $amount_transaction_nat_val_pay
                                : $amount_transaction_nat_money_val_pay;

                            $frais = $transaction_amount_robus
                                ? $commission_transaction_val_admin_receive
                                : $commission_transaction_money_val_admin_receive;
                        @endphp
                        @if (sabdy()->is_under_distributer == 1)
                            <x-transaction-detail title="Commission" value="{{ $commission }}"
                                country="{{ $usersCountry }}" />

                            {{-- Montant à recevoir --}}
                            <x-transaction-detail title="Montant à recevoir" value="{{ $amount_to_receive }}"
                                country="{{ $usersCountry }}" />

                            {{-- <x-transaction-send title="Frais d'envoie" value="{{ $frais ?? null }}"
                                country="{{ $usersCountry }}" /> --}}
                        @else
                            <x-transaction-detail title="Montant à payer" value="{{ $amount_to_pay }}"
                                country="{{ $usersCountry }}" />

                            {{-- Montant à recevoir --}}
                            <x-transaction-detail title="Montant à recevoir" value="{{ $amount_to_receive }}"
                                country="{{ $usersCountry }}" />
                        @endif


                    </div>
                @endif

            @endif

        @endif

        <div class="modal-footer">
            <button wire:click="cancelTransactionField" type="button"
                class="btn btn-outline-secondary w-20 mr-1">Annuler</button>


            <button wire:click="addTransaction" type="button" wire:loading.remove="" wire:loading.attr="disabled"
                class="btn btn-primary w-50">Envoyer
                la demande</button>
            <div wire:loading wire:target="addTransaction">
                Traitement en cours...
            </div>

        </div>
    @elseif ($step === 2)
        <div
            class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                <a href="#step-2"
                    class="w-10 h-10 rounded-full btn btn-circle {{ $step != 2 ? 'btn-default' : 'btn-secondary' }}">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validation </div>
            </div>

        </div>
        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 mt-3">


            @if ($provider_transaction == 9)
                <div class="col-span-6 mt-space-padding">
                    <label for="number_phone_credit_edit" class="form-label">Montant de la transaction Robus<span
                            class="text-danger">
                            *</span></label>

                    <div class="flex">

                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $transaction_amount_robus ?? null }} {{ $usersCountry ?? null }}
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
                            {{ $compte_number_robus ?? null }}
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
                            {{ $transaction_amount_money ?? null }} {{ $usersCountry }}
                        </div>
                    </div>


                </div>
                <div class="col-span-6 mt-space-padding">
                    <label class="form-label">Numéro du bénéficiaire<span class="text-danger">
                            *</span></label>
                    <div class="flex">


                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $transaction_phone ?? null }}
                        </div>
                    </div>

                </div>
            @endif

            <div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="validatedPassword">

                <x-password-input />

            </div>



        </div>
        <div class="modal-footer">

            <!-- Bouton "Annuler" -->
            <button wire:click="cancelTransactionField" type="button" class="btn btn-outline-secondary w-20 mr-1"
                wire:loading.class="hidden" wire:target="validatedPassword">
                Annuler
            </button>

            <!-- Bouton "Validez la demande" -->
            @if ($isPasswordValid)
                <button wire:click="validatedPassword" type="button" wire:loading.attr="disabled"
                    class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                    Validez la demande
                </button>
            @else
                <p class="text-danger mt-2">
                    Veuillez entrer un mot de passe valide pour activer le bouton de validation.
                </p>
            @endif

            <div wire:loading.flex wire:target="validatedPassword" class="flex items-center justify-center">
                <div class="loader"></div>
                <p>Traitement en cours...</p>
            </div>
        </div>

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


    @endif

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
