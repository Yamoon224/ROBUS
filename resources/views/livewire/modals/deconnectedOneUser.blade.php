<!-- BEGIN: Modal de détachement d’un COM ZONE (style unifié) -->
<div wire:ignore.self id="deconnected-zoneCom-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-to-r from-red-600 to-red-700 text-white rounded-t-lg">
                <h5 class="modal-title font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Détacher du COM ZONE
                </h5>
                <button type="button" class="close text-white hover:text-gray-200" wire:click="cancelConnectedField" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-5 text-center">
                <p class="text-gray-600 mb-4">
                    Êtes-vous sûr de vouloir détacher cet utilisateur du COM ZONE ?
                </p>
                @error('detach_error')
                    <span class="text-danger text-sm block mt-2">{{ $message }}</span>
                @enderror
            </div>

            <div class="modal-footer bg-gray-50 rounded-b-lg flex justify-end gap-3 p-4">
                <button wire:click="cancelConnectedField" type="button"
                    class="px-4 py-2  bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button wire:click="confirmDetach" wire:loading.attr="disabled" type="button"
                    class="px-4 py-2 btn btn-danger text-white rounded-md hover:bg-red-700 transition disabled:opacity-50 flex items-center gap-2">
                    <span wire:loading.remove>
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                        Confirmer le détachement
                    </span>
                    <span wire:loading>
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Traitement...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && document.getElementById('deconnected-zoneCom-modal').classList.contains('show')) {
            @this.call('cancelConnectedField');
        }
    });
    document.addEventListener('click', function(event) {
        let modal = document.getElementById('deconnected-zoneCom-modal');
        if (event.target === modal && modal.classList.contains('show')) {
            @this.call('cancelConnectedField');
        }
    });
</script>
@endpush