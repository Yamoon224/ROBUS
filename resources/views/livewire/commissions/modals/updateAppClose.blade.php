<div wire:ignore.self id="update-appRobus-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier les jours</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                 <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Jour</label>
                    <div class="text-slate-500  mt-0.5">
                        <span   class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                            {{ $robus_days ?? null }}
                        </span>
                    </div>
                   
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Heure debut</label>
                    <input wire:model.defer="time_begin" id="modal-form-1" type="time" class="form-control"
                        placeholder="Heure de debut">
                    @error('time_begin')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Heure de fin</label>
                    <input wire:model.defer="time_end" id="modal-form-1" type="time" class="form-control"
                        placeholder="Heure de fin">
                    @error('time_end')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-12">
                        <label for="modal-form-1" class="form-label">Description</label>
                        <textarea wire:model.defer="description" id="description" cols="20" rows="5" class="form-control"></textarea>
                       
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

            </div>
            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-danger-soft w-20 mr-1">Annuler</button>
                <button wire:click="updateApp" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                <div  wire:loading wire:target="updateApp">
                    Traitement en cours...
                </div>
            </div>
        </div>
    </div>
</div>
