<!-- Modal -->
<div wire:ignore.self id="create-order-card-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ajouter commission commande de carte</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom </label>
                    <input wire:model="name" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-12 mt-3">
                    <label for="provider_valeur" class="form-label">Carte<span class="text-danger">
                            *</span></label>
                    <select wire:model.defer="provider_valeur" id="provider_valeur" class="form-control">
                        <option value="0">Selectionnez la carte</option>
                        @foreach ($providers as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('provider_valeur')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-3">
                    <label for="modal-form-1" class="form-label">Prix carte <span class="text-danger">
                            *</span></label>
                    <input wire:model="amount" id="modal-form-1" type="text" class="form-control"
                        placeholder="Montant">
                    @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-3">
                    <label for="modal-form-1" class="form-label">Commission marchants<span class="text-danger">
                            *</span></label>
                    <input wire:model="amount_merchant" id="modal-form-1" type="text" class="form-control"
                        placeholder="Montant marhant">
                    @error('amount_merchant')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>


            <div class="modal-footer">
                <button wire:click="$emit('modal-hide')" type="button"
                    class="btn btn-danger-soft w-20 mr-1">Annuler</button>
                <button wire:click="add" type="button" wire:loading.remove=""
                    class="btn btn-primary w-20">Ajouter</button>
                    <div  wire:loading wire:target="add">
                        Traitement en cours...
                    </div>
            </div>
        </div>
    </div>
</div>
