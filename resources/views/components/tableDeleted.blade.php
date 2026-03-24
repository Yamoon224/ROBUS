@if (Auth::guard('web')->user()->hasPermissionTo('supprimer-utilisateur'))
    <a wire:click="edit({{ $item->id }}, 'show-deleted-users-modal'); $emit('show-deleted-users-modal')"
        class="ml-6 flex items-center mr-6 text-red-600 hover:text-red-800 transition" href="javascript:;"
        title="Supprimer">

        <!-- SVG Trash -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">

            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6 7h12M9 7V4h6v3m-7 4v6m4-6v6m4-6v6M5 7h14l-1 14H6L5 7z" />
        </svg>

    </a>
@endif
