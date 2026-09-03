<div wire:ignore.self id="create-pixel-innove-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header border-b border-slate-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <i data-feather="plus-circle" class="h-5 w-5"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">Ajouter un service Pixel Innove</h2>
                        <p class="text-xs text-slate-400">Associer un service Pixel à un provider existant</p>
                    </div>
                </div>
            </div>

            <div class="modal-body px-6 py-5">
                @include('livewire.pixel-innove.modals.form')
            </div>

            <div class="modal-footer border-t border-slate-100 px-6 py-4">
                <button wire:click="$emit('modal-hide')" type="button"
                    class="btn btn-outline-secondary mr-2">Annuler</button>
                <button wire:click="add" type="button" wire:loading.remove wire:target="add"
                    class="btn btn-primary inline-flex items-center gap-2">
                    <i data-feather="save" class="h-4 w-4"></i>
                    Ajouter
                </button>
                <button wire:loading.delay wire:target="add" type="button"
                    class="btn btn-warning inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Traitement…
                </button>
            </div>

        </div>
    </div>
</div>
