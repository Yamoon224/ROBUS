<div wire:ignore.self id="update-1xbet-transaction-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier la commission</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom</label>
                    <input wire:model.defer="transaction_name" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('transaction_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-12 mb-3">
                    <h1 class="text-2xl">
                        Les informations pour les <span class="text-green-500 uppercase tracking-wide">SD</span>
                    </h1>
                </div>

                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage <span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="percent_transaction" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage ">
                    @error('percent_transaction')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage admin<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="percent_admin" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage">
                    @error('percent_admin')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage Banque<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="percent_bank_under" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage banque">
                    @error('percent_bank_under')
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

                <div class="col-span-12 mb-3">
                    <h1 class="text-2xl">
                        Les informations pour les <span class="text-green-500 uppercase tracking-wide">Clients</span>
                    </h1>
                </div>

                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage <span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="percent_custumer" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage client">
                    @error('percent_custumer')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage admin<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="percent_admin_custumer" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage admin">
                    @error('percent_admin_custumer')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Pourcentage Banque<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="percent_bank_custumer" id="modal-form-1" type="number" class="form-control"
                        placeholder="Pourcentage banque client">
                    @error('percent_bank_custumer')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>

                @if ($editTypeTresort === true)
                    <button wire:click="updateTresort" type="submit" wire:loading.remove
                        class="btn btn-primary w-20">Modifier</button>
                @elseif ($editTypeDjamo === true)
                    <button wire:click="updateDjamo" type="submit" wire:loading.remove
                        class="btn btn-primary w-20">Modifier</button>
                @elseif ($editTypePush === true)
                    <button wire:click="updatePush" type="submit" wire:loading.remove
                        class="btn btn-primary w-20">Modifier</button>
                @else
                    <button wire:click="update" type="submit" wire:loading.remove
                        class="btn btn-primary w-20">Modifier</button>
                @endif

                <button wire:loading.delay wire:target="update" wire:click="update" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
