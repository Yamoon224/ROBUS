@php
    $is_under = sabdy()->is_under_distributer;
    $is_distributer = sabdy()->is_distributer;
@endphp

@if ($stepTresort === 1)

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary">
                <h2 class="font-medium text-base mr-auto">Demande de transfers Tresort Money</h2>

            </div>


            <div
                class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                    <a href="#step-1"
                        class="w-10 h-10 rounded-full btn btn-circle {{ $stepTresort != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                    <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                        
                    </div>
                </div>
            </div>
            <a wire:click="resetTresortField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                <div class="col-span-12 flex justify-center items-center">
                    <img class="h-16 mt-3 object-contain rounded-md shadow-md bg-yellow-100 px-3 py-2"
                        src="{{ asset_url('dist/providers/tresorMoney.png') }}" alt="Provider Xbet">
                </div>

                <div class="col-span-12 form-group required">
                    <label class="form-label" for="choose_transaction">Choisir le type de transaction</label>
                    <select wire:model="choose_transaction" id="choose_transaction" class="form-control" required>
                        <option value="" selected>Selectionnez type de transaction</option>
                        <option value="1">Transfert</option>
                        <option value="2">Rétrait</option>
                    </select>
                    @error('choose_transaction')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @if ($choose_transaction)
                    <div class="col-span-6 mt-1">
                        @include('components.transaction-phone-input', [
                            'id' => 'number_phone',
                            'model' => 'number_phone',
                            'label' => 'Numero de teléphone',
                            'error' => 'number_phone',
                        ])
                    </div>

                    <div class="col-span-6 mt-1">
                        @include('components.transaction-text-input', [
                            'id' => 'full_name_tresort',
                            'model' => 'full_name_tresort',
                            'label' => 'Nom et Prénom',
                            'error' => 'full_name_tresort',
                        ])
                    </div>

                    <div class="col-span-12 mt-1">
                        @include('components.transaction-amount-input', [
                            'id' => 'amount_tresort',
                            'model' => 'amount_tresort',
                            'label' => 'Montant',
                            'error' => 'amount_tresort',
                        ])
                    </div>
                    @if ($choose_transaction == '2')

                        @if ($need_identity_number)
                            <div class="col-span-12 mt-3 form-group required">
                                <label for="identity_number" class="form-label">Numéro de la pièce
                                    d'identité</label>
                                <input type="text" id="identity_number" wire:model.defer="identity_number"
                                    class="form-control" placeholder="Veuillez entrer le numéro de la pièce" required>
                                @error('identity_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    @endif
                @endif

            </div>
            @if ($amount_tresort)
                @php
                    $amount_to_pay = $amount_transaction_nat_val_tresort_percent;
                    $amount_to_receive = $amount_transaction_nat_val_tresort_receive;
                    $commission = $amount_transaction_nat_val_tresort_commission;
                @endphp

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @if ($is_under)
                        @if ($showRemonterTresortButton)
                            <x-transaction-detail title="Commission" value="{{ $commission }}"
                                country="{{ $usersCountry }}" />
                        @else
                            <x-transaction-detail title="Pas de commission" value="" />
                        @endif
                        <x-transaction-detail-received title="Montant à recevoir" value="{{ $amount_to_receive }}"
                            country="{{ $usersCountry }}" />
                    @else
                        <x-transaction-detail title="Montant à payer" value="{{ $amount_to_pay }}"
                            country="{{ $usersCountry }}" />
                        <x-transaction-detail-received title="Montant à recevoir" value="{{ $amount_to_receive }}"
                            country="{{ $usersCountry }}" />
                    @endif
                </div>
            @endif

            <!-- Footer avec boutons d'action -->
            <div class="modal-footer mt-4">
                <button wire:click="resetTresortField" type="button" class="btn btn-danger-soft w-20 mr-1">
                    Annuler
                </button>

                @if (!is_null($amount_tresort) && $amount_tresort != '')
                    <button wire:click="addTresort" type="button" wire:loading.attr="disabled"
                        class="btn btn-primary w-50" wire:loading.class="opacity-50 cursor-not-allowed">
                        Envoyer la demande
                    </button>

                    <div wire:loading.flex wire:target="addTresort" class="flex items-center justify-center">
                        <div class="loader"></div>
                        <p>Traitement en cours...</p>
                    </div>
                @else
                    <button type="button" class="btn btn-primary opacity-50 cursor-not-allowed" disabled>
                        Saisie le montant
                    </button>
                @endif
            </div>
        </div>
    </div>
@elseif ($stepTresort === 2)
    <div class="modal-dialog modal-lg mt-3">
        <div class="modal-content">
            <div class="modal-header alert alert-primary">
                <h2 class="font-medium text-base mr-auto">Validation de mot de passe </h2>
            </div>
            <div
                class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                    <a href="#step-1"
                        class="w-10 h-10 rounded-full btn btn-circle {{ $stepTresort != 2 ? 'btn-default' : 'btn-tertiary' }}">2</a>
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
                            {{ $number_phone ?? null }}
                        </div>
                    </div>



                </div>
                <div class="col-span-6 mt-1">
                    <label class="form-label">Nom et prénom</label>

                    <div
                        class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                        {{ $full_name_tresort ?? null }}
                    </div>


                </div>
                <div class="col-span-12 mt-1">
                    <label class="form-label">Montant</label>

                    <div
                        class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                        {{ $amount_tresort ?? null }} {{ $usersCountry ?? null }}
                    </div>


                </div>

                <x-password-input-new />

            </div>

            <div class="modal-footer style-foo">
                <button wire:click="resetTresortField" type="button" class="btn btn-danger-soft w-20 mr-1"
                    wire:loading.class="hidden" wire:target="addValidatedTresort">Annuler</button>



                @if ($isPasswordValid)
                    <button wire:click="addValidatedTresort" type="button" wire:loading.attr="disabled"
                        class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                        Validez la demande
                    </button>
                @else
                    <p class="text-danger mt-2">
                        Veuillez entrer un mot de passe valide.
                    </p>
                @endif

                <div wire:loading.flex wire:target="addValidatedTresort" class="flex items-center justify-center">
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
