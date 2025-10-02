<!-- Modal -->
<div wire:ignore.self id="create-djamo-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

     @livewire('transferts.transfert-djamo')

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-djamo-created').listen(
                '.Created_wallet_djamo',
                (e) => {
                    window.livewire.emit('notifyNewDjamo:' - e.id);
                },
            )

        });
    </script>
</div>
