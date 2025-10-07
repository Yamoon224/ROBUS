<div wire:ignore.self id="update-interTransfers-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
              @include("components.modal-header", [
                'title' => 'Valider demande internationale',
                'icon' => 'fas fa-check-circle',
                'service' => 'Internationale',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelInterField'
            ])

            @if ($choose_type_paiement == 1)

                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Transfers d'argent
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label for="provider_phone_credit_inter_edit" class="form-label">Canal transfers d'argent
                            <span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="provider_phone_credit_inter_edit" disabled
                            id="number_phone_credit_edit" type="text" class="form-control style_edit_form"
                            placeholder="Numero de telephone">
                    </div>
                    <div class="col-span-6">
                        <label for="number_phone_credit_inter_edit" class="form-label">Numero<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="number_phone_credit_inter_edit" disabled id="number_phone_credit_edit"
                            type="text" class="form-control style_edit_form" placeholder="Numero de telephone">

                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label for="amount_virement_inter_edit" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <div class="flex">
                            <input wire:model.defer="amount_virement_inter_edit" disabled
                                id="amount_virement_inter_edit" type="number"
                                class="form-control text-base font-medium" placeholder="Montant">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                <input wire:model.defer="devise_user" disabled id="devise_user" type="text"
                                    class="form-control style_edit_form" placeholder="Numero de telephone">
                            </div>

                        </div>

                    </div>
                    <div class="col-span-6  mt-space-padding">
                        <label for="country_inter_robus_inter_edit" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="country_inter_robus_inter_edit" disabled
                            id="country_inter_robus_inter_edit" type="text"
                            class="form-control text-base font-medium" placeholder="Pays">

                    </div>
                </div>
            @elseif($choose_type_paiement == 5)
                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Paiement facture
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1">
                    <div class="col-span-12">
                        <label for="provider_phone_credit_inter_edit" class="form-label">Canal paiement facture
                            <span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="provider_phone_credit_inter_edit" disabled
                            id="number_phone_credit_edit" type="text" class="form-control style_edit_form"
                            placeholder="Numero de telephone">
                    </div>


                </div>
                @if ($provider_inter_edit == 52)
                    @if (!is_null($paiementChoix_phone_edit))
                        @php
                            $phoneNumberLabel = $paiementChoix_phone_edit == 1 ? 'Numero du téléphone(10 chiffres)' : 'Numero du décodeur(14 chiffres)';
                            $phoneNumberInputId = $paiementChoix_phone_edit == 1 ? 'paiement_phone_canal_edit' : 'paiement_decoder_canal_edit';
                        @endphp
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6  mt-space-padding">
                                <label for="offer_paiement_edit" class="form-label">Offre<span class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="offer_paiement_edit" disabled id="offer_paiement_edit"
                                    type="text" class="form-control style_edit_form"
                                    placeholder="Numero de telephone">
                            </div>
                            <div class="col-span-6  mt-space-padding">
                                <label for="{{ $phoneNumberLabel }}" class="form-label">{{ $phoneNumberLabel }}<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="{{ $phoneNumberInputId }}" disabled
                                    id="{{ $phoneNumberInputId }}" type="text"
                                    class="form-control style_edit_form" placeholder="Numero de telephone">

                            </div>
                        </div>

                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                            <div class="col-span-6 mt-space-padding">
                                <label for="amount_virement_inter_edit" class="form-label">Montant<span
                                        class="text-danger">
                                        *</span></label>
                                <div class="flex">
                                    <input wire:model.defer="amount_virement_inter_edit" disabled
                                        id="amount_virement_inter_edit" type="number"
                                        class="form-control text-base font-medium" placeholder="Montant">
                                    <div
                                        class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                        <input wire:model.defer="devise_user" disabled id="devise_user"
                                            type="text" class="form-control style_edit_form"
                                            placeholder="Numero de telephone">
                                    </div>

                                </div>

                            </div>
                            <div class="col-span-6  mt-space-padding">
                                <label for="country_inter_robus_inter_edit" class="form-label">Pays<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="country_inter_robus_inter_edit" disabled
                                    id="country_inter_robus_inter_edit" type="text"
                                    class="form-control text-base font-medium" placeholder="Pays">

                            </div>
                        </div>
                    @endif

                @endif
                @if ($provider_inter_edit == 53)

                    @if (!is_null($facture_choix_type_edit))
                        @php
                            $paiementInvoiceLabel = $facture_choix_type_edit == 1 ? 'Identifiant facture(11 chiffres)' : 'Identifiant facture(09 chiffres)';
                            $paiementNumberInputId = $facture_choix_type_edit == 1 ? 'paiement_id_invoice_edit' : 'paiement_id_invoice_edit';
                        @endphp
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6">
                                <label for="paiement_name_cie_edit" class="form-label">Nom de la facture<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="paiement_name_cie_edit" disabled id="paiement_name_cie_edit"
                                    type="text" class="form-control style_edit_form" placeholder="Nom facture">

                            </div>
                            <div class="col-span-6">
                                <label for="{{ $paiementNumberInputId }}"
                                    class="form-label">{{ $paiementInvoiceLabel }}<span class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="{{ $paiementNumberInputId }}" disabled
                                    id="{{ $paiementNumberInputId }}" type="text"
                                    class="form-control style_edit_form" placeholder="Identifiant">

                            </div>
                        </div>

                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space-padding">
                                <label for="amount_virement_inter_edit" class="form-label">Montant<span
                                        class="text-danger">
                                        *</span></label>
                                <div class="flex">
                                    <input wire:model.defer="amount_virement_inter_edit" disabled
                                        id="amount_virement_inter_edit" type="number"
                                        class="form-control text-base font-medium" placeholder="Montant">
                                    <div
                                        class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                        <input wire:model.defer="devise_user" disabled id="devise_user"
                                            type="text" class="form-control style_edit_form"
                                            placeholder="Numero de telephone">
                                    </div>

                                </div>

                            </div>
                            <div class="col-span-6  mt-space-padding">
                                <label for="country_inter_robus_inter_edit" class="form-label">Pays<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="country_inter_robus_inter_edit" disabled
                                    id="country_inter_robus_inter_edit" type="text"
                                    class="form-control text-base font-medium" placeholder="Pays">

                            </div>
                        </div>
                    @endif

                @endif
                @if ($provider_inter_edit == 54)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="paiement_name_cie_edit" class="form-label">Nom de la facture<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="paiement_name_cie_edit" disabled id="paiement_name_cie_edit"
                                type="text" class="form-control style_edit_form" placeholder="Nom facture">

                        </div>
                        <div class="col-span-6">
                            <label for="paiement_num_contrat_edit" class="form-label">Numero du contrat<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="paiement_num_contrat_edit" disabled
                                id="paiement_num_contrat_edit" type="text" class="form-control style_edit_form"
                                placeholder="Identifiant">

                        </div>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6 mt-space-padding">
                            <label for="amount_virement_inter_edit" class="form-label">Montant<span
                                    class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <input wire:model.defer="amount_virement_inter_edit" disabled
                                    id="amount_virement_inter_edit" type="number"
                                    class="form-control text-base font-medium" placeholder="Montant">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                    <input wire:model.defer="devise_user" disabled id="devise_user" type="text"
                                        class="form-control style_edit_form" placeholder="Numero de telephone">
                                </div>

                            </div>

                        </div>
                        <div class="col-span-6  mt-space-padding">
                            <label for="country_inter_robus_inter_edit" class="form-label">Pays<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="country_inter_robus_inter_edit" disabled
                                id="country_inter_robus_inter_edit" type="text"
                                class="form-control text-base font-medium" placeholder="Pays">

                        </div>
                    </div>
                @endif
            @elseif($choose_type_paiement == 6)
                {{-- Canal du paiement de credit --}}

                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Credit telephonique
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label for="provider_phone_credit_inter_edit" class="form-label">Canal credit
                            telephonique<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="provider_phone_credit_inter_edit" disabled
                            id="number_phone_credit_edit" type="text" class="form-control style_edit_form"
                            placeholder="Numero de telephone">
                    </div>
                    <div class="col-span-6">
                        <label for="number_phone_credit_inter_edit" class="form-label">Numero<span
                                class="text-danger">
                                *</span></label>
                        <input wire:model.defer="number_phone_credit_inter_edit" disabled
                            id="number_phone_credit_edit" type="text" class="form-control style_edit_form"
                            placeholder="Numero de telephone">

                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label for="amount_phone_credit_inter_edit" class="form-label">Montant<span
                                class="text-danger">
                                *</span></label>
                        <div class="flex">
                            <input wire:model.defer="amount_phone_credit_inter_edit" disabled
                                id="amount_phone_credit_inter_edit" type="number"
                                class="form-control text-base font-medium" placeholder="Montant">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                <input wire:model.defer="devise_user" disabled id="devise_user" type="text"
                                    class="form-control style_edit_form" placeholder="Numero de telephone">
                            </div>

                        </div>

                    </div>
                    <div class="col-span-6  mt-space-padding">
                        <label for="country_inter_robus_inter_edit" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="country_inter_robus_inter_edit" disabled
                            id="country_inter_robus_inter_edit" type="text"
                            class="form-control text-base font-medium" placeholder="Pays">

                    </div>
                </div>
            @elseif($choose_type_paiement == 7)
                {{-- Canal du versement bancaire --}}
                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Versement bancaire
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label for="provider_phone_credit_inter_edit" class="form-label">Canal credit
                            telephonique<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="provider_phone_credit_inter_edit" disabled
                            id="number_phone_credit_edit" type="text" class="form-control style_edit_form"
                            placeholder="Numero de telephone">
                    </div>
                    <div class="col-span-6">
                        <label for="number_phone_credit_inter_edit" class="form-label">Numero<span
                                class="text-danger">
                                *</span></label>
                        <input wire:model.defer="number_phone_credit_inter_edit" disabled
                            id="number_phone_credit_edit" type="text" class="form-control style_edit_form"
                            placeholder="Numero de telephone">

                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                    <div class="col-span-6 mt-space-padding">
                        <label for="amount_virement_inter_edit" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <div class="flex">
                            <input wire:model.defer="amount_virement_inter_edit" disabled
                                id="amount_virement_inter_edit" type="number"
                                class="form-control text-base font-medium" placeholder="Montant">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                <input wire:model.defer="devise_user" disabled id="devise_user" type="text"
                                    class="form-control style_edit_form" placeholder="Numero de telephone">
                            </div>

                        </div>

                    </div>
                    <div class="col-span-6  mt-space-padding">
                        <label for="country_inter_robus_inter_edit" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="country_inter_robus_inter_edit" disabled
                            id="country_inter_robus_inter_edit" type="text"
                            class="form-control text-base font-medium" placeholder="Pays">

                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                    <div class="col-span-6  mt-space-padding">
                        <label for="firstname_user_inter_edit" class="form-label">Nom et prenom<span
                                class="text-danger">
                                *</span></label>
                        <input wire:model.defer="firstname_user_inter_edit" disabled id="firstname_user_inter_edit"
                            type="text" class="form-control text-base font-medium" placeholder="Pays">

                    </div>
                    <div class="col-span-6  mt-space-padding">
                        <label for="account_number_user_bank_inter_edit" class="form-label">Numero compte<span
                                class="text-danger">
                                *</span></label>
                        <input wire:model.defer="account_number_user_bank_inter_edit" disabled
                            id="account_number_user_bank_inter_edit" type="text"
                            class="form-control text-base font-medium">

                    </div>
                </div>
            @elseif($choose_type_paiement == 8)
                {{-- Canal de rechargement de carte --}}

                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Rechargment carte
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label for="provider_phone_credit_inter_edit" class="form-label">Canal rechargement de carte
                            <span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="provider_phone_credit_inter_edit" disabled
                            id="number_phone_credit_edit" type="text" class="form-control style_edit_form"
                            placeholder="Numero de telephone">
                    </div>
                    <div class="col-span-6">
                        <label for="phone_user_auth_inter_edit" class="form-label">Numero<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="phone_user_auth_inter_edit" disabled id="number_phone_credit_edit"
                            type="text" class="form-control style_edit_form" placeholder="Numero de telephone">

                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                    <div class="col-span-6 mt-space-padding">
                        <label for="amount_virement_inter_edit" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <div class="flex">
                            <input wire:model.defer="amount_virement_inter_edit" disabled
                                id="amount_virement_inter_edit" type="number"
                                class="form-control text-base font-medium" placeholder="Montant">
                            <div
                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-devise">
                                <input wire:model.defer="devise_user" disabled id="devise_user" type="text"
                                    class="form-control style_edit_form" placeholder="Numero de telephone">
                            </div>

                        </div>

                    </div>
                    <div class="col-span-6  mt-space-padding">
                        <label for="country_inter_robus_inter_edit" class="form-label">Pays<span class="text-danger">
                                *</span></label>
                        <input wire:model.defer="country_inter_robus_inter_edit" disabled
                            id="country_inter_robus_inter_edit" type="text"
                            class="form-control text-base font-medium" placeholder="Pays">

                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                    <div class="col-span-6  mt-space-padding">
                        <label for="name_relo_user_inter_edit" class="form-label">Nom et prenom<span
                                class="text-danger">
                                *</span></label>
                        <input wire:model.defer="name_relo_user_inter_edit" disabled id="name_relo_user_inter_edit"
                            type="text" class="form-control text-base font-medium" placeholder="Pays">

                    </div>
                    <div class="col-span-6  mt-space-padding">
                        <label for="account_number_user_bank_inter_edit" class="form-label">Numero compte<span
                                class="text-danger">
                                *</span></label>
                        <input wire:model.defer="account_number_user_bank_inter_edit" disabled
                            id="account_number_user_bank_inter_edit" type="text"
                            class="form-control text-base font-medium">

                    </div>
                </div>

            @endif

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="inter_description_robus" class="form-label">Commentaire</label>
                    <textarea wire:model="inter_description_robus" id="inter_description_robus" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>

                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-inter-validted-show' , 'validated-inter-modal')"
                        wire:model="interStatus" id="bordered-radio-50" type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-50"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Accepté</label>
                    @error('interStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-inter-validted-show' , 'validated-inter-modal')"
                        wire:model="interStatus" id="bordered-radio-51" type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-51"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Refusé</label>
                    @error('interStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>


        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_inter')
