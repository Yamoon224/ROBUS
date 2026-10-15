   @if ($stepReloadCard === 1)

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Demande de rechargement de carte</h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepReloadCard != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                            requises
                        </div>
                    </div>
                </div>
                <a wire:click="cancelReloadCardField"
                    style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="x" data-lucide="x"
                        class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-12 mt-space form-group required">
                        <label for="provider_reloadcard" class="form-label">Canal</label>
                        <select wire:model="provider_reloadcard" id="provider_reloadcard" class="form-control" required>
                            <option value="null" disabled>Selectionnez le canal</option>
                            @foreach ($provider_reloads as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_reloadcard')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if (!is_null($provider_reloadcard))
                    @php
                        // Tableau des providers avec leurs images
                        $providerImages = [
                            16 => 'bni.png',
                            15 => 'bsic.png',
                            14 => 'ecobank.png',
                            17 => 'uba.png',
                            13 => 'uba.png',
                        ];
                        $providerImg = $providerImages[$provider_reloadcard] ?? null;
                    @endphp

                    <!-- Affichage image provider -->
                    @if ($providerImg)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset_url('dist/providers/' . $providerImg) }}" alt="Provider Image">
                            </div>
                        </div>
                    @endif

                    <!-- Champs communs -->
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 form-group required">
                            <label for="account_number_reload_card" class="form-label">Numéro du compte </label>
                            <input wire:model.defer="account_number_reload_card" id="account_number_reload_card"
                                type="text" class="form-control" placeholder="Numéro du compte" maxlength="10"
                                required>
                            @error('account_number_reload_card')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 form-group required">
                            <label for="name_reloadcard" class="form-label">Nom et prénom </label>
                            <input wire:model.defer="name_reloadcard" id="name_reloadcard" type="text"
                                class="form-control" placeholder="Nom et prénom" required>
                            @error('name_reloadcard')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 form-group required">
                            <label for="amount_reloadcard" class="form-label">Montant </label>
                            <input wire:model.debounce.10ms="amount_reloadcard" id="amount_reloadcard" type="number"
                                class="form-control" placeholder="Montant" required>
                            @error('amount_reloadcard')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if (!is_null($amount_reloadcard) && $amount_reloadcard > 0)
                            <!-- Montants à payer/recevoir -->
                            <div class="col-span-6 mt-3">
                                <label
                                    class="form-label">{{ $user_auth->is_under_distributer && $showRemonterRechargementButton ? 'Montant commission' : 'Montant à payer' }}
                                </label>
                                <div
                                    class="px-5 py-3 alert {{ $user_auth->is_under_distributer && $showRemonterRechargementButton ? 'alert-primary' : 'alert-primary' }} font-medium text-white text-xl">
                                    {{ formatDisplayAmount($amount_deposit_nation_pay ?? '-') }} {{ $usersCountry }}
                                </div>
                            </div>
                            <div class="col-span-6 mt-3">
                                <label class="form-label">Montant à recevoir </label>
                                <div class="px-5 py-3 alert alert-success font-medium text-white text-xl">
                                    {{ formatDisplayAmount($amount_deposit_nation_receive ?? '-') }} {{ $usersCountry }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="modal-footer style-foo" style="margin-top: -1rem;">
                    <button wire:click="cancelReloadCardField" type="button"
                        class="btn btn-danger-soft w-20 mr-1">Annuler</button>

                    @if (!is_null($provider_reloadcard) && $provider_reloadcard != '')
                        <button wire:click="addReloadCard" type="button" wire:loading.remove=""
                            class="btn btn-primary w-50">Envoyer la demande</button>
                        <div wire:loading wire:target="addReloadCard">
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
    @elseif ($stepReloadCard === 2)
        <div class="modal-dialog modal-lg mt-3">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Validation de mot de passe </h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepReloadCard != 2 ? 'btn-default' : 'btn-tertiary' }}">2</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                        </div>
                    </div>
                </div>

                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10 mt-3">

                    @include('components.info_confirme_passe')

                    <div class="col-span-6 mt-3">
                        <label class="form-label">Nom & prenom</label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $name_reloadcard ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-6 mt-3">Montant<span class="text-danger">
                            *</span></label>
                        <div class="flex">


                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ formatDisplayAmount($amount_reloadcard ?? null) }} {{ $usersCountry }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="addValidatedReloadCard">
                        <x-password-input />

                    </div>
                </div>

                <div class="modal-footer style-foo">
                    <button wire:click="cancelReloadCardField" type="button"
                        class="btn btn-danger-soft w-20 mr-1" wire:loading.class="hidden"
                        wire:target="addValidatedReloadCard">Annuler</button>


                    @if ($isPasswordValid)
                        <button wire:click="addValidatedReloadCard" type="button" wire:loading.attr="disabled"
                            class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                            Validez la demande
                        </button>
                    @else
                        <p class="text-danger mt-2">
                            Veuillez entrer un mot de passe valide.
                        </p>
                    @endif


                    <div wire:loading.flex wire:target="addValidatedReloadCard"
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