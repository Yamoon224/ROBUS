<div>

    <a wire:click="edit({{ $amount }}, 'show-drawals-modal'); $emit('show-drawals-modal')"
        class="btn btn-sm text-dark bg-white border-white" title="Modifier" style="color: black;" href="javascript:;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6 mr-3">
            <rect x="2" y="5" width="20" height="14" rx="2" ry="2" stroke-linecap="round"
                stroke-linejoin="round" />
            <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                stroke-linejoin="round" />
            <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                stroke-linejoin="round" />
            <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        Retrait
    </a>

</div>

@include('livewire.modals.underDrawal')
<style>
    .modal {
        position: fixed;
        /* Assurez-vous que le modal est bien fixe */
        z-index: 1050;
        /* Assurez-vous que cette valeur est supérieure aux autres éléments */
    }

    .modal-backdrop {
        z-index: 1040;
        /* L'arrière-plan doit être légèrement inférieur au modal */
    }
</style>
