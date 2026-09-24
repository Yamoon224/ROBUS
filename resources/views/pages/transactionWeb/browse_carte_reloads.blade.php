@extends('layouts.master_web')

@section('title', 'Rechargement carte')

@section('content')
    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/wallet_reload.jpg',
        'title' => 'Réchargement de carte VISA/MASTER CARTE',
        'subtitle' => 'Rechargez votre carte en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])

    <x-carte-form
        type="reload"
        :form-action="route('web.transactions_reloads.stores')"
        :cards="$carte_reloads"
        :total-percent="$total_percent"
        :calcul-valeurs="$calcul_valeurs"
    />
@endsection
