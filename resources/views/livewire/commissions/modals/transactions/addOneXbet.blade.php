<!-- Modal -->
<div wire:ignore.self id="create-xbet-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

     @livewire('transferts.transfert-xbet')

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-xbet-created').listen(
                '.Created_wallet_xbet',
                (e) => {
                    window.livewire.emit('notifyNewXbet:' - e.id);
                },
            )

        });
    </script>
</div>
