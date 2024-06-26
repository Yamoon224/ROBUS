<!-- BEGIN: Disable Confirmation Modal -->
<div wire:ignore.self id="connected-master-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Selectionne le MASTER<span class="text-danger">
                            *</span></label>
                    {{-- Valeur MASTERS --}}

                    @php
                        $masters = App\Models\Sid\SidUser::whereIsMaster(true)
                            ->where('country_id', $this->user_country)
                            // ->where('city_id', $this->user_xcity)
                            ->get();
                    @endphp

                    <select wire:model="master_selected" id="modal-form-1" class="form-control">
                        <option value="null" disabled>Sélectionnez le master</option>
                        @if ($masters->isNotEmpty())
                            @foreach ($masters as $master)
                                <option value="{{ $master->id }}">{{ $master->firstname . ' ' . $master->lastname }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Aucun master disponible</option>
                        @endif
                    </select>
                    @error('master_selected')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>
            <div class="modal-body p-0">

                <div class="px-5 pb-8 text-center mt-6">
                      <button wire:click="cancelConnectedField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>

                    <input wire:click="connectedOneMaster" wire:loading.remove type="button"
                        class="btn btn-danger  w-25 mr-1 mb-1"
                        value="{{ $underdistributeurDesabled == 0 ? 'Lié au MASTER' : 'Désactiver du MASTER' }}" />

                    <div wire:loading wire:target="connectedOneMaster">
                        Traitement en cours...
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    Livewire.on('loadMasters', function() {
        @this.loadMasters();
    });
</script>
<script>
    document.addEventListener('livewire:load', function() {
        window.livewire.emit('loadMasters');
    });
</script>
<!-- END: Disable Confirmation Modal -->
