<div wire:ignore.self id="update-reloadCards-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            @include('components.modal-header', [
                'title' => 'Validation demande de réchargement de carte',
                'icon' => 'fas fa-check-circle',
                'service' => 'Réchargement de carte',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelReloadCardField',
            ])

            <div class="modal-body mt-1">
                <div class="form-section">
                    <div class="field-group">

                        <div class="field">
                            <label class="form-label required">Canal réchargement de carte</label>
                            <div class="field-value">
                                <i class="fas fa-network-wired"></i>
                                <span id="provider_reloadcard_edit"> {{ $provider_reloadcard_edit ?? null }}</span>
                            </div>
                        </div>
                         <div class="field">
                            <label class="form-label required">Numéro du compte</label>
                            <div class="field-value">
                                <i class="fas fa-user"></i>
                                <span id="account_number_reloadcard_edit"> {{ $account_number_reloadcard_edit ?? null }}</span>
                            </div>
                        </div>

                    </div>


                    <div class="field-group">
                       
                        <div class="field">
                            <label class="form-label required">Nom & prénom </label>
                            <div class="field-value">
                                <i class="fas fa-id-card"></i>
                                <span id="name_reloadcard_edit" class="inline-flex items-center gap-1">
                                    <span class="mr-3">{{ $name_reloadcard_edit ?? null }}</span>
                                 
                                </span>
                            </div>
                        </div>
                        
                        <div class="field">
                            <label class="form-label required">Montant</label>
                            <div class="field-value">
                                <i class="fas fa-money-bill-wave"></i>
                                <span id="amount_reloadcard_edit">{{ formatDisplayAmount($amount_reloadcard_edit ?? null) }}
                                    {{ $currency_custumers ?? null }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="reloadcard_description_edit" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="reloadcard_description_edit" id="reloadcard_description_edit" type="text"
                        class="form-control" cols="13" rows="3">
                    </textarea>

                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="tryOpenModalReloadCard('validated-reloadCards-modal')"
                        wire:model="reloadcardStatus" id="bordered-radio-25" type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-25"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                         <i class="fas fa-check-circle style_success"></i>
                        Accepté</label>
                   
                        @error('reloadcardStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="tryOpenModalReloadCard('validated-reloadCards-modal')"
                        wire:model="reloadcardStatus" id="bordered-radio-26" type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-26"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Refusé</label>
                    @error('reloadcardStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>


        </div>
    </div>
</div>

@include('livewire.commissions.modals.validated_reload_card')
