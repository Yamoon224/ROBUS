@extends('layouts.master_web')

@section('title', 'Rechargement carte')

@section('content')

    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/wallet_reload.jpg',
        'title' => 'Réchargement de carte VISA/MASTER CARTE',
        'subtitle' => 'Rechargez votre carte  en toute sécurité',
        'badgeText' => 'Transaction 100% sécurisée',
        'badgeIcon' => 'bi-currency-exchange',
    ])



    @if ($appStatusResult['success'])

        <section class="contact-info-section style_percent">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-sm-10 col-xs-12">

                        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                            <!-- Contact Form -->
                            <div class="card-header bg-gradient-primary text-white py-4">
                                <div class="row align-items-center">

                                    <div class="col">
                                        <h4 class="mb-0 fw-bold">Formulaire de Rechargement</h4>
                                        <p class="mb-0 opacity-75">Remplissez les informations pour demander le rechargement
                                            de votre carte
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-form">

                                <form method="POST" action="{{ route('web.transactions_reloads.stores') }}" method="post"
                                    enctype="multipart/form-data"
                                    style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                    class="p-4 p-md-5 border rounded-3 bg-light">

                                    @csrf
                                    <div class="row justify-content-center ">
                                        @foreach (getReloadMasterCards() as $carteReloads)
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
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-floating">
                                                <input type="text" name="num_carte"
                                                    class="form-control border-0 rounded-3" id="num_carte"
                                                    placeholder="12345566776" required>
                                                <label for="num_carte" class="fw-medium">
                                                    <i class="bi bi-credit-card me-2 text-primary"></i>
                                                    Identifiant ou Numéro de la carte <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir l'identifiant ou numéro de la carte
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nom et prénom sur la carte -->
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-floating">
                                                <input type="text" name="all_name"
                                                    class="form-control border-0 rounded-3" id="all_name"
                                                    placeholder="KONAN Franck" required>
                                                <label for="all_name" class="fw-medium">
                                                    <i class="bi bi-person me-2 text-primary"></i>
                                                    Nom et prénom sur la carte <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir le nom et prénom sur la carte
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 4 derniers chiffres -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="text" name="num_digits" maxlength="4"
                                                    class="form-control border-0 rounded-3" id="num_digits"
                                                    placeholder="1234" required>
                                                <label for="num_digits" class="fw-medium">
                                                    <i class="bi bi-123 me-2 text-primary"></i>
                                                    4 derniers chiffres du recto <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir les 4 derniers chiffres
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Numéro de téléphone -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="text" name="my_phone" maxlength="10"
                                                    class="form-control border-0 rounded-3" id="my_phone"
                                                    placeholder="0101010101" required>
                                                <label for="my_phone" class="fw-medium">
                                                    <i class="bi bi-phone me-2 text-primary"></i>
                                                    Numéro de téléphone <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir votre numéro de téléphone
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Carte à recharger (Select) -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <select required id="provider" name="provider"
                                                    class="form-select border-0 rounded-3 @error('provider') is-invalid @enderror">
                                                    <option disabled selected>Selectionnez la carte</option>
                                                    @foreach ($carte_reloads as $card)
                                                        <option value="{{ $card->id }}">
                                                            {{ $card->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="provider" class="fw-medium">
                                                    <i class="bi bi-wallet me-2 text-primary"></i>
                                                    Carte à recharger <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez sélectionner une carte
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Montant -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="number" class="form-control border-0 rounded-3"
                                                    id="montant_card" name="amount" min="2000" max="500000"
                                                    placeholder="2400" required>
                                                <label for="montant_card" class="fw-medium">
                                                    <i class="bi bi-cash-coin me-2 text-primary"></i>
                                                    Montant (FCFA) <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Le montant doit être entre 2.000 et 500.000 FCFA
                                                </div>
                                            </div>

                                            <!-- Info montant -->
                                            <div class="mt-3">
                                                <div class="alert alert-info border-0 rounded-3 py-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        <small class="fw-medium" id="montantInfoCard">
                                                            Min: 2.000 FCFA / Max: 500.000 FCFA
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @include('layouts.shared_web.frais')

                                    
                                         <div class=" col-md-12 col-sm-12 col-xs-12 mt-3">
                                            <button type="submit"
                                                class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-3"
                                                id="submitButtonCard" disabled>
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
                    @include('layouts.shared_web.info-open')

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
