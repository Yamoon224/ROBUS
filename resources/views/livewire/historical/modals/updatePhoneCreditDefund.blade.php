<div wire:ignore.self id="update-phoneCreditHisto-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Valider la demande de rechargement</h2>
            </div>
            <a wire:click="$emit('modal-hide-phoneCredit')"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="provider_phone_credit_edit" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="provider_phone_credit_edit" disabled id="provider_phone_credit_edit"
                        class="form-control">
                        <option selected disabled>Selectionnez le canal</option>
                        @foreach ($provider_credits as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>

            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="number_phone_credit_edit" class="form-label">Numero<span class="text-danger">
                            *</span></label>
                    <input wire:model="number_phone_credit_edit" disabled id="number_phone_credit_edit" type="text"
                        class="form-control" placeholder="Numero de telephone">

                </div>
                <div class="col-span-6">
                    <label for="amount_phone_credit_edit" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model="amount_phone_credit_edit" disabled id="amount_phone_credit_edit" type="number"
                        class="form-control" placeholder="Montant">
                </div>
            </div>
          
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">


                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:model="phoneCreditStatus" id="bordered-radio-44" type="radio" value="REFUND"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="phoneCreditStatus"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Remboursé</label>
                    @error('phoneCreditStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @if ($phoneCreditStatus == 'REFUND')
                <div class="modal-footer style-foo">
                    <button wire:click="$emit('modal-hide-phoneCreditHisto')" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="updatePhoneCreditHisto" type="submit" wire:loading.remove
                        class="btn btn-primary w-22">Mettre
                        a jour</button>
                    <button wire:loading.delay wire:target="updatePhoneCreditHisto" wire:click="updatePhoneCreditHisto"
                        type="button" class="btn btn-warning w-40"> Traitement ... </button>
                </div>
            @endif
        </div>
    </div>
</div>
