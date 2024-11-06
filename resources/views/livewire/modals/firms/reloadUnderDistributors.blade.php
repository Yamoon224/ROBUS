<!-- Modal -->
<div wire:ignore.self id="create-reload-company-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Rechargement Distributer/S-distributeur</h2>
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


            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers mt-3">
                <div class="col-span-12">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset('public/dist/providers/robus.png') }}" alt="image"wire:model="image">
                </div>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="account_reload_uv" class="form-label">Numero wallet<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.500ms="account_reload_uv" id="account_reload_uv" type="text"
                        class="form-control" placeholder="Numero wallet">
                    @error('account_reload_uv')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="relaod_uv_amout" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="relaod_uv_amout" id="relaod_uv_amout" type="number"
                        class="form-control" placeholder="Montant">
                    @error('relaod_uv_amout')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-12">


                    <div wire:loading>Recherche numero...</div>
                    <div wire:loading.remove></div>
                    @php

                        $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $account_reload_uv)
                            ->where('country_id', firm()->country->id)
                            ->first();
                        if ($wallet_search) {
                            if (
                                $wallet_search->sidUser->is_custumers &&
                                $wallet_search->sidUser->company_id == firm()->company->id
                            ) {
                                $wallet_name =
                                    $wallet_search->sidUser->firstname . ' ' . $wallet_search->sidUser->lastname;
                            } else {
                                $wallet_name = 'Numero introuvable ou n\' est pas client';
                            }
                        } else {
                            $wallet_name = 'Numero introuvable ou n\' est pas client';
                        }

                    @endphp

                    @if ($account_reload_uv == '')
                        <div class="text-gray-500 text-sm">Entrer numero wallet</div>
                    @else
                        @if ($wallet_name)
                            <div class="text-lg text-gray-900 text-bold text-secondary">
                                <span
                                    class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                    {{ $wallet_name }}
                                </span>
                            </div>
                        @endif
                    @endif
                </div>

            </div>


            <div class="modal-footer style-foo">
                <button wire:click="cancelReload" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="addReload" type="button" wire:loading.remove=""
                    class="btn btn-primary w-50">Transferer</button>
                <div wire:loading wire:target="addReload">
                    Traitement en cours...
                </div>
            </div>
        </div>
    </div>
</div>
