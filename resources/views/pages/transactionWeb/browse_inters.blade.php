@extends('layouts.master_web')

@section('title', 'Transfert a international')

@section('content')
    <section class="page-title animate__animated animate__slideInDown"
        style="background-image:url(https://robus.home.ci/public/dist/images/transaction_money.jpeg);">
        <div class="container text-center text-white">
            <h1 class="text-black">TRANSFERT A L'INTERNATIONAL</h1>
        </div>

    </section>


    <section class="contact-info-section" style="margin-top:-8%">

        <div class="container">
            <div class="row ">
                <div class="col-md-8 col-sm-10 col-xs-12">
                    <!-- Contact Form -->
                    <div class="contact-form">

                        <form method="POST" action="{{ route('web.transactions_inters_store.stores') }}" method="post"
                            enctype="multipart/form-data" style="margin-bottom:5% ; background-color: #f0fcff !important;"
                            class="p-4 p-md-5 border  rounded-3 bg-light">

                            @csrf

                            <div class="row g-2">

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="name_expeditor">Nom et prénom expéditeur
                                        <span class="text-danger"> *</span></label>

                                    <input type="text" name="name_expeditor" maxlength="80" class="form-control"
                                        placeholder="Ex: Loren Marck ..." required="">
                                    @error('name_expeditor')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="name_benef">Nom et prénom bénéficiaire
                                        <span class="text-danger"> *</span></label>

                                    <input type="text" name="name_benef" maxlength="80" class="form-control"
                                        placeholder="Ex: Loren Marck ..." required="">
                                    @error('name_benef')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">

                                    <label class="mb-2 font-14 bold black" for="country_id">Pays du bénéficiaire<span
                                            class="text-danger">
                                            *</span></label>
                                    <select required id="country_id" name="country_id" aria-label="Selectionnez la carte "
                                        class="form-select @error('country_id') is-invalid @enderror">
                                        <option disabled selected>Selectionnez le reseau </option>
                                        @foreach ($countries as $countrie)
                                            <option value="{{ $countrie->id }}">
                                                {{ $countrie->name ?? null }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="phone_benef">N° Tel du bénéficiaire <span
                                            class="text-danger"> *</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <span class="badge bg-info text-dark style_span text-white"
                                                id="indice-countrie"></span>
                                        </span>
                                        <input type="text" maxlength="10" name="phone_benef" class="form-control"
                                            placeholder="Ex: 0909090909 ..." required="">
                                    
                                    </div>
                                    @error('phone_benef')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="my_phone">N° Téléphone expéditeur <span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="text" name="my_phone" maxlength="10" class="form-control"
                                        placeholder="Ex: 0101010101" required="">
                                    @error('my_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="provider">Réseau opérateur <span
                                            class="text-danger">
                                            *</span></label>
                                    <select required id="provider" name="provider" aria-label="Selectionnez la carte "
                                        class="form-select @error('provider') is-invalid @enderror">

                                    </select>
                                    @error('provider')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class=" col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black">Montant(min : 2000Fcfa / max : 500.000Fcfa )
                                        <span class="text-danger"> *</span></label>
                                    <input type="number" class="form-control" maxlength="10" name="amount"
                                        placeholder="Ex: 2400" required="">
                                </div>


                                @include('layouts.shared_web.frais_inter')


                                <div class=" col-md-12 col-sm-12 col-xs-12 mt-3"><button type="submit"
                                        class="theme-btn btn-style-three">Valider</button></div>

                            </div>
                        </form>

                    </div>
                    <!--End Contact Form -->
                </div>
                @include('layouts.shared_web.info-open-inter')

            </div>

        </div>
    </section>


@endsection

@push('scripts')
    <script type="text/javascript">
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
                    $('#provider').empty();
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
