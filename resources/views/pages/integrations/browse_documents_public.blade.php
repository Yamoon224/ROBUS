@extends('layouts.master_integration')

@section('title', 'Documentation Api')

@section('content')

    <section class="py-5 mt-8">
        <div class="container my-4">
            <!-- Header -->
            <header class="mb-4">
                <div
                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="flex-grow-1">
                        <h1 class="h3 h2-md fw-bold text-dark mb-1">Documentation API</h1>
                        <p class="text-muted mb-0">Intégrez facilement les services Robus dans votre application</p>
                    </div>
                    <div class="badge bg-info text-dark px-3 py-2 align-self-start align-self-md-center">
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
                                            data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                                            <i class="bi bi-key me-2"></i>
                                            Authentification
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#phone-credit" class="nav-link text-dark d-flex align-items-center"
                                            data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
                                            <i class="bi bi-phone me-2"></i>
                                           Achat Crédit Téléphonique
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
                                    <li class="d-flex align-items-center text-warning mb-2">
                                        <span class="badge bg-warning me-2">&nbsp;</span>
                                        333 - Virements incompatibles
                                    </li>
                                    <li class="d-flex align-items-center text-danger mb-2">
                                        <span class="badge bg-danger me-2">&nbsp;</span>
                                        341 - Solde insuffisant
                                    </li>
                                    <li class="d-flex align-items-center text-danger mb-2">
                                        <span class="badge bg-danger me-2">&nbsp;</span>
                                        405 - Numéro invalide
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
                            <h5 class="card-title fw-semibold mb-3">Endpoints et Exemples</h5>
                            <ul class="nav nav-pills flex-column gap-2">
                                <li class="nav-item">
                                    <a href="#authentication" class="nav-link active d-flex align-items-center">
                                        <i class="bi bi-key me-2"></i>
                                        Authentification
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#phone-credit" class="nav-link text-dark d-flex align-items-center">
                                        <i class="bi bi-phone me-2"></i>
                                        Crédit Téléphonique
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#transaction" class="nav-link text-dark d-flex align-items-center">
                                        <i class="bi bi-currency-exchange me-2"></i>
                                        Transaction Robus
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
                                <li class="d-flex align-items-center text-warning mb-2">
                                    <span class="badge bg-warning me-2">&nbsp;</span>
                                    333 - Transaction incompatibles
                                </li>
                                <li class="d-flex align-items-center text-danger mb-2">
                                    <span class="badge bg-danger me-2">&nbsp;</span>
                                    341 - Solde insuffisant
                                </li>
                                <li class="d-flex align-items-center text-danger mb-2">
                                    <span class="badge bg-danger me-2">&nbsp;</span>
                                    405 - Numéro invalide
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <!-- Main Content -->
                <div class="col-12 col-lg-9">
                    <!-- Authentication Section -->
                    <section id="authentication" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-primary text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                    <i class="bi bi-shield-lock-fill fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Authentification API</h2>
                                    <p class="mb-0 text-white opacity-90 small">Sécurisez vos requêtes avec vos clés API</p>
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
                                                https://api.robus.com/api_clients/v1/login</pre>
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
                                            <pre id="js-login" class="mb-0 code-block">fetch('https://api.robus.com/api_clients/v1/login', {
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
                                            <span class="text-success">"logo_url"</span>: <span class="text-warning">"https://robus.home.ci/public/dist/images/logo_violet.jpeg"</span>,
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

                            <!-- Error Responses -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-3 d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                    Codes d'erreur possibles
                                </h5>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="card border-danger border-2 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-danger me-2">400</span>
                                                    <h6 class="fw-bold mb-0">Headers API manquants</h6>
                                                </div>
                                                <p class="text-muted small mb-0">Les en-têtes X-API-Key et/ou X-API-Secret
                                                    sont manquants dans la requête.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card border-danger border-2 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-danger me-2">401</span>
                                                    <h6 class="fw-bold mb-0">Identifiants invalides</h6>
                                                </div>
                                                <p class="text-muted small mb-0">Les clés API fournies sont incorrectes ou
                                                    expirées.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card border-warning border-2 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-warning me-2">404</span>
                                                    <h6 class="fw-bold mb-0">Utilisateur introuvable</h6>
                                                </div>
                                                <p class="text-muted small mb-0">L'utilisateur associé aux clés API est
                                                    introuvable.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="card border-warning border-2 h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-warning me-2">301</span>
                                                    <h6 class="fw-bold mb-0">Erreur serveur</h6>
                                                </div>
                                                <p class="text-muted small mb-0">Erreur interne du serveur lors de
                                                    l'authentification.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="phone-credit" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                    <i class="bi bi-phone-fill fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Achat de Crédit Téléphonique</h2>
                                    <p class="mb-0 text-white opacity-90 small">Rechargez facilement les numéros de téléphone</p>
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
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour l'achat de crédit téléphonique</p>
                                </div>
                            </div>

                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des achats de crédit téléphonique. Le système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des transactions.
                                        </p>

                                        <!-- Features Grid -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-shield-check text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Validation des opérateurs</h6>
                                                        <p class="text-muted small mb-0">MTN (18), Orange (19), Moov (20)
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
                                                        <p class="text-muted small mb-0">Empêche l'envoi de crédit à son
                                                            propre numéro</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde suffisant
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
                            </div>

                            <!-- Error Responses -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-4 d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                    Codes d'erreur possibles
                                </h5>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card border-danger h-100">
                                            <div class="card-header bg-danger text-white py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-danger fs-6 me-2">405</span>
                                                    <h6 class="fw-bold mb-0">Numéro invalide</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Le numéro de téléphone fourni est invalide
                                                    ou ne correspond pas à l'opérateur sélectionné.</p>
                                                <div class="d-flex align-items-center text-danger small">
                                                    <i class="bi bi-phone me-1"></i>
                                                    <span>Vérifiez le format du numéro</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card border-warning h-100">
                                            <div class="card-header bg-warning text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">341</span>
                                                    <h6 class="fw-bold mb-0">Solde insuffisant</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Le solde du compte est insuffisant pour
                                                    effectuer cette transaction incluant les commissions.</p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-wallet2 me-1"></i>
                                                    <span>Rechargez votre compte</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card border-warning h-100">
                                            <div class="card-header bg-warning text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">333</span>
                                                    <h6 class="fw-bold mb-0">Virements incompatibles</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Les conditions de virement ne sont pas
                                                    respectées (auto-envoi, pays différent, etc.).</p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-arrow-left-right me-1"></i>
                                                    <span>Vérifiez les conditions</span>
                                                </div>
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

                      <section id="transaction" class="card border-0 shadow-lg mb-5">
                        <div class="card-header bg-gradient-success text-white border-0 py-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                                    <i class="bi bi-currency-exchange fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="h4 fw-bold mb-1">Transaction Robus</h2>
                                    <p class="mb-0 text-white opacity-90 small">Transférez facilement entres les comptes Robus</p>
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
                                    <p class="text-muted small mb-0 mt-1">Endpoint pour les transactions entre comptes Robus</p>
                                </div>
                            </div>

                            <!-- Description & Parameters -->
                            <div class="alert alert-success border-start border-success border-4 bg-light">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill text-success fs-4 me-3"></i>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading fw-semibold mb-3">Description du service</h5>
                                        <p class="mb-4">
                                            Cet endpoint permet d'effectuer des transactions entre comptes Robus. Le système
                                            effectue
                                            plusieurs vérifications automatiques pour garantir la sécurité des transactions.
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
                                                        <p class="text-muted small mb-0">Empêche l'envoi de transfert à son
                                                            propre compte</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-wallet2 text-success me-2 mt-1 flex-shrink-0"></i>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1">Contrôle de solde</h6>
                                                        <p class="text-muted small mb-0">Vérification du solde suffisant
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
                                                                <span class="text-muted">ROBUS</span>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td class="fw-semibold text-nowrap">
                                                                <code>Numero ROBUS</code>
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

                            <!-- Error Responses -->
                            <div class="mt-5">
                                <h5 class="fw-semibold mb-4 d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                    Codes d'erreur possibles
                                </h5>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card border-danger h-100">
                                            <div class="card-header bg-danger text-white py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-danger fs-6 me-2">405</span>
                                                    <h6 class="fw-bold mb-0">Numéro invalide</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Le numéro de ROBUS fourni est invalide
                                                    ou ne correspond pas à l'opérateur sélectionné.</p>
                                                <div class="d-flex align-items-center text-danger small">
                                                    <i class="bi bi-phone me-1"></i>
                                                    <span>Vérifiez le format du numéro</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card border-warning h-100">
                                            <div class="card-header bg-warning text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">341</span>
                                                    <h6 class="fw-bold mb-0">Solde insuffisant</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Le solde du compte est insuffisant pour
                                                    effectuer cette transaction incluant les commissions.</p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-wallet2 me-1"></i>
                                                    <span>Rechargez votre compte</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card border-warning h-100">
                                            <div class="card-header bg-warning text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-white text-warning fs-6 me-2">333</span>
                                                    <h6 class="fw-bold mb-0">Transactions incompatibles</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">Les conditions de transaction ne sont pas
                                                    respectées (auto-envoi, pays différent, etc.).</p>
                                                <div class="d-flex align-items-center text-warning small">
                                                    <i class="bi bi-arrow-left-right me-1"></i>
                                                    <span>Vérifiez les conditions</span>
                                                </div>
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
