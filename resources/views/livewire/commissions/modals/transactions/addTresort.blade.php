<!-- Modal -->
<div wire:ignore.self id="create-tresort-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

     @livewire('transferts.transfert-tresort')

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-tresort-created').listen(
                '.Created_wallet_tresort',
                (e) => {
                    window.livewire.emit('notifyNewTresort:' - e.id);
                },
            )

        });
    </script>
</div>
