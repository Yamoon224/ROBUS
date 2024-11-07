<div wire:ignore.self id="update-payment-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier la commission paiement</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom</label>
                    <input wire:model="payment_name" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('payment_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Montant canal<span class="text-danger">
                            *</span></label>
                    <input wire:model="payment_amount" id="modal-form-1" type="number" class="form-control"
                        placeholder="Montant canal">
                    @error('payment_amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Montant CIE/SODECI<span class="text-danger">
                            *</span></label>
                    <input wire:model="payment_amount_auther" id="modal-form-1" type="number" class="form-control"
                        placeholder="Montant autre">
                    @error('payment_amount_auther')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Comission CANAL<span class="text-danger">
                            *</span></label>
                    <input wire:model="comission_canal" id="modal-form-1" type="number" class="form-control"
                        placeholder="Comission canal">
                    @error('comission_canal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Commission CIE/SODECI<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="comission_auther" id="modal-form-1" type="number" class="form-control"
                        placeholder="Comission autres">
                    @error('comission_auther')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Commission Compagnie<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="comissionCompanie" id="modal-form-1" type="number" class="form-control"
                        placeholder="Comission autres">
                    @error('comissionCompanie')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="update" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                    <div  wire:loading wire:target="update">
                        Traitement en cours...
                    </div>
            </div>
        </div>
    </div>
</div>
