<div wire:ignore.self id="update-transfers-visitors-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Valider la demande transfers d'argent</h2>
            </div>
            <a wire:click="cancelTransactionField()"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" style="color: white;"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Transfers d'argent
            </div>
           
            @if (!is_null($provider_transaction_))

                @if ($provider_transaction_ == 9)
                    <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label for="provider_transaction_" class="form-label">Canal de transfert
                                <span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="provider_transaction_" disabled id="number_phone_credit_edit"
                                type="text" class="form-control style_edit_form" placeholder="Numero de telephone">
                        </div>
                     
                        <div class="col-span-6">
                            <label for="compte_number_robus_" class="form-label">Wallet Robus<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="compte_number_robus_" disabled 
                                type="text" class="form-control style_edit_form" placeholder="Montant">

                        </div>
                        <div class="col-span-6">
                            <label for="name_robus_" class="form-label">Nom & prenom expéditeur<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer ="name_robus_" disabled id="name_robus_"
                                type="text" class="form-control style_edit_form" placeholder="Montant">

                        </div>
                        <div class="col-span-6">
                            <label for="phone_benef_" class="form-label">Numero téléphone bénéficiaire<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="phone_benef_" disabled id="phone_benef_"
                                type="text" class="form-control style_edit_form" placeholder="Montant">

                        </div>

                        <div class="col-span-6 mt-1">
                            <label for="transaction_amount_robus_" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
        
                                <input wire:model.defer="transaction_amount_robus_" disabled  type="text"
                                    class="form-control style_edit_form" placeholder="Montant">
                            </div>
        
                        </div>

                    </div>
                @else
                    <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                       
                        <div class="col-span-6">
                            <label for="provider_transaction_" class="form-label">Canal de transfert
                                <span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="provider_transaction_" disabled id="number_phone_credit_edit"
                                type="text" class="form-control style_edit_form" placeholder="Numero de telephone">
                        </div>
                        <div class="col-span-6">
                            <label for="name_robus_" class="form-label">Nom & prenom expéditeur<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer ="name_robus_" disabled id="name_robus_"
                                type="text" class="form-control style_edit_form" placeholder="Montant">

                        </div>
                        <div class="col-span-6">
                            <label for="transaction_phone_" class="form-label">Numero téléphone expéditeur<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="transaction_phone_" disabled id="transaction_phone_"
                                type="text" class="form-control style_edit_form" placeholder="Montant">

                        </div>
                        <div class="col-span-6">
                            <label for="phone_benef_" class="form-label">Numero téléphone bénéficiaire<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model="phone_benef_" disabled id="phone_benef_"
                                type="text" class="form-control style_edit_form" placeholder="Montant">

                        </div>
                        <div class="col-span-6 mt-1">
                            <label for="transaction_amount_robus_" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
        
                                <input wire:model.defer="transaction_amount_robus_" disabled  type="text"
                                    class="form-control style_edit_form" placeholder="Montant">
                            </div>
        
                        </div>

                        <div class="col-span-6 mt-1">
                            <label for="transaction_amount_money_" class="form-label">Montant a recevoir<span class="text-danger">
                                    *</span></label>
                            <div class="flex">
        
                                <input wire:model.defer="transaction_amount_money_" disabled  type="text"
                                    class="form-control style_edit_form" placeholder="Montant">
                            </div>
        
                        </div>

                    </div>
               
                @endif
                <div class="modal-body-transfers  grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="transaction_description_" class="form-label">Commentaire</label>
                        <textarea wire:model="transaction_description_"  type="text"
                            class="form-control" cols="13" rows="3">
                       </textarea>

                    </div>
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">
                    <div   class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input  wire:click="$emit('modal-transfers-visitors-validted-show' , 'validated-transfers-visitors-modal')"  wire:model.debounce.10ms="transactionStatus" id="bordered-radio-15"  type="radio" value="SUCCESS"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-15"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                            Accepté</label>
                        @error('transactionStatus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:click="$emit('modal-transfers-visitors-validted-show' , 'validated-transfers-visitors-modal')"  wire:model.debounce.10ms="transactionStatus" id="bordered-radio-16"  type="radio" value="ERROR"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-16"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Refusé</label>
                        @error('transactionStatus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                   
                </div>


               
            @endif




        </div>
    </div>
</div>
@include('livewire.commissions.modals.transactions.visitors.validated_transaction')
