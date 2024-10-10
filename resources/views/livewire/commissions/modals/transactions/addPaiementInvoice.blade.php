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
                                                {{ $amount_received_canal ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-span-12 mt-3"></div>
                                    @endif
                                    <div class="col-span-12 mt-3">
                                        <label for="offer_paiement" class="form-label">Montant à payer<span
                                                class="text-danger">*</span></label>
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_paiement_invoice_canal_inter ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    </div>
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
                                <div class="col-span-6">
                                    <label for="pre_id_invoice" class="form-label">Id facture<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="pre_id_invoice" id="pre_id_invoice" type="number"
                                        class="form-control" placeholder="Identifiant facture">
                                    @error('pre_id_invoice')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
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
                                            @if ($paiement_amount_total == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                    style="font-size: 25px; color:white;">
                                                    {{ $amount_paiement_invoice_auther_pay ?? null }}
                                                    {{ $usersCountry }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="col-span-6 mt-3">
                                            <label for="paiement_amount_total" class="form-label">Montant à payer<span
                                                    class="text-danger">
                                                    *</span></label>
                                            @if ($paiement_amount_total == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                    style="font-size: 25px; color:white;">
                                                    {{ $amount_paiement_invoice_auther_pay ?? null }}
                                                    {{ $usersCountry }}
                                                </div>
                                            @endif
                                        </div>

                                    @endif
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_total" class="form-label">Montant à recevoir<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($paiement_amount_total == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_receive ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
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
                                        @if ($paiement_amount_t_sodecie == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Montant à
                                            recevoir<span class="text-danger">
                                                *</span></label>
                                        @if ($paiement_amount_t_sodecie == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_receive ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($paiement_amount_t_sodecie == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-span-6 mt-3">
                                        <label for="paiement_amount_t_sodecie" class="form-label">Montant à
                                            recevoir<span class="text-danger">
                                                *</span></label>
                                        @if ($paiement_amount_t_sodecie == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_paiement_invoice_auther_receive ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
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
                <div class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepPaiment != 2 ? 'btn-default' : 'btn-secondary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                        </div>
                    </div>
                </div>

                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10 ">

                    @if ($provider_payment == 8)
                        <div class="col-span-6 mt-space-padding">
                            <label class="form-label">Nom de la facture<span class="text-danger">
                                    *</span></label>

                            <div class="flex">

                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $paiement_name_cie ?? null }}
                                </div>
                            </div>

                        </div>
                        <div class="col-span-6 mt-space-padding">
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
                        <div class="col-span-6 mt-space-padding">
                            <label class="form-label">Nom de la facture<span class="text-danger">
                                    *</span></label>

                            <div class="flex">

                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $paiement_name_sodecie ?? null }}
                                </div>
                            </div>


                        </div>
                        <div class="col-span-6 mt-space-padding">
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
                        <div class="col-span-6 mt-space-padding">
                            <label class="form-label">Offre canal<span class="text-danger">
                                    *</span></label>

                            <div class="flex">

                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $offer_paiement ?? null }}
                                </div>
                            </div>


                        </div>
                        <div class="col-span-6 mt-space-padding">Montant<span class="text-danger">
                                *</span></label>
                            <div class="flex">


                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $paiement_phone_canal ?? null }} {{ $usersCountry }}
                                </div>
                            </div>

                        </div>
                    @endif

                    <div class="col-span-12 mt-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Mot de passe<span class="text-danger">
                                    *</span></label>
                        </div>

                        <div class="input-group input-group-merge form-password-toggle">
                            <input wire:model="password_validated" placeholder="mot de passe"
                                :type="show ? 'password' : 'text'" id="password" tabindex="2"
                                aria-describedby="password" name="password" required autocomplete="current-password"
                                class="form-control
                    focus:placeholder-gray-500
                    focus:bg-white 
                    focus:border-gray-600  
                 focus:outline-none">
                            <div class="input-group-text cursor-pointer">
                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                    :class="{ 'hidden': !show, 'block': show }" xmlns="http://www.w3.org/2000/svg"
                                    viewbox="0 0 576 512">
                                    <path fill="currentColor"
                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                    </path>
                                </svg>

                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                    :class="{ 'block': !show, 'hidden': show }" xmlns="http://www.w3.org/2000/svg"
                                    viewbox="0 0 640 512">
                                    <path fill="currentColor"
                                        d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                    </path>
                                </svg>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @error('password')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>



                <div class="modal-footer style-foo">
                    <button wire:click="cancelPaiementField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="addPaiementValidated" type="button" wire:loading.attr="disabled"
                        class="btn btn-primary w-50" wire:loading.class="opacity-50 cursor-not-allowed">
                        Validez la demande
                    </button>

                    <div wire:loading.flex wire:target="addPaiementValidated"
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
