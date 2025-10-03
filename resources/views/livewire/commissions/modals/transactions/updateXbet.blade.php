<div wire:ignore.self id="update-xbet-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Valider la demande de 1xbet</h2>
            </div>
            <a wire:click="cancelXbetField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem; color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    style="color: white;" data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Service 1Xbet
            </div>
            @if ($choose_type_xbet == 1)
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1">
                    <div class="col-span-6 ">
                        <label for="provider_name_xbet_edit" class="form-label">Canal 1XBET
                            <span class="text-danger">
                                *</span></label>
                        <span
                            class="form-control style_edit_form alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                       text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $xbetProvider ?? null }}
                        </span>
                    </div>
                    <div class="col-span-6">
                        <label for="id_compte" class="form-label">Numero du compte<span class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                           text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $id_compte ?? null }}
                        </span>

                    </div>

                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space">
                        <label for="full_nameXbet" class="form-label">Nom & prenom du client<span
                                class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                           text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $full_nameXbet ?? null }}
                        </span>

                    </div>
                    <div class="col-span-6 mt-space">
                        <label for="amount_xbet_edit" class="form-label">Montant<span class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                           text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $amount_xbet ?? null }} {{ $devise_user ?? null }}
                        </span>

                    </div>
                </div>
            @else
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1">
                    <div class="col-span-12 ">
                        <label for="xbetProvider" class="form-label">Canal 1Xbet
                            <span class="text-danger">
                                *</span></label>
                        <span
                            class="form-control style_edit_form alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                   text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $xbetProvider ?? null }}
                        </span>
                    </div>
                    <div class="col-span-6">
                        <label for="id_compte" class="form-label">Numero du compte<span class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                       text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $id_compte ?? null }}
                        </span>

                    </div>
                    <div class="col-span-6">
                        <label for="code_compte" class="form-label">Code retrait du compte<span class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                       text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $code_compte ?? null }}
                        </span>

                    </div>

                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space">
                        <label for="citie_xbet" class="form-label">Ville<span class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                       text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $citie_xbet ?? null }}
                        </span>

                    </div>
                    <div class="col-span-6 mt-space">
                        <label for="area_xbet" class="form-label">Zone<span class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                           text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $area_xbet ?? null }}
                        </span>

                    </div>
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 mt-space">
                            <label for="amount_xbet" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
    
                            <span
                                class="form-control style_edit_form alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                           text-indigo-100 bg-indigo-700 rounded"
                                style="color: white;">
                                {{ $amount_xbet ?? null }} {{ $currency_custumers ?? null }}
                            </span>
    
                        </div>
                       
                    </div>
            @endif
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="xbetDescription" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="xbetDescription" id="xbetDescription" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-xbet-validted-show' , 'validated-xbet-modal')"
                        wire:model="xbetStatus" id="bordered-radio-31" type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-31"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Accepté</label>
                    @error('xbetStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-xbet-validted-show' , 'validated-xbet-modal')"
                        wire:model="xbetStatus" id="bordered-radio-32" type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-32"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Refusé</label>
                    @error('xbetStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_xbet')
