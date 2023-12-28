<!-- Modal -->
<div wire:ignore.self id="create-contactList-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ajouter contact</h2>
            </div>
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Nom du contact</label>
                    <input wire:model="firstname" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('firstname')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Prenom du contact</label>
                    <input wire:model="lastname" id="modal-form-1" type="text" class="form-control"
                        placeholder="Prenom">
                    @error('lastname')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Telephone du contact</label>
                    <input wire:model="phone" id="modal-form-1" type="text" class="form-control"
                        placeholder="Prenom">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Email du contact</label>
                    <input wire:model="email" id="modal-form-1" type="text" class="form-control" placeholder="Email">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pays du contact</label>
                    <select wire:model="countrie" id="modal-form-1" class="form-control">
                        <option selected disabled>Selectionnez le pays</option>
                        @foreach ($countries as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('countrie')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Ville du contact</label>
                    <select wire:model="citie" id="modal-form-1" class="form-control">
                        <option selected disabled>Selectionnez la ville</option>
                        @foreach ($cities as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('citie')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$emit('modal-hide')" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="add" type="button" wire:loading.remove=""
                    class="btn btn-primary w-20">Ajouter</button>
                <button wire:loading.delay="" wire:target="add" wire:click="add" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
