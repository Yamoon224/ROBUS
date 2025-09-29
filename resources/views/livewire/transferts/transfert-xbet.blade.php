@php
    $is_under = sabdy()->is_under_distributer;
    $is_distributer = sabdy()->is_distributer;
@endphp

@if ($stepXbet === 1)

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary">
                <h2 class="font-medium text-base mr-auto">Demande de transfers Xbet</h2>

            </div>

            <div
                class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                    <a href="#step-1"
                        class="w-10 h-10 rounded-full btn btn-circle {{ $stepXbet != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                    <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                        requises
                    </div>
                </div>
            </div>
            <a wire:click="resetXbetTransactionField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <!-- Sélection du type de transaction -->
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                @if ($is_distributer == 1)
                    <!-- Distributeur - seulement Transferts -->
                    <div
                        class="col-span-12 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2">
                        <input id="bordered-radio-6" type="radio" wire:model.debounce.10ms="choose_type_transaction"
                            value="1"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-6"
                            class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                            Transferts 1xbet
                        </label>
                        @error('choose_type_transaction')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @else
                    <!-- Non-distributeur -->
                    @if ($is_under)

                        @if ($showDepotButton)
                            <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                                style="{{ $choose_type_transaction == 2 ? 'display: none;' : '' }}">
                                <input id="bordered-radio-6" type="radio"
                                    wire:model.debounce.10ms="choose_type_transaction" value="1"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-6"
                                    class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Dépôts
                                </label>
                            </div>
                        @endif

                        @if ($showRetraitButton)
                            <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                                style="{{ $choose_type_transaction == 1 ? 'display: none;' : '' }}">
                                <input id="bordered-radio-7" type="radio"
                                    wire:model.debounce.10ms="choose_type_transaction" value="2"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-7"
                                    class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    Retraits
                                </label>
                            </div>
                        @endif
                    @else
                        <!-- Utilisateur normal -->
                        <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                            style="{{ $choose_type_transaction == 2 ? 'display: none;' : '' }}">
                            <input id="bordered-radio-6" type="radio"
                                wire:model.debounce.10ms="choose_type_transaction" value="1"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-6"
                                class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Dépôts
                            </label>
                        </div>

                        <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                            style="{{ $choose_type_transaction == 1 ? 'display: none;' : '' }}">
                            <input id="bordered-radio-7" type="radio"
                                wire:model.debounce.10ms="choose_type_transaction" value="2"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-7"
                                class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                Retraits
                            </label>
                        </div>
                    @endif
                    @error('choose_type_transaction')
                        <span class="text-danger col-span-12">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <!-- Formulaire selon le type choisi -->
            @if (!is_null($choose_type_transaction))
                @if ($choose_type_transaction == 1)

                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset_url('dist/providers/xbet.png') }}" alt="Provider Xbet">
                        </div>

                        <div class="col-span-6 mt-1">
                            @include('components.transaction-text-input', [
                                'id' => 'id_compte',
                                'model' => 'id_compte',
                                'label' => 'Id du compte',
                                'error' => 'id_compte',
                            ])
                        </div>

                        <div class="col-span-6 mt-1">
                            @include('components.transaction-text-input', [
                                'id' => 'full_nameXbet',
                                'model' => 'full_nameXbet',
                                'label' => 'Nom et Prénom',
                                'error' => 'full_nameXbet',
                            ])
                        </div>

                        <div class="col-span-12 mt-1">
                            @include('components.transaction-amount-input', [
                                'id' => 'amount_xbet',
                                'model' => 'amount_xbet',
                                'label' => 'Montant',
                                'error' => 'amount_xbet',
                            ])
                        </div>
                    </div>
                @else
                    <!-- Formulaire Retrait -->
                    <div
                        class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                        <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                            <a href="#step-1" class="w-10 h-10 rounded-full btn btn-circle btn-primary">Retrait</a>
                        </div>
                    </div>

                    @if ($is_distributer != 1)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            @include('components.transaction-text-input', [
                                'id' => 'code_compte',
                                'model' => 'code_compte',
                                'label' => 'Code demande',
                                'error' => 'code_compte',
                            ])

                            @include('components.transaction-text-input', [
                                'id' => 'id_compte_retrait',
                                'model' => 'id_compte_retrait',
                                'label' => 'Id du compte',
                                'error' => 'id_compte_retrait',
                            ])


                            @include('components.transaction-text-input', [
                                'id' => 'citie_xbet',
                                'model' => 'citie_xbet',
                                'label' => 'Ville',
                                'error' => 'citie_xbet',
                            ])

                            @include('components.transaction-text-input', [
                                'id' => 'area_xbet',
                                'model' => 'area_xbet',
                                'label' => 'Rue',
                                'error' => 'area_xbet',
                            ])
                            <div class="col-span-12 mt-1">
                                @include('components.transaction-amount-input', [
                                    'id' => 'amount_xbet',
                                    'model' => 'amount_xbet',
                                    'label' => 'Montant',
                                    'error' => 'amount_xbet',
                                ])

                            </div>


                        </div>
                    @endif
                @endif

                <!-- Section Montants et commissions -->
                @if ($amount_xbet)
                    @php
                        $amount_to_pay = $amount_transaction_nat_val_xbet_percent;
                        $amount_to_receive = $amount_transaction_nat_val_xbet_receive;
                        $commission = $amount_transaction_nat_val_xbet_commission;
                    @endphp

                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 mt-4">
                        @if ($is_under)
                            @if ($showRemonterCreditButton)
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
                            <x-transaction-detail-received title="Montant à recevoir"
                                value="{{ $amount_to_receive }}" country="{{ $usersCountry }}" />
                        @endif
                    </div>
                @endif
            @endif

            <!-- Footer avec boutons d'action -->
            <div class="modal-footer mt-4">
                <button wire:click="resetXbetTransactionField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">
                    Annuler
                </button>

                @if (!is_null($choose_type_transaction) && $choose_type_transaction != '')
                    <button wire:click="addXbetSend" type="button" wire:loading.attr="disabled"
                        class="btn btn-primary w-50" wire:loading.class="opacity-50 cursor-not-allowed">
                        Envoyer la demande
                    </button>

                    <div wire:loading.flex wire:target="addXbetSend" class="flex items-center justify-center">
                        <div class="loader"></div>
                        <p>Traitement en cours...</p>
                    </div>
                @else
                    <button type="button" class="btn btn-primary opacity-50 cursor-not-allowed" disabled>
                        Choisir le type
                    </button>
                @endif
            </div>
        </div>
    </div>
@elseif ($stepXbet === 2)
    <div class="modal-dialog modal-lg mt-3">
        <div class="modal-content">
            <div class="modal-header alert alert-primary">
                <h2 class="font-medium text-base mr-auto">Validation de mot de passe </h2>
            </div>
            <div
                class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                    <a href="#step-1"
                        class="w-10 h-10 rounded-full btn btn-circle {{ $stepXbet != 2 ? 'btn-default' : 'btn-tertiary' }}">2</a>
                    <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                    </div>
                </div>
            </div>

            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10 mt-3">

                @if ($choose_type_transaction == 1)
                    <div class="col-span-6 mt-3">
                        <label class="form-label">Numero du compte</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $id_compte ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-6 mt-3">
                        <label class="form-label">Nom et prénom</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $full_nameXbet ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-12 mt-3">
                        <label class="form-label">Montant</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $amount_xbet ?? null }}
                            </div>
                        </div>


                    </div>
                @else
                    <div class="col-span-6 mt-3">
                        <label class="form-label">Numero du compte</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $id_compte ?? null }}
                            </div>
                        </div>


                    </div>
                      <div class="col-span-6 mt-3">
                        <label class="form-label">Code du compte</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $code_compte ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-6 mt-3">
                        <label class="form-label">Numero du compte rétrait</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $id_compte_retrait ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-6 mt-3">
                        <label class="form-label">Ville</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $citie_xbet ?? null }}
                            </div>
                        </div>


                    </div>
                     <div class="col-span-6 mt-3">
                        <label class="form-label">Zone</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $area_xbet ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-12 mt-3">
                        <label class="form-label">Montant</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $amount_xbet ?? null }}
                            </div>
                        </div>


                    </div>
                @endif


                <div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="addValidatedXbet">

                    <x-password-input />

                </div>
            </div>

            <div class="modal-footer style-foo">
                <button wire:click="resetXbetTransactionField" type="button" class="btn btn-danger-soft w-20 mr-1"
                    wire:loading.class="hidden" wire:target="addValidatedXbet">Annuler</button>



                @if ($isPasswordValid)
                    <button wire:click="addValidatedXbet" type="button" wire:loading.attr="disabled"
                        class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                        Validez la demande
                    </button>
                @else
                    <p class="text-danger mt-2">
                        Veuillez entrer un mot de passe valide.
                    </p>
                @endif

                <div wire:loading.flex wire:target="addValidatedXbet" class="flex items-center justify-center">
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


<script>
    window.addEventListener('DOMContentLoaded', function() {
        Echo.private('wallet-xbet-created.{{ auth()->id() }}')
            .listen('.Created_wallet_xbet', (e) => {
                window.livewire.emit('notifyNewTransfers:' + e.id);
            });
    });
</script>
