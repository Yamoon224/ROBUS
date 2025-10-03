<div class="col-md-4 col-sm-2 col-xs-12">
    <div class="card border-0 shadow-lg rounded-3 overflow-hidden h-85 animate__animated animate__fadeInRight">

        <div class="card-header bg-gradient-info text-white py-3">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper  bg-opacity-20 rounded-2 p-2 me-3">
                    <i class="bi bi-info-circle text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Informations Service</h5>
                    <small class="opacity-75">Détails et horaires d'ouverture</small>
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="service-info mb-4">
                <div class="d-flex align-items-start mb-3">
                    <i class="fas fa-clock text-primary mt-1 me-3"></i>
                    <div>
                        <h6 class="fw-bold text-dark mb-2">Horaires d'ouverture</h6>
                        <p class="mb-2 text-muted">
                            Service disponible le
                            <span
                                class="badge bg-success text-white bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 ms-2">
                                {{ getAppCloseWeb()->day_of_week ?? 'N/A' }}
                            </span>
                        </p>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="text-muted">De</span>
                            <span
                                class="badge bg-primary text-white bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? '00:00:00')->format('H:i') }}
                            </span>
                            <span class="text-muted">à</span>
                            <span
                                class="badge bg-primary text-white bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? '00:00:00')->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tarifs -->
            <div class="pricing-info">
                <div class="d-flex align-items-start">
                    <i class="fas fa-percentage text-warning mt-1 me-3"></i>
                    <div>
                        <h6 class="fw-bold text-dark mb-3">Structure des frais</h6>

                        <!-- Frais de paiement en ligne -->
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white rounded-2">
                            <span class="text-muted">Frais de paiement en ligne</span>
                            <span class="badge bg-info text-white bg-opacity-10 text-info fw-bold px-3 py-2">
                                {{ $carte_percent ?? '0' }}%
                            </span>
                        </div>

                        <!-- Frais opérateur -->
                        <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded-2">
                            <span class="text-muted">Frais opérateur (Rechargement)</span>
                            <span class="badge bg-warning text-white bg-opacity-10 text-warning fw-bold px-3 py-2">
                                {{ $robus_percent ?? '0' }}%
                            </span>
                        </div>

                        <!-- Total des frais -->
                        <div class="mt-3 p-3 bg-gradient-light rounded-3 border">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-dark">Total des frais</strong>
                                <strong class="text-primary">
                                    {{ ($carte_percent ?? 0) + ($robus_percent ?? 0) }}%
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services disponibles -->
            <div class="services-list mt-4 pt-3 border-top">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    Services disponibles
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="d-flex align-items-center p-2 bg-success bg-opacity-10 rounded-2">
                            <i class="bi bi-phone text-white me-2 small"></i>
                            <small class="text-muted-service">Cartes prépayées</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center p-2 bg-primary bg-opacity-10 rounded-2">
                            <i class="bi bi-wallet text-white me-2 small"></i>
                            <small class="text-muted-service">Comptes money</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center p-2 bg-info bg-opacity-10 rounded-2">
                            <i class="bi bi-credit-card text-white me-2 small"></i>
                            <small class="text-muted-service">Crédits communication</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center p-2 bg-warning bg-opacity-10 rounded-2">
                            <i class="bi bi-lightning text-white me-2 small"></i>
                            <small class="text-muted-service">Paiement en ligne</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center p-2 btn-pink bg-opacity-10 rounded-2">
                            <i class="bi bi-dice-5 text-white me-2 small"></i>
                            <small class="text-muted-service">Paiement 1XBET</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center p-2 btn-rose bg-opacity-10 rounded-2">
                            <i class="bi bi-globe text-white me-2 small"></i>
                            <small class="text-muted-service">International</small>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="card-footer bg-white border-0 pt-0">
            <div class="alert alert-success border-0 rounded-3 mb-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-shield-alt me-2"></i>
                    <small class="fw-medium">Service 100% sécurisé et instantané</small>
                </div>
            </div>
        </div>
    </div>
</div>
