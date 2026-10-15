<div wire:ignore.self id="update-push-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @include('components.modal-header', [
                'title' => 'Validation Push',
                'icon' => 'fas fa-check-circle',
                'service' => 'push',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelPushField',
            ])


            <div class="modal-body mt-1">
                <div class="form-section">
                    <div class="field-group">

                        <div class="field">
                            <label class="form-label required">Canal Push</label>
                            <div class="field-value">
                                <i class="fas fa-network-wired"></i>
                                <span id="provider_push"> {{ $provider_push ?? null }}</span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="form-label required">Numéro du compte</label>
                            <div class="field-value">
                                <i class="fas fa-user"></i>
                                <span id="numberPhone_edit"> {{ $numberPhone_edit ?? null }}</span>
                            </div>
                        </div>
                    </div>


                    <div class="field-group">
                        <div class="field">
                            <label class="form-label required">Nom & prénom </label>
                            <div class="field-value">
                                <i class="fas fa-id-card"></i>
                                <span id="fullName_edit">{{ $fullName_edit ?? null }}</span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="form-label required">Montant</label>
                            <div class="field-value">
                                <i class="fas fa-money-bill-wave"></i>
                                <span id="amountPush">{{ formatDisplayAmount($amountPush ?? null) }}
                                    {{ $devise_user ?? null }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space-padding">
                    <label for="pushDescription" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="pushDescription" id="reloadcard_description_edit" type="text"
                        class="form-control" cols="13" rows="3">
                    </textarea>

                </div>
            </div>
           <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700"
                    wire:click="tryOpenModalPush('validated-push-modal')">
                    <input wire:model="pushStatus" id="bordered-radio-80" type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                    <label for="bordered-radio-80"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-check-circle style_success"></i>
                        Accepté
                    </label>
                </div>

                <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700"
                    wire:click="tryOpenModalPush('validated-push-modal')">
                    <input wire:model="pushStatus" id="bordered-radio-81"
                        type="radio" value="ERROR" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                    <label for="bordered-radio-81"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-times-circle style_error"></i>
                        Refusé
                    </label>
                </div>
            </div>
            

        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_push')
