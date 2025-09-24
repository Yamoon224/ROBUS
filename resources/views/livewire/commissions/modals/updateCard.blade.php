<!-- BEGIN: Disable Confirmation Modal -->
<div wire:ignore.self id="active-update-card-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Action requise !!!</div>
                    <div class="text-slate-500 mt-2">Êtes-vous sûr de vouloir
                        {{ $cardStatus == 1 ? 'Désactiver' : 'Activer' }} cette carte ?</div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="modal-form-1" class="form-label">Identifiant carte</label>
                        <input wire:model.defer="ref_card_card" id="modal-form-1" type="text" class="form-control"
                            placeholder="Id carte">
                        @error('ref_card_card')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="px-5 pb-8 text-center mt-6">
                    <button type="button" wire:click="$emit('modal-hide')"
                        class="btn btn-danger-soft w-25 mr-1 mb-1">Annuler</button>
                    <input wire:click="disable" wire:loading.remove type="button"
                        class="btn btn-danger  w-25 mr-1 mb-1"
                        value="{{ $cardStatus == 1 ? 'Désactiver' : 'Activer' }}" />
                    <button wire:loading.delay wire:target="disable" wire:click="disable" type="button"
                        class="btn btn-warning w-40"> Traitement ... </button>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END: Disable Confirmation Modal -->
