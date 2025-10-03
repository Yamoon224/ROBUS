@extends('layouts.master_web')

@section('title', 'Rechargement ROBUS')

@section('content')

    @include('components.hero-section', [
        'backgroundImage' => 'https://robus.home.ci/public/dist/images/transactions.jpeg',
        'title' => 'Réchargement par ROBUS',
        'subtitle' => 'Rechargez votre ROBUS en toute sécurité',
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
                                        <h4 class="mb-0 fw-bold">Formulaire de Rechargement Robus</h4>
                                        <p class="mb-0 opacity-75">Remplissez les informations pour demander le rechargements Robus                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="contact-form">

                                <form method="POST" action="{{ route('web.transactions_robus_store.stores') }}" method="post"
                                    enctype="multipart/form-data"
                                    style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                    class="p-4 p-md-5 border rounded-3 bg-light">

                                    @csrf

                                    <div class="row justify-content-center ">
                                        <div class="col-lg-2 col-md-12 mb-4 mb-lg-0 mb-3">
                                            <div class="bg-image hover-overlay image_mobile ripple shadow-1-strong rounded"
                                                data-ripple-color="light">
                                                <img src="https://robus.home.ci/public/dist/providers/robus.png"
                                                    class="w-70" />
                                                <a href="#!" data-mdb-modal-init data-mdb-target="#exampleModal1">
                                                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.2);">
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row g-2 mt-3">
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="text" name="num_robus" id="walletId"
                                                    class="form-control border-0 rounded-3" placeholder="CIZW7480" required>
                                                <label for="walletId" class="fw-medium">
                                                    <i class="bi bi-person-badge me-2 text-primary"></i>
                                                    ID du compte ROBUS <span class="text-danger">*</span>
                                                </label>
                                                <div class="invalid-feedback">
                                                    Veuillez saisir l'ID du compte ROBUS
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nom et prénom ROBUS (affichage) -->
                                        <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                            <div class="form-floating">
                                                <input type="text" id="resultSearch"
                                                    class="form-control border-0 rounded-3 bg-light"
                                                    placeholder="Nom et prénom" readonly>
                                                <label for="resultSearch" class="fw-medium">
                                                    <i class="bi bi-person-circle me-2 text-primary"></i>
                                                    Nom et prénom ROBUS
                                                </label>
                                                <div class="form-text">
                                                    Le nom s'affichera automatiquement après saisie du Wallet
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Numéro de téléphone -->
                                        <div class="col-md-6 col-sm-6 col-xs-12">
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

                                        <!-- Montant -->
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-floating">
                                                <input type="number" class="form-control border-0 rounded-3"
                                                    id="montant_robus" name="amount" min="2000" max="500000"
                                                    placeholder="2400" required>
                                                <label for="montant_robus" class="fw-medium">
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
                                                        <small class="fw-medium" id="montantInfoRobus">
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
                                                id="submitButtonRobus" disabled>
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
                    @include('layouts.shared_web.info-open-robus')

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
        const walletInput = document.querySelector('input[name="num_robus"]');
        const resultSearch = document.getElementById('resultSearch');

        resultSearch.innerText = 'Entrez un numéro de wallet pour afficher ';

        walletInput.addEventListener('input', function() {
            searchResult();
        });

        function searchResult() {
            const walletSearch = walletInput.value;
            if (walletSearch.trim() !== '') {
                fetch('{{ route('search.wallets') }}?num_robus=' + walletSearch)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la recherche du wallet');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.user_firstname) {
                            resultSearch.innerText = `${data.user_firstname}  ${data.user_lastname}`;
                        } else {
                            resultSearch.innerText = 'Aucun utilisateur trouvé pour ce wallet';
                        }
                    })
                    .catch(error => {
                        resultSearch.innerText = 'Le numero wallet incorrecte';
                    });


            } else {
                resultSearch.innerText = 'Saisissez le numéro du wallet';
            }
        }
    </script>
@endpush
