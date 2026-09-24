@extends('layouts.master_web')

@section('title', 'Rechargement carte PUSH')

@section('content')
    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/wallet_reload.jpg',
        'title' => 'Rechargement Carte PUSH',
        'subtitle' => 'Rechargez votre carte PUSH en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])

    <x-carte-form
        type="push"
        :form-action="route('web.transactions_push.stores')"
        :cards="$carte_reloads"
        :total-percent="$total_percent"
        :calcul-valeurs="$calcul_valeurs"
    />
@endsection
