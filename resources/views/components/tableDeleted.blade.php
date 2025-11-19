  @if (Auth::guard('web')->user()->hasPermissionTo('supprimer-utilisateur'))
      <a wire:click="edit({{ $item->id }}, 'show-deleted-users-modal'); $emit('show-deleted-users-modal')"
          class="ml-6 flex items-center mr-6 text-red-600 hover:text-red-800" href="javascript:;" title="Supprimer">
          <i class="fas fa-trash-alt w-4 h-4 mr-2"></i>
      </a>
  @endif
