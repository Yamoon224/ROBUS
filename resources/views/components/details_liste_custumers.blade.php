{{-- resources/views/components/details_liste_custumers.blade.php --}}
@props([
    'title' => 'Liste',
    'users' => null,
    'total' => 0,
    'routePrefix' => 'custumers',
    'showActions' => true
])

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ $title }} ({{ $total }})</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover -mt-2 -intro-x">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                        <th class="text-center whitespace-nowrap">Email & Solde</th>
                        <th class="text-center whitespace-nowrap">Status compte & doc</th>
                        <th class="text-center whitespace-nowrap">Status Wallet</th>
                        <th class="text-center whitespace-nowrap">Statut utilisateur</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $item)
                    <tr class="intro-x">
                        <td class="w-60">
                            <div class="mb-3">
                                @include('components.infoUserDetails', ['user' => $item])
                            </div>
                            @include('components.infoDetailsUser', ['user' => $item])
                        </td>
                        
                        <td class="w-60">
                            @include('components.solde_color', ['user' => $item])
                        </td>
                        
                        <td class="w-48 px-4 py-3">
                            <div class="flex flex-col space-y-3">
                                {{-- Badge Statut du compte --}}
                                @if ($item->is_active == '1')
                                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-success border border-green-200 text-green-700 text-sm font-medium shadow-sm">
                                    <i class="fas fa-check-circle text-green-500 mr-2 text-success"></i>
                                    <span class="text-white">Compte activé</span>
                                </div>
                                @else
                                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-pending border border-red-200 text-red-700 text-sm font-medium shadow-sm">
                                    <i class="fas fa-ban text-red-500 mr-2 text-base"></i>
                                    <span class="text-white">Compte désactivé</span>
                                </div>
                                @endif

                                {{-- Badge Statut KYC --}}
                                @if ($item->kyc && $item->kyc->status == '1')
                                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-success border border-emerald-200 text-emerald-700 text-sm font-medium shadow-sm">
                                    <i class="fas fa-shield-check text-success mr-2 text-base"></i>
                                    <span class="text-white">Documents validés</span>
                                </div>
                                @else
                                <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-pending border border-amber-200 text-amber-700 text-sm font-medium shadow-sm">
                                    <i class="fas fa-clock text-amber-500 mr-2 text-white"></i>
                                    <span class="text-white">Documents en attente</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        
                        <td class="w-40">
                            @if ($item->wallet)
                                @if ($item->wallet->status == '1')
                                <div class="flex items-center justify-center">
                                    <a class="inline-flex items-center px-3 py-1.5 rounded-full bg-success text-white border border-emerald-200 text-sm font-medium shadow-sm"
                                       href="#">
                                        <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                    </a>
                                </div>
                                @else
                                <div class="flex items-center justify-center">
                                    <a class="flex items-center mr-3 text-danger"
                                       href="#">
                                        <i class="fas fa-lock w-4 h-4 mr-2"></i> Désactivé
                                    </a>
                                </div>
                                @endif
                            @else
                                <div class="text-center text-muted">
                                    <span class="text-xs">Wallet non créé</span>
                                </div>
                            @endif
                        </td>
                        
                        <td class="w-40">
                            @if ($item->status == '1')
                                @if (Auth::guard('web')->user()->hasPermissionTo('active-clients'))
                                <div class="flex items-center justify-center">
                                    <a class="flex items-center mr-3 text-success"
                                       href="{{ route("{$routePrefix}.activate", ['id' => $item->id]) }}"
                                       data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                       onclick="promptAlert(this)">
                                        <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                    </a>
                                </div>
                                @endif
                            @else
                                @if (Auth::guard('web')->user()->hasPermissionTo('desactive-client'))
                                <div class="flex items-center justify-center">
                                    <a class="flex items-center mr-3 text-danger"
                                       href="{{ route("{$routePrefix}.activate", ['id' => $item->id]) }}"
                                       data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                       onclick="promptAlert(this)">
                                        <i class="fas fa-lock w-4 h-4 mr-2"></i> Désactivé
                                    </a>
                                </div>
                                @endif
                            @endif
                        </td>
                        
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                @if (Auth::guard('web')->user()->hasPermissionTo('voir-clients'))
                                <a class="flex items-center mr-3" href="{{ route("{$routePrefix}.show", ['id' => $item->id]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        icon-name="eye" class="lucide lucide-eye block mx-auto h-15 mr-1">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="alert alert-info mb-0">
                                Aucun {{ strtolower($title) }} trouvé
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($users && method_exists($users, 'links') && $users->hasPages())
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <div>
                <select class="form-control form-control-sm" style="width: auto;" 
                        onchange="window.location.href = this.value">
                    @foreach([10, 25, 50, 100] as $size)
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => $size]) }}" 
                                {{ request()->input('per_page', 10) == $size ? 'selected' : '' }}>
                            {{ $size }} par page
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                {{ $users->appends(request()->query())->links() }}
            </div>
            <div class="text-muted small">
                Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} 
                sur {{ $total }} résultat(s)
            </div>
        </div>
        @endif
    </div>
</div>