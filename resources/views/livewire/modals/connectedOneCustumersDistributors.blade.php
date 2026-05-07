<!-- Modal améliorée avec grille 2 colonnes et affichage du téléphone -->
<div wire:ignore.self id="connected-master-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-to-r from-red-600 to-red-700 text-white rounded-t-lg">
                <h5 class="modal-title font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                    Lier des utilisateurs au COM ZONE demo
                </h5>
                <button type="button" class="close text-white hover:text-gray-200" wire:click="cancelConnectedField" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-5">
                <div class="form-group mb-4">
                    <label class="form-label font-medium text-gray-700 mb-2 flex items-center justify-between">
                        <span>Sélectionnez les utilisateurs <span class="text-danger">*</span></span>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                            {{ count($selected_masters) }} sélectionné(s)
                        </span>
                    </label>

                    <!-- Barre de recherche -->
                    <div class="relative mb-4">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="searchCustumers"
                            class="form-input pl-10 w-full rounded-lg border-gray-300 focus:border-red-400 focus:ring focus:ring-red-200 transition"
                            placeholder="Rechercher par nom, prénom, email ou téléphone...">
                    </div>

                    <!-- Liste des utilisateurs en grille 2 colonnes -->
                    <div class="border rounded-lg overflow-hidden bg-white">
                        <div class="max-h-96 overflow-y-auto p-3">
                            @if ($masters->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($masters as $master)
                                        <label class="flex items-start gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg cursor-pointer transition border border-gray-200">
                                            <input type="checkbox" wire:model="selected_masters" value="{{ $master->id }}"
                                                class="form-checkbox h-4 w-4 text-red-600 rounded border-gray-300 focus:ring-red-500 mt-0.5">
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-gray-800 truncate">
                                                    {{ $master->firstname }} {{ $master->lastname }}
                                                </div>
                                                <div class="text-xs text-gray-500 truncate">
                                                    {{ $master->email }}
                                                </div>
                                                <div class="text-xs text-gray-500 truncate flex items-center gap-1 mt-3">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    {{ $master->phone ?? 'Non renseigné' }}
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-700">
                                                    {{ $master->is_custumers == true ? 'Client' : 'SD' }}
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-8 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2">Aucun utilisateur disponible dans ce pays.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Pagination -->
                        @if ($masters->hasPages())
                            <div class="border-t bg-gray-50 px-4 py-3">
                                {{ $masters->links() }}
                            </div>
                        @endif
                    </div>

                    @error('selected_masters')
                        <span class="text-danger text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="modal-footer bg-gray-50 rounded-b-lg flex justify-end gap-3 p-4">
                <button wire:click="cancelConnectedField" type="button"
                    class="px-4 py-2 bg-white btn border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button wire:click="connected" wire:loading.attr="disabled" type="button"
                    class="px-4 py-2 bg-red-600 btn btn-primary w-50 rounded-md hover:bg-red-700 transition disabled:opacity-50 flex items-center gap-2">
                    <span wire:loading.remove>
                        @if ($comzoneDesabled == 0)
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lier au COM ZONE
                        @else
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            Désactiver du COM ZONE
                        @endif
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
        // Gestionnaire pour fermer la modal avec la touche Echap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && document.getElementById('connected-master-modal').classList.contains('show')) {
                @this.call('cancelConnectedField');
            }
        });

        // Fermeture au clic sur le backdrop
        document.addEventListener('click', function(event) {
            let modal = document.getElementById('connected-master-modal');
            if (event.target === modal && modal.classList.contains('show')) {
                @this.call('cancelConnectedField');
            }
        });
    </script>
@endpush