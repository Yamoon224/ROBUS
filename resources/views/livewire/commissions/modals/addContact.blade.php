<!-- Modal -->
<div wire:ignore.self id="create-contact-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ajouter liste des contacts</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom de la liste<span class="text-danger">
                            *</span></label>
                    <input wire:model="contactName" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('contactName')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Decription de la liste</label>
                    <textarea wire:model="contactDescription" id="modal-form-1" type="text" class="form-control" cols="15"
                        rows="5">
                    </textarea>

                    @error('contactDescription')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$emit('modal-hide')" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="add" type="button" wire:loading.remove=""
                    class="btn btn-primary w-20">Ajouter</button>
                <button wire:loading.delay="" wire:target="add" wire:click="add" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
