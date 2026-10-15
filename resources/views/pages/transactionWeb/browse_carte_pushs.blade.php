@extends('layouts.master_web')

@section('title', 'Rechargement carte PUSH ou Djamo')

@section('content')
@include('components.hero-section', [
'backgroundImage' => 'https://robus.home.ci/public/dist/images/wallet_reload.jpg',
'title' => 'Rechargement Carte PUSH ou Djamo',
'subtitle' => 'Choisissez votre carte et rechargez-la en toute sécurité',
'badgeText' => 'Transaction 100% sécurisée',
'badgeIcon' => 'bi-currency-exchange',
])
@if ($appStatusResult['success'])

<x-carte-form type="push" :form-action="route('web.transactions_push.stores')" :cards="$carte_reloads"
    :total-percent="$total_percent" :calcul-valeurs="$calcul_valeurs" />


@else
<section class="contact-info-section style_percent">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-sm-10">
                @include('layouts.shared_web.close_app')
            </div>
        </div>
    </div>
</section>
@endif

@endsection
