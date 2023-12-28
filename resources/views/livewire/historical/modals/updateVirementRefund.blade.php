<div wire:ignore.self id="update-virementHisto-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Remboursement du virement</h2>
            </div>
            <a wire:click="$emit('modal-hide-virementHisto')"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                <div class="col-span-12 mt-space">
                    <label for="provider_virement_edit" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="provider_virement_edit" disabled id="provider_virement_edit"
                        class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provider_virements as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                </div>

            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">

                <div class="col-span-6 mt-space">
                    <label for="account_number_user_edit" class="form-label">Numero du compte<span class="text-danger">
                            *</span></label>
                    <input wire:model="account_number_user_edit" disabled id="account_number_user"_edit type="text"
                        class="form-control" placeholder="Numero du compte">

                </div>
                <div class="col-span-6 mt-space">
                    <label for="country_virement_edit" class="form-label">Pays<span class="text-danger">
                            *</span></label>
                    <select wire:model="country_virement_edit" disabled id="country_virement_edit" class="form-control">
                        <option value="null" disabled>Selectionnez le pays</option>
                        @foreach ($countries as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="firstname_user" class="form-label">Nom du client<span class="text-danger">
                            *</span></label>
                    <input wire:model="firstname_user_edit" disabled id="firstname_user_edit" type="text"
                        class="form-control" placeholder="Nom du client">

                </div>
                <div class="col-span-6 mt-space">
                    <label for="lastname_user_edit" class="form-label">Prenom du client<span class="text-danger">
                            *</span></label>
                    <input wire:model="lastname_user_edit" disabled id="lastname_user_edit" type="text"
                        class="form-control" placeholder="Prenom du client">

                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6 mt-space">
                    <label for="phone_user" class="form-label">Telephone<span class="text-danger">
                            *</span></label>
                    <input wire:model="phone_user_edit" disabled id="phone_user_edit" type="text"
                        class="form-control" placeholder="Numero du telephone">

                </div>
                <div class="col-span-6 mt-space">
                    <label for="amount_virement_edit" class="form-label">Montant<span class="text-danger">
                            *</span></label>
                    <div class="flex">

                        <input wire:model="amount_virement_edit" disabled id="amount_virement_edit" type="text"
                            class="form-control" placeholder="Montant">
                        <div
                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                            {{ $currency_custumers }}
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="virementDescription_edit" class="form-label">Commentaire</label>
                    <textarea wire:model="virementDescription_edit" id="virementDescription_edit" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>
                </div>
            </div>

        
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">


                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:model="virementStatus" id="bordered-radio-44" type="radio" value="REFUND"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="virementStatus"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Remboursé</label>
                    @error('virementStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>




            @if ($virementStatus == 'REFUND')
                <div class="modal-footer w-full  bottom-0  mt-space style-foo">
                    <button wire:click="$emit('modal-hide-virementHisto')" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="updateVirementHisto" type="button" wire:loading.remove=""
                        class="btn btn-primary w-50">Mettre a jour</button>
                    <button wire:loading.delay="" wire:target="updateVirementHisto"
                        wire:click="updateVirementHisto" type="button" class="btn btn-warning w-40"> Traitement ...
                    </button>
                </div>
            @endif

        </div>
    </div>
</div>
