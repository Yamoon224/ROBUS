<!-- Modal -->
<div wire:ignore.self id="create-phoneCredit-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">

     @livewire('transferts.transfert-phone-credit')

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-phone-created').listen(
                '.Created_wallet_phone',
                (e) => {
                    window.livewire.emit('notifyNewPhone:' - e.id);
                },
            )

        });
    </script>
</div>
