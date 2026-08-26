<div wire:ignore.self id="delete-pixel-innove-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="trash-2" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Supprimer</div>
                    <div class="text-slate-500 mt-2">
                        Voulez-vous supprimer ce service Pixel Innove ?
                    </div>
                </div>
                <div class="px-5 pb-8 text-center mt-6">
                    <button type="button" wire:click="$emit('modal-hide')"
                        class="btn btn-danger-soft w-25 mr-1 mb-1">Annuler</button>
                    <button wire:click="delete" wire:loading.remove type="button"
                        class="btn btn-danger w-25 mr-1 mb-1">Supprimer</button>
                    <button wire:loading.delay wire:target="delete" type="button"
                        class="btn btn-warning w-40">Traitement ...</button>
                </div>
            </div>
        </div>
    </div>
</div>
