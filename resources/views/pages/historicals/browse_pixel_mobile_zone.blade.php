@extends('layouts.master')

@section('title', 'Historique Transfert Mobile Zone')

@section('content')
    @php $vue = request('vue', 'wallet'); @endphp
    <div>
        <div class="intro-y flex flex-wrap gap-2 mt-2">
            <a href="{{ request()->url() }}?vue=wallet"
                class="btn {{ $vue === 'wallet' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Opérations wallet (tous pays)
            </a>
            <a href="{{ request()->url() }}?vue=transferts"
                class="btn {{ $vue === 'transferts' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Transferts mobile à mobile
            </a>
        </div>

        @if ($vue === 'transferts')
            <livewire:historical.pixel-mobile-zone-histo>
        @else
            <h2 class="intro-y text-lg font-medium mt-5">Historique Mobile Zone & International Zone — tous les clients</h2>
            <livewire:historical.pixel-wallet-histo>
        @endif
    </div>
@endsection
