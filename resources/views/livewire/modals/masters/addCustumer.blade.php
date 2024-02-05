<!-- Modal -->
<div wire:ignore.self id="create-custumer-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Ajouter un client</h2>
            </div>
            <a wire:click="cancelUndersField"
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
                    <label for="wallet_id_add" class="form-label">Numero wallet<span
                            class="text-danger">*</span></label>
                    <input wire:model.debounce.500ms="wallet_id_add" id="wallet_id_add" type="text"
                        class="form-control" placeholder="Numero wallet">
                    @error('wallet_id_add')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div wire:loading>Recherche numero...</div>
                    <div wire:loading.remove></div>

                    @if ($wallet_id_add == '')
                        <div class="text-gray-500 text-sm">Entrer le numero du wallet</div>
                    @elseif($wallet_search)
                        @php
                            $wallet_name = optional(optional($wallet_search)->sidUser)->firstname . ' ' . optional(optional($wallet_search)->sidUser)->lastname ?? 'Numero introuvable';
                            $wallet_image = optional(optional($wallet_search)->sidUser)->image ?? 'public/dist/images/profil.png';
                            $wallet_phone = optional(optional($wallet_search)->sidUser)->phone;
                            $wallet_country = optional(optional($wallet_search)->sidUser)->country->name;
                            $wallet_city = optional(optional($wallet_search)->sidUser)->city->name;
                            $wallet_type = optional(optional($wallet_search)->sidUser)->is_custumers;
                        @endphp
                        @if ($wallet_type)

                            <div class="intro-x relative mr-3 sm:mr-6 text-center  mt-6">

                                <span
                                    class="alert alert-primary inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                    {{ $wallet_country ?? null }}
                                </span>

                            </div>
                            @if ($wallet_image)
                                <div class="col-span-12 mt-3">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset($wallet_image) }}" alt="image">
                                </div>
                            @endif

                            <div class="text-lg text-gray-900 text-bold text-secondary mt-6 text-center">
                                <span
                                    class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                    {{ $wallet_name }}
                                </span>
                            </div>
                        @else
                            <div class="text-lg text-gray-900 text-bold text-secondary mt-6 text-center">
                                <span
                                    class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                     Le numero du wallet doit etre un client !!
                                </span>
                            </div>

                        @endif
                    @else
                        <div class="text-gray-500 text-sm">Numero inconnu chez Robus</div>
                    @endif
                </div>
            </div>


            <div class="modal-footer">
                {{-- @if ($wallet_info_searchs != null) --}}
                <button wire:click="cancelUndersField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="add_under_distributors" type="button" wire:loading.remove=""
                    class="btn btn-primary w-20">Ajouter</button>
                <div wire:loading wire:target="add_under_distributors">
                    Traitement en cours...
                </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
</div>
