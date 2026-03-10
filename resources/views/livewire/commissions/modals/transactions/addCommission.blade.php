<!-- Modal -->
<div wire:ignore.self id="create-reloadWalletCommission-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Réchargement des comptes adminstrateur</h2>
            </div>
            <a wire:click="cancelReloadCardField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>


            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3" wire:loading.class="hidden"
                wire:target="addReloadCommission">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="providerAllCommission" id="modal-form-1" class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        <option value="1">Compte des commissions SD total</option>
                        <option value="2">Compte des commissions Admin</option>

                    </select>
                    @error('providerAllCommission')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>
            @if (!is_null($providerAllCommission))
                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                    <div class="col-span-12">
                        <img class="object-none object-center bg-yellow-300 w-logo"
                            src="{{ asset('public/dist/providers/robus.png') }}" alt="image"wire:model="image">
                    </div>
                </div>

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3" wire:loading.class="hidden"
                    wire:target="addReloadCommission">

                    <div class="col-span-12">
                        <label for="relaod_amount_commission" class="form-label">Montant<span class="text-danger">
                                *</span></label>
                        <input wire:model.lazy="relaod_amount_commission" id="relaod_amount_commission" type="number"
                            class="form-control" placeholder="Montant">
                        @error('relaod_amount_commission')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            @endif

            <div class="modal-footer bg-gray-50 px-6 py-5 border-t border-gray-200">
                <div class="flex justify-end space-x-4 w-full items-center">
                    <!-- Bouton Annuler -->
                    <button wire:click="cancelReloadCardField" wire:loading.attr="disabled"
                        wire:target="addReloadCommission" wire:loading.class="hidden" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">
                        Annuler
                    </button>

                    <!-- Bouton principal avec état de chargement -->
                    <button wire:click="addReloadCommission" type="button" wire:loading.attr="disabled"
                        wire:target="addReloadCommission" class="btn btn-primary w-50">

                        <!-- Texte normal -->
                        <span wire:loading.remove wire:target="addReloadCommission" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Envoyer la demande
                        </span>

                        <!-- Loader dans le bouton -->
                        <span wire:loading wire:target="addReloadCommission" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Traitement...
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
