<div class="grid grid-cols-12 gap-4">

    {{-- Section Référentiel --}}
    <div class="col-span-12">
        <div class="p-4 border border-slate-200 rounded-lg bg-slate-50">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-5 bg-primary rounded-full"></span>
                <span class="font-semibold text-slate-700">Référentiel</span>
            </div>
            <div class="grid grid-cols-12 gap-4">

                {{-- Pays --}}
                <div class="col-span-12 md:col-span-4">
                    <label class="form-label text-xs font-medium text-slate-600">
                        Pays <span class="text-danger">*</span>
                    </label>
                    <select wire:model.defer="countryId" class="form-control form-select">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    @error('countryId')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Provider --}}
                <div class="col-span-12 md:col-span-4">
                    <label class="form-label text-xs font-medium text-slate-600">
                        Provider <span class="text-danger">*</span>
                    </label>
                    <select wire:model="providerId" class="form-control form-select">
                        <option value="">-- Sélectionner --</option>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                        @endforeach
                    </select>
                    @error('providerId')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type provider — auto-rempli, non éditable --}}
                <div class="col-span-12 md:col-span-4">
                    <label class="form-label text-xs font-medium text-slate-500">
                        Type provider
                        <span class="ml-1 px-1.5 py-0.5 text-xs bg-slate-200 text-slate-500 rounded">auto</span>
                    </label>
                    <select wire:model="providerTypeId" class="form-control form-select bg-slate-100 text-slate-400 cursor-not-allowed" disabled>
                        <option value="">--</option>
                        @foreach ($providerTypes as $providerType)
                            <option value="{{ $providerType->id }}">{{ $providerType->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-slate-400 text-xs mt-1">Rempli automatiquement selon le provider</p>
                </div>

            </div>
        </div>
    </div>

    {{-- Section Paramètres Pixel --}}
    <div class="col-span-12">
        <div class="p-4 border border-slate-200 rounded-lg">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-5 bg-warning rounded-full"></span>
                <span class="font-semibold text-slate-700">Paramètres Pixel</span>
            </div>
            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label text-xs font-medium text-slate-600">
                        Opération <span class="text-danger">*</span>
                    </label>
                    <select wire:model.defer="operationType" class="form-control form-select">
                        <option value="Cash In">Cash In</option>
                        <option value="Cash Out">Cash Out</option>
                    </select>
                    @error('operationType')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label text-xs font-medium text-slate-600">
                        ID service <span class="text-danger">*</span>
                    </label>
                    <input wire:model.defer="serviceId" type="number" class="form-control" placeholder="Ex: 150">
                    @error('serviceId')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label text-xs font-medium text-slate-600">Frais fixe</label>
                    <div class="input-group">
                        <input wire:model.defer="fixedFee" type="number" step="0.01" class="form-control" placeholder="0">
                        <div class="input-group-text text-slate-500 text-xs">%</div>
                    </div>
                    @error('fixedFee')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-12 md:col-span-3">
                    <label class="form-label text-xs font-medium text-slate-600">
                        Commission <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input wire:model.defer="commissionPercent" type="number" step="0.01" class="form-control" placeholder="1.90">
                        <div class="input-group-text text-slate-500 text-xs">%</div>
                    </div>
                    @error('commissionPercent')
                        <p class="text-danger text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
    </div>

</div>
