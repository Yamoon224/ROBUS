@extends('layouts.master_integration')

@section('title', 'Contact - Support Robus')

@section('content')

    <section class="py-5 mt-8">
        <div class="auto-container my-4">
            <!-- Header -->
            <header class="mb-4">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <h1 class="h3 h2-md fw-bold text-dark mb-1">Contactez-nous</h1>
                        <p class="text-muted mb-0">Notre équipe est là pour vous accompagner dans votre intégration</p>
                    </div>
                    <div class="badge bg-info text-dark px-3 py-2 align-self-start align-self-md-center">
                        <span class="fw-semibold">Support :</span> Réponse sous 24h
                    </div>
                </div>
            </header>

            <div class="row g-3 g-md-4 justify-content-center">

                <!-- Sidebar Navigation - Desktop -->
                <div class="col-lg-6 d-lg-block">
                    <section id="support-technique" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-primary text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class=" rounded-circle p-3 me-3">
                                    <i class="bi bi-headset fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Support technique</h2>
                                    <p class="mb-0 text-white opacity-90 small">Notre équipe d'experts API à votre service
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Information Message -->
                            <div class="alert alert-info border-start border-info border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-2">Comment bénéficier du meilleur support ?
                                        </h5>
                                        <p class="mb-3">
                                            Pour une résolution rapide de vos problèmes techniques, veuillez nous fournir
                                            les informations suivantes dans votre demande :
                                        </p>
                                        <ul class="mb-0">
                                            <li>Votre clé API (partie publique uniquement)</li>
                                            <li>Le endpoint concerné</li>
                                            <li>Le code d'erreur renvoyé</li>
                                            <li>Les logs d'erreur (si disponibles)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Contact Methods -->
                            <div class="row g-4 mt-3">
                                <!-- Email Support -->
                                <div class="col-12 col-md-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-envelope-fill me-2"></i>
                                                Support par email
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                Pour les problèmes complexes nécessitant des explications détaillées ou
                                                l'envoi de fichiers.
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-clock text-primary me-2"></i>
                                                <span class="small">Réponse sous 24h ouvrées</span>
                                            </div>
                                            <a href="mailto:info@robus.africa" class="btn btn-primary w-100">
                                                <i class="bi bi-envelope me-2"></i>
                                                info@robus.africa
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Support -->
                                <div class="col-12 col-md-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-telephone-fill me-2"></i>
                                                Support téléphonique
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                Pour une assistance immédiate sur des problèmes urgents ou bloquants.
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-clock text-success me-2"></i>
                                                <span class="small">Pendant les heures d'ouverture</span>
                                            </div>
                                            <a href="tel:+2252722252147" class="btn btn-success w-100">
                                                <i class="bi bi-telephone me-2"></i>
                                                +225 07 11 74 58 81
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- WhatsApp Support -->
                                <div class="col-12 col-md-6">
                                    <div class="card border-info h-100">
                                        <div class="card-header bg-info text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-whatsapp me-2"></i>
                                                WhatsApp Business
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                Pour des échanges rapides, partage de screenshots et résolution en temps
                                                réel.
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-check-circle text-info me-2"></i>
                                                <span class="small">Réponse rapide garantie</span>
                                            </div>
                                            <a href="https://wa.me/2250711745881" target="_blank"
                                                class="btn btn-info text-white w-100">
                                                <i class="bi bi-whatsapp me-2"></i>
                                                Démarrer une conversation
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ticket System -->
                                <div class="col-12 col-md-6">
                                    <div class="card border-warning h-100">
                                        <div class="card-header bg-warning text-dark py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-ticket-detailed me-2"></i>
                                                Système de tickets
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                Pour suivre l'évolution de vos demandes et garder un historique complet.
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-graph-up text-warning me-2"></i>
                                                <span class="small">Statut en temps réel</span>
                                            </div>
                                            <a href="#" class="btn btn-warning w-100">
                                                <i class="bi bi-box-arrow-up-right me-2"></i>
                                                Accéder au portail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Emergency Contacts -->
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <div class="card border-danger h-100">
                                        <div class="card-header bg-danger text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-telephone-fill me-2"></i>
                                                Ligne d'urgence technique
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                Pour les incidents critiques affectant votre production (API inaccessible,
                                                transactions bloquées, etc.).
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-clock text-danger me-2"></i>
                                                <span class="small">24h/24 - 7j/7</span>
                                            </div>
                                            <a href="tel:+2250700000000" class="btn btn-danger w-100">
                                                <i class="bi bi-telephone me-2"></i>
                                                +225 07 11 74 58 81
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card border-warning h-100">
                                        <div class="card-header bg-warning text-dark py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-shield-exclamation me-2"></i>
                                                Incident de sécurité
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted mb-3">
                                                Pour signaler des vulnérabilités de sécurité, fuites de données
                                                ou activités suspectes.
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-envelope-check text-warning me-2"></i>
                                                <span class="small">Réponse immédiate</span>
                                            </div>
                                            <a href="mailto:info@robus.africa" class="btn btn-warning w-100">
                                                <i class="bi bi-envelope me-2"></i>
                                                info@robus.africa
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Section -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-4 d-flex align-items-center">
                                    <i class="bi bi-question-circle-fill text-primary me-2"></i>
                                    Questions fréquentes
                                </h5>

                                <div class="accordion" id="faqAccordion">
                                    <div class="accordion-item border-0 mb-3">
                                        <h2 class="accordion-header" id="faqHeading1">
                                            <button class="accordion-button collapsed rounded-3" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#faqCollapse1">
                                                <i class="bi bi-clock-history text-primary me-2"></i>
                                                Quel est le délai moyen de réponse ?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse1" class="accordion-collapse collapse"
                                            data-bs-parent="#faqAccordion">
                                            <div class="accordion-body bg-light rounded-bottom-3">
                                                <p class="mb-0">
                                                    Nous nous engageons à répondre à toutes les demandes dans un délai
                                                    maximum de 24 heures ouvrées.
                                                    Pour les problèmes urgents (incidents critiques), nous fournissons une
                                                    réponse initiale dans l'heure.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item border-0 mb-3">
                                        <h2 class="accordion-header" id="faqHeading2">
                                            <button class="accordion-button collapsed rounded-3" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#faqCollapse2">
                                                <i class="bi bi-shield-check text-primary me-2"></i>
                                                Mes données sont-elles sécurisées ?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse2" class="accordion-collapse collapse"
                                            data-bs-parent="#faqAccordion">
                                            <div class="accordion-body bg-light rounded-bottom-3">
                                                <p class="mb-0">
                                                    Absolument. Toutes nos communications sont chiffrées et nous ne
                                                    partageons
                                                    jamais vos informations avec des tiers. Vos clés API privées ne doivent
                                                    jamais être partagées par email - utilisez notre portail sécurisé pour
                                                    toute transmission d'informations sensibles.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item border-0 mb-3">
                                        <h2 class="accordion-header" id="faqHeading3">
                                            <button class="accordion-button collapsed rounded-3" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#faqCollapse3">
                                                <i class="bi bi-translate text-primary me-2"></i>
                                                Proposez-vous un support en plusieurs langues ?
                                            </button>
                                        </h2>
                                        <div id="faqCollapse3" class="accordion-collapse collapse"
                                            data-bs-parent="#faqAccordion">
                                            <div class="accordion-body bg-light rounded-bottom-3">
                                                <p class="mb-0">
                                                    Oui, notre équipe de support parle couramment français et anglais.
                                                    Nous pouvons également traiter les demandes en espagnol avec un léger
                                                    délai supplémentaire pour certaines requêtes complexes.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>
                </div>

                <!-- Main Content -->
                <div class="col-12 col-lg-6">
                    <!-- Contact Commercial Section -->
                    <section id="contact-commercial" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class=" rounded-circle p-3 me-3">
                                    <i class="bi bi-person-badge fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Contact commercial</h2>
                                    <p class="mb-0 text-white opacity-90 small">Développez votre activité avec nos
                                        solutions</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Description -->
                            <div class="alert alert-success border-start border-success border-4 bg-light mb-4">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-2">Un partenariat sur mesure</h5>
                                        <p class="mb-0">
                                            Notre équipe commerciale est à votre disposition pour étudier vos besoins
                                            spécifiques,
                                            vous proposer des solutions adaptées et négocier des conditions avantageuses
                                            pour
                                            votre entreprise.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Form -->
                            <div class="row g-4">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                        <i class="bi bi-send-fill text-success me-2"></i>
                                        Demande d'information commerciale
                                    </h5>
                                </div>

                                <form method="POST" action="{{ route('web.prospect_store.sends') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-12 col-md-6">
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

                                        <div class="col-12 col-md-6">
                                            <div class="form-floating">
                                                <input type="text" name="phone" maxlength="80"
                                                    class="form-control border border-2 border-light rounded-3 shadow-sm @error('phone') is-invalid @enderror"
                                                    value="{{ old('phone') }}" id="phone"
                                                    placeholder="Nom de votre entreprise" required>
                                                <label for="phone" class="fw-medium text-muted">
                                                    <i class="bi bi-phone me-2 text-primary"></i>
                                                    Téléphone <span class="text-danger">*</span>
                                                </label>
                                                @error('phone')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="form-floating">
                                                <input type="email" name="email" maxlength="80"
                                                    class="form-control border border-2 border-light rounded-3 shadow-sm @error('email') is-invalid @enderror"
                                                    id="email" placeholder="votre@email.com"
                                                    value="{{ old('email') }}" required>
                                                <label for="email" class="fw-medium text-muted">
                                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                                    Email <span class="text-danger">*</span>
                                                </label>
                                                @error('email')
                                                    <div class="invalid-feedback d-block">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-12 col-md-6">
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

                                        <div class="col-12">
                                            <div>
                                                <label for="description">Description de votre projet <span class="text-danger">*</span></label>

                                                <textarea class="form-control mt-3" name="description" placeholder="Décrivez votre préoccupation" cols="15"
                                                    rows="10"></textarea>
                                            </div>
                                        </div>



                                    </div>

                                    <div class="col-12 mt-3">
                                        <button class="btn btn-success btn-lg px-4">
                                            <i class="bi bi-send me-2"></i>
                                            Envoyer ma demande
                                        </button>
                                    </div>

                                </form>

                            </div>

                            <!-- Additional Contact Info -->
                            <div class="row g-4 mt-5">

                                <!-- Emergency Protocol -->
                                <div class="mt-5">
                                    <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                        <i class="bi bi-list-check text-danger me-2"></i>
                                        Protocole d'urgence
                                    </h5>

                                    <div class="row g-3">
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card border-danger h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-3"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-1-circle-fill text-white fs-4"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-2">Identifier l'urgence</h6>
                                                    <p class="text-muted small mb-0">Vérifiez que l'incident affecte votre
                                                        production</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card border-danger h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-3"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-2-circle-fill text-white fs-4"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-2">Contacter l'urgence</h6>
                                                    <p class="text-muted small mb-0">Utilisez les numéros dédiés aux
                                                        urgences
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card border-danger h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-3"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="bi bi-3-circle-fill text-white fs-4"></i>
                                                    </div>
                                                    <h6 class="fw-semibold mb-2">Suivre les instructions</h6>
                                                    <p class="text-muted small mb-0">Notre équipe vous guide vers la
                                                        résolution
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Emergency Checklist -->
                                <div class="mt-4">
                                    <div class="alert alert-light border d-flex align-items-start">
                                        <i class="bi bi-lightbulb-fill text-warning fs-4 me-3 mt-1"></i>
                                        <div>
                                            <strong class="text-dark">Checklist avant d'appeler :</strong>
                                            <ul class="mb-0 mt-2">
                                                <li class="text-muted">Vérifiez votre connexion Internet</li>
                                                <li class="text-muted">Testez l'endpoint avec différents paramètres</li>
                                                <li class="text-muted">Vérifiez vos clés API et leur validité</li>
                                                <li class="text-muted">Consultez le statut de nos services sur
                                                    status.robus.ci
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>
                </div>

            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Script pour la fonctionnalité de copie (si nécessaire)
            document.querySelectorAll('.copy-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const target = document.querySelector(this.getAttribute('data-clipboard-target'));
                    const textToCopy = target.textContent || target.innerText;

                    navigator.clipboard.writeText(textToCopy).then(() => {
                        // Feedback visuel temporaire
                        const originalHTML = this.innerHTML;
                        const originalTitle = this.getAttribute('data-bs-title') || '';

                        this.innerHTML = '<i class="bi bi-check"></i>';
                        this.setAttribute('data-bs-title', 'Copié!');

                        // Mise à jour du tooltip si Bootstrap est disponible
                        if (typeof bootstrap !== 'undefined') {
                            const tooltip = bootstrap.Tooltip.getInstance(this);
                            if (tooltip) {
                                tooltip.setContent({
                                    '.tooltip-inner': 'Copié!'
                                });
                                tooltip.show();
                            }
                        }

                        this.classList.remove('btn-outline-light', 'btn-outline-secondary');
                        this.classList.add('btn-success');

                        setTimeout(() => {
                            this.innerHTML = originalHTML;
                            this.setAttribute('data-bs-title', originalTitle);
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-light', 'btn-outline-secondary');

                            // Restaurer le tooltip original
                            if (typeof bootstrap !== 'undefined') {
                                const tooltip = bootstrap.Tooltip.getInstance(this);
                                if (tooltip) {
                                    tooltip.setContent({
                                        '.tooltip-inner': originalTitle
                                    });
                                }
                            }
                        }, 2000);
                    }).catch(err => {
                        console.error('Erreur lors de la copie: ', err);
                    });
                });
            });

            // Initialiser les tooltips Bootstrap
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Smooth scroll pour les ancres
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    @endpush

@endsection
