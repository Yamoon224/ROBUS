<div wire:ignore.self id="drawal-unders-modal"class="modal-backdrop fade show" tabindex="-1" role="dialog"  data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Validation retrait</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Type</label>
                    <input wire:model.defer="type" id="modal-form-1" type="text" class="form-control"
                        placeholder="Type">
                    @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
           
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Montant</label>
                    <input wire:model.defer="amount" id="modal-form-1" type="text" class="form-control"
                        placeholder="Montant">
                    @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Montant a retirer</label>
                    <input wire:model.defer="amountDrawal" id="modal-form-1" type="text" class="form-control"
                        placeholder="Montant">
                    @error('amountDrawal')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
           
          
            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="submit" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                <button wire:loading.delay wire:target="submit" wire:click="submit" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
