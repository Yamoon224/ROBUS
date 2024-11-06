<div wire:ignore.self id="update-employers-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-lg shadow-lg">
            <!-- Header -->
            <div class="modal-header bg-indigo-700 text-white">
                <h2 class="font-semibold text-lg mr-auto">Modifier employé</h2>
                <button type="button" class="close" wire:click="cancelEmployesField">&times;</button>
            </div>
            <!-- Body -->
            <div class="modal-body grid grid-cols-12 gap-6 p-6 bg-gray-50">
                <!-- Employee Name -->
                <div class="col-span-6">
                    <label for="wallet_id_add" class="form-label font-semibold">Nom & Prénom<span class="text-danger">
                            *</span></label>
                    <div class="text-lg text-gray-800 font-semibold">
                        <span
                            class="alert alert-secondary inline-flex items-center justify-center px-4 py-2 text-indigo-100 bg-indigo-600 rounded">
                            {{ $userdetails ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <!-- Location -->
                <div class="col-span-6">
                    <label for="userLocation" class="form-label font-semibold">Localisation<span class="text-danger">
                            *</span></label>
                    <div class="text-lg text-gray-800 font-semibold">
                        <span
                            class="alert alert-secondary inline-flex items-center justify-center px-4 py-2 text-indigo-100 bg-indigo-600 rounded">
                            {{ $userLocation ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="col-span-6">
                    <label for="userPhone" class="form-label font-semibold">Numéro de téléphone<span
                            class="text-danger"> *</span></label>
                    <div class="text-lg text-gray-800 font-semibold">
                        <span
                            class="alert alert-secondary inline-flex items-center justify-center px-4 py-2 text-indigo-100 bg-indigo-600 rounded">
                            {{ $userPhone ?? 'N/A' }}
                        </span>
                    </div>
                </div>

                <!-- Wallet Number -->
                <div class="col-span-6">
                    <label for="wallet_id_add" class="form-label font-semibold">Numéro Wallet<span class="text-danger">
                            *</span></label>
                    <div class="text-lg text-gray-800 font-semibold">
                        <span
                            class="alert alert-secondary inline-flex items-center justify-center px-4 py-2 text-indigo-100 bg-indigo-600 rounded">
                            {{ $wallet_id_add ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Category Selection -->
            <div class="modal-body grid grid-cols-12 gap-4 px-6 py-4">
                <div class="col-span-12">
                    <label for="categorieId_add" class="form-label font-semibold">Catégorie<span class="text-danger">
                            *</span></label>
                    <select wire:model="categorieId_add" id="categorieId_add" class="form-control rounded">
                        <option value="null" disabled>Selectionnez la catégorie</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('categorieId_add')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button wire:click="cancelEmployesField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>

                    <button wire:click="update" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                    <div wire:loading wire:target="update" class="ml-3 text-indigo-600 font-medium">Traitement en
                        cours...</div>
             
            </div>
        </div>
    </div>
</div>
