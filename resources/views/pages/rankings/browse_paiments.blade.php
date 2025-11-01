@extends('layouts.master')

@section('title', 'Liste des classements des paiements')

@section('content')
    <div>
        <livewire:rankings.wallet-payment-rankings>
    </div>
@endsection

