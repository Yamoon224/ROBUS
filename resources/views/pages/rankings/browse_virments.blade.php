@extends('layouts.master')

@section('title', 'Liste des classements des virements')

@section('content')
    <div>
        <livewire:rankings.wallet-virement-rankings>
    </div>
@endsection

