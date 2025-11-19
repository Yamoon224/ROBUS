<!-- BEGIN: Disable Confirmation Modal -->
<div wire:ignore.self id="deleted-users-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Action requise !!!</div>
                    <div class="text-slate-500 mt-2">Êtes-vous sûr de vouloir supprimer
                        {{ $sidUserInfos }} cet utilisateur ?</div>
                </div>

                <div class="px-5 pb-8 text-center mt-6">
                    <button type="button" wire:click="resetField()"
                        class="btn btn-secondary w-25 mr-1 mb-1">Annuler</button>
                    <input wire:click="deletedUser" wire:loading.remove type="button"
                        class="btn btn-danger  w-25 mr-1 mb-1"
                        value="Supprimer" />
                    <button wire:loading.delay wire:target="deletedUser" wire:click="disable" type="button"
                        class="btn btn-warning w-40"> Traitement ... </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Disable Confirmation Modal -->
