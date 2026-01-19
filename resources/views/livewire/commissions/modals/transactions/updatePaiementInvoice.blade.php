<div wire:ignore.self id="update-paiementInvoice-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            @include('components.modal-header', [
                'title' => 'Valider demande de paiement facture',
                'icon' => 'fas fa-check-circle',
                'service' => 'Demande de paiement Factures',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelPaiementField',
            ])


            <div class="modal-body mt-1">

                <div class="field-group">

                    <div class="field field-full">
                        <label class="form-label required">Canal de paiement</label>
                        <div class="field-value text-center">
                            <i class="fas fa-network-wired"></i>
                            <span id="provider_payment_name"> {{ $provider_payment_name ?? null }}</span>
                        </div>
                    </div>

                </div>


                @if (!is_null($provider_payment_edit))
                    @if ($provider_payment_edit == 7)
                        @if (!is_null($paiementChoix_phone_edit))
                            @php
                                $phoneNumberLabel =
                                    $paiementChoix_phone_edit == 1
                                        ? 'Numero du téléphone(10 chiffres)'
                                        : 'Numero du décodeur(14 chiffres)';
                                $phoneNumberInputId =
                                    $paiementChoix_phone_edit == 1
                                        ? 'paiement_phone_canal_edit'
                                        : 'paiement_decoder_canal_edit';
                            @endphp

                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">{{ $phoneNumberLabel }} </label>
                                    <div class="field-value">
                                        <i class="fas fa-id-card"></i>
                                        <span id="phone_user_edit">{{ $paiement_phone_canal_edit ?? $paiement_decoder_canal_edit }} </span>
                                    </div>
                                </div>
                             
                                <div class="field">
                                    <label class="form-label required">Offre</label>
                                    <div class="field-value">
                                        <i class="fas fa-id-card"></i>
                                        <span id="offer_paiement_edit">
                                            {{ $offer_paiement_edit }}</span>
                                    </div>
                                </div>
                                 <div class="field-full">
                                    <label class="form-label required">Nom & Prénom</label>
                                    <div class="field-value">
                                        <i class="fas fa-id-card"></i>
                                        <span id="offer_paiement_name">
                                            {{ $offer_paiement_name  ?? null}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="field-group">
                                <div class="field field-full">

                                    <label class="form-label required">Montant</label>
                                    <div class="field-value">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span id="offer_paiement_edit_amount">{{ $offer_paiement_edit_amount ?? null }}
                                            {{ $devise_user ?? null }}</span>
                                    </div>

                                </div>
                            </div>
                        @endif

                    @endif
                    @if ($provider_payment_edit == 8)

                        @if (!is_null($facture_choix_type_edit))
                            @php
                                $paiementInvoiceLabel =
                                    $facture_choix_type_edit == 1
                                        ? 'Identifiant facture(11 chiffres)'
                                        : 'Identifiant facture(09 chiffres)';
                                $paiementNumberInputId =
                                    $facture_choix_type_edit == 1
                                        ? 'paiement_id_invoice_edit'
                                        : 'paiement_id_invoice_edit';
                            @endphp

                            <div class="field-group">
                                <div class="field field-full">
                                    <label class="form-label required">Nom de la facture</label>
                                    <div class="field-value">
                                        <i class="fas fa-id-card"></i>
                                        <span id="paiement_name_cie_edit" class="inline-flex items-center gap-1">
                                            <span class="mr-3">{{ $paiement_name_cie_edit ?? null }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required"> {{ $paiementInvoiceLabel }} </label>
                                    <div class="field-value">
                                        <i class="fas fa-id-card"></i>
                                        <span id="paiement_id_invoice_edit" class="inline-flex items-center gap-1">
                                            <span class="mr-3">{{ $paiement_id_invoice_edit ?? null }}</span>
                                        </span>
                                    </div>
                                </div>


                                <div class="field">
                                    <label class="form-label required">Montant</label>
                                    <div class="field-value">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span
                                            id="paiement_amount_t_sodecie_edit">{{ $paiement_amount_t_sodecie_edit ?? null }}
                                            {{ $devise_user ?? null }}</span>
                                    </div>
                                </div>

                            </div>
                        @endif

                    @endif

                    @if ($provider_payment_edit == 10)
                        <div class="field-group">
                            <div class="field field-full">
                                <label class="form-label required">Nom de la facture</label>
                                <div class="field-value">
                                    <i class="fas fa-id-card"></i>
                                    <span id="paiement_name_sodecie_edit" class="inline-flex items-center gap-1">
                                        <span class="mr-3">{{ $paiement_name_sodecie_edit ?? null }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <label class="form-label required">Montant</label>
                                <div class="field-value">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span
                                        id="paiement_amount_t_sodecie_edit">{{ $paiement_amount_t_sodecie_edit ?? null }}
                                        {{ $devise_user ?? null }}</span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="form-label required">Numero du contrat (09
                                    chiffres)</label>
                                <div class="field-value">
                                    <i class="fas fa-user"></i>
                                    <span id="paiement_num_contrat_edit">
                                        {{ $paiement_num_contrat_edit ?? null }}</span>
                                </div>
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
                            <input
                                wire:click="tryOpenModalPaiementInvoice('validated-paiementInvoice-modal')"
                                wire:model="paiementStatus" id="bordered-radio-19" type="radio"
                                value="SUCCESS"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-19"
                                class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                                <i class="fas fa-check-circle style_success"></i>
                                Accepté</label>
                            @error('paiementStatus')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div
                            class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                            <input
                                wire:click="tryOpenModalPaiementInvoice('validated-paiementInvoice-modal')"
                                wire:model="paiementStatus" id="bordered-radio-20" type="radio"
                                value="ERROR"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-20"
                                class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                                <i class="fas fa-times-circle style_error"></i>
                                Refusé</label>
                            @error('paiementStatus')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                @endif

            </div>

        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_paiement')
