@extends('layouts.master_integration')

@section('title', 'Documentation Api')

@section('content')

    <section class="py-5 mt-8">
        <div class="auto-container my-4">
            <!-- Header -->
            <header class="mb-4">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <h1 class="h3 h2-md fw-bold text-dark mb-1">Documentation API</h1>
                        <p class="text-muted mb-0">Intégrez facilement les services Robus dans votre application</p>
                    </div>
                    <div class="badge bg-info text-dark px-3 py-2 text-white align-self-start align-self-md-center">
                        <span class="fw-semibold">Environnement :</span> Production
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
                                <span>Navigation & Codes Réponse</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        </div>
                        <div class="collapse" id="mobileSidebar">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold mb-3">Endpoints</h5>
                                <ul class="nav nav-pills flex-column gap-2">
                                    <li class="nav-item">
                                        <a href="#authentication" class="nav-link active d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#authentication">
                                            <i class="bi bi-key me-2"></i>
                                            Authentification
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#dashboard" class="nav-link  d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#dashboard">
                                            <i class="bi bi-speedometer2 me-2"></i>
                                            Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#providers" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#providers">
                                            <i class="bi bi-people me-2"></i>
                                            Providers
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#phone-credit" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#phone-credit">
                                            <i class="bi bi-phone me-2"></i>
                                            Crédit Téléphonique
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#transaction" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#transaction">
                                            <i class="bi bi-shuffle me-2"></i>
                                            Transactions
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#push_tresort" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#push_tresort">
                                            <i class="bi bi-arrow-left-right me-2"></i>
                                            Transactions Push & Tresort Money
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#djamo" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#djamo">
                                            <i class="bi bi-wallet2 me-2"></i>
                                            Transactions Djamo
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#paiements" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#paiements">
                                            <i class="bi bi-credit-card me-2"></i>
                                            Transactions des Paiements
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#virements" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#virements">
                                            <i class="bi bi-bank me-2"></i>
                                            Transactions des Virements
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#xbets" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#xbets">
                                            <i class="bi bi-dice-5 me-2"></i>
                                            Transactions des Xbets
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#transaction_inters" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#transaction_inters">
                                            <i class="bi bi-globe me-2"></i>
                                            Transactions internationales
                                        </a>
                                    </li>
                                </ul>

                                <hr class="my-4">

                                <h5 class="card-title fw-semibold mb-3">Codes Réponse</h5>
                                <ul class="list-unstyled small">
                                    <li class="d-flex align-items-center text-success mb-2">
                                        <span class="badge bg-success me-2">&nbsp;</span>
                                        200 - Succès
                                    </li>
                                    <li class="d-flex align-items-center text-danger mb-2">
                                        <span class="badge bg-danger me-2">&nbsp;</span>
                                        333 - Error de creation de transfert du montant
                                    </li>
                                </ul>

                                <div class="mt-5">
                                    <h5 class="fw-semibold mb-4 d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                        Codes d'erreur possibles
                                    </h5>

                                    <div class="row g-3">
                                        <div class="col-12 col-md-12">
                                            <div class="card border-danger h-100">
                                                <div class="card-header bg-danger text-white py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-white text-danger fs-6 me-2">301</span>
                                                        <h6 class="fw-bold mb-0">Application indisponible</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-3">L'application Robus n'est pas
                                                        disponible ou en maintenance.</p>
                                                    <div class="d-flex align-items-center text-danger small">
                                                        <i class="bi bi-cloud-slash me-1"></i>
                                                        <span>Vérifiez le statut de l'application</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-12">
                                            <div class="card border-warning h-100">
                                                <div class="card-header bg-warning text-dark py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-white text-warning fs-6 me-2">401</span>
                                                        <h6 class="fw-bold mb-0">Non autorisé</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-3">Token d'authentification manquant ou
                                                        invalide.</p>
                                                    <div class="d-flex align-items-center text-warning small">
                                                        <i class="bi bi-shield-lock me-1"></i>
                                                        <span>Vérifiez vos credentials</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-12">
                                            <div class="card border-info h-100">
                                                <div class="card-header bg-info text-white py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-white text-info fs-6 me-2">403</span>
                                                        <h6 class="fw-bold mb-0">Permissions insuffisantes</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-3">L'utilisateur n'a pas les permissions
                                                        nécessaires pour accéder aux providers.</p>
                                                    <div class="d-flex align-items-center text-info small">
                                                        <i class="bi bi-person-x me-1"></i>
                                                        <span>Contactez l'administrateur</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <div class="card border-warning h-100">
                                                <div class="card-header bg-warning text-dark py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-white text-warning fs-6 me-2">341</span>
                                                        <h6 class="fw-bold mb-0">Solde insuffisant</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-3">Le solde du compte est insuffisant
                                                        pour
                                                        effectuer cette transaction incluant les commissions.</p>
                                                    <div class="d-flex align-items-center text-warning small">
                                                        <i class="bi bi-wallet2 me-1"></i>
                                                        <span>Rechargez votre compte</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <div class="card border-info h-100">
                                                <div class="card-header bg-info text-dark py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-white text-warning fs-6 me-2">350</span>
                                                        <h6 class="fw-bold mb-0 text-white">Votre montant journalier est
                                                            atteint !!</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-3">Votre montant journalier est atteint
                                                        !! .</p>
                                                    <div class="d-flex align-items-center text-warning small">
                                                        <i class="bi bi-wallet2 me-1"></i>
                                                        <span>Transactions</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <div class="card border-info h-100">
                                                <div class="card-header bg-info text-dark py-3">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-white text-warning fs-6 me-2">403</span>
                                                        <h6 class="fw-bold mb-0 text-white">Niveau de pourcentage
                                                            introuvable !</h6>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted small mb-3">Niveau de pourcentage introuvable ! .
                                                    </p>
                                                    <div class="d-flex align-items-center text-warning small">
                                                        <i class="bi bi-wallet2 me-1"></i>
                                                        <span>Transactions</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Navigation - Desktop -->
                <div class="col-lg-3 d-lg-block">
                    <nav class="card shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold mb-3">Endpoints et Exemples</h5>
                            <ul class="nav nav-pills flex-column gap-2">
                                <li class="nav-item">
                                    <a href="#authentication" class="nav-link active d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#authentication">
                                        <i class="bi bi-key me-2"></i>
                                        Authentification
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#dashboard" class="nav-link  d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#dashboard">
                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#providers" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#providers">
                                        <i class="bi bi-people me-2"></i>
                                        Providers
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#phone-credit" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#phone-credit">
                                        <i class="bi bi-phone me-2"></i>
                                        Crédit Téléphonique
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#transaction" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#transaction">
                                        <i class="bi bi-shuffle me-2"></i>
                                        Transactions
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#push_tresort" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#push_tresort">
                                        <i class="bi bi-arrow-left-right me-2"></i>
                                        Transactions Push & Tresort Money
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#djamo" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#djamo">
                                        <i class="bi bi-wallet2 me-2"></i>
                                        Transactions Djamo
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#paiements" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#paiements">
                                        <i class="bi bi-credit-card me-2"></i>
                                        Transactions des Paiements
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#virements" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#virements">
                                        <i class="bi bi-bank me-2"></i>
                                        Transactions des Virements
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#xbets" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#xbets">
                                        <i class="bi bi-dice-5 me-2"></i>
                                        Transactions des Xbets
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#transaction_inters" class="nav-link text-dark d-flex align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#transaction_inters">
                                        <i class="bi bi-globe me-2"></i>
                                        Transactions internationales
                                    </a>
                                </li>

                            </ul>

                            <hr class="my-4">

                            <h5 class="card-title fw-semibold mb-3">Codes Réponse</h5>
                            <ul class="list-unstyled small">
                                <li class="d-flex align-items-center text-success mb-2">
                                    <span class="badge bg-success me-2">&nbsp;</span>
                                    200 - Succès
                                </li>
                                <li class="d-flex align-items-center text-danger mb-2">
                                    <span class="badge bg-danger me-2">&nbsp;</span>
                                    333 - Error de creation de transfert du montant
                                </li>
                            </ul>
                            <!-- Error Responses -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-4 d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                    Codes d'erreur possibles
                                </h5>

                                <div class="row g-3">
                                    <div class="col-12 col-md-12">
                                        <div class="card border-danger h-100">
                                            <div class="card-header bg-danger text-white py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-danger fs-6 me-2">301</span>
                                                    <h6 class="fw-bold mb-0">Application indisponible</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">L'application Robus n'est pas
                                                    disponible ou en maintenance.</p>
                                                <div class="d-flex align-items-center text-danger small">
                                                    <i class="bi bi-cloud-slash me-1"></i>
                                                    <span>Vérifiez le statut de l'application</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <div class="card border-warning h-100">
                                            <div class="card-header bg-warning text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">401</span>
                                                    <h6 class="fw-bold mb-0">Non autorisé</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Token d'authentification manquant ou
                                                    invalide.</p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-shield-lock me-1"></i>
                                                    <span>Vérifiez vos credentials</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12">
                                        <div class="card border-info h-100">
                                            <div class="card-header bg-info text-white py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-info fs-6 me-2">403</span>
                                                    <h6 class="fw-bold mb-0">Permissions insuffisantes</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">L'utilisateur n'a pas les permissions
                                                    nécessaires pour accéder aux providers.</p>
                                                <div class="d-flex align-items-center text-info small">
                                                    <i class="bi bi-person-x me-1"></i>
                                                    <span>Contactez l'administrateur</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="card border-warning h-100">
                                            <div class="card-header bg-warning text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">341</span>
                                                    <h6 class="fw-bold mb-0">Solde insuffisant</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Le solde du compte est insuffisant
                                                    pour
                                                    effectuer cette transaction incluant les commissions.</p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-wallet2 me-1"></i>
                                                    <span>Rechargez votre compte</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="card border-info h-100">
                                            <div class="card-header bg-info text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">350</span>
                                                    <h6 class="fw-bold mb-0 text-white">Votre montant journalier est
                                                        atteint !!</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Votre montant journalier est atteint !! .
                                                </p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-wallet2 me-1"></i>
                                                    <span>Transactions</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <div class="card border-info h-100">
                                            <div class="card-header bg-info text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">403</span>
                                                    <h6 class="fw-bold mb-0 text-white">Niveau de pourcentage introuvable !
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Niveau de pourcentage introuvable ! .</p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-wallet2 me-1"></i>
                                                    <span>Transactions</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="col-12 col-lg-9">
                    <!-- Authentication Section -->
                    <section id="authentication" class="card border-0 shadow-lg mb-5 collapse show">
                        <div class="card-header bg-gradient-primary text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class=" rounded-circle p-3 me-3">
                                    <i class="bi bi-shield-lock-fill text-black fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Authentification API</h2>
                                    <p class="mb-0 text-white opacity-90 small">Sécurisez vos requêtes avec vos clés API
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
                                        <h5 class="alert-heading fw-semibold mb-2">Comment utiliser vos clés API ?</h5>
                                        <p class="mb-3">
                                            Utilisez votre API Key et Secret Key pour authentifier vos requêtes HTTP.
                                            Incluez-les dans les en-têtes de votre requête POST vers l'endpoint de login.
                                        </p>
                                        <div class="bg-dark rounded-3 p-4 border position-relative mt-3">
                                            <button
                                                class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                data-clipboard-target="#auth-code" data-bs-toggle="tooltip"
                                                data-bs-title="Copier le code">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                            <code id="auth-code" class="text-light small font-monospace d-block">
                                                <span class="text-info">// Headers de la requête</span><br>
                                                {<br>
                                                &nbsp;&nbsp;<span class="text-warning">"X-API-Key"</span>:
                                                <span
                                                    class="text-success">"{{ substr('pk_zrsedavsbnnqsyrtts', 0, 10) }}..."</span>,<br>
                                                &nbsp;&nbsp;<span class="text-warning">"X-API-Secret"</span>:
                                                <span
                                                    class="text-success">"{{ substr('sk_zrsedavsbnnqsyrtts', 0, 10) }}..."</span><br>
                                                }
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Code Examples with Tabs -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                    <i class="bi bi-code-slash text-primary me-2"></i>
                                    Exemples d'implémentation
                                </h5>

                                <ul class="nav nav-pills mb-4" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#curl-tab"
                                            type="button" role="tab">
                                            <i class="bi bi-terminal me-2"></i>cURL
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#javascript-tab"
                                            type="button" role="tab">
                                            <i class="bi bi-file-code me-2"></i>JavaScript
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- cURL Tab -->
                                    <div class="tab-pane fade show active" id="curl-tab" role="tabpanel">
                                        <div class="bg-light rounded-3 p-4 border">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold text-dark">Requête cURL</span>
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-clipboard-target="#curl-login" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier le code">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                            </div>
                                            <pre id="curl-login" class="mb-0 code-block">curl -X POST \
                                                -H "X-API-Key: votre_api_key" \
                                                -H "X-API-Secret: votre_api_secret" \
                                                https://api.robus.com/api_clients/v1/auth/prospects/login</pre>
                                        </div>
                                    </div>

                                    <!-- JavaScript Tab -->
                                    <div class="tab-pane fade" id="javascript-tab" role="tabpanel">
                                        <div class="bg-light rounded-3 p-4 border">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold text-dark">Requête JavaScript</span>
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-clipboard-target="#js-login" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier le code">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                            </div>
                                            <pre id="js-login" class="mb-0 code-block">fetch('https://api.robus.com/api_clients/v1/auth/prospects/login', {
                                            method: 'POST',
                                            headers: {
                                                'X-API-Key': 'votre_api_key',
                                                'X-API-Secret': 'votre_api_secret'
                                            }
                                            })
                                            .then(res => res.json())
                                            .then(data => console.log(data));</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Response Section -->
                            <div class="mt-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold mb-0 d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Réponse de succès
                                    </h5>
                                    <span class="badge bg-success bg-opacity-20 text-success">Status 200</span>
                                </div>

                                <div class="bg-dark rounded-3 p-4 border position-relative">
                                    <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                        data-clipboard-target="#response-login" data-bs-toggle="tooltip"
                                        data-bs-title="Copier la réponse">
                                        <i class="bi bi-copy me-1"></i>Copier
                                    </button>
                                    <pre id="response-login" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"success"</span>: <span class="text-info">true</span>,
                                        <span class="text-success">"token"</span>: <span class="text-warning">"TOKEN_GENERATED_HERE"</span>,
                                        <span class="text-success">"integration"</span>: {
                                            <span class="text-success">"company_name"</span>: <span class="text-warning">"ROBUS"</span>,
                                            <span class="text-success">"logo_url"</span>: <span class="text-warning">"/images/logo_violet.jpeg"</span>,
                                            <span class="text-success">"theme_color"</span>: <span class="text-warning">"#1A73E8"</span>
                                        },
                                        <span class="text-success">"user"</span>: {
                                            <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                            <span class="text-success">"firstname"</span>: <span class="text-warning">"John"</span>,
                                            <span class="text-success">"lastname"</span>: <span class="text-warning">"Doe"</span>,
                                            <span class="text-success">"email"</span>: <span class="text-warning">"john@example.com"</span>,
                                            <span class="text-success">"phone"</span>: <span class="text-warning">"0546963728"</span>,
                                            <span class="text-success">"wallet"</span>: {
                                            <span class="text-success">"wallet_id"</span>: <span class="text-warning">"WALLET123"</span>,
                                            <span class="text-success">"amount"</span>: <span class="text-info">10000</span>,
                                            <span class="text-success">"currency"</span>: <span class="text-warning">"FCFA"</span>
                                            }
                                        }
                                        }</pre>
                                </div>
                            </div>


                        </div>
                    </section>

                    <section id="dashboard" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-primary text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class=" rounded-circle p-3 me-3">
                                    <i class="bi bi-speedometer2 text-black fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Dashboard API</h2>
                                    <p class="mb-0 text-white opacity-90 small">Sécurisez vos requêtes avec vos clés API
                                    </p>
                                </div>
                            </div>
                        </div>
                        

                        <div class="card-body p-4">
                               <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary badge-method">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/dashboard</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour récupérer la liste des providers disponibles dans le dashboard</p>
                                </div>
                                <span class="badge bg-success">Authentification requise</span>
                            </div>

                                     <!-- Information Message -->
                            <div class="alert alert-info border-start border-info border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-2">Comment utiliser vos dashboard ?</h5>
                                        <p class="mb-3">
                                            Pour accéder au Dashboard, vous devez inclure dans chaque requête votre
                                            <strong>token d’accès</strong> obtenu lors de l’authentification.
                                            Ajoutez-le dans l'en-tête HTTP :
                                                                    </p>
                                                                    <div class="bg-dark rounded-3 p-4 border position-relative mt-3">
                                                                        <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2 copy-btn"
                                                data-clipboard-target="#token-header" data-bs-toggle="tooltip"
                                                data-bs-title="Copier le code">
                                                <i class="bi bi-copy"></i>
                                            </button>

                                            <code id="token-header" class="text-light small">
                             Authorization: Bearer VOTRE_TOKEN
                                            </code>
                                            <button
                                                class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                data-clipboard-target="#auth-code" data-bs-toggle="tooltip"
                                                data-bs-title="Copier le code">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                            <code id="auth-code" class="text-light small font-monospace d-block">
                                                <span class="text-info">// Headers de la requête</span><br>
                                                {<br>
                                                &nbsp;&nbsp;<span class="text-warning">"X-API-Key"</span>:
                                                <span
                                                    class="text-success">"{{ substr('pk_zrsedavsbnnqsyrtts', 0, 10) }}..."</span>,<br>
                                                &nbsp;&nbsp;<span class="text-warning">"X-API-Secret"</span>:
                                                <span
                                                    class="text-success">"{{ substr('sk_zrsedavsbnnqsyrtts', 0, 10) }}..."</span><br>
                                                }
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Description & Workflow -->
                            <div class="alert alert-primary border-start border-primary border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint retourne une liste structurée des providers (opérateurs)
                                            disponibles
                                            pour l'utilisateur connecté, basée sur ses permissions et restrictions
                                            d'opérateur.
                                        </p>

                                        <!-- Workflow -->
                                        <div class="mb-4">
                                            <h6 class="fw-semibold mb-3">Processus de filtrage :</h6>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-4">
                                                    <div class="d-flex align-items-start">
                                                        <span class="badge bg-primary rounded-circle me-2 mt-1">1</span>
                                                        <div>
                                                            <h6 class="fw-semibold mb-1">Vérification application</h6>
                                                            <p class="text-muted small mb-0">Statut de l'application
                                                                Robus</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="d-flex align-items-start">
                                                        <span class="badge bg-primary rounded-circle me-2 mt-1">2</span>
                                                        <div>
                                                            <h6 class="fw-semibold mb-1">Récupération permissions</h6>
                                                            <p class="text-muted small mb-0">Services autorisés pour
                                                                l'opérateur</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="d-flex align-items-start">
                                                        <span class="badge bg-primary rounded-circle me-2 mt-1">3</span>
                                                        <div>
                                                            <h6 class="fw-semibold mb-1">Filtrage providers</h6>
                                                            <p class="text-muted small mb-0">Providers disponibles par
                                                                type</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-shield-check text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Gestion des permissions</h6>
                                                        <p class="text-muted small mb-0">Filtrage basé sur les
                                                            restrictions d'opérateur configurées</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-diagram-3 text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Structure hiérarchique</h6>
                                                        <p class="text-muted small mb-0">Organisation par catégories et
                                                            sous-catégories</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-geo-alt text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Filtrage par pays</h6>
                                                        <p class="text-muted small mb-0">Providers spécifiques au pays
                                                            de l'utilisateur</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-building text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Support distributeur/API</h6>
                                                        <p class="text-muted small mb-0">Adaptation selon le type de
                                                            compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                   

                            <!-- Code Examples with Tabs -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                    <i class="bi bi-code-slash text-primary me-2"></i>
                                    Exemples d'implémentation
                                </h5>

                                <ul class="nav nav-pills mb-4" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="pill"
                                            data-bs-target="#curl-dasboard-tab" type="button" role="tab">
                                            <i class="bi bi-terminal me-2"></i>cURL
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#javascript-dasboard-tab" type="button" role="tab">
                                            <i class="bi bi-file-code me-2"></i>JavaScript
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- cURL Tab -->
                                    <div class="tab-pane fade show active" id="curl-dasboard-tab" role="tabpanel">
                                        <div class="bg-light rounded-3 p-4 border">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold text-dark">Requête cURL</span>
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-clipboard-target="#curl-dasboard" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier le code">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                            </div>
                                            <pre id="curl-login" class="mb-0 code-block">curl -X POST \
                                                -H "X-API-Key: votre_api_key" \
                                                -H "X-API-Secret: votre_api_secret" \
                                                -H "Token: bearer token" \
                                                https://api.robus.com/api_clients/v1/user/dashboard</pre>
                                        </div>
                                    </div>

                                    <!-- JavaScript Tab -->
                                    <div class="tab-pane fade" id="javascript-dasboard-tab" role="tabpanel">
                                        <div class="bg-light rounded-3 p-4 border">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold text-dark">Requête JavaScript</span>
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-clipboard-target="#js-login" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier le code">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                            </div>
                                            <pre id="js-login" class="mb-0 code-block">fetch('https://api.robus.com/api_clients/v1/user/dashboard/login', {
                                            method: 'GET',
                                            headers: {
                                                'X-API-Key': 'votre_api_key',
                                                'X-API-Secret': 'votre_api_secret',
                                                'Bearer token': 'token bearer',
                                            }
                                            })
                                            .then(res => res.json())
                                            .then(data => console.log(data));</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Response Section -->
                            <div class="mt-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold mb-0 d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Réponse de succès
                                    </h5>
                                    <span class="badge bg-success bg-opacity-20 text-success">Status 200</span>
                                </div>

                                <div class="bg-dark rounded-3 p-4 border position-relative">
                                    <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                        data-clipboard-target="#response-historicals" data-bs-toggle="tooltip"
                                        data-bs-title="Copier la réponse">
                                        <i class="bi bi-copy me-1"></i>Copier
                                    </button>

                                    <pre id="response-historicals" class="text-light small mb-0 code-block">{
                                <span class="text-success">"success"</span>: <span class="text-info">true</span>,
                                <span class="text-success">"historicals"</span>: {
                                    <span class="text-success">"wallet_user"</span>: {
                                        <span class="text-success">"id"</span>: <span class="text-info">922</span>,
                                        <span class="text-success">"wallet_id"</span>: <span class="text-warning">"TRVT08A6"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-warning">"180 500,00"</span>,
                                        <span class="text-success">"reload_by"</span>: <span class="text-warning">"Luiui LuilUI"</span>,
                                        <span class="text-success">"status"</span>: <span class="text-info">true</span>,
                                        <span class="text-success">"user"</span>: {
                                            <span class="text-success">"firstname"</span>: <span class="text-warning">"Luiui"</span>,
                                            <span class="text-success">"id"</span>: <span class="text-info">67</span>,
                                            <span class="text-success">"lastname"</span>: <span class="text-warning">"LuilUI"</span>,
                                            <span class="text-success">"phone"</span>: <span class="text-warning">"1223221222222"</span>,
                                            // ... autres champs utilisateur omis pour la concision ...
                                            <span class="text-success">"currencie"</span>: <span class="text-warning">"CFA"</span>
                                        }
                                    },
                                    <span class="text-success">"Transactions"</span>: [],
                                    <span class="text-success">"Commission remonté"</span>: [],
                                    <span class="text-success">"Paiements"</span>: [
                                                {
                                                    <span class="text-success">"id"</span>: <span class="text-info">15</span>,
                                                    <span class="text-success">"provider"</span>: <span class="text-warning">"CIE"</span>,
                                                    <span class="text-success">"amount"</span>: <span class="text-warning">"1000.00"</span>,
                                                    <span class="text-success">"type"</span>: <span class="text-warning">"Paiement facture"</span>,
                                                    <span class="text-success">"status"</span>: <span class="text-warning">"PENDING"</span>,
                                                    <span class="text-success">"solde_account"</span>: <span class="text-warning">"181500 CFA"</span>
                                                }
                                            ],
                                            <span class="text-success">"Versements"</span>: [],
                                            <span class="text-success">"Credit telephoniques"</span>: [
                                                {
                                                    <span class="text-success">"id"</span>: <span class="text-info">86</span>,
                                                    <span class="text-success">"provider"</span>: <span class="text-warning">"MTN"</span>,
                                                    <span class="text-success">"amount"</span>: <span class="text-warning">"1000.00"</span>,
                                                    <span class="text-success">"type"</span>: <span class="text-warning">"Credit téléphonique"</span>,
                                                    <span class="text-success">"status"</span>: <span class="text-warning">"PENDING"</span>
                                                }
                                            ],
                                            <span class="text-success">"Push"</span>: [
                                                {
                                                    <span class="text-success">"id"</span>: <span class="text-info">15</span>,
                                                    <span class="text-success">"provider"</span>: <span class="text-warning">"Push"</span>,
                                                    <span class="text-success">"amount"</span>: <span class="text-warning">"3000.00"</span>,
                                                    <span class="text-success">"type"</span>: <span class="text-warning">"Tresort Money"</span>,
                                                    <span class="text-success">"status"</span>: <span class="text-warning">"PENDING"</span>
                                                }
                                            ],
                                            <span class="text-success">"Djamo"</span>: [
                                                {
                                                    <span class="text-success">"id"</span>: <span class="text-info">5</span>,
                                                    <span class="text-success">"provider"</span>: <span class="text-warning">"Djamo"</span>,
                                                    <span class="text-success">"amount"</span>: <span class="text-warning">"3000.00"</span>,
                                                    <span class="text-success">"type"</span>: <span class="text-warning">"Djamo"</span>,
                                                    <span class="text-success">"status"</span>: <span class="text-warning">"PENDING"</span>
                                                }
                                            ],
                                            <span class="text-success">"Tresort Money"</span>: [
                                                {
                                                    <span class="text-success">"id"</span>: <span class="text-info">2</span>,
                                                    <span class="text-success">"provider"</span>: <span class="text-warning">"Tresort Money"</span>,
                                                    <span class="text-success">"amount"</span>: <span class="text-warning">"3000.00"</span>,
                                                    <span class="text-success">"type"</span>: <span class="text-warning">"Tresort Money"</span>,
                                                    <span class="text-success">"status"</span>: <span class="text-warning">"PENDING"</span>
                                                }
                                            ],
                                            <span class="text-success">"Virements"</span>: [],
                                            <span class="text-success">"Bank send"</span>: []
                                        },
                                        <span class="text-success">"som_commission"</span>: <span class="text-warning">"0 CFA"</span>
                                    }</pre>
                                </div>
                            </div>


                        </div>
                    </section>

                    <section id="providers" class="card border-0 shadow-lg mb-5 collapse">


                        <!-- Main Endpoint Card -->
                        <div class="card-header bg-gradient-primary text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="feature-icon me-4">
                                    <i class="bi bi-list-check fs-4 text-white"></i>
                                </div>
                                <div>
                                    <h2 class="h3 fw-bold mb-1">Liste des API Providers</h2>
                                    <p class="mb-0 text-white opacity-90">Récupère les services disponibles selon les
                                        restrictions d'opérateur</p>
                                </div>
                            </div>

                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary badge-method">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/providers</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour récupérer la liste des
                                        providers disponibles</p>
                                </div>
                                <span class="badge bg-success">Authentification requise</span>
                            </div>

                            <!-- Description & Workflow -->
                            <div class="alert alert-primary border-start border-primary border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint retourne une liste structurée des providers (opérateurs)
                                            disponibles
                                            pour l'utilisateur connecté, basée sur ses permissions et restrictions
                                            d'opérateur.
                                        </p>

                                        <!-- Workflow -->
                                        <div class="mb-4">
                                            <h6 class="fw-semibold mb-3">Processus de filtrage :</h6>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-4">
                                                    <div class="d-flex align-items-start">
                                                        <span class="badge bg-primary rounded-circle me-2 mt-1">1</span>
                                                        <div>
                                                            <h6 class="fw-semibold mb-1">Vérification application</h6>
                                                            <p class="text-muted small mb-0">Statut de l'application
                                                                Robus</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="d-flex align-items-start">
                                                        <span class="badge bg-primary rounded-circle me-2 mt-1">2</span>
                                                        <div>
                                                            <h6 class="fw-semibold mb-1">Récupération permissions</h6>
                                                            <p class="text-muted small mb-0">Services autorisés pour
                                                                l'opérateur</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <div class="d-flex align-items-start">
                                                        <span class="badge bg-primary rounded-circle me-2 mt-1">3</span>
                                                        <div>
                                                            <h6 class="fw-semibold mb-1">Filtrage providers</h6>
                                                            <p class="text-muted small mb-0">Providers disponibles par
                                                                type</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-shield-check text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Gestion des permissions</h6>
                                                        <p class="text-muted small mb-0">Filtrage basé sur les
                                                            restrictions d'opérateur configurées</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-diagram-3 text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Structure hiérarchique</h6>
                                                        <p class="text-muted small mb-0">Organisation par catégories et
                                                            sous-catégories</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-geo-alt text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Filtrage par pays</h6>
                                                        <p class="text-muted small mb-0">Providers spécifiques au pays
                                                            de l'utilisateur</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-building text-primary me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Support distributeur/API</h6>
                                                        <p class="text-muted small mb-0">Adaptation selon le type de
                                                            compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Response Schema -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                    <i class="bi bi-code-slash text-primary me-2"></i>
                                    Exemples d'implémentation
                                </h5>

                                <ul class="nav nav-pills mb-4" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="pill"
                                            data-bs-target="#curl-providers-tab" type="button" role="tab">
                                            <i class="bi bi-terminal me-2"></i>cURL
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#javascript-providers-tab" type="button" role="tab">
                                            <i class="bi bi-file-code me-2"></i>JavaScript
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- cURL Tab -->
                                    <div class="tab-pane fade show active" id="curl-providers-tab" role="tabpanel">
                                        <div class="bg-light rounded-3 p-4 border">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold text-dark">Requête cURL</span>
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-clipboard-target="#curl-login" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier le code">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                            </div>
                                            <pre id="curl-login" class="mb-0 code-block">curl -X GET \
                                                -H "X-API-Key: votre_api_key" \
                                                -H "X-API-Secret: votre_api_secret" \
                                                -H "Bearer Token: accessToken" \
                                                https://api.robus.com/api_clients/v1/providers</pre>
                                        </div>
                                    </div>

                                    <!-- JavaScript Tab -->
                                    <div class="tab-pane fade" id="javascript-providers-tab" role="tabpanel">
                                        <div class="bg-light rounded-3 p-4 border">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="fw-semibold text-dark">Requête JavaScript</span>
                                                <button class="btn btn-sm btn-outline-primary copy-btn"
                                                    data-clipboard-target="#js-login" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier le code">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                            </div>
                                            <pre id="js-login" class="mb-0 code-block">fetch('https://api.robus.com/api_clients/v1/providers', {
                                            method: 'GET',
                                            headers: {
                                                'X-API-Key': 'votre_api_key',
                                                'X-API-Secret': 'votre_api_secret',
                                                'Bearer Token': 'accessToken',
                                            }
                                            })
                                            .then(res => res.json())
                                            .then(data => console.log(data));</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->



                            <div class="mt-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold mb-0 d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        Réponse de succès
                                    </h5>
                                    <span class="badge bg-success bg-opacity-20 text-success">Status 200</span>
                                </div>

                                <div class="bg-dark rounded-3 p-4 border position-relative">
                                    <button class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                        data-clipboard-target="#response-login" data-bs-toggle="tooltip"
                                        data-bs-title="Copier la réponse">
                                        <i class="bi bi-copy me-1"></i>Copier
                                    </button>
                                    <pre id="response-example" class="text-light small mb-0 code-block">{
                                            {
                                                    "success": true,
                                                    "Wallets": {
                                                        "Crédit téléphonique": [
                                                            {
                                                                "id": 20,
                                                                "name": "MOOV",
                                                                "country": "Côte d'Ivoire",
                                                                "provider_type": "Crédit téléphonique",
                                                                "image": "",
                                                                "amount": 0,
                                                            },
                                                            {
                                                                "id": 18,
                                                                "name": "MTN",
                                                                "country": "Côte d'Ivoire",
                                                                "provider_type": "Crédit téléphonique",
                                                                "image": "/providers/mtn.png",
                                                                "amount": 0,
                                                            },
                                                            {
                                                                "id": 19,
                                                                "name": "Orange",
                                                                "country": "Côte d'Ivoire",
                                                                "provider_type": "Crédit téléphonique",
                                                                "image": "/providers/orange.png",
                                                                "amount": 0,
                                                            }
                                                        ],
                                                        "Factures": [
                                                            {
                                                                "id": 7,
                                                                "name": "CANAL",
                                                                "country": "Côte d'Ivoire",
                                                                "provider_type": "Facture",
                                                                "image": "/providers/canal.png",
                                                                "amount": 0,
                                                            },
                                                            {
                                                                "id": 8,
                                                                "name": "CIE",
                                                                "country": "Côte d'Ivoire",
                                                                "provider_type": "Facture",
                                                                "image": "/providers/cie.png",
                                                                "amount": 0,
                                                            
                                                            },
                                                        ],
                                                        "Push": [],
                                                        "Djamo": [],
                                                        "Tresort": [],
                                                        "Versement": [],
                                                        "Transfert international": {
                                                            "Factures": [],
                                                            "Transfert": [
                                                                {
                                                                    "id": 51,
                                                                    "name": "ROBUS",
                                                                    "country": null,
                                                                    "provider_type": "MOBILE MONEY inter",
                                                                    "image": "/providers/robus.png",
                                                                    "amount": 0,
                                                                }
                                                            ],
                                                            "Crédit téléphonique": [],
                                                            "Versement bancaire": [],
                                                            "Rechargement de carte": [],
                                                            "Push": [],
                                                            "Djamo": [],
                                                            "Tresort": [],
                                                            "Bank Send": [],
                                                            "Versement": [],
                                                            "Xbets": []
                                                        },
                                                        "Versement bancaire": [
                                                            {
                                                                "id": 11,
                                                                "name": "ECOBANK",
                                                                "country": "Côte d'Ivoire",
                                                                "provider_type": "Versement bancaire",
                                                                "image": "/providers/ecobank.png",
                                                                "amount": 0,
                                                            },
                                                        ],
                                                        "Transfert national": {
                                                            "Dépôts": [],
                                                            "Mobile Money": []
                                                        }
                                                    }
                                                }
                                        }</pre>
                                </div>
                            </div>


                            <!-- Quick Notes -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Note importante :</strong>
                                        <span class="text-muted">
                                            La liste des providers retournée dépend des permissions de l'opérateur
                                            connecté
                                            et des services activés pour son compte.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </section>

                    <section id="phone-credit" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-phone-fill fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Achat de Crédit Téléphonique</h2>
                                    <p class="mb-0 text-white opacity-90 small">Rechargez facilement les numéros de
                                        téléphone</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code
                                        class="text-dark font-monospace fs-6">/api_clients/v1/user/phone_credits/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour l'achat de crédit téléphonique
                                    </p>
                                </div>
                            </div>

                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_phone_credit/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_phone_credit_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/phone_credit/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction de crédit téléphonique
                                    </p>
                                </div>
                            </div>


                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des achats de crédit téléphonique. Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">MTN (18), Orange (19), Moov
                                                            (20)
                                                            avec contrôle des numéros par pays</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-phone text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Format Côte d'Ivoire</h6>
                                                        <p class="text-muted small mb-0">10 chiffres obligatoires avec
                                                            validation des préfixes opérateurs</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>
                                                        <p class="text-muted small mb-0">Empêche l'envoi de crédit à
                                                            son
                                                            propre numéro</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">(18=MTN, 19=Orange,
                                                                    20=Moov)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>phone</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numéro de téléphone du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du crédit à envoyer</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">18</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0546963728"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"MTN"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de credit telephonique"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0546963728"</span>,
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2022-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête pour montant envoyé et du montant reçu
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"amount_pay"</span>: <span class="text-info">10</span>,
                                        <span class="text-success">"amount_send"</span>: <span class="text-warning">2000</span>,
                                        <span class="text-success">"amount_receive"</span>: <span class="text-info">1990</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                     Réponse de succès pour la recherche des montants envoyés et reçus
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                             <pre id="response-example" class="text-light small mb-0 code-block">{
                                                <span class="text-success">"Montant à payer"</span>: <span class="text-warning">"10"</span>,
                                                <span class="text-success">"Montant envoyé"</span>: <span class="text-warning">"2000"</span>,
                                                <span class="text-success">"Montant à recevoir"</span>: <span class="text-info">1990</span>
                                            }</pre>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro de téléphone est au format
                                            international et correspond à l'opérateur sélectionné.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="transaction" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-currency-exchange fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Transaction Robus</h2>
                                    <p class="mb-0 text-white opacity-90 small">Transférez facilement entres les
                                        comptes Robus</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code
                                        class="text-dark font-monospace fs-6">/api_clients/v1/user/transactions/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions
                                    </p>
                                </div>
                            </div>

                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_transaction/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_transaction_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/transaction/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction 
                                    </p>
                                </div>
                            </div>

                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des transactions entre comptes. Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">Robus
                                                            avec contrôle des numéros des utilisateurs</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>
                                                        <p class="text-muted small mb-0">Empêche l'envoi de transfert à
                                                            son
                                                            propre compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête ROBUS
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur(provider == 9)
                                                                <span class="text-muted">ROBUS</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>num_wallet</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Compte du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête Mobile Money
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">ROBUS</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>phone</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numero du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">5</span>,
                                        <span class="text-success">"account"</span>: <span class="text-warning">"CIAZ..."</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"ROBUS"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de transfert"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"wallet"</span>: <span class="text-warning">"CIAZ..."</span>,
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2025-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro de ROBUS est au format
                                            international et correspond à l'opérateur sélectionné.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="push_tresort" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-arrow-left-right fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Transactions Push et tresort Money</h2>
                                    <p class="mb-0 text-white opacity-90 small">Transférez facilement </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/push/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions Push
                                    </p>
                                </div>
                            </div>

                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/tresorts/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions Tresort Money</p>
                                </div>
                            </div>

                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_push/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_push_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés pour Push
                                        et reçus</p>
                                </div>
                            </div>
                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_tresorts/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_tresort_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés pour Trésort Money
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/push/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction Push
                                    </p>
                                </div>
                            </div>
                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/tresorts/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction Tresort Money
                                    </p>
                                </div>
                            </div>

                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des transactions entre comptes Robus. Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i
                                                        class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">Robus
                                                            avec contrôle des numéros des utilisateurs</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>
                                                        <p class="text-muted small mb-0">Empêche l'envoi de transfert à
                                                            son
                                                            propre compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête Push
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">Push</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>Phone</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Compte du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>full_name</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Nom & Prénom du bénéficiaires</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête Tresort Money
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">Tresort Money</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>phone</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numero du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>full_name</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>

                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Nom & Prénom du bénéficiaires</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">5</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0734352425"</span>,
                                        <span class="text-success">"full_name"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"ROBUS"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de transfert"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0734352425"</span>,
                                        <span class="text-success">"nom & Prénom"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2025-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro de ROBUS est au format
                                            international et correspond à l'opérateur sélectionné.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section id="djamo" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-wallet2 fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Transactions Djamo</h2>
                                    <p class="mb-0 text-white opacity-90 small">Transférez facilement </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/djamo/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions Djamo
                                    </p>
                                </div>
                            </div>
                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_djamo/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_djamo_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés Djamo
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/djamo/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction de crédit téléphonique
                                    </p>
                                </div>
                            </div>

                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des transactions Djamo. Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i
                                                        class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">Djamo
                                                            avec contrôle des numéros des utilisateurs</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>
                                                        <p class="text-muted small mb-0">Empêche l'envoi de transfert à
                                                            son
                                                            propre compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête Djamo
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">Djamo</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>Phone</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Compte du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>full_name</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Nom & Prénom du bénéficiaires</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>image_piece</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Le code QR djamo</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">5</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0734352425"</span>,
                                        <span class="text-success">"full_name"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"image_piece"</span>: <span class="text-warning">"image code QR"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"ROBUS"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de transfert"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0734352425"</span>,
                                        <span class="text-success">"nom & Prénom"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"Image QRCode"</span>: <span class="text-warning">"image_piece"</span>,
                                        
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2025-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro de ROBUS est au format
                                            international et correspond à l'opérateur sélectionné.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="paiements" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-credit-card fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Transactions Paiements</h2>
                                    <p class="mb-0 text-white opacity-90 small">Transférez facilement </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/payments/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions Paiements
                                    </p>
                                </div>
                            </div>

                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_payment/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_payment_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés des paiements
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/payment/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction des paiements
                                    </p>
                                </div>
                            </div>
                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des transactions des providers. Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i
                                                        class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">Robus
                                                            avec contrôle des numéros des utilisateurs</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>
                                                        <p class="text-muted small mb-0">Empêche l'envoi de transfert à
                                                            son
                                                            propre compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête CIE
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">CIE</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>id_invoice</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Id de la facture du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>name</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Nom & Prénom du bénéficiaires</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête SODECI
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">SODECI</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>number_contract</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numero du contract</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>name</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>

                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Nom & Prénom du bénéficiaires</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête CANAL
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">CANAL</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>offer</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Offre du canal</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>number_phone ou number_decoder </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>

                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numero ou numeor decodeur du bénéficiaires(selon le choix du
                                                                type de paiement)</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">5</span>,
                                        <span class="text-success">"id_invoice"</span>: <span class="text-warning">"0734352425112222"</span>,
                                        <span class="text-success">"name"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"ROBUS"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de transfert"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"id_invoice"</span>: <span class="text-warning">"0734352425112222"</span>,
                                        <span class="text-success">"nom & Prénom"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2025-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro de ROBUS est au format
                                            international et correspond à l'opérateur sélectionné.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="virements" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-bank fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Les virements</h2>
                                    <p class="mb-0 text-white opacity-90 small">Rechargez facilement les comptes bancaires
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code
                                        class="text-dark font-monospace fs-6">/api_clients/v1/user/virements/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour virements bancaires
                                    </p>
                                </div>
                            </div>

                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_virement/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_virement_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés pour les virements
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/virement/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction des virements
                                    </p>
                                </div>
                            </div>
                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des virements bancaires. Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i
                                                        class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">ECOBANK (11), NSIA (12)</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-phone text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Format Numero obligatoires</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">(12=NSIA, 11=ECOBANK)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>firstname</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Nom du bénéficiaire</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>lastname</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Prénom du bénéficiaire</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>number_compte</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numero du compte du bénéficiaire</td>
                                                        </tr>


                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>phone</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numéro de téléphone du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant à envoyer</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">11</span>,
                                        <span class="text-success">"firstname"</span>: <span class="text-info">"Charles"</span>,
                                        <span class="text-success">"lastname"</span>: <span class="text-info">"Dupond"</span>,
                                        <span class="text-success">"number_compte"</span>: <span class="text-info">121232132132131</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0546963728"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"MTN"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de credit telephonique"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"nom"</span>: <span class="text-warning">"Charles"</span>,
                                        <span class="text-success">"lastname"</span>: <span class="text-warning">"Dupond"</span>,
                                        <span class="text-success">"number_compte"</span>: <span class="text-warning">"121232132132131"</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0546963728"</span>,
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2022-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro du compte est au format
                                            international et correspond à l'opérateur sélectionné.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="xbets" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-dice-5 fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Les transactions Xbets</h2>
                                    <p class="mb-0 text-white opacity-90 small">Rechargez facilement les transactions
                                        Xbets</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/oneXbet/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions Xbets
                                    </p>
                                </div>
                            </div>
                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_onexbet/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_onexbet_received/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/oneXbet/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction des Xbets
                                    </p>
                                </div>
                            </div>

                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des virements bancaires. Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i
                                                        class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">Xbets</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-phone text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Format Numero obligatoires</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête Dépot Xbets
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">(12=NSIA, 11=ECOBANK)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>id_compte</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Numero du compte du bénéficiaire</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>full_name</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>NOm & prénom du bénéficiaire</td>
                                                        </tr>



                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant à envoyer</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">11</span>,
                                        <span class="text-success">"id_compte"</span>: <span class="text-info">"Numero du compte"</span>,
                                        <span class="text-success">"full_name"</span>: <span class="text-info">"Dupond"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"MTN"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de credit telephonique"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"nom & prenom"</span>: <span class="text-warning">"Charles"</span>,
                                        <span class="text-success">"id_compte"</span>: <span class="text-warning">"12123213132131"</span>,
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2022-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro du compte est au format
                                            international et correspond à l'opérateur sélectionné.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="transaction_inters" class="card border-0 shadow-lg mb-5 collapse">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle p-3 me-3">
                                    <i class="bi bi-globe fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Transactions a l'international</h2>
                                    <p class="mb-0 text-white opacity-90 small">Transférez facilement </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Endpoint Info -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-success fs-6 px-3 py-2">POST</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">/api_clients/v1/user/inters/store</code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions
                                    </p>
                                </div>
                            </div>


                             <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-primary fs-6 px-3 py-2">GET</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_phone_credit/{amount}
                                    </code>
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/search_phone_credit/{amount_received}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les recherches des montants envoyés
                                        et reçus</p>
                                </div>
                            </div>
                            <!-- DELETE -->
                            <div
                                class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 mb-4 p-4 bg-light rounded-3 border">
                                <span class="badge bg-danger fs-6 px-3 py-2">DELETE</span>
                                <div class="flex-grow-1">
                                    <code class="text-dark font-monospace fs-6">
                                        /api_clients/v1/user/phone_credit/delete/{id}
                                    </code>
                                    <p class="text-muted small mb-0 mt-1">
                                        Endpoint pour supprimer une transaction de crédit téléphonique
                                    </p>
                                </div>
                            </div>
                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des transactions a l'international . Le
                                            système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des
                                            transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i
                                                        class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">Robus
                                                            avec contrôle des numéros des utilisateurs</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-person-x text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Anti auto-envoi</h6>
                                                        <p class="text-muted small mb-0">Empêche l'envoi de transfert à
                                                            son
                                                            propre compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde
                                                            suffisant
                                                            incluant les commissions</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête des transactions a l'international sont faites par
                                                type
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider_type </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de type
                                                                <span class="text-muted">1</span>
                                                                <span class="text-muted">Ce type est pour les operations
                                                                    (ROBUS , PUSH, DJAMO , etc...)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider_type </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de type
                                                                <span class="text-muted">1</span>
                                                                <span class="text-muted">Ce type est pour les operations
                                                                    ROBUS</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider_type </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de type
                                                                <span class="text-muted">5</span>
                                                                <span class="text-muted">Ce type est pour les operations
                                                                    SODECI; CIE and SODECI</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider_type </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de type
                                                                <span class="text-muted">6</span>
                                                                <span class="text-muted">Ce type est pour les operations
                                                                    Crédit téléphonique</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider_type </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de type
                                                                <span class="text-muted">7</span>
                                                                <span class="text-muted">Ce type est pour les operations
                                                                    de rechargement des cartes(ECOBANK , NSIA)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider_type </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de type
                                                                <span class="text-muted">8</span>
                                                                <span class="text-muted">Ce type est pour les operations
                                                                    de virements bancaires(ECOBANK , NSIA)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider_type </code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de type
                                                                <span class="text-muted">9</span>
                                                                <span class="text-muted">Ce type est pour les operations
                                                                    de versements bancaires(UBA,GT BANK , BDA, NSIA ,
                                                                    etc...)</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Parameters Table -->
                                        <div class="mt-4">
                                            <h6 class="fw-semibold mb-3 d-flex align-items-center">
                                                <i class="bi bi-list-check text-success me-2"></i>
                                                Paramètres de la requête des transactions a l'international
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover mb-0">
                                                    <thead class="table-success">
                                                        <tr>
                                                            <th class="text-nowrap">Paramètre</th>
                                                            <th class="text-nowrap">Type</th>
                                                            <th class="text-nowrap">Obligatoire</th>
                                                            <th>Description</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>provider</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-secondary">integer</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>
                                                                ID de l'opérateur
                                                                <span class="text-muted">Push</span>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>Phone</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Compte du bénéficiaire</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>amount</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Montant du transfert à envoyer</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>full_name</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-info text-dark">string</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Nom & Prénom du bénéficiaires</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>country</code>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-warning text-dark">number</span>
                                                            </td>
                                                            <td class="text-nowrap">
                                                                <span class="badge bg-success">Oui</span>
                                                            </td>
                                                            <td>Ajouter le pays pour tout type de transaction avec les meme
                                                                providers et les meme propriétés pour chaque transactions
                                                                précedent</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request & Response Examples -->
                            <div class="row g-4 mt-2">
                                <!-- Request Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-primary h-100">
                                        <div class="card-header bg-primary text-white py-3">
                                            <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Exemple de requête
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#request-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la requête">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="request-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"provider"</span>: <span class="text-info">5</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0734352425"</span>,
                                        <span class="text-success">"country"</span>: <span class="text-warning">"107"</span>,
                                        <span class="text-success">"full_name"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Example -->
                                <div class="col-12 col-lg-6">
                                    <div class="card border-success h-100">
                                        <div class="card-header bg-success text-white py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="fw-semibold mb-0 d-flex align-items-center">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Réponse de succès
                                                </h6>
                                                <span class="badge bg-white text-success">200 OK</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="bg-dark rounded-bottom p-4 position-relative">
                                                <button
                                                    class="btn btn-sm btn-outline-light position-absolute top-0 end-0 m-3 copy-btn"
                                                    data-clipboard-target="#response-example" data-bs-toggle="tooltip"
                                                    data-bs-title="Copier la réponse">
                                                    <i class="bi bi-copy me-1"></i>Copier
                                                </button>
                                                <pre id="response-example" class="text-light small mb-0 code-block">{
                                        <span class="text-success">"id"</span>: <span class="text-info">1</span>,
                                        <span class="text-success">"provider"</span>: <span class="text-warning">"ROBUS"</span>,
                                        <span class="text-success">"description"</span>: <span class="text-warning">"Demande de transfert"</span>,
                                        <span class="text-success">"amount"</span>: <span class="text-info">1000</span>,
                                        <span class="text-success">"phone"</span>: <span class="text-warning">"0734352425"</span>,
                                        <span class="text-success">"pays"</span>: <span class="text-warning">"COTE D'IVOIRE"</span>,
                                        <span class="text-success">"nom & Prénom"</span>: <span class="text-warning">"Charle Dupond"</span>,
                                        <span class="text-success">"status"</span>: <span class="text-warning">"Pending"</span>,
                                        <span class="text-success">"update_at"</span>: <span class="text-warning">"2025-08-11T09:25:53.000000Z"</span>
                                        }</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Action Note -->
                            <div class="mt-4">
                                <div class="alert alert-light border d-flex align-items-center">
                                    <i class="bi bi-lightbulb-fill text-warning fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-dark">Conseil :</strong>
                                        <span class="text-muted">Assurez-vous que le numéro de ROBUS est au format
                                            international et correspond à l'opérateur sélectionné.</span>
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
            // Script pour la fonctionnalité de copie
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

            document.addEventListener('DOMContentLoaded', function() {

                // Initialiser les tooltips Bootstrap
                if (typeof bootstrap !== 'undefined') {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
                }

                // Fonction pour mettre à jour les liens actifs et collapse
                function activateCollapse(targetId) {
                    document.querySelectorAll('.collapse').forEach(collapseEl => {
                        const collapseInstance = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap
                            .Collapse(collapseEl, {
                                toggle: false
                            });

                        if (collapseEl.id === targetId) {
                            collapseInstance.show();
                        } else {
                            collapseInstance.hide();
                        }
                    });

                    // Mise à jour des classes des liens
                    document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]').forEach(link => {
                        if (link.getAttribute('data-bs-target') === `#${targetId}`) {
                            link.classList.add('active');
                            link.classList.remove('text-dark');
                        } else {
                            link.classList.remove('active');
                            link.classList.add('text-dark');
                        }
                    });
                }

                // Initialiser la section par défaut si elle a "show"
                const defaultCollapse = document.querySelector('.collapse.show');
                if (defaultCollapse) {
                    activateCollapse(defaultCollapse.id);
                }

                // Écoute des clics sur les liens du menu
                document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('data-bs-target').substring(1);
                        activateCollapse(targetId);
                    });
                });

                // Smooth scroll pour les ancres normales (sans data-bs-toggle)
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    if (!anchor.hasAttribute('data-bs-toggle') || anchor.getAttribute('data-bs-toggle') !==
                        'collapse') {
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
                    }
                });

            });
        </script>
    @endpush

@endsection
