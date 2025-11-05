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

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="providerAllCommission" id="modal-form-1" class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        <option value="1">Commission</option>
                        <option value="2">Compte transactions</option>

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

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

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
            <div class="modal-footer style-foo">
                <button wire:click="cancelReloadCardField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="addReloadCommission" type="button" wire:loading.remove=""
                    class="btn btn-primary w-50">Envoyer la demande</button>
                <div wire:loading wire:target="addReloadCommission">
                    Traitement en cours...
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-reload-created').listen(
                '.Created_wallet_reload',
                (e) => {
                    window.livewire.emit('notifyNewReload:' + e.id);
                },
            )
        });
    </script>
</div>
