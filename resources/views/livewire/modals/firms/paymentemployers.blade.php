<!-- Modal -->
<div wire:ignore.self id="payment-employers-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Payés les employés</h2>
            </div>
            <a wire:click="cancelEmployesField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <div
                        class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">

                        <div class="font-medium text-historique mt-6">Montant total Transferts </div>
                        <div class="text-slate-500 mt-3">
                            {{ number_format($wallet_amount->amount, 2, ',', ' ') ?? null }}
                            {{ $wallet_amount->currencie->code ?? null }}
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3  mt-6">

                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2 ">
                    <input id="bordered-radio-50" type="radio" wire:model="choose_type_payement_emp" value="1"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                    <label for="bordered-radio-50"
                        class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Paiement par
                        catégories</label>
                    @error('choose_type_payement_emp')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2 ">
                    <input id="bordered-radio-51" type="radio" wire:model="choose_type_payement_emp" value="2"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-51"
                        class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Paiement par
                        Wallet ID</label>

                    @error('choose_type_payement_emp')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            @if (!is_null($choose_type_payement_emp))
                @if ($choose_type_payement_emp == 1)
                    <div></div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 mt-space">
                            <label for="categorieId" class="form-label">Catégorie<span class="text-danger">
                                    *</span></label>
                            <select wire:model.debounce.10ms="categorieId" id="categorieId" class="form-control">
                                <option value="null" disabled>Selectionnez la categorie</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('categorieId')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!is_null($categorieId))

                            <div class="col-span-12 mt-1">
                                <label for="amount_payment" class="form-label">Montant<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="amount_payment" id="amount_payment" min="1"
                                    max="5" type="number" class="form-control" placeholder="Montant">
                                @error('amount_payment')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-12 mt-2">
                                <label for="transaction_description_robus" class="form-label">Decription<span
                                        class="text-danger">
                                        *</span></label>
                                <textarea wire:model.defer="transaction_description_robus" id="transaction_description_robus" type="text"
                                    class="form-control" cols="13" rows="3">
                                </textarea>
                                @error('transaction_description_robus')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (!is_null($amount_payment))
                                <div class="col-span-6 mt-3">
                                    <label for="amount_payment" class="form-label">Montant à payer<span
                                            class="text-danger">
                                            *</span></label>
                                    @if ($amount_payment == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_transaction_earch_pay ?? null }}
                                            {{ $wallet_amount->currencie->code ?? null }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-span-6 mt-3">
                                    <label for="amount_payment" class="form-label">Montant à
                                        recevoir chacun<span class="text-danger">
                                            *</span></label>
                                    @if ($amount_payment == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_transaction_each_receive ?? null }}
                                            {{ $wallet_amount->currencie->code ?? null }}

                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div class="col-span-12 mt-3">
                                <div
                                    class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-3 cursor-pointer zoom-in style_table2">

                                    <div class="font-medium text-historique mt-6">Nombre d'employés de la categorie :
                                        {{ $account_valeurs ?? null }} </div>

                                    <div class="px-5 py-3 mt-1  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                        style="font-size: 25px; color:white;">
                                        Montant a payé :
                                        {{ number_format($amount_valeurs, 2, ',', ' ') ?? null }}
                                        {{ $wallet_amount->currencie->code ?? null }}

                                    </div>
                                </div>

                            </div>

                        @endif
                    </div>
                @else
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="wallet_id_employer" class="form-label">Numero wallet<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.debounce.10ms="wallet_id_employer" id="wallet_id_employer"
                                type="text" class="form-control" placeholder="Numero wallet">
                            @error('wallet_id_employer')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div wire:loading>Recherche numero...</div>
                            <div wire:loading.remove></div>
                            @if ($wallet_id_employer == '')
                                <div class="text-gray-500 text-sm">
                                    Entrer le numero du wallet
                                </div>
                            @elseif($wallet_info_search_payment != null)
                                <div class="text-lg text-gray-900 text-bold text-secondary ">
                                    <span
                                        class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $wallet_info_search_payment ?? null }}
                                    </span>

                                </div>
                            @else
                                <div class="text-gray-500 text-sm">
                                    Numero inconnu chez Robus
                                </div>
                            @endif
                        </div>
                        <div class="col-span-12 mt-2">
                            <label for="transaction_description_robus" class="form-label">Decription<span
                                    class="text-danger">
                                    *</span></label>
                            <textarea wire:model.defer="transaction_description_robus" id="transaction_description_robus" type="text"
                                class="form-control" cols="13" rows="3">
                            </textarea>
                            @error('transaction_description_robus')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        </div>
                        <div class="col-span-12">
                            <label for="amount_payment_wallet" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <input wire:model.debounce.10ms="amount_payment_wallet" id="amount_payment_wallet"
                                min="1" max="5" type="number" class="form-control"
                                placeholder="Montant">
                            @error('amount_payment_wallet')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!is_null($amount_payment_wallet))
                            <div class="col-span-6 mt-3">
                                <label for="amount_payment_wallet" class="form-label">Montant à payer<span
                                        class="text-danger">
                                        *</span></label>
                                @if ($amount_payment_wallet == '')
                                    <div class="text-gray-500 text-sm">
                                        Entrer le montant
                                    </div>
                                @else
                                    <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                        style="font-size: 25px; color:white;">
                                        {{ $amount_transaction_under_pay ?? null }}
                                        {{ $wallet_amount->currencie->code ?? null }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="amount_payment_wallet" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                @if ($amount_payment_wallet == '')
                                    <div class="text-gray-500 text-sm">
                                        Entrer le montant
                                    </div>
                                @else
                                    <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                        style="font-size: 25px; color:white;">
                                        {{ $amount_transaction_under_receive ?? null }}
                                        {{ $wallet_amount->currencie->code ?? null }}

                                    </div>
                                @endif
                            </div>
                        @endif


                    </div>

                @endif
            @endif

            <div class="modal-footer">
                <button wire:click="cancelEmployesField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="payments_employers" type="button" wire:loading.remove=""
                    class="btn btn-primary w-20">Payer</button>
                <div wire:loading wire:target="payments_employers">
                    Traitement en cours...
                </div>
            </div>

        </div>
    </div>
</div>
