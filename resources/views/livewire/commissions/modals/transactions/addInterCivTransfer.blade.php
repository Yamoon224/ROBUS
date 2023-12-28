<!-- Modal -->
<div wire:ignore.self id="create-interCivTransfers-modal" class="modal  hide  overflow-y-auto" data-tw-backdrop="static"
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


            <div class="text-center mt-3">
                <span
                    class="alert alert-pending inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                    Paiement money Internaional
                </span>

            </div>
            @livewire('transaction-inters.transaction-civ')
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
