<div wire:ignore.self id="update-transfersUV-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            @include('components.modal-header', [
                'title' => 'Valider transferts d\'argent',
                'icon' => 'fas fa-check-circle',
                'service' => 'transfers d\'argent',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelTransactionField',
            ])

            <div class="modal-body mt-1">

                <div class="field-group">

                    <div class="field field-full">
                        <label class="form-label required">Canal de transfert</label>
                        <div class="field-value text-center">
                            <i class="fas fa-network-wired"></i>
                            <span id="provider_transaction_edit"> {{ $provider_transaction_edit ?? null }}</span>
                        </div>
                    </div>

                </div>


                @if (!is_null($provider_transaction_edit))

                    @if ($provider_transaction_edit == 9)
                        <div class="field-group">
                            <div class="field">
                                <label class="form-label required">Numéro du compte</label>
                                <div class="field-value">
                                    <i class="fas fa-user"></i>
                                    <span id="compte_number_robus_edit"> {{ $compte_number_robus_edit ?? null }}</span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="form-label required">Montant</label>
                                <div class="field-value">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span
                                        id="transaction_amount_robus_edit">{{ $transaction_amount_robus_edit ?? null }}
                                        {{ $currency_custumers ?? null }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="field-group">
                            <div class="field">
                                <label class="form-label required">Numéro téléphone</label>
                                <div class="field-value">
                                    <i class="fas fa-user"></i>
                                    <span id="transaction_phone_edit"> {{ $transaction_phone_edit ?? null }}</span>
                                </div>
                            </div>

                            @if ($provider_transaction_edit == 'Moov')
                                <div class="field-full">
                                    <label class="form-label required">Reference du paiement</label>
                                    <div class="field-value">
                                        <i class="fas fa-user"></i>
                                        <span id="references"> {{ $references ?? null }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="field">
                                <label class="form-label required">Montant</label>
                                <div class="field-value">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span
                                        id="transaction_amount_money_edit">{{ $transaction_amount_money_edit ?? null }}
                                        {{ $currency_custumers ?? null }}</span>
                                </div>
                            </div>

                        </div>


                    @endif
                    <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="transaction_description_edit" class="form-label">Commentaire</label>
                            <textarea wire:model="transaction_description_edit" id="transaction_description_edit" type="text"
                                class="form-control" cols="13" rows="3">
                       </textarea>

                        </div>
                    </div>
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">
                        <div
                            class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                            <input wire:click="$emit('modal-transfers-validted-show' , 'validated-transfersUV-modal')"
                                wire:model.debounce.10ms="transactionStatus" id="bordered-radio-15" type="radio"
                                value="SUCCESS"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-15"
                                class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                                <i class="fas fa-check-circle style_success"></i>
                                Accepté</label>
                            @error('transactionStatus')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div
                            class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                            <input wire:click="$emit('modal-transfers-validted-show' , 'validated-transfersUV-modal')"
                                wire:model.debounce.10ms="transactionStatus" id="bordered-radio-16" type="radio"
                                value="ERROR"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-16"
                                class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                                <i class="fas fa-times-circle style_error"></i>
                                Refusé</label>
                            @error('transactionStatus')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>



                @endif

            </div>



        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_transaction')
