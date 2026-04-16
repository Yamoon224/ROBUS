<!-- Modal -->
<div wire:ignore.self id="create-transactionWalletCommissionBank-modal" class="modal hide overflow-y-auto"
    data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Transaction des commissions Banque</h2>
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

            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers mt-6">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset_url('dist/providers/robus.png') }}" alt="image"wire:model="image">
                </div>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 mt-3">

                <div class="col-span-12">
                    <div class="amount-label text-sm text-gray-500">Compte des commissions Banque </div>
                    <div class="amount-remonte-balance">
                        <div class="flex items-baseline justify-between">
                            <span class="amount-balance-value text-xl font-semibold">
                                {{ $walletCommissions ?? 0 }}
                            </span>
                            <span class="amount-balance-currency text-white">
                                {{ $usersCountry ?? 'CFA' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 mt-3">
                    <label for="account_reload_uv" class="form-label">Numero wallet<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="account_reload_uv" id="account_reload_uv" type="text"
                        class="form-control" placeholder="Numero wallet">
                    @error('account_reload_uv')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div wire:loading>Recherche numero...</div>
                    <div wire:loading.remove></div>
                    @if ($account_reload_uv == '')
                        <div class="text-gray-500 text-sm">
                            Entrer le numero du wallet
                        </div>
                    @else
                        <div class="text-lg text-gray-900 text-bold text-secondary ">
                            <span
                                class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                {{ $wallet_info_searchs ?? null }}
                            </span>

                        </div>
                    @endif
                </div>
                <div class="col-span-6 mt-3">
                    <label for="relaod_uv_amout_robus" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.lazy="relaod_uv_amout_robus" id="relaod_uv_amout_robus" type="number"
                        class="form-control" placeholder="Montant">
                    @error('relaod_uv_amout_robus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div class="modal-footer bg-gray-50 px-6 py-5 border-t border-gray-200">
                <div class="flex justify-end space-x-4 w-full items-center">
                    <!-- Bouton Annuler -->
                    <button wire:click="cancelReloadCardField" wire:loading.attr="disabled"
                        wire:target="cancelReloadCardField" type="button" class="btn btn-outline-secondary w-20 mr-1">
                        Annuler
                    </button>
                    <!-- Bouton principal avec état de chargement -->
                    <button wire:click="addTransactionBankCommission" type="button" wire:loading.attr="disabled"
                        wire:target="addTransactionBankCommission" class="btn btn-primary w-50">

                        <!--985995.8 986095.8 Texte normal -->
                        <span wire:loading.remove wire:target="addTransactionBankCommission" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Envoyer la demande
                        </span>

                        <!-- Loader dans le bouton -->
                        <span wire:loading wire:target="addTransactionBankCommission" class="flex items-center">
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
