@extends('layouts.master_web')

@section('title', 'Crédit téléphonique CI - Cameroun')

@push('styles')
<style>
    .phone-credit-page { padding: 64px 0 76px; background: linear-gradient(180deg, #fff8ef, #fff 52%, #f8fafc); }
    .phone-credit-panel { overflow: hidden; border: 1px solid rgba(15,23,42,.08); border-radius: 24px; background: #fff; box-shadow: 0 24px 70px rgba(15,23,42,.10); }
    .phone-credit-head { display:flex; align-items:center; justify-content:space-between; gap:18px; padding:28px; color:#fff; background:linear-gradient(135deg,#5c0f58,#a30071 58%,#f8a21f); }
    .phone-credit-head h2 { margin:0; color:#fff; font-size:1.35rem; font-weight:800; }
    .phone-credit-head p { margin:8px 0 0; color:rgba(255,255,255,.84); }
    .phone-credit-icon { display:grid; place-items:center; width:62px; height:62px; flex:0 0 auto; border-radius:18px; background:rgba(255,255,255,.15); font-size:1.65rem; }
    .phone-credit-body { padding:28px; }
    .phone-credit-field { height:100%; padding:14px; border:1px solid rgba(15,23,42,.08); border-radius:18px; background:#fffaf6; }
    .phone-credit-field label { display:block; margin-bottom:9px; color:#334155; font-size:.85rem; font-weight:800; }
    .phone-credit-field .form-control, .phone-credit-field .form-select { min-height:50px; border:1px solid rgba(15,23,42,.12); border-radius:14px; background:#fff; font-weight:700; }
    .phone-credit-field .form-control:focus, .phone-credit-field .form-select:focus { border-color:#a30071; box-shadow:0 0 0 .2rem rgba(163,0,113,.10); }
    .phone-credit-operators { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; }
    .phone-credit-operator { cursor:pointer; margin:0; }
    .phone-credit-operator input { position:absolute; opacity:0; pointer-events:none; }
    .phone-credit-operator span { display:flex; align-items:center; justify-content:center; gap:8px; min-height:58px; padding:9px; border:2px solid transparent; border-radius:14px; background:#fff; font-weight:800; }
    .phone-credit-operator img { width:30px; height:30px; object-fit:contain; }
    .phone-credit-operator input:checked + span { border-color:#a30071; box-shadow:0 8px 20px rgba(163,0,113,.12); }
    .phone-credit-submit { min-height:56px; border:0; border-radius:17px; background:linear-gradient(135deg,#5c0f58,#a30071 58%,#f8a21f); box-shadow:0 15px 30px rgba(163,0,113,.20); font-weight:800; }
    @media(max-width:575.98px) { .phone-credit-page{padding:42px 0 56px}.phone-credit-head,.phone-credit-body{padding:22px}.phone-credit-operators{grid-template-columns:1fr}.phone-credit-head{align-items:flex-start} }
</style>
@endpush

@section('content')
@include('components.hero-section', [
    'backgroundImage' => 'https://robus.home.ci/public/dist/images/transaction_acoumpte.jpg',
    'title' => 'Crédit téléphonique CI - Cameroun',
    'subtitle' => 'Rechargez un numéro en Côte d’Ivoire ou au Cameroun',
    'badgeText' => 'Transaction 100% sécurisée',
    'badgeIcon' => 'bi-phone',
])

@if ($appStatusResult['success'])
<section class="phone-credit-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-11">
                <div class="phone-credit-panel">
                    <div class="phone-credit-head">
                        <div>
                            <h2>Crédit téléphonique</h2>
                            <p>Choisissez le pays, le réseau, puis renseignez le numéro et le montant.</p>
                        </div>
                        <div class="phone-credit-icon"><i class="bi bi-phone"></i></div>
                    </div>

                    <form method="POST" action="{{ route('web.transactions_phone_credits.store') }}" id="phoneCreditForm">
                        @csrf
                        <div class="phone-credit-body">
                            @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="phone-credit-field">
                                        <label for="country_id"><i class="bi bi-geo-alt me-1"></i>Pays <span class="text-danger">*</span></label>
                                        <select class="form-select" name="country_id" id="country_id" required>
                                            <option value="">Sélectionnez le pays</option>
                                            @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @selected((string) old('country_id') === (string) $country->id)>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="phone-credit-field">
                                        <label for="phone"><i class="bi bi-telephone me-1"></i>Numéro de téléphone <span class="text-danger">*</span></label>
                                        <input class="form-control" type="tel" inputmode="numeric" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Ex : 0700000000" pattern="[0-9]{8,15}" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="phone-credit-field">
                                        <label><i class="bi bi-sim me-1"></i>Opérateur <span class="text-danger">*</span></label>
                                        <div class="phone-credit-operators" id="phoneCreditOperators">
                                            @foreach ($providers as $provider)
                                            <label class="phone-credit-operator" data-country="{{ $provider->country_id }}" hidden>
                                                <input type="radio" name="provider" value="{{ $provider->id }}" @checked((string) old('provider') === (string) $provider->id)>
                                                <span><img src="{{ $provider->image }}" alt="{{ $provider->name }}">{{ $provider->name }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                        <small class="text-muted" id="operatorHint">Sélectionnez d’abord un pays.</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="phone-credit-field">
                                        <label for="amount"><i class="bi bi-cash-stack me-1"></i>Montant <span class="text-danger">*</span></label>
                                        <input class="form-control" type="number" name="amount" id="amount" value="{{ old('amount') }}" min="300" max="500000" placeholder="Ex : 5 000" required>
                                        <small class="text-muted">Minimum : 300 FCFA — Maximum : 500 000 FCFA</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    @include('layouts.shared_web.frais', [
                                        'total_percent' => $total_percent,
                                        'calcul_valeurs' => $calcul_valeurs,
                                    ])
                                    <div class="frais-card mt-3">
                                        <div class="frais-card-body">
                                            <div class="frais-card-result">
                                                <small>Montant à recevoir (montant à envoyer − frais)</small>
                                                <div class="frais-card-result-amount" id="amount-received">0 FCFA</div>
                                                <div class="frais-card-result-detail">Montant crédité sur le numéro</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-center">
                                    <button class="btn btn-primary phone-credit-submit w-100" type="submit">
                                        <i class="bi bi-send me-2"></i>Procéder à la demande
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<section class="contact-info-section style_percent"><div class="container"><div class="row justify-content-center"><div class="col-md-6">@include('layouts.shared_web.close_app')</div></div></div></section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const country = document.getElementById('country_id');
    const labels = [...document.querySelectorAll('.phone-credit-operator')];
    const hint = document.getElementById('operatorHint');

    function refreshOperators() {
        const visible = labels.filter(label => {
            const show = country.value && label.dataset.country === country.value;
            label.hidden = !show;
            label.querySelector('input').required = show;
            if (!show) label.querySelector('input').checked = false;
            return show;
        });
        hint.hidden = visible.length > 0;
        if (visible.length === 1) visible[0].querySelector('input').checked = true;
    }

    country.addEventListener('change', refreshOperators);
    refreshOperators();

    const amountInput = document.getElementById('amount');
    const rate = parseFloat(document.getElementById('total-percent').dataset.totalPercent || 0) / 100;
    const fmt = n => new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(n) + ' FCFA';

    function refreshAmounts() {
        const amount = parseFloat(amountInput.value) || 0;
        const fees = Math.round(amount * rate);
        document.getElementById('result').textContent = fmt(fees);
        document.getElementById('amount-received').textContent = fmt(Math.max(amount - fees, 0));
    }

    amountInput.addEventListener('input', refreshAmounts);
    refreshAmounts();
});
</script>
@endpush
