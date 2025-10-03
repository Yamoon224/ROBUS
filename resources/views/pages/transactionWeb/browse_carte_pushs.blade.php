@extends('layouts.master_web')

@section('title', 'Rechargement carte')

@section('content')

@include('components.hero-section', [
    'backgroundImage' => 'https://robus.home.ci/public/dist/images/wallet_reload.jpg',
    'title' => 'Rechargement Carte PUSH',
    'subtitle' => 'Rechargez votre carte PUSH en toute sécurité',
    'badgeText' => 'Transaction 100% sécurisée',
    'badgeIcon' => 'bi-currency-exchange'
])
    
    @if ($appStatusResult['success'])
        <section class="contact-info-section style_percent">

            <div class="container">

                <div class="row justify-content-center">


                    <div class="col-md-8 col-sm-10 col-xs-12">
                        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">

                            <div class="card-header bg-gradient-primary text-white py-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="icon-wrapper bg-white rounded-3 p-3">
                                            <img src="https://robus.home.ci/public/dist/providers/push.png"
                                                class="img-fluid" style="height: 40px;" alt="PUSH Card" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h4 class="mb-0 fw-bold">Formulaire de Rechargement</h4>
                                        <p class="mb-0 opacity-75">Remplissez les informations pour demander le rechargement de votre carte
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Contact Form -->
                            <div class="contact-form">

                                <form method="POST" action="{{ route('web.transactions_push.stores') }}" method="post"
                                    enctype="multipart/form-data"
                                    style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                    class="p-4 p-md-5 border rounded-3 needs-validation">

                                    @csrf

                                    <div class="row justify-content-center">
                                        <div class="col-lg-4 col-md-12 mb-4 mb-lg-0 mb-3">
                                            <div class="bg-image hover-overlay image_mobile ripple shadow-1-strong rounded"
                                                data-ripple-color="light">
                                                <img src="https://robus.home.ci/public/dist/providers/push.png"
                                                    class="w-50 rounded-3 syle_logo_providers" />

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-3">
                                        <div class="alert alert-primary border-0 rounded-3 mb-4">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-1 fw-bold">Carte sélectionnée :</h6>
                                                    <span class="badge bg-opacity-10 text-primary fs-6 px-3 py-2">
                                                        {{ $carte_reloads->name ?? 'Carte PUSH' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="num_carte" maxlength="10"
                                                    class="form-control border-0  rounded-3" id="num_carte"
                                                    placeholder="0707077777" required>
                                                <label for="num_carte" class="fw-medium">
                                                    <i class="bi bi-phone me-2 text-primary"></i>
                                                    N° Téléphone PUSH *
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir le numéro de téléphone PUSH
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="all_name"
                                                    class="form-control border-0  rounded-3" id="all_name"
                                                    placeholder="KONAN Franck" required>
                                                <label for="all_name" class="fw-medium">
                                                    <i class="bi bi-person me-2 text-primary"></i>
                                                    Nom complet sur le compte *
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir le nom complet
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-8">
                                            <div class="form-floating">
                                                <input type="text" name="my_phone" maxlength="10"
                                                    class="form-control border-0  rounded-3" id="my_phone"
                                                    placeholder="0101010101" required>
                                                <label for="my_phone" class="fw-medium">
                                                    <i class="bi bi-telephone me-2 text-primary"></i>
                                                    Votre numéro personnel *
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir votre numéro personnel
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-8">
                                            <div class="form-floating">
                                                <input type="number" class="form-control border-0  rounded-3"
                                                    id="montant_push" name="amount" min="2000" max="500000"
                                                    placeholder="2400" required>
                                                <label for="montant_push" class="fw-medium">
                                                    <i class="bi bi-cash me-2 text-primary"></i>
                                                    Montant (FCFA) *
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
                                                        <small class="fw-medium" id="montantInfoPush">
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
                                                id="submitButtonPush" disabled>
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
