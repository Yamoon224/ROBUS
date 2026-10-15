<div wire:ignore.self id="update-phoneCredit-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
              @include("components.modal-header", [
                'title' => 'Valider crédit téléphonique',
                'icon' => 'fas fa-check-circle',
                'service' => 'Crédit téléphonique',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelPhoneCreditField'
            ])
           
          
            <div class="modal-body">
              
                <div class="field-group">

                    <div class="field field-full">
                        <label class="form-label required">Canal Crédit Téléphonique</label>
                        <div class="field-value text-center">
                            <i class="fas fa-network-wired"></i>
                            <span id="provider_phone_credit_edit"> {{ $provider_phone_credit_edit ?? null }}</span>
                        </div>
                    </div>

                </div>

                <div class="field-group">
                      <div class="field">
                            <label class="form-label required">Numéro de téléphone</label>
                            <div class="field-value">
                                <i class="fas fa-user"></i>
                                <span id="number_phone_credit_edit"> {{ $number_phone_credit_edit ?? null }}</span>
                            </div>
                        </div>
                         <div class="field">
                            <label class="form-label required">Montant</label>
                            <div class="field-value">
                                <i class="fas fa-money-bill-wave"></i>
                                <span id="amount_phone_credit_edit">{{ formatDisplayAmount($amount_phone_credit_edit ?? null) }}
                                    {{ $currency_custumers ?? null }}</span>
                            </div>
                        </div>
                </div>
                 

            </div>
            
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space-padding">
                    <label for="description_phone_credit" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="description_phone_credit" id="reloadcard_description_edit" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>

                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">

                <div class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-phoneCredit-validted-show' , 'validated-phoneCredit-modal')" wire:model="phoneCreditStatus" id="bordered-radio-22"  type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-22"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                         <i class="fas fa-check-circle style_success"></i>
                        Accepté</label>
                    @error('phoneCreditStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-phoneCredit-validted-show' , 'validated-phoneCredit-modal')" wire:model="phoneCreditStatus" id="bordered-radio-23"  type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-23"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                         <i class="fas fa-times-circle style_error"></i>
                        Refusé</label>
                    @error('phoneCreditStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
           
        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_phoneCredit')
