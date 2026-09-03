<div class="space-y-4">

    {{-- ── Section Référentiel ─────────────────────────────────────────── --}}
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
        <div class="mb-3 flex items-center gap-2">
            <span class="h-5 w-1 rounded-full bg-primary"></span>
            <span class="text-sm font-semibold text-slate-700">Référentiel</span>
        </div>
        <div class="grid grid-cols-12 gap-3">

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
                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
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
                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type provider — auto --}}
            <div class="col-span-12 md:col-span-4">
                <label class="form-label text-xs font-medium text-slate-500">
                    Type provider
                    <span class="ml-1 rounded bg-slate-200 px-1.5 py-0.5 text-xs text-slate-500">auto</span>
                </label>
                <select wire:model="providerTypeId"
                    class="form-control form-select cursor-not-allowed bg-slate-100 text-slate-400" disabled>
                    <option value="">--</option>
                    @foreach ($providerTypes as $providerType)
                    <option value="{{ $providerType->id }}">{{ $providerType->name }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-400">Rempli automatiquement selon le provider</p>
            </div>

        </div>
    </div>

    {{-- ── Section Paramètres Pixel ─────────────────────────────────────── --}}
    <div class="rounded-xl border border-slate-200 p-4">
        <div class="mb-3 flex items-center gap-2">
            <span class="h-5 w-1 rounded-full bg-warning"></span>
            <span class="text-sm font-semibold text-slate-700">Paramètres Pixel</span>
        </div>
        <div class="grid grid-cols-12 gap-3">

            <div class="col-span-12 md:col-span-3">
                <label class="form-label text-xs font-medium text-slate-600">
                    Opération <span class="text-danger">*</span>
                </label>
                <select wire:model.defer="operationType" class="form-control form-select">
                    <option value="Cash In">Cash In</option>
                    <option value="Cash Out">Cash Out</option>
                </select>
                @error('operationType')
                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-12 md:col-span-3">
                <label class="form-label text-xs font-medium text-slate-600">
                    ID service <span class="text-danger">*</span>
                </label>
                <input wire:model.defer="serviceId" type="number" class="form-control" placeholder="Ex: 150">
                @error('serviceId')
                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-12 md:col-span-3">
                <label class="form-label text-xs font-medium text-slate-600">Frais fixe</label>
                <div class="input-group">
                    <input wire:model.defer="fixedFee" type="number" step="0.01" class="form-control" placeholder="0">
                    <div class="input-group-text text-xs text-slate-500">%</div>
                </div>
                @error('fixedFee')
                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-12 md:col-span-3">
                <label class="form-label text-xs font-medium text-slate-600">
                    Commission <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <input wire:model.defer="commissionPercent" type="number" step="0.01" class="form-control"
                        placeholder="1.90">
                    <div class="input-group-text text-xs text-slate-500">%</div>
                </div>
                @error('commissionPercent')
                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </div>

    {{-- ── Section Zone monétaire ───────────────────────────────────────── --}}
    <div class="rounded-xl border border-slate-200 p-4 mt-6 mb-6">
        <div class="mb-1 flex items-center gap-2 mt-6 ml-6">
            <span class="h-5 w-1 rounded-full bg-indigo-500"></span>
            <span class="text-sm font-semibold text-slate-700">Zone monétaire</span>
            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-500">Transfert Zone CFA</span>
        </div>
        <p class="mb-4 mt-1 text-xs text-slate-400  ml-6">
            Un seul choix possible — activez la zone pour afficher ce service dans l'application mobile.
        </p>

        <div class="grid grid-cols-12 gap-4  ml-6 mb-6">

            {{-- XOF --}}
            <div class="col-span-12 lg:col-span-4">
                <label class="form-label font-medium">
                    Zone XOF
                    <span class="ml-1 text-xs font-normal text-slate-400">UEMOA</span>
                </label>
                <div class="form-switch mt-2">
                    <input id="switch_xof" type="checkbox" wire:model="isXof" class="form-check-input">
                    <label class="input-switch-label" for="switch_xof"></label>
                </div>
                @error('isXof')
                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
                <div class="alert {{ $isXof ? 'alert-success' : 'alert-secondary' }} show mb-0 mt-3 py-2 text-xs" role="alert">
                    {{ $isXof ? 'Actif — CI · SN · ML · BF · BJ · TG · NE · GW' : 'Inactif pour les pays XOF' }}
                </div>
            </div>

            {{-- XAF --}}
            <div class="col-span-12 lg:col-span-4">
                <label class="form-label font-medium">
                    Zone XAF
                    <span class="ml-1 text-xs font-normal text-slate-400">CEMAC</span>
                </label>
                <div class="form-switch mt-2">
                    <input id="switch_xaf" type="checkbox" wire:model="isXaf" class="form-check-input">
                    <label class="input-switch-label" for="switch_xaf"></label>
                </div>
                @error('isXaf')
                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                @enderror
                <div class="alert {{ $isXaf ? 'alert-success' : 'alert-secondary' }} show mb-0 mt-3 py-2 text-xs" role="alert">
                    {{ $isXaf ? 'Actif — CM · CG · GA · CF · TD · GQ' : 'Inactif pour les pays XAF' }}
                </div>
            </div>

        </div>
    </div>

</div>