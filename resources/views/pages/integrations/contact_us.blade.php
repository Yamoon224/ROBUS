@extends('layouts.master_integration')

@section('title', 'Contact - Support Robus')

@section('content')

    <section class="py-5 mt-8">
        <div class="container my-4">
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
                <!-- Sidebar Navigation - Mobile Collapsible -->
                <div class="col-12 d-lg-none mb-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <button class="btn btn-outline-primary w-100 d-flex justify-content-between align-items-center"
                                type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                                <span>Informations de contact</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        </div>
                        <div class="collapse" id="mobileSidebar">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold mb-3">Nos canaux</h5>
                                <ul class="nav nav-pills flex-column gap-2">
                                    <li class="nav-item">
                                        <a href="#support-technique" class="nav-link active d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                                            <i class="bi bi-headset me-2"></i>
                                            Support technique
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#contact-commercial" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                                            <i class="bi bi-person-badge me-2"></i>
                                            Contact commercial
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#urgences" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Urgences
                                        </a>
                                    </li>
                                </ul>

                                <hr class="my-4">

                                <h5 class="card-title fw-semibold mb-3">Horaires d'ouverture</h5>
                                <ul class="list-unstyled small">
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Lundi - Vendredi</span>
                                        <span class="fw-semibold">8h00 - 18h00</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Samedi</span>
                                        <span class="fw-semibold">9h00 - 13h00</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Dimanche</span>
                                        <span class="fw-semibold text-danger">Fermé</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Navigation - Desktop -->
                <div class="col-lg-3 d-lg-block">
                    <nav class="card shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-3">Nos canaux de contact</h5>
                            <ul class="nav nav-pills flex-column gap-2">
                                <li class="nav-item">
                                    <a href="#support-technique" class="nav-link active d-flex align-items-center">
                                        <i class="bi bi-headset me-2"></i>
                                        Support technique
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#contact-commercial" class="nav-link text-dark d-flex align-items-center">
                                        <i class="bi bi-person-badge me-2"></i>
                                        Contact commercial
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#urgences" class="nav-link text-dark d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Urgences
                                    </a>
                                </li>
                            </ul>

                            <hr class="my-4">

                            <h5 class="card-title fw-semibold mb-3">Horaires d'ouverture</h5>
                            <ul class="list-unstyled small">
                                <li class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Lundi - Vendredi</span>
                                    <span class="fw-semibold">8h00 - 18h00</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Samedi</span>
                                    <span class="fw-semibold">9h00 - 13h00</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Dimanche</span>
                                    <span class="fw-semibold text-danger">Fermé</span>
                                </li>
                            </ul>

                            <hr class="my-4">

                            <h5 class="card-title fw-semibold mb-3">Contact rapide</h5>
                            <ul class="list-unstyled small">
                                <li class="d-flex align-items-center text-primary mb-2">
                                    <i class="bi bi-telephone-fill me-2"></i>
                                    <span>+225 27 22 25 21 47</span>
                                </li>
                                <li class="d-flex align-items-center text-primary mb-2">
                                    <i class="bi bi-whatsapp me-2"></i>
                                    <span>WhatsApp Business</span>
                                </li>
                                <li class="d-flex align-items-center text-primary mb-2">
                                    <i class="bi bi-envelope-fill me-2"></i>
                                    <span>support@robus.ci</span>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="col-12 col-lg-9">
                    <!-- Support Technique Section -->
                    <section id="support-technique" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-primary text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                    <i class="bi bi-headset fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Support technique</h2>
                                    <p class="mb-0 text-white opacity-90 small">Notre équipe d'experts API à votre service</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Information Message -->
                            <div class="alert alert-info border-start border-info border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-2">Comment bénéficier du meilleur support ?</h5>
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
                                            <a href="mailto:support@robus.ci" class="btn btn-primary w-100">
                                                <i class="bi bi-envelope me-2"></i>
                                                support@robus.ci
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
                                                +225 27 22 25 21 47
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
                                                Pour des échanges rapides, partage de screenshots et résolution en temps réel.
                                            </p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="bi bi-check-circle text-info me-2"></i>
                                                <span class="small">Réponse rapide garantie</span>
                                            </div>
                                            <a href="https://wa.me/2252722252147" target="_blank" class="btn btn-info text-white w-100">
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
                                                    Nous nous engageons à répondre à toutes les demandes dans un délai maximum de 24 heures ouvrées. 
                                                    Pour les problèmes urgents (incidents critiques), nous fournissons une réponse initiale dans l'heure.
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
                                                    Absolument. Toutes nos communications sont chiffrées et nous ne partageons 
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

                    <!-- Contact Commercial Section -->
                    <section id="contact-commercial" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                    <i class="bi bi-person-badge fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Contact commercial</h2>
                                    <p class="mb-0 text-white opacity-90 small">Développez votre activité avec nos solutions</p>
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
                                            Notre équipe commerciale est à votre disposition pour étudier vos besoins spécifiques, 
                                            vous proposer des solutions adaptées et négocier des conditions avantageuses pour 
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

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="companyName" placeholder="Nom de votre entreprise">
                                        <label for="companyName">Nom de l'entreprise *</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="contactPerson" placeholder="Personne à contacter">
                                        <label for="contactPerson">Personne à contacter *</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="commercialEmail" placeholder="Adresse email">
                                        <label for="commercialEmail">Adresse email *</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="commercialPhone" placeholder="Numéro de téléphone">
                                        <label for="commercialPhone">Numéro de téléphone</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <select class="form-select" id="businessType">
                                            <option selected>Sélectionnez votre secteur d'activité</option>
                                            <option value="fintech">Fintech / Services financiers</option>
                                            <option value="ecommerce">E-commerce</option>
                                            <option value="telecom">Télécommunications</option>
                                            <option value="logistics">Logistique & Transport</option>
                                            <option value="other">Autre</option>
                                        </select>
                                        <label for="businessType">Secteur d'activité *</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="Décrivez votre projet" id="projectDescription" style="height: 120px"></textarea>
                                        <label for="projectDescription">Description de votre projet *</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="newsletterConsent">
                                        <label class="form-check-label small text-muted" for="newsletterConsent">
                                            Je souhaite recevoir la newsletter Robus avec les dernières actualités et offres
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-success btn-lg px-4">
                                        <i class="bi bi-send me-2"></i>
                                        Envoyer ma demande
                                    </button>
                                </div>
                            </div>

                            <!-- Additional Contact Info -->
                            <div class="row g-4 mt-5">
                                <div class="col-12">
                                    <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill text-success me-2"></i>
                                        Rencontrons-nous
                                    </h5>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card border-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <i class="bi bi-building text-success fs-5 me-3 mt-1"></i>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Siège social</h6>
                                                    <p class="text-muted mb-0 small">
                                                        Abidjan, Plateau<br>
                                                        Avenue Chardy, Immeuble CCIA<br>
                                                        01 BP 1234 Abidjan 01
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="#" class="btn btn-outline-success btn-sm w-100">
                                                <i class="bi bi-map me-1"></i>
                                                Voir sur la carte
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card border-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <i class="bi bi-calendar-check text-success fs-5 me-3 mt-1"></i>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Prendre rendez-vous</h6>
                                                    <p class="text-muted mb-0 small">
                                                        Réservez un créneau avec notre équipe commerciale 
                                                        pour une démonstration personnalisée.
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="#" class="btn btn-outline-success btn-sm w-100">
                                                <i class="bi bi-calendar-plus me-1"></i>
                                                Planifier un rendez-vous
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Urgences Section -->
                    <section id="urgences" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-danger text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                    <i class="bi bi-exclamation-triangle fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Service d'urgences</h2>
                                    <p class="mb-0 text-white opacity-90 small">Pour les incidents critiques affectant votre production</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Warning Alert -->
                            <div class="alert alert-danger border-start border-danger border-4 bg-light mb-4">
                                <div class="d-flex">
                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-2">Utilisation réservée aux urgences critiques</h5>
                                        <p class="mb-0">
                                            Ce service est exclusivement réservé aux incidents bloquant votre activité en production. 
                                            Pour les problèmes non urgents, utilisez les canaux de support standard.
                                        </p>
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
                                                +225 07 00 00 00 00
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
                                            <a href="mailto:security@robus.ci" class="btn btn-warning w-100">
                                                <i class="bi bi-envelope me-2"></i>
                                                security@robus.ci
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Emergency Protocol -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                    <i class="bi bi-list-check text-danger me-2"></i>
                                    Protocole d'urgence
                                </h5>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card border-danger h-100">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-1-circle-fill text-danger fs-4"></i>
                                                </div>
                                                <h6 class="fw-semibold mb-2">Identifier l'urgence</h6>
                                                <p class="text-muted small mb-0">Vérifiez que l'incident affecte votre production</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card border-danger h-100">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-2-circle-fill text-danger fs-4"></i>
                                                </div>
                                                <h6 class="fw-semibold mb-2">Collecter les informations</h6>
                                                <p class="text-muted small mb-0">Rassemblez logs, codes d'erreur et preuves</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card border-danger h-100">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-3-circle-fill text-danger fs-4"></i>
                                                </div>
                                                <h6 class="fw-semibold mb-2">Contacter l'urgence</h6>
                                                <p class="text-muted small mb-0">Utilisez les numéros dédiés aux urgences</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="card border-danger h-100">
                                            <div class="card-body text-center p-3">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: 60px; height: 60px;">
                                                    <i class="bi bi-4-circle-fill text-danger fs-4"></i>
                                                </div>
                                                <h6 class="fw-semibold mb-2">Suivre les instructions</h6>
                                                <p class="text-muted small mb-0">Notre équipe vous guide vers la résolution</p>
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
                                            <li class="text-muted">Consultez le statut de nos services sur status.robus.ci</li>
                                        </ul>
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