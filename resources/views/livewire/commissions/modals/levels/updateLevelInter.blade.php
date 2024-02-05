<div wire:ignore.self id="update-level-inter-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier le frais</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Montant debut</label>
                    <input wire:model.defer="amount_begin" id="modal-form-1" type="number" class="form-control"
                        placeholder="Nom">
                    @error('amount_begin')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">montant fin</label>
                    <input wire:model.defer="amount_end" id="modal-form-1" type="number" class="form-control"
                        placeholder="Montant">
                    @error('amount_end')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage admin</label>
                    <input wire:model.defer="percent" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage">
                    @error('percent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage distributer</label>
                    <input wire:model.defer="comission_distributer" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage">
                    @error('comission_distributer')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Pourcentage Master</label>
                    <input wire:model.defer="comission_master" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage">
                    @error('comission_master')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
               
            </div>

            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="update" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                <button wire:loading.delay wire:target="update" wire:click="update" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
