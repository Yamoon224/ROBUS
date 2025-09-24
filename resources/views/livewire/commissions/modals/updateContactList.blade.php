<div wire:ignore.self id="update-contactList-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier la commission banque</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Nom</label>
                    <input wire:model="bankName" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('bankName')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage</label>
                    <input wire:model="bankPercent" id="modal-form-1" type="text" class="form-control"
                        placeholder="Pourcentage">
                    @error('bankPercent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-danger-soft w-20 mr-1">Annuler</button>
                <button wire:click="update" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                <button wire:loading.delay wire:target="update" wire:click="update" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
