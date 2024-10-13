<!-- Modal -->

<div wire:ignore.self id="create-paiementInvoice-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

    @if ($stepPaiment === 1)

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Demande de paiement de facture</h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepPaiment != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                            requises
                        </div>
                    </div>
                </div>
                <a wire:click="cancelPaiementField"
                    style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="x" data-lucide="x"
                        class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                    <div class="col-span-12">
                        <label for="provider_payment" class="form-label">Canal<span class="text-danger">
                                *</span></label>
                        <select wire:model="provider_payment" id="provider_payment" class="form-control">
                            <option value="null" disabled>Selectionnez le canal</option>
                            @foreach ($provider_paiements as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_payment')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                </div>
                @if (!is_null($provider_payment))
                    @if ($provider_payment == 7)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/canal.png') }}" alt="MTN money">
                            </div>
                        </div>
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                            <div class="col-span-12">
                                <div class="alert alert-secondary show mb-2" role="alert">
                                    Faire le choix entre les deux numero
                                </div>
                            </div>
                            <div class="col-span-12">
                                <label class="form-label" for="paiementChoix_phone">Le choix de numero<span
                                        class="text-danger">
                                        *</span></label>
                                <select wire:model="paiementChoix_phone" id="paiementChoix_phone" class="form-control">
                                    <option value="null" disabled>Selectionnez type numero</option>
                                    <option value="1">Numero de telephone</option>
                                    <option value="2">Numero du decodeur</option>
                                </select>
                            </div>
                        </div>
                        @if (!is_null($paiementChoix_phone))
                            @php
                                $phoneNumberLabel =
                                    $paiementChoix_phone == 1
                                        ? 'Numero du téléphone(10 chiffres)'
                                        : 'Numero du décodeur(14 chiffres)';
                                $phoneNumberInputId =
                                    $paiementChoix_phone == 1 ? 'paiement_phone_canal' : 'paiement_decoder_canal';
                            @endphp

                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                                <div class="col-span-6">
                                    <label for="{{ $phoneNumberInputId }}"
                                        class="form-label">{{ $phoneNumberLabel }}<span
                                            class="text-danger">*</span></label>
                                    <input wire:model.defer="{{ $phoneNumberInputId }}" id="{{ $phoneNumberInputId }}"
                                        type="text" class="form-control" value=""
                                        maxlength="{{ $paiementChoix_phone == 1 ? 10 : 14 }}"
                                        placeholder="{{ $phoneNumberLabel }}">
                                    @error($phoneNumberInputId)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="offer_paiement" class="form-label">Offre<span
                                            class="text-danger">*</span></label>
                                    <select wire:model.debounce.10ms="offer_paiement" class="select2 form-select">
                                        <option value="null" disabled>Selectionnez offre</option>
                                        @foreach ($offers_canal as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('offer_paiement')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if (!is_null($offer_paiement))
                                    @if ($user_auth->is_under_distributer == 1)
                                        <div class="col-span-6 mt-3">
                                            <label for="offer_paiement" class="form-label">Commission<span
                                                    class="text-danger">*</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_canal_inter ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                        <div class="col-span-6 mt-3">
                                            <label for="offer_paiement" class="form-label">Montant à payer<span
                                                    class="text-danger">*</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_received_canal ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-span-12 mt-3">
                                            <label for="offer_paiement" class="form-label">Montant à payer<span
                                                    class="text-danger">*</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_received_canal ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                    @endif

                                @endif
                            </div>
                        @endif

                    @endif

                    @if ($provider_payment == 8)

                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/cie.png') }}" alt="Cie">
                            </div>
                        </div>


                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                            <div class="col-span-12">
                                <label class="form-label" for="facture_choix_type">Le choix de type de facture<span
                                        class="text-danger">
                                        *</span></label>
                                <select wire:model="facture_choix_type" id="facture_choix_type" class="form-control">
                                    <option value="null" disabled>Choix type facture</option>
                                    <option value="1">Facture prépayée</option>
                                    <option value="2">Facture normale</option>
                                </select>
                            </div>
                        </div>
                        @if (!is_null($facture_choix_type))

                            @php
                                $paiementInvoiceLabel =
                                    $facture_choix_type == 1
                                        ? 'Identifiant facture(11 chiffres)'
                                        : 'Identifiant facture(09 chiffres)';
                                $paiementNumberInputId =
                                    $facture_choix_type == 1 ? 'paiement_id_invoice_pre' : 'paiement_id_invoice';
                            @endphp

                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                                <div class="col-span-12">
                                    <label for="{{ $paiementNumberInputId }}"
                                        class="form-label">{{ $paiementInvoiceLabel }}<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="{{ $paiementNumberInputId }}"
                                        id="{{ $paiementNumberInputId }}" type="text" class="form-control"
                                        value="" maxlength="11" placeholder="{{ $paiementInvoiceLabel }}">
                                    @error('{{ $paiementNumberInputId }}')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                                <div class="col-span-6">
                                    <label for="paiement_name_cie" class="form-label">Nom de la facture<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="paiement_name_cie" id="paiement_name_cie" type="text"
                                        class="form-control" placeholder="Nom facture">
                                    @error('paiement_name_cie')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="pre_phone_invoice" class="form-label">Numero </label>
                                    <input wire:model.defer="pre_phone_invoice" id="pre_phone_invoice" type="number"
                                        class="form-control" placeholder="Numero de telephone">
                                    @error('pre_phone_invoice')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                                {{-- <div class="col-span-6">
                                    <label for="pre_id_invoice" class="form-label">Id facture<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="pre_id_invoice" id="pre_id_invoice" type="number"
                                        class="form-control" placeholder="Identifiant facture">
                                    @error('pre_id_invoice')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                                <div class="col-span-12">
                                    <label for="paiement_amount_total" class="form-label">Montant total<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.debounce.10ms="paiement_amount_total" id="paiement_amount_total"
                                        type="number" class="form-control" placeholder="Montant total">
                                    @error('paiement_amount_total')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (!is_null($paiement_amount_total))
                                    @if ($user_auth->is_under_distributer == 1)
                                        <div class="col-span-6 mt-3">
                                            <label for="paiement_amount_total" class="form-label">Commission<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                        <div class="col-span-6 mt-3">
                                            <label for="paiement_amount_total" class="form-label">Frais<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiemet_frais_receive ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                        <div class="col-span-12 mt-3">
                                            <label for="paiement_amount_total" class="form-label">Montant à
                                                traité<span class="text-danger">
                                                    *</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_receive ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-span-6 mt-3">
                                            <label for="paiement_amount_total" class="form-label">Montant à payer<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                        <div class="col-span-6 mt-3">
                                            <label for="paiement_amount_total" class="form-label">Montant à
                                                traité<span class="text-danger">
                                                    *</span></label>
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_receive ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                    @endif

                                @endif

                            </div>

                        @endif

                    @endif

                    @if ($provider_payment == 10)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/sodeci.png') }}" alt="MTN money">
                            </div>
                        </div>
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                            <div class="col-span-12">
                                <label for="paiement_name_sodecie" class="form-label">Nom de la facture<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="paiement_name_sodecie" id="paiement_name_sodecie"
                                    type="text" class="form-control" placeholder="Nom facture">
                                @error('paiement_name_sodecie')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10">
                            <div class="col-span-6">
                                <label for="paiement_num_contrat" class="form-label">Numero du contrat (09
                                    chiffres)<span class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="paiement_num_contrat" id="paiement_num_contrat"
                                    type="text" class="form-control" placeholder="Numero contrat" value=""
                                    maxlength="9">
                                @error('paiement_num_contrat')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6">
                                <label for="paiement_amount_t_sodecie" class="form-label">Montant total<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="paiement_amount_t_sodecie"
                                    id="paiement_amount_t_sodecie" type="number" class="form-control"
                                    placeholder="Montant total">
                                @error('paiement_amount_t_sodecie')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (!is_null($paiement_amount_t_sodecie))
                                @if ($user_auth->is_under_distributer == 1)
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Commission<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_paiement_invoice_auther_pay ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    </div>
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Frais<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_paiemet_frais_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    </div>
                                    <div class="col-span-12 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Montant à
                                            traité<span class="text-danger">
                                                *</span></label>
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_paiement_invoice_auther_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    </div>
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_paiement_invoice_auther_pay ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    </div>
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Montant à
                                            traité<span class="text-danger">
                                                *</span></label>
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_paiement_invoice_auther_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                @endif

                <div class="modal-footer style-foo">
                    <button wire:click="cancelPaiementField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="addPaiement" type="button" wire:loading.remove=""
                        class="btn btn-primary w-50">Envoyer la demande</button>
                    <div wire:loading wire:target="addPaiement">
                        Traitement en cours...
                    </div>
                </div>
            </div>
        </div>
    @elseif ($stepPaiment === 2)
        <div class="modal-dialog modal-lg mt-3">
            <div class="modal-content">
                <div class="modal-header alert alert-primary mb-3">
                    <h2 class="font-medium text-base mr-auto">Validation de mot de passe </h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepPaiment != 2 ? 'btn-default' : 'btn-secondary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                        </div>
                    </div>
                </div>

                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10 ">

                    @if ($provider_payment == 8)
                        <div class="col-span-6 mt-3">
                            <label class="form-label">Nom de la facture<span class="text-danger">
                                    *</span></label>

                            <div class="flex">

                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $paiement_name_cie ?? null }}
                                </div>
                            </div>

                        </div>
                        <div class="col-span-6 mt-3">
                            <label class="form-label">Montant de la facture<span class="text-danger">
                                    *</span></label>
                            <div class="flex">

                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $paiement_amount_total ?? null }} {{ $usersCountry ?? null }}
                                </div>
                            </div>

                        </div>
                    @elseif($provider_payment == 10)
                        <div class="col-span-6 mt-3">
                            <label class="form-label">Nom de la facture<span class="text-danger">
                                    *</span></label>

                            <div class="flex">

                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $paiement_name_sodecie ?? null }}
                                </div>
                            </div>


                        </div>
                        <div class="col-span-6 mt-3">
                            <label class="form-label">Montant <span class="text-danger">
                                    *</span></label>
                            <div class="flex">


                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $paiement_amount_t_sodecie ?? null }} {{ $usersCountry }}
                                </div>
                            </div>

                        </div>
                    @elseif ($provider_payment == 7)
                        <div class="col-span-6 mt-3">
                            <label class="form-label">Offre canal<span class="text-danger">
                                    *</span></label>

                            <div class="flex">

                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $name_canal_send ?? null }}
                                </div>
                            </div>


                        </div>
                        <div class="col-span-6 mt-3">
                            <label class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <div class="flex">


                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $amount_canal_send ?? null }} {{ $usersCountry }}
                                </div>
                            </div>

                        </div>
                    @endif

                    <div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="addPaiementValidated">
                        <x-password-input />

                    </div>
                </div>



                <div class="modal-footer style-foo">
                    <button wire:click="cancelPaiementField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1" wire:loading.class="hidden"
                        wire:target="addPaiementValidated">Annuler</button>

                    @if ($isPasswordValid)
                        <button wire:click="addPaiementValidated" type="button" wire:loading.attr="disabled"
                            class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                            Validez la demande
                        </button>
                    @else
                        <p class="text-danger mt-2">
                            Veuillez entrer un mot de passe valide pour activer le bouton de validation.
                        </p>
                    @endif

                    <div wire:loading.flex wire:target="addPaiementValidated"
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

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-paiement-created').listen(
                '.Created_wallet_paiement',
                (e) => {
                    window.livewire.emit('notifyNewPaiement:' + e.id);
                },
            )

        });
    </script>
</div>
