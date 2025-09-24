<!-- Modal -->
<div wire:ignore.self id="create-virementBank-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">

    @livewire('transferts.transfert-deposit')
</div>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        Echo.channel('wallet-bank-created').listen('.Created_wallet_bank', (e) => {
            window.livewire.emit('notifyNewBank:' + e.id);
        });
    });
</script>
