<div wire:ignore.self id="update-provider-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Modifier operateur</h2>
            </div>
            <a onclick="close_give()"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body-transfers-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label class="form-label">Logo actuel</label>
                    <div class="flex items-center gap-4 mb-2">
                        @if (!empty($currentLogo))
                            <img src="{{ Str::startsWith($currentLogo, 'http') ? $currentLogo : asset($currentLogo) }}"
                                 alt="logo actuel"
                                 class="w-16 h-16 object-contain rounded border border-slate-200 bg-slate-50 p-1">
                        @else
                            <div class="w-16 h-16 flex items-center justify-center rounded border border-dashed border-slate-300 bg-slate-50 text-slate-400 text-xs text-center">
                                Aucun logo
                            </div>
                        @endif
                        <div class="flex-1">
                            <label class="form-label text-xs text-slate-500">Changer le logo (PNG, JPG, SVG — max 2 Mo)</label>
                            <input wire:model="image" type="file" accept="image/*"
                                   class="form-control form-control-sm">
                            @error('image')
                                <span class="text-danger text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if ($image)
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-slate-500">Aperçu :</span>
                            <img src="{{ $image->temporaryUrl() }}"
                                 class="w-12 h-12 object-contain rounded border border-green-200 bg-green-50 p-1"
                                 alt="aperçu">
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom</label>
                    <input wire:model.defer="providerName" id="modal-form-1" type="text" class="form-control"
                        placeholder="Nom">
                    @error('providerName')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-6">
                    <label for="countrys" class="form-label">Pays</label>
                    {{-- @php
                        $old_countries = $check->id;
                        dd($old_countries);
                    @endphp --}}
                    <select wire:model.defer="countrys" id="countrys" class="form-control">
                        <option selected disabled>Selectionnez le pays</option>
                        @foreach ($countries as $item)
                            <option value="{{ $item->id }}" >
                                {{ $item->name }}</option>

                            {{-- <option value="{{ $item->id }}">{{ $item->name }}</option> --}}
                        @endforeach
                    </select>
                    @error('country')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="providerType" class="form-label">Type operateur</label>
                    <select wire:model.defer="providerType" id="providerType" class="form-control">
                        <option selected disabled>Selectionnez le type</option>
                        @foreach ($providerTypes as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('providerType')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @if ($providerId == 13 || $providerId == 14 || $providerId == 15 || $providerId == 16 || $providerId == 17)
                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="modal-form-1" class="form-label">Montant</label>
                        <input wire:model.defer="provider_amount" id="modal-form-1" type="text" class="form-control"
                            placeholder="Montant">
                        @error('provider_amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Description</label>
                    <textarea wire:model.defer="providerDescription" id="modal-form-1" type="text" class="form-control" cols="15"
                        rows="5">
                    </textarea>

                    @error('providerDescription')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="resetField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="update" type="submit" wire:loading.remove
                    class="btn btn-primary w-20">Modifier</button>
                <button wire:loading.delay wire:target="update" wire:click="update" type="button"
                    class="btn btn-warning w-40"> Traitement ... </button>
            </div>
        </div>
    </div>
</div>
