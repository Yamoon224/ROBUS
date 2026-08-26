<div wire:ignore.self id="update-pixel-innove-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="font-medium text-base mr-auto">Modifier service Pixel Innove</h2>
                    <div class="text-slate-500 text-xs mt-1">Mettre a jour le pays, le provider ou les parametres Pixel</div>
                </div>
            </div>
            <div class="modal-body">
                @include('livewire.pixel-innove.modals.form')
            </div>
            <div class="modal-footer">
                <button wire:click="$emit('modal-hide')" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="update" type="button" wire:loading.remove
                    class="btn btn-primary w-24">Modifier</button>
                <button wire:loading.delay wire:target="update" type="button"
                    class="btn btn-warning w-40">Traitement ...</button>
            </div>
        </div>
    </div>
</div>
