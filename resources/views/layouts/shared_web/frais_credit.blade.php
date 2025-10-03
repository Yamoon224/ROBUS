<div class="row justify-content-center mt-3 d-none active_phone " >
    <div class="col-12 col-md-8 col-lg-7" >
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4 animate__animated animate__fadeInUp mx-auto"
            style="max-width: 500px;" >
            <!-- En-tête de la carte -->
            <div class="card-header bg-gradient-primary text-white py-3 text-center" >
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                    <!-- Flex responsive -->
                    <div class="d-flex align-items-center">
                        <i class="bi bi-percent fa-lg me-2"></i>
                        <h6 class="mb-0 fw-bold">Détail des Frais</h6>
                    </div>
                    <span class="badge bg-white text-primary fs-6 px-3 py-2 rounded-pill" id="total-percent-phone"
                        data-total-percent-x="{{ $total_percent_phone }}">
                        Total: {{ $total_percent_phone ?? '0' }}%
                    </span>
                </div>
            </div>

            <div class="bg-light text-center">
                <!-- Résultat calculé -->
                <div class="calculation-result p-4 bg-gradient-info text-white rounded-3 border-0 mx-auto"
                    style="max-width: 400px;"> <!-- Centrage interne -->
                    <div class="text-center">
                        <small class="opacity-75 d-block mb-2" >Montant des frais pour votre transaction crédit
                            téléphonique </small>
                        <h3 class="mb-2 fw-bold" id="resultPhone">0 FCFA</h3> <!-- Taille augmentée -->
                        <small class="opacity-75" id="result-detail">Basé sur le montant saisi</small>
                    </div>
                </div>

                <!-- Barre de progression optionnelle -->
                <div class="progress-bar-container mt-4 d-none" id="progress-container"> <!-- Cachée par défaut -->
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted fw-medium">Progression</small>
                        <small class="text-muted fw-medium" id="progress-percent">0%</small>
                    </div>
                    <div class="progress rounded-pill" style="height: 8px;">
                        <div class="progress-bar bg-gradient-progress" role="progressbar" id="frais-progress"
                            style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
