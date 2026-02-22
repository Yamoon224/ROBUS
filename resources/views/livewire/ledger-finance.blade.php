<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Header avec actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mt-6">
                Grand Livre Comptable
            </h1>
            <p class="text-gray-500 text-sm">
                Journal des mouvements financiers (Débit / Crédit)
            </p>
        </div>
        <div class="flex gap-2 mt-4 md:mt-0">
            <button wire:click="exportToCsv" class="btn btn-success flex items-center gap-2">
                <i class="fas fa-download"></i> Exporter CSV
            </button>
        </div>
    </div>

    <!-- Statistiques améliorées -->
    <div class="grid grid-cols-12 gap-4 mt-6">
        <!-- Solde net -->
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
            <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Solde net</p>
                        <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($balance, 2) }} FCFA
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-scale-balanced text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Crédits -->
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
            <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Crédits</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ number_format($totalCredits, 2) }} FCFA
                        </p>
                        <p class="text-xs text-gray-400">{{ $creditCount }} opérations</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Débits -->
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
            <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total Débits</p>
                        <p class="text-2xl font-bold text-red-600">
                            {{ number_format($totalDebits, 2) }} FCFA
                        </p>
                        <p class="text-xs text-gray-400">{{ $debitCount }} opérations</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ratio -->
        <div class="col-span-12 sm:col-span-6 lg:col-span-3 intro-y">
            <div class="bg-white rounded-lg shadow p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Ratio C/D</p>
                        <p class="text-2xl font-bold text-indigo-600">
                            @if ($totalDebits > 0)
                                {{ number_format($totalCredits / $totalDebits, 2) }}
                            @else
                                ∞
                            @endif
                        </p>
                    </div>
                    <div class="p-3 bg-indigo-100 rounded-full">
                        <i class="fas fa-chart-pie text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de résumé des commissions (conservées) -->
    <div class="grid grid-cols-12 gap-4 mt-4">
        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y">
            <div class="amount-balance p-4">
                <div class="amount-label text-sm text-white">Total Commissions Admin</div>
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($totalCommissionsAdmin, 2) }} FCFA
                    </span>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y">
            <div class="amount-commission-balance p-4">
                <div class="amount-label text-sm text-white">Commissions Admin Aujourd'hui</div>
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($commissionsAdminToday, 2) }} FCFA
                    </span>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y">
            <div class="amount-balance p-4">
                <div class="amount-label text-sm text-white">Total Commissions Banque</div>
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($totalCommissionsBanque, 2) }} FCFA
                    </span>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 lg:col-span-3 intro-y">
            <div class="amount-commission-balance p-4">
                <div class="amount-label text-sm text-white">Commissions Banque Aujourd'hui</div>
                <div class="flex items-baseline justify-between">
                    <span class="amount-balance-value text-xl font-semibold">
                        {{ number_format($commissionsBanqueToday, 2) }} FCFA
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres avancés -->
    <div class="grid grid-cols-12 gap-4 mt-5 bg-white p-4 rounded-lg shadow">
        <!-- Ligne 1: Dates rapides -->
        <div class="col-span-12 ">
            <label class="block text-sm font-medium text-gray-700 mb-2 mr-6 ml-6 mt-3">Période rapide</label>
            <div class="flex flex-wrap gap-3 mx-6">

                @php
                    $ranges = [
                        'today' => "Aujourd'hui",
                        'yesterday' => 'Hier',
                        'this_week' => 'Cette semaine',
                        'last_week' => 'Semaine dernière',
                        'this_month' => 'Ce mois',
                        'last_month' => 'Mois dernier',
                        'this_year' => 'Cette année',
                        'custom' => 'Personnalisé',
                    ];
                @endphp

                @foreach ($ranges as $key => $label)
                    <button wire:click="$set('dateRange', '{{ $key }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200
                focus:outline-none focus:ring-2 focus:ring-blue-400
                {{ $dateRange === $key
                    ? 'bg-blue-600 text-orange-600 shadow-md scale-105'
                    : 'bg-gray-100 text-black hover:bg-blue-100 hover:text-blue-600' }}">
                        {{ $label }}
                    </button>
                @endforeach

            </div>
        </div>

        <!-- Ligne 2: Dates personnalisées -->
        <div class="col-span-12 md:col-span-6 lg:col-span-3  ml-6">
            <label class="block text-sm font-medium text-gray-700">Date de début</label>
            <input type="date" class="form-control w-full mt-1" wire:model="startDate">
        </div>
        <div class="col-span-12 md:col-span-6 lg:col-span-3 ">
            <label class="block text-sm font-medium text-gray-700">Date de fin</label>
            <input type="date" class="form-control w-full mt-1" wire:model="endDate">
        </div>

        <!-- Ligne 3: Recherche et filtres -->
        <div class="col-span-12 md:col-span-6 lg:col-span-3">
            <label class="block text-sm font-medium text-gray-700">Recherche</label>
            <input type="text" class="form-control w-full mt-1" wire:model.debounce.300ms="search"
                placeholder="Transaction, description...">
        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-3 mr-6">
            <label class="block text-sm font-medium text-gray-700">Type de mouvement</label>
            <select class="form-select w-full mt-1" wire:model="movementType">
                <option value="all">Tous</option>
                <option value="credit">Crédit uniquement</option>
                <option value="debit">Débit uniquement</option>
            </select>
        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-3 ml-6">
            <label class="block text-sm font-medium text-gray-700">Catégorie</label>
            <select class="form-select w-full mt-1" wire:model="typeFilter">
                <option value="all">Toutes</option>
                <option value="commission">Commissions Banque</option>
                <option value="admin">Commissions Admin</option>
                <option value="standard">Transactions standard</option>
            </select>
        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-3">
            <label class="block text-sm font-medium text-gray-700">Montant min (FCFA)</label>
            <input type="number" class="form-control w-full mt-1" wire:model="minAmount" placeholder="0">
        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-3">
            <label class="block text-sm font-medium text-gray-700">Montant max (FCFA)</label>
            <input type="number" class="form-control w-full mt-1" wire:model="maxAmount" placeholder="1000000">
        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-3 mr-6">
            <label class="block text-sm font-medium text-gray-700">Affichage par page</label>
            <select class="form-select w-full mt-1" wire:model="perPage">
                <option value="20">20 lignes</option>
                <option value="50">50 lignes</option>
                <option value="100">100 lignes</option>
                <option value="200">200 lignes</option>
            </select>
        </div>

        <div class="col-span-12 md:col-span-6 lg:col-span-3 flex items-end ml-6 mb-6">
            <button wire:click="$set('dateRange', 'today')" class="btn btn-secondary w-full">
                <i class="fas fa-undo mr-2"></i> Réinitialiser
            </button>
        </div>
    </div>

    <!-- Tableau des transactions -->
    <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto">
        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                <div class="items-center px-5 py-3 border-b border-slate-200/60">
                    <div class="flex justify-between items-center">
                        <h2 class="font-medium text-base">
                            Journal des mouvements financiers ({{ $gains->total() }} entrées)
                        </h2>
                        <div class="text-sm text-gray-600">
                            Période: {{ $startDate ? Carbon\Carbon::parse($startDate)->format('d/m/Y') : '...' }}
                            - {{ $endDate ? Carbon\Carbon::parse($endDate)->format('d/m/Y') : '...' }}
                        </div>
                    </div>
                </div>
                <div class="tiny-slider py-5">
                    <div class="px-5">
                        <div class="overflow-x-auto">
                            <table id="table_orders" class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="border-b-2 whitespace-nowrap cursor-pointer hover:bg-gray-600"
                                            wire:click="sortBy('transaction_id')">
                                            Transaction
                                            @if ($sortField === 'transaction_id')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </th>
                                        <th class="border-b-2 whitespace-nowrap cursor-pointer hover:bg-gray-600"
                                            wire:click="sortBy('description')">
                                            Description
                                            @if ($sortField === 'description')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </th>
                                        <th class="border-b-2 text-center whitespace-nowrap cursor-pointer hover:bg-gray-600"
                                            wire:click="sortBy('debit')">
                                            Débit
                                            @if ($sortField === 'debit')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </th>
                                        <th class="border-b-2 text-center whitespace-nowrap cursor-pointer hover:bg-gray-600"
                                            wire:click="sortBy('credit')">
                                            Crédit
                                            @if ($sortField === 'credit')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </th>
                                        <th class="border-b-2 text-center whitespace-nowrap cursor-pointer hover:bg-gray-600"
                                            wire:click="sortBy('created_at')">
                                            Date
                                            @if ($sortField === 'created_at')
                                                <i
                                                    class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                            @endif
                                        </th>
                                        <th class="border-b-2 text-center whitespace-nowrap">
                                            Type
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($gains->count() > 0)
                                        @foreach ($gains as $item)
                                            @php
                                                $type = $item->adminCom->type ?? 'UNKNOWN';
                                                $isCommission = $item->is_comission;
                                                $isAdmin = $item->is_admin;
                                            @endphp
                                            <tr class="intro-x hover:bg-gray-100">
                                                <td class="text-center">
                                                    <span
                                                        class="px-3 py-2 bg-primary text-white rounded font-medium text-sm">
                                                        {{ $item->transaction_id ?? 'N/A' }}
                                                    </span>
                                                </td>

                                                <td class="text-center">
                                                    {{ $item->description }}
                                                    @if ($isCommission || $isAdmin)
                                                        <br>
                                                        <small class="text-gray-500">
                                                            ({{ $isCommission ? 'Commission' : '' }}
                                                            {{ $isAdmin ? 'Admin' : '' }})
                                                        </small>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if ($item->debit > 0)
                                                        <span class="text-red-600 font-semibold">
                                                            -{{ number_format($item->debit, 2) }} FCFA
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if ($item->credit > 0)
                                                        <span class="text-green-600 font-semibold">
                                                            +{{ number_format($item->credit, 2) }} FCFA
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                                    <br>
                                                    <small class="text-gray-500">
                                                        {{ $item->created_at->diffForHumans() }}
                                                    </small>
                                                </td>

                                                <td class="text-center">
                                                    @if ($isCommission)
                                                        <span
                                                            class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                                            Commission
                                                        </span>
                                                    @elseif($isAdmin)
                                                        <span
                                                            class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs">
                                                            Admin
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                                            Standard
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center py-8">
                                                <div class="text-gray-500">
                                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                                    <p>Aucune donnée pour la période sélectionnée</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <!-- Ligne de totaux -->
                                @if ($gains->count() > 0)
                                    <tfoot class="bg-gray-200 font-bold">
                                        <tr>
                                            <td colspan="2" class="text-right">TOTAUX:</td>
                                            <td class="text-center text-red-600">
                                                {{ number_format($gains->sum('debit'), 2) }} FCFA
                                            </td>
                                            <td class="text-center text-green-600">
                                                {{ number_format($gains->sum('credit'), 2) }} FCFA
                                            </td>
                                            <td colspan="2" class="text-center">
                                                Solde: <span
                                                    class="{{ $gains->sum('credit') - $gains->sum('debit') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($gains->sum('credit') - $gains->sum('debit'), 2) }}
                                                    FCFA
                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div
                class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-between mt-6 mb-6">
                <div class="text-sm text-gray-600">
                    Affichage de {{ $gains->firstItem() ?? 0 }} à {{ $gains->lastItem() ?? 0 }} sur
                    {{ $gains->total() }} entrées
                </div>
                <nav class="w-full sm:w-auto">
                    <ul class="pagination">
                        {{ $gains->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
