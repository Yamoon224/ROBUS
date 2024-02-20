<div wire:ignore.self id="update-paiementInvoice-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Valider la demande de paiement facture</h2>
            </div>
            <a wire:click="$emit('modal-hide-paiement')"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    style="color: white;" data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Paiement facture
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1">
                <div class="col-span-12">
                    <label for="provider_payment_name" class="form-label">Canal paiement de facture
                        <span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="provider_payment_name" disabled id="number_phone_credit_edit"
                        type="text" class="form-control style_edit_form" placeholder="Numero de telephone">
                </div>

            </div>
            @if (!is_null($provider_payment_edit))
                @if ($provider_payment_edit == 7)
                    @if (!is_null($paiementChoix_phone_edit))
                        @php
                            $phoneNumberLabel = $paiementChoix_phone_edit == 1 ? 'Numero du téléphone(10 chiffres)' : 'Numero du décodeur(14 chiffres)';
                            $phoneNumberInputId = $paiementChoix_phone_edit == 1 ? 'paiement_phone_canal_edit' : 'paiement_decoder_canal_edit';
                        @endphp

                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12 mt-space-padding">
                                <label for="{{ $phoneNumberLabel }}" class="form-label">{{ $phoneNumberLabel }}<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="{{ $phoneNumberInputId }}" disabled type="number"
                                    class="form-control style_edit_form" placeholder="Numero du telephone">

                            </div>
                        </div>


                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">

                            <div class="col-span-6 mt-space-padding">
                                <label for="offer_paiement_edit_amount" class="form-label">Montant<span
                                        class="text-danger">
                                        *</span></label>
                                <div class="flex">
                                    <input wire:model.defer="offer_paiement_edit_amount" disabled
                                        id="offer_paiement_edit_amount" type="number"
                                        class="form-control text-base font-medium" placeholder="Montant">
                                    <div
                                        class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                        <input wire:model.defer="devise_user" disabled id="devise_user" type="text"
                                            class="form-control style_edit_form" placeholder="Numero de telephone">
                                    </div>

                                </div>

                            </div>
                            <div class="col-span-6 mt-space-padding">
                                <label for="offer_paiement_edit" class="form-label">Offre<span class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="offer_paiement_edit" disabled id="offer_paiement_edit"
                                    type="text" class="form-control style_edit_form">
                            </div>
                        </div>
                    @endif

                @endif
                @if ($provider_payment_edit == 8)

                    @if (!is_null($facture_choix_type_edit))
                        @php
                            $paiementInvoiceLabel = $facture_choix_type_edit == 1 ? 'Identifiant facture(11 chiffres)' : 'Identifiant facture(09 chiffres)';
                            $paiementNumberInputId = $facture_choix_type_edit == 1 ? 'paiement_id_invoice_edit' : 'paiement_id_invoice_edit';
                        @endphp

                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12 mt-space-padding">
                                <label for="paiement_name_cie_edit" class="form-label">Nom de la facture<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="paiement_name_cie_edit" disabled id="paiement_name_cie_edit"
                                    type="text" class="form-control style_edit_form" placeholder="Nom facture">

                            </div>
                        </div>
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space-padding">
                                <label for="{{ $paiementInvoiceLabel }}"
                                    class="form-label">{{ $paiementInvoiceLabel }}<span class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="{{ $paiementNumberInputId }}" disabled
                                    id="{{ $paiementNumberInputId }}" type="text"
                                    class="form-control style_edit_form" placeholder="Identifiant">

                            </div>
                            <div class="col-span-6 mt-space-padding">
                                <label for="paiement_amount_t_sodecie_edit" class="form-label">Montant<span
                                        class="text-danger">
                                        *</span></label>
                                <div class="flex">
                                    <input wire:model.defer="paiement_amount_t_sodecie_edit" disabled
                                        id="paiement_amount_t_sodecie_edit" type="number"
                                        class="form-control text-base font-medium" placeholder="Montant">
                                    <div
                                        class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                        <input wire:model.defer="devise_user" disabled id="devise_user"
                                            type="text" class="form-control style_edit_form"
                                            placeholder="Numero de telephone">
                                    </div>

                                </div>

                            </div>
                        </div>
                    @endif

                @endif

                @if ($provider_payment_edit == 10)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="paiement_name_sodecie_edit" class="form-label">Nom de la facture<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="paiement_name_sodecie_edit" disabled id="paiement_name_sodecie_edit"
                                type="text" class="form-control style_edit_form" placeholder="Nom facture">

                        </div>
                    </div>

                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6 mt-1">
                            <label for="paiement_amount_t_sodecie_edit" class="form-label">Montant<span
                                    class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <input wire:model.defer="paiement_amount_t_sodecie_edit" disabled
                                    id="paiement_amount_t_sodecie_edit" type="number"
                                    class="form-control text-base font-medium" placeholder="Montant">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                    <input wire:model.defer="devise_user" disabled id="devise_user" type="text"
                                        class="form-control style_edit_form" placeholder="Numero de telephone">
                                </div>

                            </div>

                        </div>
                        <div class="col-span-6  mt-1">
                            <label for="paiement_num_contrat_edit" class="form-label">Numero du contrat (09
                                chiffres)<span class="text-danger">
                                    *</span></label>
                            <input wire:model="paiement_num_contrat_edit" disabled id="paiement_num_contrat_edit"
                                type="number" class="form-control style_edit_form" placeholder="Numero contrat">

                        </div>
                    </div>
                @endif
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12 mt-space-padding">
                        <label for="payment_description_robus" class="form-label">Commentaire</label>
                        <textarea wire:model.defer="payment_description_robus" id="payment_description_robus" type="text"
                            class="form-control" cols="13" rows="3">
                        </textarea>

                    </div>
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                    <div
                        class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:click="$emit('modal-paiement-validted-show' , 'validated-paiementInvoice-modal')"
                            wire:model.debounce.10ms="paiementStatus" id="bordered-radio-19" type="radio"
                            value="SUCCESS"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-19"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Accepté</label>
                        @error('paiementStatus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div
                        class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:click="$emit('modal-paiement-validted-show' , 'validated-paiementInvoice-modal')"
                            wire:model.debounce.10ms="paiementStatus" id="bordered-radio-20" type="radio"
                            value="ERROR"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-20"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Refusé</label>
                        @error('paiementStatus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            @endif


        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_paiement')
