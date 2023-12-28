<!-- Modal -->
<div wire:ignore.self id="create-provider-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Ajouter Operateur</h2>
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
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    @if ($image)
                        <img class="object-none object-center bg-yellow-300 w-30 h-30"
                            src="{{ $image->temporaryUrl() ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                            alt="image"wire:model="image">
                    @else
                        <img class="object-none object-center bg-yellow-300 w-30 h-30"
                            src="{{ asset('public/dist/images/food-beverage-1.jpg') }}"
                            alt="image"wire:model="image">
                    @endif
                    <div class="mx-auto cursor-pointer relative mt-5">
                        <input id="imageUpload" type="file" wire:model="image" placeholder="Photo"
                            accept="image/png, image/jpeg">
                        <div wire:loading wire:target="image">Uploading...</div>
                    </div>
                </div>
            </div>
            {{-- <div class="mb-3">
                <label class="form-label">Image Upload</label>
                <input type="file" class="form-control" wire:model="image" multiple>
                <div wire:loading wire:target="image">Uploading...</div>
                @error('images.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div> --}}
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="modal-form-1" class="form-label">Nom operateur</label>
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
                    <select wire:model.defer="countrys" id="countrys" class="tom-select w-full" multiple>
                        <option selected disabled></option>
                        @foreach ($countries as $item)
                            <option value="{{ $item->id }}" @if ($item->id === old('countrys')) selected @endif>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>

                    {{-- <select id="product_id" name="product_id[]" data-placeholder="Selectionne les produits"
                        class="tom-select w-full @error('product_id') is-invalid @enderror" multiple>
                        <option disabled selected></option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if ($product->id === old('product_id')) selected @endif>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select> --}}
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
            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12">
                    <label for="providerDescription" class="form-label">Description</label>
                    <textarea wire:model.defer="providerDescription" id="providerDescription" type="text" class="form-control" cols="15"
                        rows="5">
                    </textarea>

                    @error('providerDescription')
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
