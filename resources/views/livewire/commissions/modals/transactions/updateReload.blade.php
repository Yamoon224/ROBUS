<div wire:ignore.self id="update-reloadWallet-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Valider la demande de rechargement</h2>
            </div>  
            <a wire:click="$emit('modal-hide-reload')"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="provider_reload_uv_edit" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="provider_reload_uv_edit" disabled id="provider_reload_uv_edit"
                        class="form-control">
                        <option selected disabled>Selectionnez le canal</option>
                        @foreach ($providers as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            @if (!is_null($provider_reload_uv_edit))
                @if ($provider_reload_uv_edit == 9)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset('public/dist/providers/Robus_logo.jpg') }}"
                                alt="image"wire:model="image">
                        </div>
                    </div>

                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="account_reload_uv_edit" class="form-label">Numero wallet<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="account_reload_uv_edit" disabled id="account_reload_uv_edit"
                                min="1" max="5" type="text" class="form-control"
                                placeholder="Numero wallet">

                        </div>
                        <div class="col-span-6">
                            <label for="relaod_uv_amout_edit" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <input wire:model="relaod_uv_amout_edit" disabled id="relaod_uv_amout_edit" min="1"
                                max="5" type="number" class="form-control" placeholder="Montant">

                        </div>

                    </div>
                @elseif ($provider_reload_uv_edit == 4)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset('public/dist/providers/OrangeMoney.jpg') }}" alt="Orange money">
                        </div>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="number_reload_uv_edit" class="form-label">Numero telephone<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_number_local }}
                                </div>

                                <input wire:model="number_reload_uv_edit" disabled id="number_reload_uv_edit"
                                    type="text" class="form-control" placeholder="Numero">
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label for="relaod_uv_amout_edit" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <input wire:model="relaod_uv_amout_edit" disabled id="relaod_uv_amout_edit" min="1"
                                max="5" type="number" class="form-control" placeholder="Montant">

                        </div>
                    </div>
                @elseif ($provider_reload_uv_edit == 3)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset('public/dist/providers/MoovMoney.png') }}" alt="Orange money">
                        </div>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="number_reload_uv_edit" class="form-label">Numero telephone<span
                                    class="text-danger">
                                    *</span></label>
                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_number_local }}
                                </div>

                                <input wire:model="number_reload_uv_edit" disabled id="number_reload_uv_edit"
                                    type="text" class="form-control" placeholder="Numero">
                            </div>


                        </div>
                        <div class="col-span-6">
                            <label for="relaod_uv_amout_edit" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <input wire:model="relaod_uv_amout_edit" disabled id="relaod_uv_amout_edit"
                                min="1" max="5" type="number" class="form-control"
                                placeholder="Montant">

                        </div>
                    </div>
                @elseif ($provider_reload_uv_edit == 1)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset('public/dist/providers/MtnMoney.jpg') }}" alt="MTN money">
                        </div>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="number_reload_uv_edit" class="form-label">Numero telephone<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="flex">
                                <div
                                    class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                    {{ $countrie_number_local }}
                                </div>

                                <input wire:model="number_reload_uv_edit" disabled id="number_reload_uv_edit"
                                    type="text" class="form-control" placeholder="Numero">
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label for="relaod_uv_amout_edit" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <input wire:model="relaod_uv_amout_edit" disabled id="relaod_uv_amout_edit"
                                min="1" max="5" type="number" class="form-control"
                                placeholder="Montant">

                        </div>
                    </div>
                @endif


                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">


                    <div
                        class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:model.debounce.10ms="reload_uv_status" id="bordered-radio-28" type="radio" value="SUCCESS"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-28"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Accepté</label>
                        @error('reload_uv_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div
                        class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:model.debounce.10ms="reload_uv_status" id="bordered-radio-29" type="radio" value="ERROR"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-29"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Refusé</label>
                        @error('reload_uv_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div
                        class=" col-span-4 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:model.debounce.10ms="reload_uv_status" id="bordered-radio-30" type="radio" value="REFUND"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-30"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Remboursé</label>
                        @error('reload_uv_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if ($reload_uv_status == 'ERROR' || $reload_uv_status == 'SUCCESS' || $reload_uv_status == 'REFUND')
                    <div class="modal-footer">
                        <button wire:click="cancelReloadCardField" type="button"
                            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                        <button wire:click="updateReload" type="submit" wire:loading.remove=""
                            class="btn btn-primary w-22">Mettre
                            a jour</button>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
