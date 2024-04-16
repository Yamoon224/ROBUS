<div wire:ignore.self id="update-reloadCards-visitors-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Valider la  demande de réchargement des cartes</h2>
            </div>
            <a wire:click="cancelReloadCardField()"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x" style="color: white;"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Rechargement de carte
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1">
                <div class="col-span-6 ">
                    <label for="provider_reloadcard_" class="form-label">Canal rechargement carte
                        <span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="provider_reloadcard_" disabled 
                        type="text" class="form-control style_edit_form" placeholder="Numero de telephone">
                </div>
                <div class="col-span-6">
                    <label for="account_number_reloadcard_" class="form-label">Numero du compte<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="account_number_reloadcard_" disabled id="account_number_user"_edit
                        type="text" class="form-control style_edit_form" placeholder="Numero du compte">

                </div>

            </div>
           
           
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3">
                <div class="col-span-6 mt-space">
                    <label for="name_reloadcard_" class="form-label">Nom et prenom<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="name_reloadcard_" disabled  type="text"
                        class="form-control style_edit_form" placeholder="Nom et prenom">
                </div>
                <div class="col-span-6 mt-space">
                    <label for="account_phone_" class="form-label">Numero <span class="text-danger">
                            *</span></label>
                    <div class="flex">

                        <input wire:model.defer="account_phone_" disabled type="text"
                            class="form-control style_edit_form" placeholder="Montant"> 
                    </div>

                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3">
              
                <div class="col-span-6 mt-space">
                    <label for="amount_reloadcard_" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <div class="flex">

                        <input wire:model.defer="amount_reloadcard_" disabled type="text"
                            class="form-control style_edit_form" placeholder="Montant"> FCFA
                    </div>

                </div>

                <div class="col-span-6 mt-space">
                    <label for="amount_receved_" class="form-label">Montant  a recevoir<span class="text-danger">
                            *</span></label>
                    <div class="flex">

                        <input wire:model.defer="amount_receved_" disabled type="text"
                            class="form-control style_edit_form" placeholder="Montant"> FCFA
                    </div>

                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3">
              
        
                <div class="col-span-12 mt-space">
                    <label for="account_last_digits_" class="form-label">4 dernier chiffres<span class="text-danger">
                            *</span></label>
                    <div class="flex">

                        <input wire:model.defer="account_last_digits_" disabled type="text"
                            class="form-control style_edit_form" placeholder="Montant"> 
                    </div>

                </div>
            </div>



            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="reloadcard_description_" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="reloadcard_description_"  type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>

                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-reloadCards-visitors-validted-show' , 'validated-reloadCards-visitors-modal')" wire:model="reloadcardStatus" id="bordered-radio-25"  type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-25"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Accepté</label>
                    @error('reloadcardStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="$emit('modal-reloadCards-visitors-validted-show' , 'validated-reloadCards-visitors-modal')" wire:model="reloadcardStatus" id="bordered-radio-26"  type="radio" value="ERROR"
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

@include('livewire.commissions.modals.transactions.visitors.validated_reload_card')

