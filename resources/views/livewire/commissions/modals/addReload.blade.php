<!-- Modal -->
<div wire:ignore.self id="create-reload-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ajouter commission rechargement</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom de la commission</label>
                    <input wire:model="reloadName" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('reloadName')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
             <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Pourcentage</label>
                    <input wire:model="reloadPercent" id="modal-form-1" type="text" class="form-control"
                        placeholder="Reduction">
                    @error('reloadPercent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$emit('modal-hide')" type="button"
                    class="btn btn-danger-soft w-20 mr-1">Annuler</button>
                <button wire:click="add" type="button" wire:loading.remove=""
                    class="btn btn-primary w-20">Ajouter</button>
                <button wire:loading.delay="" wire:target="add" wire:click="add" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
