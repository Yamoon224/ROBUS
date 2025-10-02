<!-- Modal -->
<div wire:ignore.self id="create-versement-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

     @livewire('transferts.transfert-versement')

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-versement-created').listen(
                '.Created_wallet_versement',
                (e) => {
                    window.livewire.emit('notifyNewVersement:' - e.id);
                },
            )

        });
    </script>
</div>
