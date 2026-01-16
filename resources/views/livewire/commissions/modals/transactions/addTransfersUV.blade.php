<!-- Modal -->
@php

    $is_under = sabdy()->is_under_distributer;
    $is_distributer = sabdy()->is_distributer;
@endphp
<div wire:ignore.self id="create-transfersUV-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Demande de transfers d'argent</h2>
            </div>
            <a wire:click="cancelTransactionField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3  mt-6">

                @if (sabdy()->is_distributer != 1)
                    <!-- Option 1: Transferts -->

                    @if ($is_under)


                        @if ($showDepotButton)
                            <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                                @if ($choose_type_transaction == 2) style="display: none;" @endif>
                                <input id="bordered-radio-6" type="radio"
                                    wire:model.debounce.10ms="choose_type_transaction" value="1"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-6"
                                    class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Transferts</label>
                                @error('choose_type_transaction')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif


                        @if ($showRetraitButton)
                            <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                                @if ($choose_type_transaction == 1) style="display: none;" @endif>
                                <input id="bordered-radio-7" type="radio"
                                    wire:model.debounce.10ms="choose_type_transaction" value="2"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="bordered-radio-7"
                                    class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Retraits</label>
                                @error('choose_type_transaction')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    @else
                        <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                            @if ($choose_type_transaction == 2) style="display: none;" @endif>
                            <input id="bordered-radio-6" type="radio"
                                wire:model.debounce.10ms="choose_type_transaction" value="1"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-6"
                                class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Transferts</label>
                            @error('choose_type_transaction')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2"
                            @if ($choose_type_transaction == 1) style="display: none;" @endif>
                            <input id="bordered-radio-7" type="radio"
                                wire:model.debounce.10ms="choose_type_transaction" value="2"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="bordered-radio-7"
                                class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Retraits</label>
                            @error('choose_type_transaction')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                @else
                    <div
                        class="col-span-12 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2 ">
                        <input id="bordered-radio-6" type="radio" wire:model.debounce.10ms="choose_type_transaction"
                            value="1"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                        <label for="bordered-radio-6"
                            class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Transferts</label>
                        @error('choose_type_transaction')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

            </div>

            @if (!is_null($choose_type_transaction))

                @if ($choose_type_transaction == 1)
                    @livewire('transaction-groupe.transaction-civ')
                @else
                    @if ($currentStep == 1)
                        <div
                            class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                                <a href="#step-1"
                                    class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Numero & Montant
                                </div>
                            </div>
                        </div>
                    @else
                        <div
                            class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                                <a href="#step-2"
                                    class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 2 ? 'btn-default' : 'btn-tertiary' }}">2</a>
                                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validation retrait
                                </div>
                            </div>
                        </div>
                    @endif


                    @if (Sabdy()->is_distributer == 1)
                        <div class="modal-header alert alert-secondary text-white">
                            <h2 class="font-medium text-base mr-auto">En tant qu'opératrice, vous avez la possibilité de
                                retirer de l'argent chez des clients
                                et des sous-distributeurs à l'intérieur.</h2>
                        </div>
                    @elseif(Sabdy()->is_supervisor == 1)
                        <div class="modal-header alert alert-secondary text-white">
                            <h2 class="font-medium text-base mr-auto">En tant qu'Superviseur, vous avez la possibilité
                                de retirer de l'argent chez des clients et des sous-distributeurs à l'intérieur et
                                international.
                            </h2>
                        </div>
                    @elseif(Sabdy()->is_admin == 1)
                        <div class="modal-header alert alert-secondary text-white">
                            <h2 class="font-medium text-base mr-auto">En tant qu'Admin, vous avez la possibilité de
                                retirer de l'argent chez les autres à l'intérieur comme international. </h2>
                        </div>
                    @endif
                    @if (Sabdy()->is_distributer != 1)

                        <div class=" {{ $currentStep != 1 ? 'displayNone' : '' }}" id="step-1">

                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="modal-form-1" class="form-label">Canal retrait d'argent <span
                                            class="text-danger">
                                            *</span></label>

                                    <select wire:model="provider_transaction" id="modal-form-1" class="form-control">
                                        <option value="null" disabled>Selectionnez le canal</option>
                                        @foreach ($withdrawal_robus as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('provider_transaction')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>

                            @if (!is_null($provider_transaction))

                                @if ($provider_transaction == 9)
                                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-12">
                                            <img class="object-none object-center bg-yellow-300 w-logo"
                                                src="{{ asset_url('dist/providers/robus.png') }}"
                                                alt="image"wire:model="image">
                                        </div>
                                    </div>
                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                                        <div class="col-span-6">
                                            <label for="compte_number_robus" class="form-label">Numero wallet<span
                                                    class="text-danger">
                                                    *</span></label>

                                            <input wire:model.debounce.10ms="compte_number_robus"
                                                id="compte_number_robus" type="text" class="form-control"
                                                value="" maxlength="9" placeholder="Numero wallet">
                                            <div wire:loading>Recherche wallet...</div>
                                            <div wire:loading.remove></div>
                                            @if ($compte_number_robus == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer numero wallet
                                                </div>
                                            @else
                                                @if ($wallet_search == null)
                                                    <div class="text-gray-500 text-sm">
                                                        Ce numero n'existe pas
                                                    </div>
                                                @else
                                                    <div class="text-lg text-gray-900 text-bold text-secondary ">
                                                        <span
                                                            class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $wallet_search->sidUser->firstname ?? null }}
                                                            {{ $wallet_search->sidUser->lastname ?? null }}
                                                        </span>

                                                    </div>
                                                @endif
                                            @endif

                                            @error('compte_number_robus')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror


                                        </div>

                                        <div class="col-span-6">
                                            <label for="transaction_amount_robus" class="form-label">Montant<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <input wire:model.debounce.500ms="transaction_amount_robus"
                                                id="transaction_amount_robus" min="1" max="5"
                                                type="number" class="form-control" placeholder="Montant">
                                            @error('transaction_amount_robus')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if ($transaction_amount_robus)

                                            @php
                                                $amount_to_pay = $amount_transaction_nat_val_percent;
                                                $amount_to_receive = $amount_transaction_nat_val_receive;
                                                $commission = $amount_transaction_nat_val_drawal;
                                                $frais = $commission_transaction_val_admin_receive;
                                            @endphp

                                            @if (sabdy()->is_under_distributer == 1)

                                                @if ($showRemonterRetraitButton)
                                                    <x-transaction-detail title="Commission"
                                                        value="{{ $commission }}" country="{{ $usersCountry }}" />
                                                @else
                                                    <div class="col-span-6 mt-3">
                                                        <label class="form-label">Pas de commission<span
                                                                class="text-danger"> *</span></label>
                                                        <div class="px-5 py-3 flex-1 zoom-in alert alert-secondary relative font-medium leading-6 pl-3 2xl:pl-4"
                                                            style="font-size: 25px; color:white;">

                                                        </div>
                                                    </div>
                                                @endif


                                                <x-transaction-detail title="Montant à remettre"
                                                    value="{{ $amount_to_receive }}"
                                                    country="{{ $usersCountry }}" />
                                            @endif
                                        @endif

                                    </div>

                                    <div class="modal-footer">
                                        <button wire:click="cancelTransactionField" type="button"
                                            class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                                        <button wire:click="firstStepSubmit" type="button" wire:loading.remove=""
                                            class="btn btn-primary w-50">Validez</button>
                                        <div wire:loading wire:target="firstStepSubmit">
                                            Traitement en cours...
                                        </div>
                                    </div>

                                @endif
                            @endif
                        </div>

                        <div class="{{ $currentStep != 2 ? 'displayNone' : '' }}" id="step-2">
                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6 mt-space-padding">
                                    <label class="form-label">Wallet de retrait<span class="text-danger">
                                            *</span></label>

                                    <div class="flex">

                                        <div
                                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                            {{ $compte_number_robus ?? null }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-span-6 mt-space-padding">
                                    <label class="form-label">Montant <span class="text-danger">
                                            *</span></label>

                                    <div class="flex">

                                        <div
                                            class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                            {{ $transaction_amount_robus ?? null }}
                                            {{ $user_send->wallet->currencie->code ?? null }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-span-12" wire:loading.class="hidden"
                                    wire:target="addTransactionWithdrawal">
                                    <label for="code_validated" class="form-label">Code OTP <span
                                            class="text-danger">*</span></label>
                                    <input wire:model.defer="code_validated" id="code_validated" type="text"
                                        class="form-control" placeholder="Entrez votre code OTP">
                                    @error('code_validated')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button wire:click="addTransactionWithdrawal" type="button"
                                    wire:loading.attr="disabled" class="btn btn-primary w-50"
                                    wire:loading.class="hidden opacity-50 cursor-not-allowed">
                                    Validez code
                                </button>


                                <div wire:loading.flex wire:target="addTransactionWithdrawal"
                                    class="flex items-center justify-center">
                                    <div class="loader"></div>
                                    <p>Traitement en cours...</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif

        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.private('wallet-transfers-created.').listen(
                '.Created_wallet_transfers',
                (e) => {
                    console.log('valeur send');
                    console.log(e.id);
                    window.livewire.emit('notifyNewTransfers:' + e.id);
                },
            )

        });
    </script>
</div>
