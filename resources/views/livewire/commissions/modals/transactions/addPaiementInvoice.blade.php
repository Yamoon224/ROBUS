<!-- Modal -->

<div wire:ignore.self id="create-paiementInvoice-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

    @livewire('transferts.transfert-payment')

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-paiement-created').listen(
                '.Created_wallet_paiement',
                (e) => {
                    window.livewire.emit('notifyNewPaiement:' + e.id);
                },
            )

        });
    </script>
</div>
