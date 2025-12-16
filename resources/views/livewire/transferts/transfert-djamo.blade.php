@php
    $is_under = sabdy()->is_under_distributer;
    $is_distributer = sabdy()->is_distributer;
@endphp

@if ($stepDjamo === 1)

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary">
                <h2 class="font-medium text-base mr-auto">Demande de transfers Djamo</h2>

            </div>


            <div
                class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                    <a href="#step-1"
                        class="w-10 h-10 rounded-full btn btn-circle {{ $stepDjamo != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                    <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                    </div>
                </div>
            </div>
            <a wire:click="resetDjamoField"
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
                        src="{{ asset_url('dist/providers/djamo.png') }}" alt="Provider Djamo">
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
                    <div class="col-span-12 flex flex-col items-center gap-4">

                        <!-- Preview avec Base64 ou image existante -->
                        <div class="w-full flex justify-center">
                            @if ($image_preview)
                                <div
                                    class="relative group w-80 h-50 rounded-lg overflow-hidden border-2 border-green-300 shadow hover:shadow-lg transition">
                                    <img src="{{ $image_preview }}" alt="Preview" class="h-full w-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 flex items-center justify-center transition">
                                        <span
                                            class="text-white font-medium text-sm opacity-0 group-hover:opacity-100">Aperçu</span>
                                    </div>
                                </div>
                            @elseif(is_string($image) && $image)
                                <div
                                    class="relative group w-80 h-50 rounded-lg overflow-hidden border-2 border-blue-300 shadow hover:shadow-lg transition">
                                    <img src="{{ Storage::url($image) }}" alt="Image existante"
                                        class="h-full w-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 flex items-center justify-center transition">
                                        <span
                                            class="text-white font-medium text-sm opacity-0 group-hover:opacity-100">Image
                                            actuelle</span>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="w-40 h-40 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 text-gray-400">
                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-xs text-center">Télecharger le code QR</span>
                                </div>
                            @endif
                        </div>

                        <!-- Input file -->
                        <div class="w-full flex justify-center">
                            <label class="cursor-pointer">
                                <div
                                    class="bg-primary hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 shadow hover:shadow-md transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <span>{{ $image ? 'Changer l\'image' : 'Choisir une image' }}</span>
                                </div>
                                <input type="file" wire:model="image" accept="image/*" class="hidden">
                            </label>
                        </div>

                        <!-- Erreurs -->
                        @error('image')
                            <div
                                class="flex items-center space-x-2 text-red-600 bg-red-50 px-4 py-2 rounded-lg border border-red-200 w-full max-w-md">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm">{{ $message }}</span>
                            </div>
                        @enderror

                        <!-- Chargement -->
                        <div wire:loading wire:target="image"
                            class="flex items-center gap-2 text-blue-600 w-full justify-center">
                            <div
                                class="animate-spin rounded-full h-5 w-5 border-2 border-blue-600 border-t-transparent">
                            </div>
                            <span class="font-medium">Chargement de l'image...</span>
                        </div>

                    </div>


                    <div class="col-span-6 mt-1">
                        @include('components.transaction-text-input', [
                            'id' => 'full_name_djamo',
                            'model' => 'full_name_djamo',
                            'label' => 'Nom et Prénom',
                            'error' => 'full_name_djamo',
                        ])
                    </div>

                    <div class="col-span-6 mt-1">
                        @include('components.transaction-amount-input', [
                            'id' => 'amount_djamo',
                            'model' => 'amount_djamo',
                            'label' => 'Montant',
                            'error' => 'amount_djamo',
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

            @if ($amount_djamo)
                @php
                    $amount_to_pay = $amount_transaction_nat_val_djamo_percent;
                    $amount_to_receive = $amount_transaction_nat_val_djamo_receive;
                    $commission = $amount_transaction_nat_val_djamo_commission;
                @endphp

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    @if ($is_under)
                        @if ($showRemonterDjamoButton)
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
                <button wire:click="resetDjamoField" type="button" class="btn btn-danger-soft w-20 mr-1">
                    Annuler
                </button>

                @if (!is_null($amount_djamo) && $amount_djamo != '')
                    <button wire:click="addDjamo" type="button" wire:loading.attr="disabled"
                        class="btn btn-primary w-50" wire:loading.class="opacity-50 cursor-not-allowed">
                        Envoyer la demande
                    </button>

                    <div wire:loading.flex wire:target="addDjamo" class="flex items-center justify-center">
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
@elseif ($stepDjamo === 2)
    <div class="modal-dialog modal-lg mt-3">
        <div class="modal-content">
            <div class="modal-header alert alert-primary">
                <h2 class="font-medium text-base mr-auto">Validation de mot de passe </h2>
            </div>
            <div
                class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                    <a href="#step-1"
                        class="w-10 h-10 rounded-full btn btn-circle {{ $stepDjamo != 2 ? 'btn-default' : 'btn-tertiary' }}">2</a>
                    <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                    </div>
                </div>
            </div>

            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10 mt-3">

                <div class="col-span-12 flex flex-col items-center gap-4">
                    @if ($image_preview)
                        <div
                            class="relative group w-80 h-50 rounded-lg overflow-hidden border-2 border-green-300 shadow hover:shadow-lg transition">
                            <img src="{{ $image_preview }}" alt="Preview" class="h-full w-full object-cover">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 flex items-center justify-center transition">
                                <span
                                    class="text-white font-medium text-sm opacity-0 group-hover:opacity-100">Aperçu</span>
                            </div>
                        </div>
                    @elseif(is_string($image) && $image)
                        <div
                            class="relative group w-80 h-50 rounded-lg overflow-hidden border-2 border-blue-300 shadow hover:shadow-lg transition">
                            <img src="{{ Storage::url($image) }}" alt="Image existante"
                                class="h-full w-full object-cover">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 flex items-center justify-center transition">
                                <span class="text-white font-medium text-sm opacity-0 group-hover:opacity-100">Image
                                    actuelle</span>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="col-span-6 mt-1">
                    <label class="form-label">Nom et prénom</label>

                    <div class="flex">
                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $full_name_djamo ?? null }}
                        </div>
                    </div>

                </div>
                <div class="col-span-6 mt-1">
                    <label class="form-label">Montant</label>

                    <div class="flex">
                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                            {{ $amount_djamo ?? null }} {{ $usersCountry ?? null }}
                        </div>
                    </div>
                </div>

                <x-password-input-new />
            </div>

            <div class="modal-footer style-foo">
                <button wire:click="resetDjamoField" type="button" class="btn btn-danger-soft w-20 mr-1"
                    wire:loading.class="hidden" wire:target="addValidatedDjamo">Annuler</button>



                @if ($isPasswordValid)
                    <button wire:click="addValidatedDjamo" type="button" wire:loading.attr="disabled"
                        class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                        Validez la demande
                    </button>
                @else
                    <p class="text-danger mt-2">
                        Veuillez entrer un mot de passe valide.
                    </p>
                @endif

                <div wire:loading.flex wire:target="addValidatedDjamo" class="flex items-center justify-center">
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

