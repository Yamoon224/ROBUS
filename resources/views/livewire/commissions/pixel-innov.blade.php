<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions Pixel Innov — Structure tarifaire directe
    </h2>
    <p class="text-slate-500 text-sm mt-1 mb-4">
        Un seul niveau de frais appliqué directement (pas de cumul de frais lors des transferts internationaux via Pixel Innov).
    </p>

    {{-- Recherche --}}
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 mb-4 gap-3">
        <div class="w-56 relative text-slate-500">
            <input type="text" wire:model="search" class="form-control w-56 box pr-10"
                placeholder="Rechercher par nom ou pays...">
            <i class="w-4 h-4 absolute my-auto inset-y-0 right-3" data-feather="search"></i>
        </div>

        {{-- Export Excel --}}
        <a href="{{ route('commissions.pixel_innov.export.excel') }}"
            class="btn btn-outline-secondary btn-sm flex items-center gap-1 ml-auto">
            <i data-feather="file-text" class="w-4 h-4"></i> Excel
        </a>

        {{-- Export PDF --}}
        <a href="{{ route('commissions.pixel_innov.export.pdf') }}"
            class="btn btn-outline-danger btn-sm flex items-center gap-1">
            <i data-feather="file" class="w-4 h-4"></i> PDF
        </a>
    </div>

    {{-- Tableau --}}
    <div class="intro-y col-span-12 overflow-x-auto">
        <table class="table table-bordered table-hover table-sm min-w-full">
            <thead class="table-dark">
                <tr>
                    <th class="whitespace-nowrap">Pays</th>
                    <th class="whitespace-nowrap">Service / Opérateur</th>
                    <th class="whitespace-nowrap">Type</th>
                    <th class="whitespace-nowrap text-center">Frais direct (%)</th>
                    <th class="whitespace-nowrap text-center">Frais fixe</th>
                    <th class="whitespace-nowrap text-center">S-Distributeur (%)</th>
                    <th class="whitespace-nowrap text-center">Compagnie (%)</th>
                    <th class="text-center whitespace-nowrap">Statut</th>
                    <th class="text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $item)
                    <tr class="intro-x">
                        <td class="whitespace-nowrap">
                            <span class="font-medium">{{ $item->country->name ?? '—' }}</span>
                        </td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <span class="badge badge-sm bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs">
                                {{ $item->operation_type }}
                            </span>
                        </td>
                        <td class="text-center font-semibold text-primary">
                            {{ number_format($item->commission_percent, 2) }} %
                        </td>
                        <td class="text-center">
                            {{ $item->fixed_fee ? number_format($item->fixed_fee, 0) : '—' }}
                        </td>
                        <td class="text-center">
                            {{ number_format($item->commission_under_pct, 2) }} %
                        </td>
                        <td class="text-center">
                            {{ number_format($item->commission_companie_pct, 2) }} %
                        </td>
                        <td class="text-center">
                            @if($item->status)
                                <span class="text-success font-medium">
                                    <i class="fas fa-unlock-alt w-3 h-3 mr-1"></i>Actif
                                </span>
                            @else
                                <span class="text-danger font-medium">
                                    <i class="fas fa-lock w-3 h-3 mr-1"></i>Inactif
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                @if(Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                    <button wire:click="edit({{ $item->id }})"
                                        class="btn btn-primary btn-sm px-2 py-1">
                                        Modifier
                                    </button>
                                @endif
                                @if(Auth::guard('web')->user()->hasPermissionTo('active-caution'))
                                    <button wire:click="toggleStatus({{ $item->id }})"
                                        class="btn btn-sm px-2 py-1 {{ $item->status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        {{ $item->status ? 'Désactiver' : 'Activer' }}
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-6 text-slate-500">
                            Aucun service Pixel Innov configuré.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $services->links() }}
    </div>

    @include('livewire.commissions.modals.updatePixelInnov')
</div>
