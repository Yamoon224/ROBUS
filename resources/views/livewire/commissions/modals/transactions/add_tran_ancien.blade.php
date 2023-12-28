<!-- Modal -->
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

                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2 ">
                    <input id="bordered-radio-6" type="radio" wire:model.debounce.10ms="choose_type_transaction"
                        value="1"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                    <label for="bordered-radio-6"
                        class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Transferts</label>
                    @error('choose_type_transaction')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 form-check mr-2 ">
                    <input id="bordered-radio-7" type="radio" wire:model.debounce.10ms="choose_type_transaction"
                        value="2"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-7"
                        class="w-full py-4 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Retraits</label>

                    @error('choose_type_transaction')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            @if (!is_null($choose_type_transaction))

                @if ($choose_type_transaction == 1)

                    @if (sabdy()->country->name_groupe == 'AFL')
                        @livewire('transaction-groupe.transaction-nation-west')
                    @elseif(sabdy()->country->id == 107)
                        @livewire('transaction-groupe.transaction-civ')
                    @elseif (sabdy()->country->name_groupe == 'AFC')
                        @livewire('transaction-groupe.transaction-nation-central')
                    @elseif (sabdy()->country->name_groupe == 'USA')
                        @livewire('transaction-groupe.transaction-usa')
                    @elseif (sabdy()->country->name_groupe == 'URP')
                        @livewire('transaction-groupe.transaction-europe')
                    @endif
                @else
                    <div
                        class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                        <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                            <a href="#step-1"
                                class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                            <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Numero & Montant</div>
                        </div>
                        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                            <a href="#step-2"
                                class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 2 ? 'btn-default' : 'btn-primary' }}">2</a>
                            <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600 dark:text-slate-400">
                                Validation</div>
                        </div>

                    </div>

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
                                            src="{{ asset('public/dist/providers/robus.png') }}"
                                            alt="image"wire:model="image">
                                    </div>
                                </div>
                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                                    <div class="col-span-6">
                                        <label for="compte_number_robus" class="form-label">Numero wallet<span
                                                class="text-danger">
                                                *</span></label>

                                        <input wire:model.debounce.10ms="compte_number_robus" id="compte_number_robus"
                                            type="text" class="form-control" value="" maxlength="9"
                                            placeholder="Numero wallet">
                                        <div wire:loading>Recherche wallet...</div>
                                        <div wire:loading.remove></div>
                                        @if ($compte_number_robus == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer numero wallet
                                            </div>
                                        @else
                                            @if ($wallet_search->isEmpty())
                                                <div class="text-gray-500 text-sm">
                                                    Ce numero n'existe pas
                                                </div>
                                            @else
                                                @foreach ($wallet_search as $wallet)
                                                    <div class="text-lg text-gray-900 text-bold text-secondary ">
                                                        <span
                                                            class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $wallet->sidUser->firstname ?? null }}
                                                            {{ $wallet->sidUser->lastname ?? null }}
                                                        </span>

                                                    </div>
                                                    {{-- @endif --}}
                                                @endforeach
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
                                        <input wire:model.defer="transaction_amount_robus" id="transaction_amount_robus"
                                            min="1" max="5" type="number" class="form-control"
                                            placeholder="Montant">
                                        @error('transaction_amount_robus')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

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

                        <div class="alert alert-primary show mb-2 mr-6 ml-6" role="alert">Un retrait sur
                            {{ $compte_number_robus }}</div>

                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3 ">

                            <div class="col-span-12">
                                <label for="code_validated" class="form-label">Le code de
                                    validation<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="code_validated" id="code_validated" min="1"
                                    max="5" type="number" class="form-control"
                                    placeholder="Code de validation">
                                @error('code_validated')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        @if (!is_null($code_validated))
                            <div class="col-span-12">
                                @if ($code_validated == '')
                                    <div class="text-gray-500 text-sm">
                                        Entrer le code valide
                                    </div>
                                @else
                                    <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                        {{-- <div  class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4"> --}}
                                        @if ($code_valided != null)
                                            <p class="alert alert-success" style="color: white;">Le code est valide
                                            </p>
                                        @else
                                            <p class="alert alert-danger" style="color: white;">Le code est Invalide
                                            </p>
                                        @endif

                                        {{-- </div> --}}
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="modal-footer">
                            <button wire:click="cancelTransactionField" type="button"
                                class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                            <button wire:click="addTransactionWithdrawal" type="button" wire:loading.remove=""
                                class="btn btn-primary w-50">Validez</button>
                            <div wire:loading wire:target="addTransactionWithdrawal">
                                Traitement en cours...
                            </div>
                        </div>
                    </div>
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
