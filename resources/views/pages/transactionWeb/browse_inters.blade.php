@extends('layouts.master_web')

@section('title', 'Transfert a international')

@section('content')

    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/wallet_reload.jpg',
        'title' => 'Transfert a l\'international',
        'subtitle' => 'Vos transferts a l\international en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])

    @include('layouts.shared_web.open_days')

    @if ($appStatusResult['success'])
        <section class="contact-info-section style_percent">

            <div class="container">
                <div class="row ">
                    <div class="col-md-8 col-sm-10 col-xs-12">
                        <!-- Contact Form -->
                        <div class="contact-form">

                            <form method="POST" action="{{ route('web.transactions_inters_store.stores') }}" method="post"
                                enctype="multipart/form-data"
                                style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                class="p-4 p-md-5 border  rounded-3 bg-light">

                                @csrf

                                <div class="container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="scroll-container" class="d-flex flex-row flex-nowrap overflow-auto">
                                                @foreach (getProviderInters() as $carteReloads)
                                                    <div class="card  mr-2">
                                                        <img src="{{ $carteReloads->image ?? null }}" class="card-img-top"
                                                            alt="Image 1">
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row g-2 mt-3">

                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <div class="form-floating">
                                            <input type="text" name="name_expeditor" maxlength="80"
                                                class="form-control border-0 rounded-3 @error('name_expeditor') is-invalid @enderror"
                                                id="name_expeditor" placeholder="Loren Marck" required>
                                            <label for="name_expeditor" class="fw-medium">
                                                <i class="bi bi-person me-2 text-primary"></i>
                                                Nom et prénom expéditeur <span class="text-danger">*</span>
                                            </label>
                                            @error('name_expeditor')
                                                <div class="invalid-feedback d-block">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nom et prénom bénéficiaire -->
                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <div class="form-floating">
                                            <input type="text" name="name_benef" maxlength="80"
                                                class="form-control border-0 rounded-3 @error('name_benef') is-invalid @enderror"
                                                id="name_benef" placeholder="Loren Marck" required>
                                            <label for="name_benef" class="fw-medium">
                                                <i class="bi bi-person-check me-2 text-primary"></i>
                                                Nom et prénom bénéficiaire <span class="text-danger">*</span>
                                            </label>
                                            @error('name_benef')
                                                <div class="invalid-feedback d-block">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Pays du bénéficiaire -->
                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <div class="form-floating">
                                            <select required id="country_id" name="country_id"
                                                class="form-select border-0 rounded-3 @error('country_id') is-invalid @enderror">
                                                <option disabled selected>Sélectionnez le pays</option>
                                                @foreach ($countries as $countrie)
                                                    <option value="{{ $countrie->id }}"
                                                        data-indicatif="{{ $countrie->indicatif ?? '' }}">
                                                        {{ $countrie->name ?? null }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="country_id" class="fw-medium">
                                                <i class="bi bi-globe me-2 text-primary"></i>
                                                Pays du bénéficiaire <span class="text-danger">*</span>
                                            </label>
                                            @error('country_id')
                                                <div class="invalid-feedback d-block">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- N° Tel du bénéficiaire avec indicatif -->
                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <div class="form-floating">
                                            <input type="text" maxlength="15" name="phone_benef"
                                                class="form-control  rounded-3 @error('phone_benef') is-invalid @enderror"
                                                id="phone_benef" placeholder="0909090909" required>
                                            <label for="phone_benef" class="fw-medium ml-input ">
                                                <i class="bi bi-phone me-2 text-primary"></i>
                                                Téléphone bénéficiaire <span class="text-danger">*</span>
                                            </label>

                                            <div class="position-absolute top-50 start-0 translate-middle-y">
                                                <span class="badge bg-primary text-white bg-opacity-10 text-primary fw-bold px-2 py-2"
                                                    id="indice-countrie">
                                                    +--
                                                </span>
                                            </div>
                                        </div>
                                        @error('phone_benef')
                                            <div class="invalid-feedback d-block">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>


                                    <!-- N° Téléphone expéditeur -->
                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <div class="form-floating">
                                            <input type="text" name="my_phone" maxlength="10"
                                                class="form-control border-0 rounded-3 @error('my_phone') is-invalid @enderror"
                                                id="my_phone" placeholder="0101010101" required>
                                            <label for="my_phone" class="fw-medium">
                                                <i class="bi bi-telephone me-2 text-primary"></i>
                                                Votre numéro téléphone <span class="text-danger">*</span>
                                            </label>
                                            @error('my_phone')
                                                <div class="invalid-feedback d-block">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Réseau opérateur -->
                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <div class="form-floating">
                                            <select required id="provider" name="provider"
                                                class="form-select border-0 rounded-3 @error('provider') is-invalid @enderror">
                                                <option value="">Sélectionnez un réseau</option>
                                            </select>
                                            <label for="provider" class="fw-medium">
                                                <i class="bi bi-wifi me-2 text-primary"></i>
                                                Réseau opérateur <span class="text-danger">*</span>
                                            </label>
                                            @error('provider')
                                                <div class="invalid-feedback d-block">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Montant -->
                                    <div class="col-md-12 col-sm-12 col-xs-12 mt-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control border-0 rounded-3"
                                                id="montant_inter" name="amount" min="2000" max="500000"
                                                placeholder="2400" required>
                                            <label for="montant_inter" class="fw-medium">
                                                <i class="bi bi-cash-coin me-2 text-primary"></i>
                                                Montant (FCFA) <span class="text-danger">*</span>
                                            </label>
                                            <div class="invalid-feedback">
                                                Le montant doit être entre 2.000 et 500.000 FCFA
                                            </div>
                                        </div>

                                        <!-- Info montant améliorée -->
                                        <div class="mt-3">
                                            <div class="alert alert-info border-0 rounded-3 py-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <small class="fw-medium" id="montantInfoInter">
                                                        Min: 2.000 FCFA / Max: 500.000 FCFA
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @include('layouts.shared_web.frais_inter')


                                    <div class=" col-md-12 col-sm-12 col-xs-12 mt-3">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-3"
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
                            </form>

                        </div>
                        <!--End Contact Form -->
                    </div>
                    @include('layouts.shared_web.info-open-inter')

                </div>

            </div>
        </section>
    @else
        <section class="contact-info-section style_percent">


            <div class="container">
                <div class="row ">
                    <div class="col-md-3 col-sm-10 col-xs-12">

                    </div>

                    <div class="col-md-6 col-sm-10 col-xs-12">
                        @include('layouts.shared_web.close_app')

                    </div>

                    <div class="col-md-3 col-sm-10 col-xs-12">

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
            let scrollAmount = 0;

            // Set interval to adjust the scroll position
            const step = 1; // Amount to scroll on each interval
            const speed = 20; // Interval in milliseconds
            let scrollInterval = setInterval(function() {
                scrollContainer.scrollLeft += step;
                scrollAmount += step;
                if (scrollAmount >= scrollContainer.scrollWidth) {
                    scrollAmount = 0;
                    scrollContainer.scrollLeft = 0; // Reset scroll to start
                }
            }, speed);
        }

        // Call the function to start scrolling
        window.onload = function() {
            autoScroll();
        };

        $(document).ready(function() {

            const amountInput = document.querySelector('input[name="amount"]');
            var totalPercent;


            amountInput.addEventListener('input', function() {
                updateResult();
            });

            $('#country_id').on('change', function() {
                var country_id = $('#country_id').val();
                provider_id = false;
                ProviderUpdate(country_id);
                ProviderPercent(country_id);
                CountrieIndice(country_id);
            });

            // Requête Ajax pour les villes
            function ProviderUpdate(countryId) {
                $.get('{{ url('transactions/robus/search-countrie') }}/' + countryId + "'", function(data) {
                    $('#provider').empty().append($('<option>', {
                        value: '',
                        text: 'Sélectionnez un réseau',
                        disabled: false // Vous pouvez mettre à true pour le rendre non-sélectionnable
                    }));
                    $.each(data, function(index, providers) {
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

            // Requête Ajax pour les percents
            function ProviderPercent(countryId) {
                $.get('{{ url('transactions/robus/search-percent') }}/' + countryId, function(data) {

                    // Vérifier si l'objet data n'est pas vide
                    if (data) {

                        var percentValue = data.percent * 100;

                        var adminPercentValue = data.percent_admin * 100;

                        totalPercent = percentValue + adminPercentValue;

                        var totalPercentElement = document.getElementById('total-percent-inters');

                        if (totalPercentElement) {
                            totalPercentElement.textContent = totalPercent;
                        }

                        // Mettre à jour le contenu du span avec les informations récupérées
                        $('#total-percent-inters').text('Frais Total : ' + totalPercent + ' % ');
                        $('#total-percent-wave').text(percentValue + "%");
                        $('#total-percent-robus').text(adminPercentValue + "%");

                    } else {
                        // Afficher un message si aucune donnée n'est retournée
                        $('#total-percent-inters').text('Aucune information disponible');
                        $('#total-percent-wave').text('Aucune information disponible');
                        $('#total-percent-robus').text('Aucune information disponible');
                    }
                });
            }


            function CountrieIndice(countryId) {
                $.get('{{ url('transactions/robus/search-indice') }}/' + countryId, function(data) {

                    // Vérifier si l'objet data n'est pas vide
                    if (data) {

                        var indicative = data.indecative;

                        $('#indice-countrie').text(indicative);

                    } else {
                        // Afficher un message si aucune donnée n'est retournée
                        $('#indice-countrie').text('Aucune information disponible');
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
