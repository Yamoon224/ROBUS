<!-- Modal -->
<div wire:ignore.self id="create-employers-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Ajouter un employés</h2>
            </div>
            <a wire:click="cancelEmployesField"
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
                    <label for="wallet_id_add" class="form-label">Numero wallet<span class="text-danger">
                            *</span></label>
                    <input wire:model.debounce.10ms="wallet_id_add" id="wallet_id_add" type="text"
                        class="form-control" placeholder="Numero wallet">
                    @error('wallet_id_add')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div wire:loading>Recherche numero...</div>
                    <div wire:loading.remove></div>
                    @if ($wallet_id_add == '')
                        <div class="text-gray-500 text-sm">
                            Entrer le numero du wallet
                        </div>
                    @elseif($wallet_info_searchs != null)
                        @php
                            $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $wallet_id_add)
                                ->where('country_id', sabdy()->country->id)
                                ->first();

                            if ($wallet_search != null) {
                                $wallet_name = $wallet_search->sidUser->firstname . ' ' . $wallet_search->sidUser->lastname;
                            } else {
                                $wallet_name = 'Numero introuvable';
                            }

                        @endphp
                        <div class="text-lg text-gray-900 text-bold text-secondary ">
                            <span
                                class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                {{ $wallet_name ?? null }}
                            </span>

                        </div>
                    @else
                        <div class="text-gray-500 text-sm">
                            Numero inconnu chez Robus
                        </div>
                    @endif
                </div>

            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3">
                <div class="col-span-12 mt-space">
                    <label for="categorieId_add" class="form-label">Categorie<span class="text-danger">
                            *</span></label>
                    <select wire:model.defer="categorieId_add" id="categorieId_add" class="form-control">
                        <option value="null" disabled>Selectionnez la categorie</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('categorieId_add')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="modal-footer">
                {{-- @if ($wallet_info_searchs != null) --}}
                <button wire:click="cancelEmployesField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="add_employers" type="button" wire:loading.remove=""
                    class="btn btn-primary w-20">Ajouter</button>
                <div wire:loading wire:target="add_employers">
                    Traitement en cours...
                </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
</div>
