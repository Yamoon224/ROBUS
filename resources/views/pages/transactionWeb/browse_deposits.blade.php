@extends('layouts.master_web')

@section('title', 'Rechargement carte')

@section('content')

    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/transaction_acoumpte.jpg',
        'title' => 'Réchargement crédit Téléphonique & Transfert Mooney',
        'subtitle' => 'Rechargez  en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])


    @if ($appStatusResult['success'])
        <section class="contact-info-section style_percent">

            <div class="container">
                <div class="row justify-content-center ">
                    <div class="col-md-8 col-sm-10 col-xs-12">

                        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                            <div class="card-header bg-gradient-primary text-white py-4">
                                <div class="row align-items-center">
                                   
                                    <div class="col">
                                        <h4 class="mb-0 fw-bold">Formulaire de Rechargement & Transferts</h4>
                                        <p class="mb-0 opacity-75">Remplissez les informations pour demander le rechargement & Transferts                                        </p>
                                    </div>
                                </div>
                            </div>
                           
                            <!-- Contact Form -->
                            <div class="contact-form">

                                <form method="POST" action="{{ route('web.transactions_deposits_store.stores') }}"
                                    method="post" enctype="multipart/form-data"
                                    style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                    class="p-4 p-md-5 border  rounded-3 bg-light">

                                    @csrf

                                    <div class="row justify-content-center ">
                                        @foreach (getReloadMoneysPaiements() as $carteReloads)
                                            <div class="col-lg-2 col-md-12 mb-4 mb-lg-0 mb-3">
                                                <div class="bg-image hover-overlay image_mobile ripple shadow-1-strong rounded"
                                                    data-ripple-color="light">
                                                    <img src="{{ $carteReloads->image ?? null }}" class="w-70" />
                                                    <a href="#!" data-mdb-modal-init data-mdb-target="#exampleModal1">
                                                        <div class="mask"
                                                            style="background-color: rgba(251, 251, 251, 0.2);">
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                    <div class="row g-2 mt-3">

                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="text" name="name_expeditor" maxlength="80"
                                                    class="form-control border-0 rounded-3" id="name_expeditor"
                                                    placeholder="Loren Marck" required>
                                                <label for="name_expeditor" class="fw-medium">
                                                    <i class="bi bi-person me-2 text-primary"></i>
                                                    Nom et prénom expéditeur <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir le nom et prénom de l'expéditeur
                                                </div>
                                            </div>
                                        </div>

                                        <!-- N° Tel du bénéficiaire -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="text" maxlength="10" name="phone_benef"
                                                    class="form-control border-0 rounded-3" id="phone_benef"
                                                    placeholder="0909090909" required>
                                                <label for="phone_benef" class="fw-medium">
                                                    <i class="bi bi-phone me-2 text-primary"></i>
                                                    N° Téléphone bénéficiaire <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir le numéro du bénéficiaire
                                                </div>
                                            </div>
                                        </div>

                                        <!-- N° Téléphone expéditeur -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="text" name="my_phone" maxlength="10"
                                                    class="form-control border-0 rounded-3" id="my_phone"
                                                    placeholder="0101010101" required>
                                                <label for="my_phone" class="fw-medium">
                                                    <i class="bi bi-telephone me-2 text-primary"></i>
                                                    Votre numéro téléphone <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir votre numéro de téléphone
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Réseau -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <select required id="provider" name="provider"
                                                    class="form-select border-0 rounded-3 @error('provider') is-invalid @enderror">
                                                    <option disabled selected>Sélectionnez le réseau</option>
                                                    @foreach ($providers as $provider)
                                                        <option value="{{ $provider->id }}">
                                                            {{ $provider->name ?? null }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="provider" class="fw-medium">
                                                    <i class="bi bi-wifi me-2 text-primary"></i>
                                                    Réseau <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez sélectionner un réseau
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Type d'opération -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <select required name="operators"
                                                    class="form-select border-0 rounded-3 @error('operators') is-invalid @enderror">
                                                    <option disabled selected>Sélectionnez le type d'opération</option>
                                                    <option value="1">Transfert mobile money</option>
                                                    <option value="2">Rechargement téléphonique</option>
                                                </select>
                                                <label for="operators" class="fw-medium">
                                                    <i class="bi bi-gear me-2 text-primary"></i>
                                                    Type d'opération <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez sélectionner le type d'opération
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Montant -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="number" class="form-control border-0 rounded-3"
                                                    id="montant_deposit" name="amount" min="500" max="500000"
                                                    placeholder="2400" required>
                                                <label for="montant_deposit" class="fw-medium">
                                                    <i class="bi bi-cash-coin me-2 text-primary"></i>
                                                    Montant (FCFA) <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Le montant doit être entre 500 et 500.000 FCFA
                                                </div>
                                            </div>

                                            <!-- Info montant -->
                                            <div class="mt-3">
                                                <div class="alert alert-info border-0 rounded-3 py-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        <small class="fw-medium" id="montantInfoDeposit">
                                                            Min: 500 FCFA / Max: 500.000 FCFA
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        @include('layouts.shared_web.frais_transfert')


                                        @include('layouts.shared_web.frais_credit')



                                        <div class=" col-md-12 col-sm-12 col-xs-12 mt-3">
                                            <button type="submit"
                                                class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-3"
                                                id="submitButtonDeposit" disabled>
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


                    </div>
                    @include('layouts.shared_web.info-open-transfert')

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
        // Sélection de l'élément <select> par son attribut name
        const selectElement = document.querySelector('select[name="operators"]');
        const elementToToggle = document.querySelector('.active_phone');
        const elementToToggle1 = document.querySelector('.active_phone1');
        const elementToToggle2 = document.querySelector('.active_transfert');
        const elementToToggle3 = document.querySelector('.active_transfert2');


        selectElement.addEventListener('change', function() {
            const selectedValue = selectElement.value;
            if (selectedValue === '1') {
                // Si la valeur sélectionnée est "1", supprimer la classe d-none
                elementToToggle2.classList.remove('d-none');
                elementToToggle.classList.add('d-none');
                elementToToggle1.classList.add('d-none');
                elementToToggle3.classList.remove('d-none');

            } else if (selectedValue === '2') {
                elementToToggle.classList.remove('d-none');
                elementToToggle1.classList.remove('d-none');
                elementToToggle2.classList.add('d-none');
                elementToToggle3.classList.add('d-none');

            } else {
                // Sinon, ajouter la classe d-none
                elementToToggle.classList.add('d-none');
                elementToToggle2.classList.add('d-none');
                elementToToggle1.classList.add('d-none');
                elementToToggle3.classList.add('d-none');
            }
        });
    </script>
@endpush
