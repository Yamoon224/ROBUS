 @if ($stepVirement === 1)
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Demande de virement bancaire</h2>
                </div>

                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="step-number w-10 h-10 rounded-full btn btn-circle {{ $stepVirement != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                        <div class="step-label lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">
                            Saisissez les informations requises
                        </div>
                    </div>
                </div>
                <a wire:click="cancelVirementField"
                    style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-12 mt-space form-group required">
                        <label for="provider_virement" class="form-label">Canal </label>
                        <select wire:model="provider_virement" id="provider_virement" class="form-control" required>
                            <option value="" selected>Selectionnez le canal</option>
                            @foreach ($provider_virements as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_virement')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if (!is_null($provider_virement) && in_array($provider_virement, [11, 12]))
                    @php
                        $provider_logos = [
                            11 => 'ecobank.png',
                            12 => 'nsia.png',
                        ];
                    @endphp

                    <!-- Logo -->
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset_url('dist/providers/' . $provider_logos[$provider_virement]) }}"
                                alt="{{ $provider_virements->firstWhere('id', $provider_virement)?->name }}">
                        </div>
                    </div>

                    <!-- Champs communs -->
                    @include('components.virement-input')

                    <!-- Montants -->
                    @include('components.virement-montants')
                @endif


                <div class="modal-footer style-foo" style="margin-top: -1rem;">
                    <button wire:click="cancelVirementField" type="button"
                        class="btn btn-danger-soft w-20 mr-1">Annuler</button>
                    @if (!is_null($provider_virement) && $provider_virement != '')
                        <button wire:click="addVirement" type="button" wire:loading.attr="disabled"
                            class="btn btn-primary">
                            <span wire:loading.remove wire:target="addVirement">Envoyer la demande</span>
                            <span wire:loading wire:target="addVirement" class="loading-spinner">
                                Traitement en cours...
                            </span>
                        </button>
                    @else
                        <button type="button" class="btn btn-primary" disabled>
                            Sélectionnez un canal
                        </button>
                    @endif


                </div>
            </div>
        </div>
    @elseif ($stepVirement === 2)
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- En-tête -->
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Validation de mot de passe</h2>
                </div>

                <!-- Indicateur de progression -->
               <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepVirement != 2 ? 'btn-default' : 'btn-tertiary' }}">2</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                        </div>
                    </div>
                </div>


                <!-- Corps de la modal -->
                <div class="modal-body">
                    <div class="validation-summary">
                        <div class="validation-item">
                            <label class="validation-label">Nom & prénom</label>
                            <div class="validation-value">{{ $firstname_user ?? '' }} {{ $lastname_user ?? '' }}
                            </div>
                        </div>

                        <div class="validation-item">
                            <label class="validation-label">Montant</label>
                            <div class="validation-value">
                                {{ number_format($amount_virement ?? 0, 2, ',', ' ') }} {{ $usersCountry }}
                            </div>
                        </div>

                        <div class="validation-item full-width">
                            <label class="validation-label">Canal</label>
                            <div class="validation-value">
                                @foreach ($provider_virements as $item)
                                    @if ($item->id == $provider_virement)
                                        {{ $item->name }}
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="validation-item">
                            <label class="validation-label">Numéro de compte</label>
                            <div class="validation-value">{{ $account_number_user_bank ?? '' }}</div>
                        </div>

                        <div class="validation-item">
                            <label class="validation-label">Téléphone</label>
                            <div class="validation-value">{{ $phone_user ?? '' }}</div>
                        </div>
                    </div>

                    <!-- Champ mot de passe (obligatoire) -->
                    <div class="password-section " wire:loading.class="hidden" wire:target="addValidatedVirement">
                        <x-password-input />
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Pied de page -->
                <div class="modal-footer">
                    <button wire:click="cancelVirementField" type="button" class="btn btn-danger-soft"
                        wire:loading.class="hidden" wire:target="addValidatedVirement">
                        Annuler
                    </button>

                    @if ($isPasswordValid)
                        <button wire:click="addValidatedVirement" type="button" wire:loading.attr="disabled"
                            class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                            Validez la demande
                        </button>
                    @else
                        <p class="text-danger mt-2">
                            Mot de passe requis
                        </p>
                    @endif
                    <div wire:loading.flex wire:target="addValidatedVirement"
                        class="flex items-center justify-center">
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
            </div>
        </div>
    @endif