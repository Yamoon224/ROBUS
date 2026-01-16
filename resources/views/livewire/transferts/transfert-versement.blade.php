    @if ($stepVersment === 1)
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Demande de versement bancaire</h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepVersment != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                        </div>
                    </div>
                </div>
                <a wire:click="resetVersmentField"
                    style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="x" data-lucide="x"
                        class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 form-group required">
                        <label for="provider_versement" class="form-label">Canal</label>
                        <select wire:model="provider_versement" id="provider_versement" class="form-control" required>
                            <option value="null" disabled>Selectionnez le canal</option>
                            @foreach ($provider_versements as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_versement')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>

                </div>
                <!-- Provider details -->
                @if (!is_null($provider_versement))
                    @php
                        $providersImages = [
                            108 => 'uba.png',
                            109 => 'gt_bank.png',
                            110 => 'versus.png',
                            111 => 'nsia.png',
                            112 => 'atlantique.png',
                            113 => 'bni.png',
                            114 => 'sgbci.png',
                            115 => 'sib.png',
                            116 => 'boa.jpeg',
                            117 => 'afg_bank.png',
                            118 => 'bda_bank.png',
                            119 => 'bridge_bank.jpeg',
                            120 => 'Orabank_bank.png',
                        ];
                        $providerImage = $providersImages[$provider_versement] ?? null;
                    @endphp

                    @if ($providerImage)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset_url('dist/providers/' . $providerImage) }}" alt="Provider">
                            </div>


                            @include('components.transaction-versement-input', [
                                'id' => 'num_accompte',
                                'model' => 'num_accompte',
                                'label' => 'Numero du compte',
                                'error' => 'num_accompte',
                            ])

                            @include('components.transaction-versement-input', [
                                'id' => 'bank_code',
                                'model' => 'bank_code',
                                'label' => 'Code banque',
                                'error' => 'bank_code',
                            ])
                            @include('components.transaction-versement-input', [
                                'id' => 'guichet_code',
                                'model' => 'guichet_code',
                                'label' => 'Code Guichet',
                                'error' => 'guichet_code',
                            ])

                            @include('components.transaction-versementCle-input', [
                                'id' => 'num_authers',
                                'model' => 'num_authers',
                                'label' => 'CLE RIB',
                                'error' => 'num_authers',
                            ])

                            <div class="col-span-12 mt-1">
                                @include('components.transaction-amount-input', [
                                    'id' => 'amount_versement',
                                    'model' => 'amount_versement',
                                    'label' => 'Montant',
                                    'error' => 'amount_versement',
                                ])
                            </div>
                        </div>
                    @endif

                    <!-- Montants et commissions -->
                    @if ($amount_versement)
                        @php
                            $amount_to_pay = $amount_transaction_nat_val_versement_percent;
                            $amount_to_receive = $amount_transaction_nat_val_versement_receive;
                            $commission = $amount_transaction_nat_val_versement_commission;
                        @endphp

                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            @if (sabdy()->is_under_distributer == 1)
                                @if ($showRemonterVersementButton)
                                    <x-transaction-detail title="Commission" value="{{ $commission }}"
                                        country="{{ $usersCountry }}" />
                                @else
                                    <x-transaction-detail title="Pas de commission" value="" country="" />
                                @endif
                                <x-transaction-detail-received title="Montant à recevoir"
                                    value="{{ $amount_to_receive }}" country="{{ $usersCountry }}" />
                            @else
                                <x-transaction-detail title="Montant à payer" value="{{ $amount_to_pay }}"
                                    country="{{ $usersCountry }}" />
                                <x-transaction-detail-received title="Montant à recevoir"
                                    value="{{ $amount_to_receive }}" country="{{ $usersCountry }}" />
                            @endif
                        </div>
                    @endif
                @endif

                <div class="modal-footer style-foo">
                    <button wire:click="resetVersmentField" type="button"
                        class="btn btn-danger-soft w-20 mr-1">Annuler</button>

                    @if (!is_null($provider_versement) && $provider_versement != '')
                        <button wire:click="addVersement" type="button" wire:loading.remove=""
                            class="btn btn-primary w-50">Envoyer la demande</button>
                        <div wire:loading wire:target="addVersement">
                            Traitement en cours...
                        </div>
                    @else
                        <button type="button" class="btn btn-primary" disabled>
                            Sélectionnez un canal
                        </button>
                    @endif



                </div>
            </div>
        </div>
    @elseif ($stepVersment === 2)
        <div class="modal-dialog modal-lg mt-3">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Validation de mot de passe </h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepVersment != 2 ? 'btn-default' : 'btn-tertiary' }}">2</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                        </div>
                    </div>
                </div>

                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10 mt-3">

                    @include('components.info_confirme_passe')

                    <div class="col-span-6 mt-3">
                        <label class="form-label">Numero du compte</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $num_accompte ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-6 mt-3">
                        <label class="form-label">Code de la banque</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $bank_code ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-3">
                        <label class="form-label">Code du Guichet</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $guichet_code ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-6 mt-3">
                        <label class="form-label">CLE RIB</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $num_authers ?? null }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-12 mt-3">Montant<span class="text-danger">
                            *</span></label>
                        <div class="flex">


                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $amount_versement ?? null }} {{ $usersCountry }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="addValidatedVersement">

                        <x-password-input />

                    </div>
                </div>

                <div class="modal-footer style-foo">
                    <button wire:click="resetVersmentField" type="button" class="btn btn-danger-soft w-20 mr-1"
                        wire:loading.class="hidden" wire:target="addValidatedVersement">Annuler</button>



                    @if ($isPasswordValid)
                        <button wire:click="addValidatedVersement" type="button" wire:loading.attr="disabled"
                            class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                            Validez la demande
                        </button>
                    @else
                        <p class="text-danger mt-2">
                            Veuillez entrer un mot de passe valide.
                        </p>
                    @endif

                    <div wire:loading.flex wire:target="addValidatedVersement"
                        class="flex items-center justify-center">
                        <div class="loader"></div>
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
            </div>
        </div>
    @endif
