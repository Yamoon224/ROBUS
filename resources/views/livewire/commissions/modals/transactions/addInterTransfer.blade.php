<!-- Modal -->
<div wire:ignore.self id="create-interTransfers-modal" class="modal  hide  overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content px-10">
            <div class="modal-header alert alert-primary ">
                <h2 class="font-medium text-base mr-auto">Demande de transfers internationnal</h2>
            </div>
            <a wire:click="cancelInterField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>


            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3  mt-6">


                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:model="choose_type_paiement" id="bordered-radio-1" type="radio" value="1"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-1"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300"> Paiement
                        money</label>
                    @error('choose_type_paiement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>

                @if (sabdy()->country->code != 'CI')
                    <div
                        class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                        <input wire:model="choose_type_paiement" id="bordered-radio-5" type="radio" value="8"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-5"
                            class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300"> Rechargement
                            de
                            carte</label>
                        @error('choose_type_paiement')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                @endif

            </div>
            @if (!is_null($choose_type_paiement))


                @if ($choose_type_paiement == 1)
                    <input type="hidden" wire:init="loadChoose3">
                    @livewire('transaction-inters.transaction-civ')
                @elseif ($choose_type_paiement == 5)
                    @livewire('transaction-payments.payment-invoice')
                @elseif ($choose_type_paiement == 6)
                    @livewire('transaction-payments.phone-transaction')
                @elseif ($choose_type_paiement == 7)
                    @livewire('transaction-payments.virement-transaction')
                @elseif ($choose_type_paiement == 8)
                    @livewire('transaction-payments.reload-transaction')
                    <input type="hidden" wire:init="loadChoose3">
                @endif
            @endif
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-bank-created').listen(
                '.Created_wallet_bank',
                (e) => {
                    window.livewire.emit('notifyNewBank:' + e.id);
                },
            )

        });
    </script>
</div>
