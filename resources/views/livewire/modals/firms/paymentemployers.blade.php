<!-- Modal -->
<div wire:ignore.self id="payment-employers-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">


    <div class="modal-dialog modal-lg">
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
            @if ($step === 1)
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $step != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                            requises
                        </div>
                    </div>

                </div>
                <div class="text-slate-500 mt-3 text-center">
                    <span
                        class="alert text-center alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                        Solde : {{ number_format($wallet_amount->amount, 2, ',', ' ') ?? null }}
                        {{ $wallet_amount->currencie->code ?? null }}
                    </span>
                </div>

                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3  mt-6">

                    <div
                        class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2 ">
                        <input id="bordered-radio-50" type="radio" wire:model="choose_type_payement_emp"
                            value="1"
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
                        <input id="bordered-radio-51" type="radio" wire:model="choose_type_payement_emp"
                            value="2"
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

                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-1">
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

                            <div class="col-span-6 mt-1">
                                <label for="amount_payment" class="form-label">Montant<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="amount_payment" id="amount_payment" min="1"
                                    max="5" type="number" class="form-control" placeholder="Montant">
                                @error('amount_payment')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-12 mt-1">
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
                            @if ($amount_payment)
                                @php

                                    $amount_to_pay = $amount_transaction_nat_val_percent;
                                    $amount_to_receive = $amount_transaction_nat_val_receive;
                                    $commission = $amount_transaction_nat_val_pay;
                                    $frais = $commission_transaction_val_admin_receive;
                                    $usersCountry = $wallet_amount->currencie->code ?? null;

                                @endphp

                                <x-transaction-detail title="Commission" value="{{ $commission }}"
                                    country="{{ $usersCountry }}" />

                                {{-- Montant à recevoir --}}
                                <x-transaction-detail title="Montant à recevoir" value="{{ $amount_to_receive }}"
                                    country="{{ $usersCountry }}" />
                            @endif

                            <div class="col-span-12 mt-3">
                                <div
                                    class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-3 cursor-pointer zoom-in style_table2">

                                    <div class="font-medium text-historique mt-6">Nombre d'employés de la categorie
                                        :
                                        {{ $account_valeurs ?? null }} </div>

                                    <div class="px-5 py-3 mt-1  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                        style="font-size: 25px; color:white;">
                                        Montant a payé :
                                        {{ number_format($amount_valeurs, 2, ',', ' ') ?? null }}
                                        {{ $wallet_amount->currencie->code ?? null }}

                                    </div>
                                </div>

                            </div>
                        </div>
                    @else
                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6">
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

                                @php

                                    $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $wallet_id_employer)
                                        ->where('country_id', firm()->country->id)
                                        ->first();
                                    if ($wallet_search) {
                                        if (
                                            $wallet_search->sidUser->is_custumers &&
                                            $wallet_search->sidUser->company_id == firm()->company->id
                                        ) {
                                            $wallet_name =
                                                $wallet_search->sidUser->firstname .
                                                ' ' .
                                                $wallet_search->sidUser->lastname;
                                        } else {
                                            $wallet_name = 'Numero introuvable ou n\' est pas client';
                                        }
                                    } else {
                                        $wallet_name = 'Numero introuvable ou n\' est pas client';
                                    }

                                @endphp

                                @if ($wallet_id_employer == '')
                                    <div class="text-gray-500 text-sm">Entrer numero wallet</div>
                                @else
                                    @if ($wallet_name)
                                        <div class="text-lg text-gray-900 text-bold text-secondary">
                                            <span
                                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $wallet_name }}
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="col-span-6">
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
                                        *</span>
                                </label>
                                <textarea wire:model.defer="transaction_description_robus" id="transaction_description_robus" type="text"
                                    class="form-control" cols="13" rows="3">
                                </textarea>

                                @error('transaction_description_robus')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($amount_payment)
                                @php

                                    $amount_to_pay = $amount_transaction_nat_val_percent ?? null;
                                    $amount_to_receive = $amount_transaction_nat_val_receive ?? null;
                                    $commission = $amount_transaction_nat_val_pay ?? null;
                                    $frais = $commission_transaction_val_admin_receive ?? null;
                                    $usersCountry = $wallet_amount->currencie->code ?? null;

                                @endphp

                                <x-transaction-detail title="frais" value="{{ $frais }}"
                                    country="{{ $usersCountry }}" />

                                <x-transaction-detail title="Commission" value="{{ $commission }}"
                                    country="{{ $usersCountry }}" />

                                <x-transaction-send title="Montant à recevoir" value="{{ $amount_to_receive }}"
                                    country="{{ $usersCountry }}" />
                            @endif

                        </div>

                    @endif
                @endif

                <div class="modal-footer">
                    <button wire:click="cancelEmployesField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="addTransaction" type="button" wire:loading.remove=""
                        wire:loading.attr="disabled" class="btn btn-primary w-50">Envoyer la demande</button>
                    <div wire:loading wire:target="addTransaction">
                        Traitement en cours...
                    </div>
                </div>
            @elseif ($step === 2)
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-2"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $step != 2 ? 'btn-default' : 'btn-secondary' }}">2</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validation </div>
                    </div>

                </div>
                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 mt-3">


                    <div class="col-span-6 mt-space-padding">
                        <label for="number_phone_credit_edit" class="form-label">Montant de la transaction Robus<span
                                class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ formatDisplayAmount($amount_payment ?? null) }} {{ $wallet_amount->currencie->code ?? null }}
                            </div>
                        </div>

                    </div>

                    @if ($choose_type_payement_emp == 1)

                        <div class="col-span-6 mt-space-padding">
                            <label for="number_phone_credit_edit" class="form-label">Categorie<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="flex">
                                @php
                                    $categorieId = App\Models\Categorie::whereId($categorieId)->first();

                                @endphp
                                <div
                                    class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                    {{ $categorieId->name ?? null }}
                                </div>
                            </div>
                        </div>

                        <div class="col-span-12 mt-3">
                            <div
                                class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-3 cursor-pointer zoom-in style_table2">

                                <div class="font-medium text-historique mt-6">Nombre d'employés de la categorie
                                    :
                                    {{ $account_valeurs ?? null }} </div>

                                <div class="px-5 py-3 mt-1  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                    style="font-size: 25px; color:white;">
                                    Montant a payé :
                                    {{ number_format($amount_valeurs, 2, ',', ' ') ?? null }}
                                    {{ $wallet_amount->currencie->code ?? null }}

                                </div>
                            </div>

                        </div>
                    @else
                        <div class="col-span-6 mt-space-padding">
                            <label for="number_phone_credit_edit" class="form-label">Nom & Prenom<span
                                    class="text-danger">
                                    *</span></label>

                            <div class="flex">
                                @php

                                    $wallet_search = App\Models\Wallets\Wallet::where('wallet_id', $wallet_id_employer)
                                        ->where('country_id', firm()->country->id)
                                        ->first();

                                    if ($wallet_search) {
                                        if (
                                            $wallet_search->sidUser->is_custumers &&
                                            $wallet_search->sidUser->company_id == firm()->company->id
                                        ) {
                                            $wallet_name =
                                                $wallet_search->sidUser->firstname .
                                                ' ' .
                                                $wallet_search->sidUser->lastname;
                                        } else {
                                            $wallet_name = 'Numero introuvable ou n\' est pas client';
                                        }
                                    } else {
                                        $wallet_name = 'Numero introuvable ou n\' est pas client';
                                    }
                                @endphp

                                @if ($wallet_name)
                                    <div
                                        class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                        {{ $wallet_name ?? null }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endif




                    <div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="validatedPassword">

                        <x-password-input />

                    </div>

                </div>

                <div class="modal-footer">

                    <!-- Bouton "Annuler" -->
                    <button wire:click="cancelEmployesField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1" wire:loading.class="hidden"
                        wire:target="validatedPassword">
                        Annuler
                    </button>

                    <!-- Bouton "Validez la demande" -->
                    @if ($isPasswordValid)
                        <button wire:click="validatedPassword" type="button" wire:loading.attr="disabled"
                            class="btn btn-primary w-50" wire:loading.class="hidden opacity-50 cursor-not-allowed">
                            Validez la demande
                        </button>
                    @else
                        <p class="text-danger mt-2">
                            Veuillez entrer un mot de passe valide pour activer le bouton de validation.
                        </p>
                    @endif

                    <div wire:loading.flex wire:target="validatedPassword" class="flex items-center justify-center">
                        <div class="loader"></div>
                        <p>Traitement en cours...</p>
                    </div>
                </div>


            @endif


        
        </div>
    </div>
</div>
