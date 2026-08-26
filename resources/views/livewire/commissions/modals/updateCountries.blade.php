<div wire:ignore.self id="update-countrie-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ajouter la devise & Indecatif</h2>
            </div>


            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Devise <span class="text-danger">
                            *</span></label>
                    <select wire:model.defer="currencie" id="modal-form-1" class="form-control">
                        <option selected disabled>Selectionnez la devise</option>
                        @foreach ($currencies as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('currencie')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Indecatif<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="countrie_indic" id="modal-form-1" type="text" class="form-control">
                    @error('countrie_indic')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Plafond avec piece<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="ceiling" id="modal-form-1" type="number" class="form-control"
                        placeholder="Plafond">
                    @error('ceiling')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-3">
                    <label for="modal-form-1" class="form-label">Plafond sans piece<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="silver_no_identity" id="modal-form-1" type="number" class="form-control"
                        placeholder="Plafond">
                    @error('silver_no_identity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6 mt-3">
                    <label for="modal-form-1" class="form-label">Motant maxi envoyé<span class="text-danger">
                            *</span></label>
                    <input wire:model.defer="amount_max_send" id="modal-form-1" type="number" class="form-control"
                        placeholder="Motant Maxi">
                    @error('amount_max_send')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>




            </div>

            <div class="modal-footer">
                <button wire:click="resetField" type="button" class="btn btn-danger-soft w-20 mr-1">Annuler</button>
                <button wire:click="update" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Ajouter</button>
                <button wire:loading.delay wire:target="update" wire:click="update" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', () => {
        function initTomSelect() {
            const selectEl = document.getElementById('operator_id');
            const modal = document.getElementById('update-countrie-modal');

            if (selectEl && !selectEl.tomselect) {
                new TomSelect(selectEl, {
                    plugins: ['remove_button'],
                    placeholder: 'Sélectionnez les opérateurs',
                    maxItems: null,
                    create: false,
                    dropdownParent: modal.querySelector(
                        '.modal-content') // ✅ garde la liste DANS le modal
                });
            }
        }

        // Initialisation au chargement
        initTomSelect();

        // Réinitialiser après chaque rendu Livewire
        Livewire.hook('message.processed', (message, component) => {
            initTomSelect();
        });
    });
</script>
