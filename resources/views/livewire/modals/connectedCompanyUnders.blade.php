<div wire:ignore.self id="disabled-company-admin-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                    <div class="text-3xl mt-5">Action requise !!!</div>
                    <div class="text-slate-500 mt-2">Êtes-vous sûr de vouloir
                        {{ $underdistributeurDesabled == 0 ? 'Lié' : 'Désactiver' }} ce sous distributeur ?
                    </div>
                </div>

                <div class="px-5 pb-8 text-center mt-6">
                    <button type="button" wire:click="$emit('modal-hide')" wire:loading.attr="disabled"
                        class="btn btn-outline-secondary w-25 mr-1 mb-1">Annuler</button>

                    <input wire:click="connected" wire:loading.remove type="button"
                        class="btn btn-danger  w-25 mr-1 mb-1"
                        value="{{ $underdistributeurDesabled == 0  ? 'Lié' : 'Désactiver' }}" />

                    <div wire:loading wire:target="connected">
                        Traitement en cours...
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
