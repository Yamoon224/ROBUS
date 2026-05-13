@extends('layouts.master')

@section('title', 'Statistiques des COM ZONES')

@section('content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Statistiques des COM ZONES</h2>
        <a href="{{ route('comzones.admins.browse') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5">
            <div class="text-slate-500">Utilisateurs liés</div>
            <div class="text-2xl font-medium mt-2">{{ $totals['linked_users_count'] }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5">
            <div class="text-slate-500">Transactions</div>
            <div class="text-2xl font-medium mt-2">{{ $totals['transactions_count'] }}</div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5">
            <div class="text-slate-500">Montant total</div>
            <div class="text-2xl font-medium mt-2">
                {{ number_format($totals['transactions_amount'], 2, ',', ' ') }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-3 box p-5">
            <div class="text-slate-500">Commission COM ZONE</div>
            <div class="text-2xl font-medium mt-2 text-success">
                {{ number_format($totals['commission'], 2, ',', ' ') }}
            </div>
        </div>
    </div>

    <div class="intro-y box p-5 mt-5">
        <div class="overflow-auto">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center whitespace-nowrap">COM ZONE</th>
                        <th class="text-center whitespace-nowrap">Pays</th>
                        <th class="text-center whitespace-nowrap">Utilisateurs liés</th>
                        <th class="text-center whitespace-nowrap">Transactions</th>
                        <th class="text-center whitespace-nowrap">Montant</th>
                        <th class="text-center whitespace-nowrap">Commission</th>
                        <th class="text-center whitespace-nowrap">Services</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($statistics as $item)
                        @php
                            $comZone = $item['com_zone'];
                            $stats = $item['stats'];
                        @endphp
                        <tr>
                            <td>
                                <div class="font-medium whitespace-nowrap">
                                    {{ $comZone->firstname }} {{ $comZone->lastname }}
                                </div>
                                <div class="text-slate-500 text-xs mt-1">{{ $comZone->phone ?? $comZone->email }}</div>
                            </td>
                            <td class="text-center">{{ $comZone->country->name ?? 'Non renseigné' }}</td>
                            <td class="text-center">{{ $stats['linked_users_count'] }}</td>
                            <td class="text-center">{{ $stats['transactions_count'] }}</td>
                            <td class="text-center">{{ number_format($stats['transactions_amount'], 2, ',', ' ') }}</td>
                            <td class="text-center text-success font-medium">
                                {{ number_format($stats['commission'], 2, ',', ' ') }}
                            </td>
                            <td>
                                @forelse ($stats['services'] as $service)
                                    <div class="mb-3 p-2 border border-slate-200 rounded">
                                        <div class="font-medium">{{ $service['name'] }}</div>
                                        <div class="text-slate-500 text-xs mt-1">
                                            Tx: {{ $service['transactions_count'] }}
                                            | Montant: {{ number_format($service['transactions_amount'], 2, ',', ' ') }}
                                            | Commission: {{ number_format($service['commission'], 2, ',', ' ') }}
                                        </div>
                                        <div class="text-slate-500 text-xs mt-1">
                                            Règle: {{ $service['numbers_rule'] ?? 0 }} tx /
                                            {{ number_format((float) ($service['amount_rule'] ?? 0), 2, ',', ' ') }}
                                        </div>
                                    </div>
                                @empty
                                    <span class="text-slate-500 text-sm">Aucun service configuré</span>
                                @endforelse
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">Aucune COM ZONE trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
