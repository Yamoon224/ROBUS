<div wire:ignore.self id="update-employers-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier employé</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="wallet_id_add" class="form-label">Nom & prenom<span class="text-danger">
                            *</span></label>
                    <div class="text-lg text-gray-900 text-bold text-secondary ">
                        <span
                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3  font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                            {{ $wallet_info_searchs ?? null }}
                        </span>

                    </div>
                
                </div>
                <div class="col-span-12">
                   
                    <label for="wallet_id_add" class="form-label">Numero wallet<span class="text-danger">
                            *</span></label>
                    <div class="text-lg text-gray-900 text-bold text-secondary ">
                        <span
                            class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                            {{ $wallet_id_add ?? null }}
                        </span>

                    </div>

                </div>

            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3">
                <div class="col-span-12 mt-space">
                    <label for="categorieId_add" class="form-label">Categorie<span class="text-danger">
                            *</span></label>
                    <select wire:model="categorieId_add" id="categorieId_add" class="form-control">
                        <option value="null" disabled>Selectionnez la categorie</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('categorieId_add')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button wire:click="cancelEmployesField" type="button"
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
