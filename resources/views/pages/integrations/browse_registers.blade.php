@extends('layouts.master_integration')

@section('title', 'Enregistrement prospect')

@section('content')


    <section class="contact-info-section py-5 mt-8">
        <div class="container container-large my-4">
            <!-- Header Section -->
            <div class="text-center mb-5 mt-6" style="padding-top: 2rem;">
                <!-- Badge d'en-tête -->
                <div class="mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary  rounded-pill fw-medium">
                        <i class="fas fa-rocket me-2"></i>Démarrage en 2 minutes
                    </span>
                </div>

                <!-- Titre principal avec animation -->
                <h1 class="fw-bold text-dark mb-3 display-5"
                    style="
                background: linear-gradient(135deg, #a30071 0%, #8a0060 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                line-height: 1.2;
            ">
                    Intégrez les paiements avec l'API <span class="text-dark">ROBUS</span>
                </h1>

                <!-- Sous-titre amélioré -->
                <p class="lead text-muted mb-4 position-relative" style="font-size: 1.25rem;">
                    Rejoignez <span class="fw-bold text-primary">des milliers de commerçants</span> qui nous font confiance
                    <br>
                    <span class="text-dark">Solution de paiement sécurisée et innovante</span>

                    <!-- Élément décoratif -->
                    <span class="position-absolute top-0 start-0 translate-middle" style="z-index: -1;">
                        <i class="fas fa-star text-warning opacity-25" style="font-size: 1.5rem;"></i>
                    </span>
                </p>
            </div>
            <div class="row justify-content-center">
                <div class="col-xxl-12">
                    <div class="row g-5">
                        <!-- Features Column -->
                        <div class="col-lg-4">
                            <div
                                class="card card-custom gradient-bg text-white h-100 border-0 position-relative overflow-hidden">
                                <!-- Floating Shapes -->
                                <div class="floating-shape shape-1"></div>
                                <div class="floating-shape shape-2"></div>

                                <div class="card-body p-4 p-md-5 d-flex flex-column position-relative">
                                    <div class="text-center mb-4">
                                        <div class="feature-main-icon mb-3">
                                            <i class="fas fa-code fa-2x"></i>
                                        </div>
                                        <h3 class="fw-bold mb-2 display-6">API Robus</h3>
                                        {{-- <p class="mb-0 opacity-90 small">Solution de paiement complète pour développeurs</p> --}}
                                    </div>
                                    <h3 class="fw-bold mb-4 display-8">Pourquoi choisir ROBUS ?</h3>

                                    <div class="flex-grow-1">
                                        <!-- Features List -->


                                        <div class="feature-item d-flex mb-4">
                                            <div class="feature-icon me-3">
                                                <i class="fas fa-shield-alt fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Sécurité bancaire</h5>
                                                <p class="mb-0 text-white opacity-90 small">Transactions cryptées et
                                                    conformes PCI DSS</p>
                                            </div>
                                        </div>

                                        <div class="feature-item d-flex mb-4">
                                            <div class="feature-icon me-3">
                                                <i class="fas fa-mobile-alt fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Multi-canaux</h5>
                                                <p class="mb-0 text-white opacity-90 small">Orange Money, MTN, Wave, Cartes
                                                    bancaires
                                                </p>
                                            </div>
                                        </div>

                                        <div class="feature-item d-flex mb-4">
                                            <div class="feature-icon me-3">
                                                <i class="fas fa-chart-line fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Analytics avancés</h5>
                                                <p class="mb-0 text-white opacity-90 small">Tableaux de bord et rapports
                                                    détaillés</p>
                                            </div>
                                        </div>

                                        <div class="feature-item d-flex">
                                            <div class="feature-icon me-3">
                                                <i class="fas fa-headset fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Support dédié</h5>
                                                <p class="mb-0 text-white opacity-90 small">Équipe technique disponible
                                                    24h/24</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Testimonials -->
                                    <div class="border-top border-light border-opacity-25 pt-4 mt-4">
                                        <div class="testimonial-card">
                                            <div class="text-center">
                                                <div class="mb-3">
                                                    <i class="fas fa-quote-left fa-2x opacity-50"></i>
                                                </div>
                                                <p class="fst-italic text-white opacity-90   mb-3">ROBUS a révolutionné
                                                    notre système de
                                                    paiement. L'intégration était simple et nos clients adorent l'expérience
                                                    fluide."</p>
                                                {{-- <footer class="fw-bold">Marie Diop</footer>
                                            <small class="opacity-75">CEO - ShopSenegal</small> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Form Column -->
                        <div class="col-lg-8">
                            <div class="card card-custom h-100 border-0">
                                <div class="card-body p-4 p-md-5">
                                    <div class="alert alert-info border-0 bg-light-info">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle text-info me-3 fs-5"></i>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="alert-heading mb-1 fw-bold text-primary">Contactez un commercial
                                                </h6>
                                                <p class="mb-0 text-dark opacity-90">Un commercial vous contactera
                                                    pour finaliser
                                                    l'ouverture de votre compte</p>
                                            </div>

                                        </div>
                                    </div>


                                    <form method="POST" action="{{ route('web.prospect_store.stores') }}" method="post"
                                        enctype="multipart/form-data" style="margin-bottom:5%" class="bg-light">
                                        @csrf
                                        <!-- Company Information -->
                                        <div class="form-section mb-3">
                                            <div class="section-header mb-4">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="section-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-0 text-dark">Informations Entreprise</h5>
                                                </div>
                                                <div class="section-divider mt-2"></div>
                                            </div>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text" name="name_firm" maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm @error('name_firm') is-invalid @enderror"
                                                            value="{{ old('name_firm') }}" id="name_firm"
                                                            placeholder="Nom de votre entreprise" required>
                                                        <label for="name_firm" class="fw-medium text-muted">
                                                            <i class="fas fa-building me-2 text-primary"></i>
                                                            Nom entreprise <span class="text-danger">*</span>
                                                        </label>
                                                        @error('name_firm')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <select required name="sector_of_activity"
                                                            class="form-select border border-2 border-light rounded-3 shadow-sm @error('sector_of_activity') is-invalid @enderror">
                                                            <option disabled selected>Sélectionnez votre secteur d'activité
                                                            </option>
                                                            @php
                                                                $sectors = [
                                                                    'ecommerce' => 'Commerce en ligne (E-commerce)',
                                                                    'retail' => 'Commerce de détail',
                                                                    'services' => 'Services aux entreprises',
                                                                    'digital' => 'Services Digitaux & Tech',
                                                                    'health' => 'Santé & Bien-être',
                                                                    'food' => 'Alimentation & Restauration',
                                                                    'education' => 'Éducation & Formation',
                                                                    'tourism' => 'Tourisme & Voyage',
                                                                    'real_estate' => 'Immobilier',
                                                                    'finance' => 'Services Financiers',
                                                                    'transport' => 'Transport & Logistique',
                                                                    'manufacturing' => 'Industrie & Production',
                                                                    'agriculture' => 'Agriculture',
                                                                    'art_culture' => 'Art & Culture',
                                                                    'other' => 'Autre secteur',
                                                                ];
                                                            @endphp
                                                            @foreach ($sectors as $value => $label)
                                                                <option value="{{ $value }}"
                                                                    {{ old('sector_of_activity') == $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="sector_of_activity" class="fw-medium text-muted">
                                                            <i class="fas fa-industry me-2 text-primary"></i>
                                                            Secteur d'activité <span class="text-danger">*</span>
                                                        </label>
                                                        @error('sector_of_activity')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="url" name="site_url" maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm @error('site_url') is-invalid @enderror"
                                                            value="{{ old('site_url') }}" id="site_url"
                                                            placeholder="https://votre-site.com">
                                                        <label for="site_url" class="fw-medium text-muted">
                                                            <i class="fas fa-globe me-2 text-primary"></i>
                                                            Site Internet (URL)
                                                        </label>
                                                        @error('site_url')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <select required name="legal_form"
                                                            class="form-select border border-2 border-light rounded-3 shadow-sm @error('legal_form') is-invalid @enderror">
                                                            <option disabled selected>Forme juridique</option>
                                                            <option value="sarl"
                                                                {{ old('legal_form') == 'sarl' ? 'selected' : '' }}>SARL
                                                            </option>
                                                            <option value="ei"
                                                                {{ old('legal_form') == 'ei' ? 'selected' : '' }}>
                                                                Entreprise Individuelle</option>
                                                            <option value="sasu"
                                                                {{ old('legal_form') == 'sasu' ? 'selected' : '' }}>SASU
                                                            </option>
                                                        </select>
                                                        <label for="legal_form" class="fw-medium text-muted">
                                                            <i class="fas fa-balance-scale me-2 text-primary"></i>
                                                            Forme juridique <span class="text-danger">*</span>
                                                        </label>
                                                        @error('legal_form')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-section mb-3">
                                            <div class="section-header mb-4">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="section-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-0 text-dark">Contact Principal</h5>
                                                </div>
                                                <div class="section-divider mt-2"></div>
                                            </div>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text" name="firstname" maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm @error('firstname') is-invalid @enderror"
                                                            id="firstname" placeholder="Votre prénom"
                                                            value="{{ old('firstname') }}" required>
                                                        <label for="firstname" class="fw-medium text-muted">
                                                            <i class="fas fa-user me-2 text-primary"></i>
                                                            Prénom <span class="text-danger">*</span>
                                                        </label>
                                                        @error('firstname')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text" name="lastname" maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm @error('lastname') is-invalid @enderror"
                                                            id="lastname" placeholder="Votre nom"
                                                            value="{{ old('lastname') }}" required>
                                                        <label for="lastname" class="fw-medium text-muted">
                                                            <i class="fas fa-user me-2 text-primary"></i>
                                                            Nom <span class="text-danger">*</span>
                                                        </label>
                                                        @error('lastname')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="email" name="email" maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm @error('email') is-invalid @enderror"
                                                            id="email" placeholder="votre@email.com"
                                                            value="{{ old('email') }}" required>
                                                        <label for="email" class="fw-medium text-muted">
                                                            <i class="fas fa-envelope me-2 text-primary"></i>
                                                            Email professionnel <span class="text-danger">*</span>
                                                        </label>
                                                        @error('email')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <input type="text" name="zone" maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm @error('zone') is-invalid @enderror"
                                                            id="zone" value="{{ old('zone') }}"
                                                            placeholder="Zone géographique">
                                                        <label for="zone" class="fw-medium text-muted">
                                                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                            Zone géographique
                                                        </label>
                                                        @error('zone')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Section Localisation -->
                                        <div class="form-section mb-3">
                                            <div class="section-header">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="section-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-0 text-dark">Localisation</h5>
                                                </div>
                                                <div class="section-divider mt-2"></div>
                                            </div>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <select required id="country_id" name="country_id"
                                                            class="form-select border border-2 border-light rounded-3 shadow-sm @error('country_id') is-invalid @enderror">
                                                            <option disabled selected>Sélectionnez le pays</option>
                                                            @foreach ($countries as $countrie)
                                                                <option value="{{ $countrie->id }}"
                                                                    {{ old('country_id') == $countrie->id ? 'selected' : '' }}
                                                                    data-indicatif="{{ $countrie->indicatif ?? '' }}">
                                                                    {{ $countrie->name ?? null }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="country_id" class="fw-medium text-muted">
                                                            <i class="fas fa-flag me-2 text-primary"></i>
                                                            Pays <span class="text-danger">*</span>
                                                        </label>
                                                        @error('country_id')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating position-relative">
                                                        <input type="text" maxlength="15" name="phone"
                                                            class="form-control  rounded-3 @error('phone') is-invalid @enderror"
                                                            id="phone" placeholder="0909090909"
                                                            value="{{ old('phone') }}" required>
                                                        <label for="phone" class="fw-medium ml-input ">
                                                            <i class="bi bi-phone me-2 text-primary"></i>
                                                            Téléphone bénéficiaire <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="position-absolute top-50 start-0 translate-middle-y">
                                                            <span
                                                                class="badge bg-primary text-white bg-opacity-10 text-primary fw-bold px-2 py-2"
                                                                id="indice-countrie">
                                                                +--
                                                            </span>
                                                        </div>
                                                        @error('phone')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Password Section -->
                                        <div class="form-section mb-3">
                                            <div class="section-header mb-4">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="section-icon bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                                        <i class="fas fa-lock"></i>
                                                    </div>
                                                    <h5 class="fw-bold mb-0 text-dark">Sécurité du compte</h5>
                                                </div>
                                                <div class="section-divider mt-2"></div>
                                            </div>

                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <div class="form-floating position-relative">
                                                        <input type="password" name="password" maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm pe-5 @error('password') is-invalid @enderror"
                                                            id="password" placeholder="••••••••" required>
                                                        <label for="password" class="fw-medium text-muted">
                                                            <i class="fas fa-lock me-2 text-primary"></i>
                                                            Mot de passe <span class="text-danger">*</span>
                                                        </label>
                                                        <button
                                                            class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted me-3"
                                                            type="button" id="togglePassword">
                                                            <i class="far fa-eye"></i>
                                                        </button>
                                                        @error('password')
                                                            <div class="invalid-feedback d-block">
                                                                <strong>{{ $message }}</strong>
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <!-- Password Requirements -->
                                                    <div class="mt-3 p-3 bg-light rounded-3 border border-light">
                                                        <small class="text-muted fw-semibold d-block mb-2">Critères de
                                                            sécurité :</small>
                                                        <div id="length" class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-times text-danger me-2 fa-xs"></i>
                                                            <span class="small">8 caractères minimum</span>
                                                        </div>
                                                        <div id="uppercase" class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-times text-danger me-2 fa-xs"></i>
                                                            <span class="small">1 lettre majuscule</span>
                                                        </div>
                                                        <div id="number" class="d-flex align-items-center">
                                                            <i class="fas fa-times text-danger me-2 fa-xs"></i>
                                                            <span class="small">1 chiffre</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-floating position-relative">
                                                        <input type="password" name="password_confirmation"
                                                            maxlength="80"
                                                            class="form-control border border-2 border-light rounded-3 shadow-sm pe-5 @error('password_confirmation') is-invalid @enderror"
                                                            id="confirmPassword" placeholder="••••••••" required>
                                                        <label for="confirmPassword" class="fw-medium text-muted">
                                                            <i class="fas fa-lock me-2 text-primary"></i>
                                                            Confirmation <span class="text-danger">*</span>
                                                        </label>
                                                        <button
                                                            class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted me-3"
                                                            type="button" id="toggleConfirmPassword">
                                                            <i class="far fa-eye"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Password Match Indicators -->
                                                    <div id="passwordMatch"
                                                        class="mt-3 p-3 bg-danger bg-opacity-10 rounded-3 border border-danger border-opacity-25 d-none">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                                            <span class="small text-white">Les mots de passe ne
                                                                correspondent pas</span>
                                                        </div>
                                                    </div>

                                                    <div id="passwordMatchSuccess"
                                                        class="mt-3 p-3 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25 d-none">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-check-circle text-success me-2"></i>
                                                            <span class="small text-black">Les mots de passe
                                                                correspondent</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Terms and Conditions -->
                                        <div class="form-check mb-4 p-3 bg-light rounded">
                                            <input class="form-check-input" type="checkbox" name="terms"
                                                id="terms" required style="width: 1.2em; height: 1.2em;">
                                            <label class="form-check-label text-dark ms-2" for="terms">
                                                J'accepte les <a href="#"
                                                    class="text-primary text-decoration-none fw-semibold">conditions
                                                    générales</a>
                                                et la <a href="#"
                                                    class="text-primary text-decoration-none fw-semibold">politique de
                                                    confidentialité</a> <span class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit"
                                            class="btn btn-primary-custom btn-lg w-100 text-white py-3 mb-2 fs-6">
                                            <i class="fas fa-rocket me-2"></i>Créer mon compte
                                        </button>
                                    </form>



                                    <!-- Login Link -->
                                    <div class="text-center">
                                        <p class="text-muted mb-0">
                                            Vous avez déjà un compte ?
                                            <a href="#" class="text-primary text-decoration-none fw-semibold">Se
                                                connecter</a>
                                        </p>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {


                $('#country_id').on('change', function() {
                    var country_id = $('#country_id').val();
                    CountrieIndice(country_id);
                });

                function CountrieIndice(countryId) {
                    $.get('{{ url('transactions/robus/search-indice') }}/' + countryId, function(data) {

                        if (data) {

                            var indicative = data.indecative;
                            $('#indice-countrie').text(indicative);

                        } else {
                            $('#indice-countrie').text('Aucune information disponible');
                        }
                    });
                }

            });

            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
                const confirmInput = document.getElementById('confirmPassword');
                const icon = this.querySelector('i');

                if (confirmInput.type === 'password') {
                    confirmInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    confirmInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            // Password validation
            document.getElementById('password').addEventListener('input', function(e) {
                const password = e.target.value;
                const length = document.getElementById('length');
                const uppercase = document.getElementById('uppercase');
                const number = document.getElementById('number');

                // Check length
                updateValidationUI(length, password.length >= 8);

                // Check uppercase
                updateValidationUI(uppercase, /[A-Z]/.test(password));

                // Check number
                updateValidationUI(number, /[0-9]/.test(password));
            });

            function updateValidationUI(element, isValid) {
                const icon = element.querySelector('i');
                if (isValid) {
                    element.classList.remove('text-danger');
                    element.classList.add('text-success');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-check');
                } else {
                    element.classList.remove('text-success');
                    element.classList.add('text-danger');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-times');
                }
            }

            // Password match validation
            document.getElementById('confirmPassword').addEventListener('input', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = e.target.value;
                const matchMessage = document.getElementById('passwordMatch');

                if (confirmPassword && password !== confirmPassword) {
                    matchMessage.classList.remove('d-none');
                } else {
                    matchMessage.classList.add('d-none');
                }
            });

            // Form submission
            document.getElementById('registrationForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Basic validation
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (password !== confirmPassword) {
                    alert('Les mots de passe ne correspondent pas!');
                    return;
                }

                if (!document.getElementById('terms').checked) {
                    alert('Veuillez accepter les conditions générales!');
                    return;
                }

                // Simulate form submission
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Création en cours...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    alert('Compte créé avec succès! Un commercial vous contactera sous peu.');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            });
        </script>
    @endpush

@endsection
