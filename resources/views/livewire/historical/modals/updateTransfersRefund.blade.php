<div wire:ignore.self id="update-transfersHisto-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto"> Remboursement de la demande</h2>
            </div>
            <a wire:click="$emit('modal-hide-transfers-histo')"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="provider_transaction_edit" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="provider_transaction_edit" disabled id="provider_transaction_edit"
                        class="form-control">
                        <option selected disabled>Selectionnez le canal</option>
                        @foreach ($providers as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            @if (!is_null($provider_transaction_edit))
                @if ($provider_transaction_edit == 9)
                    <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="compte_number_robus_edit" class="form-label">Numero compte wallet<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="compte_number_robus_edit" disabled id="compte_number_robus_edit"
                                type="text" class="form-control" placeholder="Montant">

                        </div>
                        <div class="col-span-6">
                            <label for="transaction_amount_robus_edit" class="form-label">Montant<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="transaction_amount_robus_edit" disabled
                                id="transaction_amount_robus_edit" type="number" class="form-control"
                                placeholder="Montant">

                        </div>
                    </div>
                    <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="transaction_description_robus_edit" class="form-label">Commentaire</label>
                            <textarea wire:model="transaction_description_robus_edit" id="transaction_description_robus_edit " type="text"
                                class="form-control" cols="13" rows="3">
                           </textarea>
                        </div>
                    </div>
                @else
                    <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="transaction_phone" class="form-label">Numero telephone<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="transaction_phone_edit" disabled id="transaction_phone_edit"
                                type="text" class="form-control" placeholder="Numero">

                        </div>
                        <div class="col-span-6">
                            <label for="transaction_amount_robus_edit" class="form-label">Montant<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="transaction_amount_robus_edit" disabled
                                id="transaction_amount_robus_edit" type="number" class="form-control"
                                placeholder="Montant">

                        </div>
                    </div>
                    <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="transaction_description_edit" class="form-label">Commentaire</label>
                            <textarea wire:model.defer="transaction_description_edit" id="transaction_description_edit" type="text"
                                class="form-control" cols="13" rows="3">
                           </textarea>

                        </div>
                    </div>
                @endif

                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1 mb-3">

                    <div class="col-span-12">
                        <label for="transactionStatus" class="form-label">Status<span class="text-danger">
                                *</span></label>
                        <input wire:model="transactionStatus" disabled id="transactionStatus" type="text"
                            class="form-control" placeholder="Montant">

                    </div>

                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                    <div
                        class=" col-span-4 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:model="transactionStatus" id="transactionStatus" type="radio" value="REFUND"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="transactionStatus"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Remboursé</label>
                        @error('transactionStatus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>


                @if ($transactionStatus == 'REFUND')
                    <div class="modal-footer style-foo">
                        <button wire:click="$emit('modal-hide-transfers-histo')" type="button"
                            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                        <button wire:click="updateTransactionHisto" type="button" wire:loading.remove=""
                            class="btn btn-primary w-50">Mettre a jour</button>
                        <div wire:loading.delay="" wire:target="updateTransactionHisto"
                            wire:click="updateTransactionHisto" type="button" class="btn btn-warning w-40">
                            Traitement
                            ... </div>
                    </div>
                @endif
            @endif




        </div>
    </div>
</div>
