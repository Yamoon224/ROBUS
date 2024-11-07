<div wire:ignore.self id="update-phone-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
     

        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier la commission banque</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom</label>
                    <input wire:model="phoneName" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('phoneName')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="phonePercent" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage">
                    @error('phonePercent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage marchants<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="orderCardMerchant" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage marchant">
                    @error('orderCardMerchant')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage Compagnie<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="comissionCompanie" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage marchant">
                    @error('comissionCompanie')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage admin<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="comission_admin" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage admin">
                    @error('comission_admin')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="update" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                <div wire:loading wire:target="update">
                    Traitement en cours...
                </div>
            </div>
        </div>
    </div>
</div>
