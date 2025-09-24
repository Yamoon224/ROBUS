<!-- Modal -->
<div wire:ignore.self id="create-reloadCards-modal" class="modal  hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">

     @livewire('transferts.transfert-reload-card')


    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-card-created').listen(
                '.Created_wallet_card',
                (e) => {
                    window.livewire.emit('notifyNewCard:' + e.id);
                },
            )

        });
    </script>
</div>
