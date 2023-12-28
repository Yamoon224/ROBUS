<div wire:ignore.self id="update-paiementHisto-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Remboursement</h2>
            </div>
            <a wire:click="$emit('modal-hide-paiementHisto')"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="provider_payment_edit" disabled id="modal-form-1" class="form-control">
                        @foreach ($provider_paiements as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            @if (!is_null($provider_payment_edit))
                @if ($provider_payment_edit == 7)
                    @if (!is_null($paiementChoix_phone_edit))

                        @if ($paiementChoix_phone_edit == 1)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="paiement_phone_canal_edit" class="form-label">Numero du telephone(10
                                        chiffres)<span class="text-danger">
                                            *</span></label>
                                    <input wire:model="paiement_phone_canal_edit" disabled
                                        id="paiement_phone_canal_edit" type="number" class="form-control"
                                        placeholder="Numero du telephone">

                                </div>
                            </div>
                        @elseif ($paiementChoix_phone_edit == 2)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="paiement_decoder_canal_edit" class="form-label">Numero du decodeur(14
                                        chiffres)<span class="text-danger">
                                            *</span></label>
                                    <input wire:model="paiement_decoder_canal_edit" disabled
                                        id="paiement_decoder_canal_edit" type="number" class="form-control"
                                        placeholder="Numero du decodeur">

                                </div>
                            </div>
                        @endif

                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12">
                                <label for="offer_paiement_edit" class="form-label">Offre<span class="text-danger">
                                        *</span></label>
                                <select wire:model="offer_paiement_edit" disabled class="select2 form-select">
                                    @foreach ($offers_canal as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                       
                    @endif

                @endif
                @if ($provider_payment_edit == 8)

                    @if (!is_null($facture_choix_type_edit))
                        @if ($facture_choix_type_edit == 1)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="paiement_name_cie_edit" class="form-label">Nom de la facture<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="paiement_name_cie_edit" disabled
                                        id="paiement_name_cie_edit" type="text" class="form-control"
                                        placeholder="Nom facture">

                                </div>
                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="paiement_id_invoice_edit" class="form-label">Identifiant facture(11
                                        chiffres)<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="paiement_id_invoice_edit" disabled
                                        id="paiement_id_invoice_edit" type="text" class="form-control"
                                        placeholder="Identifiant">

                                </div>
                            </div>
                        @elseif ($facture_choix_type_edit == 2)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="paiement_name_cie_edit" class="form-label">Nom de la facture<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="paiement_name_cie_edit" disabled
                                        id="paiement_name_cie_edit" type="text" class="form-control"
                                        placeholder="Nom facture">

                                </div>
                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="paiement_id_invoice_edit" class="form-label">Identifiant facture(09
                                        chiffres)<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="paiement_id_invoice_edit" disabled
                                        id="paiement_id_invoice_edit" type="text" class="form-control"
                                        placeholder="Identifiant">

                                </div>
                            </div>
                        @endif
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12">
                                <label for="paiement_amount_total_edit" class="form-label">Montant total<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="paiement_amount_total_edit" disabled
                                    id="paiement_amount_total_edit" type="number" class="form-control"
                                    placeholder="Montant total">

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
                                type="text" class="form-control" placeholder="Nom facture">

                        </div>
                    </div>
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="paiement_amount_t_sodecie_edit" class="form-label">Montant total<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="paiement_amount_t_sodecie_edit" disabled
                                id="paiement_amount_t_sodecie_edit" type="text" class="form-control">

                        </div>
                    </div>
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="paiement_num_contrat_edit" class="form-label">Numero du contrat (09
                                chiffres)<span class="text-danger">
                                    *</span></label>
                            <input wire:model="paiement_num_contrat_edit" disabled id="paiement_num_contrat_edit"
                                type="number" class="form-control" placeholder="Numero contrat">

                        </div>
                    </div>
                @endif

                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">


                    <div
                        class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:model="paiementStatus" id="bordered-radio-44" type="radio" value="REFUND"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="paiementStatus"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Remboursé</label>
                        @error('paiementStatus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if ($paiementStatus == 'REFUND')
                    <div class="modal-footer style-foo">
                        <button wire:click="$emit('modal-hide-paiementHisto')" type="button"
                            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                        <button wire:click="updatePaiementHisto" type="button" wire:loading.remove=""
                            class="btn btn-primary w-50">Mettre a jour</button>
                        <button wire:loading.delay="" wire:target="updatePaiementHisto"
                            wire:click="updatePaiementHisto" type="button" class="btn btn-warning w-40"> Traitement
                            ... </button>
                    </div>
                @endif
            @endif


        </div>
    </div>
</div>
