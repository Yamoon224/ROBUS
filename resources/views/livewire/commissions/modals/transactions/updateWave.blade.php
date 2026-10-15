<div wire:ignore.self id="update-wave-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Valider la demande achat UV par Wave</h2>
            </div>
            <a wire:click="cancelWaveField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem; color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    style="color: white;" data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>

            <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Achat UV wave
            </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1">
                    <div class="col-span-6 ">
                        <label for="provider_name_wave_edit" class="form-label">Canal Wave
                            <span class="text-danger">
                                *</span></label>
                        <span
                            class="form-control style_edit_form alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                   text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $provider_name_wave_edit ?? null }}
                        </span>
                    </div>
                    <div class="col-span-6">
                        <label for="code_wave_edit" class="form-label">Numero du compte<span class="text-danger">
                                *</span></label>

                        <span
                            class="form-control style_edit_form alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                       text-indigo-100 bg-indigo-700 rounded"
                            style="color: white;">
                            {{ $number_wave_edit ?? null }}
                        </span>

                    </div>

                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 mt-space">
                            <label for="amount_wave_edit" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
    
                            <span
                                class="form-control style_edit_form alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                           text-indigo-100 bg-indigo-700 rounded"
                                style="color: white;">
                                {{ formatDisplayAmount($amount_wave_edit ?? null) }} {{ $devise_user ?? null }}
                            </span>
    
                        </div>
                       
                </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="waveDescription_edit" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="waveDescription_edit" id="waveDescription_edit" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-wave-validted-show' , 'validated-wave-modal')"
                        wire:model="status_wave_edit" id="bordered-radio-31" type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-31"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Accepté</label>
                    @error('status_wave_edit')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-wave-validted-show' , 'validated-wave-modal')"
                        wire:model="status_wave_edit" id="bordered-radio-32" type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-32"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Refusé</label>
                    @error('status_wave_edit')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_wave')
