@extends('layouts.master_web')

@section('title', 'Transfert a international')

@push('styles')
<style>
    /* ===== THEME INTERNATIONAL ===== */
    [data-txn-type="inter"] {
        --txn-page-bg:
            radial-gradient(circle at 10% 10%, rgba(2, 132, 199, .10), transparent 30%),
            radial-gradient(circle at 90% 5%, rgba(56, 189, 248, .08), transparent 28%),
            linear-gradient(180deg, #f0f9ff 0%, #ffffff 54%, #f8fafc 100%);
        --txn-head-gradient: linear-gradient(135deg, #0c4a6e 0%, #0284c7 58%, #38bdf8 100%);
        --txn-submit-gradient: linear-gradient(135deg, #0c4a6e 0%, #0284c7 60%, #0ea5e9 100%);
        --txn-submit-shadow: rgba(2, 132, 199, .22);
        --txn-field-bg: #f0f9ff;
        --txn-field-focus-border: rgba(2, 132, 199, .55);
        --txn-field-focus-shadow: rgba(2, 132, 199, .12);
        --txn-section-title-color: #0c4a6e;
        --txn-icon-color: #0284c7;
        --txn-icon-bg: rgba(2, 132, 199, .10);
        --txn-amount-note-bg: rgba(56, 189, 248, .12);
        --txn-amount-note-color: #075985;
    }

    /* ===== SHARED txn-* STYLES ===== */
    .txn-page { background: var(--txn-page-bg); padding: 64px 0 76px; }
    .txn-shell { align-items: flex-start; }

    .txn-form-panel {
        background: #ffffff;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 24px;
        box-shadow: 0 24px 70px rgba(15,23,42,.10);
        overflow: hidden;
    }

    .txn-form-head {
        display: flex; gap: 18px; align-items: center;
        justify-content: space-between;
        background: var(--txn-head-gradient);
        color: #ffffff; padding: 28px;
    }
    .txn-form-head h2 { color: #ffffff; font-size: 1.35rem; font-weight: 800; line-height: 1.25; margin: 0; }
    .txn-form-head p { color: rgba(255,255,255,.82); margin: 8px 0 0; }

    .txn-logo-box {
        width: 72px; height: 72px; border-radius: 20px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #ffffff; border: 1px solid rgba(255,255,255,.24);
        box-shadow: 0 18px 38px rgba(12,74,110,.22);
        flex: 0 0 auto; padding: 14px;
    }
    .txn-logo-box i { color: #0284c7; font-size: 2rem; }

    .txn-trust-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
    .txn-trust-chip {
        display: inline-flex; align-items: center; gap: 7px;
        min-height: 34px; padding: 8px 12px; border-radius: 999px;
        background: rgba(255,255,255,.13); color: rgba(255,255,255,.94);
        font-size: .82rem; font-weight: 700;
    }

    /* Providers scroll strip */
    .txn-providers-strip {
        width: 100%; padding: 14px 20px;
        background: rgba(2, 132, 199, .05);
        border-bottom: 1px solid rgba(2, 132, 199, .10);
    }
    #scroll-container {
        width: 100%; scrollbar-width: none;
    }
    #scroll-container::-webkit-scrollbar { display: none; }
    .txn-provider-card {
        flex: 0 0 auto; width: 64px; height: 44px;
        border-radius: 10px; border: 1px solid rgba(2,132,199,.15);
        overflow: hidden; padding: 4px;
        display: flex; align-items: center; justify-content: center;
        background: #ffffff;
    }
    .txn-provider-card img { max-width: 100%; max-height: 100%; object-fit: contain; }

    .txn-form-body { padding: 28px; }

    .txn-section-title {
        display: flex; align-items: center; gap: 10px;
        color: var(--txn-section-title-color);
        font-size: .95rem; font-weight: 800; margin-bottom: 16px;
    }
    .txn-section-title span {
        width: 32px; height: 32px; display: inline-flex;
        align-items: center; justify-content: center;
        border-radius: 11px;
        color: var(--txn-icon-color); background: var(--txn-icon-bg);
    }

    .txn-field {
        height: 100%; padding: 14px;
        border: 1px solid rgba(15,23,42,.08);
        border-radius: 18px; background: var(--txn-field-bg);
    }
    .txn-field label {
        display: flex; align-items: center; gap: 8px;
        color: #334155; font-size: .84rem; font-weight: 800; margin-bottom: 10px;
    }
    .txn-field .form-control,
    .txn-field .form-select {
        min-height: 48px;
        border: 1px solid rgba(15,23,42,.10) !important;
        border-radius: 14px !important;
        background-color: #ffffff; color: #0f172a;
        font-weight: 700; box-shadow: none;
    }
    .txn-field .form-control:focus,
    .txn-field .form-select:focus {
        border-color: var(--txn-field-focus-border) !important;
        box-shadow: 0 0 0 .2rem var(--txn-field-focus-shadow);
    }
    .txn-field .input-group .form-control {
        border-radius: 0 14px 14px 0 !important;
    }
    .txn-field .input-group-text {
        border: 1px solid rgba(15,23,42,.10) !important;
        border-right: 0 !important;
        border-radius: 14px 0 0 14px !important;
        background: rgba(2, 132, 199, .10);
        color: #0284c7; font-weight: 800; min-height: 48px;
        padding: 0 12px;
    }

    .txn-amount-note {
        display: flex; align-items: center; gap: 10px;
        margin-top: 12px; padding: 11px 12px; border-radius: 14px;
        background: var(--txn-amount-note-bg);
        color: var(--txn-amount-note-color);
        font-size: .84rem; font-weight: 700;
    }

    .txn-submit-wrap { display: flex; justify-content: center; }
    .txn-submit-wrap .txn-submit-btn { width: min(100%, 420px); }

    .txn-submit-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 8px; min-height: 58px; height: auto;
        padding-right: 18px !important; padding-left: 18px !important;
        border: 0; border-radius: 18px;
        background: var(--txn-submit-gradient);
        box-shadow: 0 16px 30px var(--txn-submit-shadow);
        line-height: 1.25; white-space: normal;
        overflow-wrap: anywhere; text-align: center;
    }
    .txn-submit-btn:disabled { background: #a8b3c7; box-shadow: none; cursor: not-allowed; }
    .txn-submit-btn .submit-text { display: inline-flex; }
    .txn-submit-btn .loading-spinner { flex-wrap: wrap; }
    .txn-submit-btn i { flex: 0 0 auto; }
    .txn-submit-btn .submit-text,
    .txn-submit-btn .loading-spinner {
        align-items: center; justify-content: center;
        max-width: 100%; min-width: 0; line-height: 1.25;
    }

    .txn-side-note {
        margin-top: 16px; padding: 18px; border-radius: 20px;
        background: #ffffff; border: 1px solid rgba(15,23,42,.08);
        box-shadow: 0 14px 34px rgba(15,23,42,.07);
    }
    .txn-side-note strong { display: block; color: #0f172a; font-size: .95rem; margin-bottom: 6px; }
    .txn-side-note p { color: #64748b; font-size: .88rem; margin: 0; }

    .txn-page .card { border-radius: 22px !important; }

    @media (max-width: 991.98px) {
        .txn-page { padding: 46px 0 58px; }
        .txn-form-head { align-items: flex-start; flex-direction: column-reverse; }
    }
    @media (max-width: 575.98px) {
        .txn-form-head, .txn-form-body { padding: 22px; }
        .txn-submit-btn {
            min-height: 54px; padding: 13px 14px !important;
            border-radius: 15px; font-size: .94rem;
        }
        .txn-submit-btn .submit-text,
        .txn-submit-btn .loading-spinner { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/wallet_reload.jpg',
        'title' => 'Transfert à l\'international',
        'subtitle' => 'Vos transferts à l\'international en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])

    @include('layouts.shared_web.open_days')

    @if ($appStatusResult['success'])
        <section class="txn-page contact-info-section style_percent" data-txn-type="inter">
            <div class="container">
                <div class="row txn-shell g-4 justify-content-center">
                    <div class="col-lg-8 col-md-11 col-sm-12">
                        <div class="txn-form-panel">

                            <div class="txn-form-head">
                                <div>
                                    <h2>Formulaire de transfert international</h2>
                                    <p>Renseignez les informations de l'expéditeur et du bénéficiaire.
                                        Les frais sont calculés automatiquement selon le pays de destination.</p>
                                    <div class="txn-trust-list">
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-shield-check"></i> Transaction sécurisée
                                        </span>
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-globe2"></i> International
                                        </span>
                                        <span class="txn-trust-chip">
                                            <i class="bi bi-receipt"></i> Demande suivie
                                        </span>
                                    </div>
                                </div>
                                <div class="txn-logo-box">
                                    <i class="bi bi-globe2"></i>
                                </div>
                            </div>

                            <!-- Scrolling provider logos -->
                            <div class="txn-providers-strip">
                                <div id="scroll-container" class="d-flex flex-row flex-nowrap overflow-auto gap-2">
                                    @foreach (getProviderInters() as $carteReloads)
                                        <div class="txn-provider-card">
                                            <img src="{{ $carteReloads->image ?? null }}" alt="">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <form method="POST" action="{{ route('web.transactions_inters_store.stores') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="txn-form-body">
                                    <div class="txn-section-title">
                                        <span><i class="bi bi-people"></i></span>
                                        Informations des parties
                                    </div>

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="name_expeditor">
                                                    <i class="bi bi-person text-primary"></i>
                                                    Nom et prénom expéditeur <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="name_expeditor" maxlength="80"
                                                    class="form-control @error('name_expeditor') is-invalid @enderror"
                                                    id="name_expeditor" placeholder="Ex: KONAN Franck" required>
                                                @error('name_expeditor')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="name_benef">
                                                    <i class="bi bi-person-check text-primary"></i>
                                                    Nom et prénom bénéficiaire <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="name_benef" maxlength="80"
                                                    class="form-control @error('name_benef') is-invalid @enderror"
                                                    id="name_benef" placeholder="Ex: DIALLO Mamadou" required>
                                                @error('name_benef')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="txn-section-title mb-0">
                                                <span><i class="bi bi-globe"></i></span>
                                                Destination et contact
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="country_id">
                                                    <i class="bi bi-flag text-primary"></i>
                                                    Pays du bénéficiaire <span class="text-danger">*</span>
                                                </label>
                                                <select required id="country_id" name="country_id"
                                                    class="form-select @error('country_id') is-invalid @enderror">
                                                    <option disabled selected>Sélectionnez le pays</option>
                                                    @foreach ($countries as $countrie)
                                                        <option value="{{ $countrie->id }}"
                                                            data-indicatif="{{ $countrie->indicatif ?? '' }}">
                                                            {{ $countrie->name ?? null }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('country_id')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="phone_benef">
                                                    <i class="bi bi-phone text-primary"></i>
                                                    Téléphone bénéficiaire <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="indice-countrie">+--</span>
                                                    <input type="text" maxlength="15" name="phone_benef"
                                                        class="form-control @error('phone_benef') is-invalid @enderror"
                                                        id="phone_benef" placeholder="0909090909" required>
                                                </div>
                                                @error('phone_benef')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="my_phone">
                                                    <i class="bi bi-telephone text-primary"></i>
                                                    Votre numéro téléphone <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="my_phone" maxlength="10"
                                                    class="form-control @error('my_phone') is-invalid @enderror"
                                                    id="my_phone" placeholder="Ex: 0101010101" required>
                                                @error('my_phone')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="txn-field">
                                                <label for="provider">
                                                    <i class="bi bi-wifi text-primary"></i>
                                                    Réseau opérateur <span class="text-danger">*</span>
                                                </label>
                                                <select required id="provider" name="provider"
                                                    class="form-select @error('provider') is-invalid @enderror">
                                                    <option value="">Sélectionnez un réseau</option>
                                                </select>
                                                @error('provider')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="txn-field">
                                                <label for="montant_inter">
                                                    <i class="bi bi-cash-coin text-primary"></i>
                                                    Montant (FCFA) <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control"
                                                    id="montant_inter" name="amount" min="2000" max="500000"
                                                    placeholder="Ex: 5000" required>
                                                <div class="invalid-feedback">
                                                    Le montant doit être entre 2.000 et 500.000 FCFA
                                                </div>
                                                <div class="txn-amount-note">
                                                    <i class="bi bi-info-circle"></i>
                                                    <small id="montantInfoInter">Min: 2.000 FCFA / Max: 500.000 FCFA</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            @include('layouts.shared_web.frais_inter')
                                        </div>

                                        <div class="col-12 txn-submit-wrap">
                                            <button type="submit"
                                                class="btn btn-primary btn-lg py-3 fw-bold txn-submit-btn"
                                                id="submitButtonInter" disabled>
                                                <span class="submit-text">
                                                    <i class="bi bi-send me-2"></i>
                                                    Procéder à la demande
                                                </span>
                                                <span class="loading-spinner" style="display: none;">
                                                    <i class="bi bi-arrow-repeat me-2"></i>
                                                    Traitement en cours...
                                                </span>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="txn-side-note">
                            <strong>Avant d'envoyer votre demande</strong>
                            <p>Sélectionnez le pays du bénéficiaire pour charger les opérateurs disponibles
                                et calculer les frais applicables à votre transfert international.</p>
                        </div>
                    </div>

                    @include('layouts.shared_web.info-open-inter')

                </div>
            </div>
        </section>
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

@push('scripts')
    <script type="text/javascript">
        function autoScroll() {
            const scrollContainer = document.getElementById('scroll-container');
            if (!scrollContainer) return;
            const step = 1;
            const speed = 20;
            setInterval(function () {
                scrollContainer.scrollLeft += step;
                if (scrollContainer.scrollLeft >= scrollContainer.scrollWidth - scrollContainer.clientWidth) {
                    scrollContainer.scrollLeft = 0;
                }
            }, speed);
        }

        window.onload = function () {
            autoScroll();
        };

        $(document).ready(function () {

            const amountInput = document.querySelector('input[name="amount"]');
            var totalPercent;

            amountInput.addEventListener('input', function () {
                updateResult();
            });

            $('#country_id').on('change', function () {
                var country_id = $('#country_id').val();
                provider_id = false;
                ProviderUpdate(country_id);
                ProviderPercent(country_id);
                CountrieIndice(country_id);
            });

            function ProviderUpdate(countryId) {
                $.get('{{ url('transactions/robus/search-countrie') }}/' + countryId + "'", function (data) {
                    $('#provider').empty().append($('<option>', {
                        value: '',
                        text: 'Sélectionnez un réseau',
                        disabled: false
                    }));
                    $.each(data, function (index, providers) {
                        $('#provider').append($('<option>', {
                            value: providers,
                            text: providers
                        }));
                    });
                    if (provider_id) {
                        $('#provider').val(provider_id).prop('selected', true);
                    }
                });
            }

            function ProviderPercent(countryId) {
                $.get('{{ url('transactions/robus/search-percent') }}/' + countryId, function (data) {
                    if (data) {
                        var percentValue = data.percent * 100;
                        var adminPercentValue = data.percent_admin * 100;
                        totalPercent = percentValue + adminPercentValue;

                        var totalPercentElement = document.getElementById('total-percent-inters');
                        if (totalPercentElement) {
                            totalPercentElement.textContent = totalPercent;
                        }

                        $('#total-percent-inters').text('Frais Total : ' + totalPercent + ' % ');
                        $('#total-percent-wave').text(percentValue + "%");
                        $('#total-percent-robus').text(adminPercentValue + "%");
                    } else {
                        $('#total-percent-inters').text('Aucune information disponible');
                        $('#total-percent-wave').text('Aucune information disponible');
                        $('#total-percent-robus').text('Aucune information disponible');
                    }
                });
            }

            function CountrieIndice(countryId) {
                $.get('{{ url('transactions/robus/search-indice') }}/' + countryId, function (data) {
                    if (data) {
                        $('#indice-countrie').text(data.indecative);
                    } else {
                        $('#indice-countrie').text('+--');
                    }
                });
            }

            function updateResult() {
                const montantSaisi = parseFloat(amountInput.value);
                if (!isNaN(montantSaisi)) {
                    const montantCalcule = montantSaisi * (totalPercent / 100);
                    $('#total-percent-cumuls').text(montantCalcule.toFixed(2) + " FCFA");
                } else {
                    $('#total-percent-cumuls').text('Aucune information disponible');
                }
            }
        });
    </script>
@endpush
