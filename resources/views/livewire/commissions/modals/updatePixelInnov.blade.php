<div id="update-pixel-modal" class="modal" tabindex="-1" aria-hidden="true"
    x-data="{}"
    x-on:update-pixel-modal.window="$el.classList.add('show'); $el.style.display = 'block';"
    x-on:modal-hide.window="$el.classList.remove('show'); $el.style.display = 'none';">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Modifier la commission Pixel Innov
                </h2>
            </div>
            <div class="modal-body p-0">
                <div class="p-5">
                    <div class="text-slate-500 font-semibold mb-3">
                        {{ $serviceName ?? '' }}
                        @if($serviceOperationType ?? null)
                            <span class="text-xs text-primary ml-2">({{ $serviceOperationType }})</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Frais direct (%)</label>
                            <input wire:model="serviceCommissionPercent"
                                type="number" step="0.01" min="0" max="100"
                                class="form-control @error('serviceCommissionPercent') border-danger @enderror"
                                placeholder="ex: 1.90">
                            @error('serviceCommissionPercent')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Frais fixe (montant)</label>
                            <input wire:model="serviceFixedFee"
                                type="number" step="0.01" min="0"
                                class="form-control"
                                placeholder="0">
                        </div>
                        <div>
                            <label class="form-label">Commission S-distributeur (%)</label>
                            <input wire:model="serviceUnderPct"
                                type="number" step="0.01" min="0" max="100"
                                class="form-control @error('serviceUnderPct') border-danger @enderror"
                                placeholder="0">
                            @error('serviceUnderPct')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Commission Compagnie (%)</label>
                            <input wire:model="serviceCompaniePct"
                                type="number" step="0.01" min="0" max="100"
                                class="form-control @error('serviceCompaniePct') border-danger @enderror"
                                placeholder="0">
                            @error('serviceCompaniePct')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3 p-3 bg-slate-100 rounded-md text-xs text-slate-500">
                        <i data-feather="info" class="w-3 h-3 inline mr-1"></i>
                        Structure tarifaire directe : un seul niveau de frais appliqué au transfert Pixel Innov.
                        Les frais direct (%) sont les seuls frais facturés au client final.
                    </div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" class="btn btn-outline-secondary w-24 mr-1" wire:click="resetFields"
                        x-on:click="$dispatch('modal-hide')">
                        Annuler
                    </button>
                    <button type="button" class="btn btn-primary w-24" wire:click="update">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
