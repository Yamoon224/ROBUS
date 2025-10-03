<div class="col-12">
    <div class="card border-0 shadow-lg rounded-3 overflow-hidden animate__animated animate__shakeX">
        <!-- En-tête d'alerte -->
       
         <div class="card-header bg-gradient-info text-white py-3">
            <div class="d-flex align-items-center">
                <div class="icon-wrapper  bg-opacity-20 rounded-2 p-2 me-3">
                    <i class="bi bi-info-circle text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Informations Service</h5>
                    <small class="opacity-75">Service Temporairement Indisponible</small>
                </div>
            </div>
        </div>
        
        <div class="card-body bg-light">
            <!-- Message principal -->
            <div class="alert alert-warning border-0 rounded-3 mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-clock fa-lg me-3 text-warning"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Horaires de fermeture</h6>
                        <p class="mb-0">
                           <span class="fw-bold-text">Le service Robus était disponible ce  </span> 
                            <span class="badge bg-warning text-white bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 mx-1">
                                {{ getAppCloseWeb()->day_of_week ?? 'N/A' }}
                            </span>
                           <span class="fw-bold-text">de </span> 
                            <span class="badge bg-secondary text-white bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 mx-1">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? '00:00:00')->format('H:i') }}
                            </span>
                           <span class="fw-bold-text">à </span> 
                            <span class="badge bg-secondary text-white bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 mx-1">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? '00:00:00')->format('H:i') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if(getAppCloseWeb()->description ?? null)
            <div class="alert alert-info border-0 rounded-3 mb-4">
                <div class="d-flex align-items-start">
                     <i class="bi bi-info-circle text-black"></i>
                    <div>
                        <h6 class="fw-bold mb-2 ms-3">Information</h6>
                        <p class="mb-0 fw-bold-text">{{ getAppCloseWeb()->description }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Reprise du service -->
            <div class="alert alert-success border-0 rounded-3">
                <div class="d-flex align-items-center">

                    <div>
                        <h6 class="fw-bold mb-2">Reprise du service</h6>
                        <p class="mb-0 fw-bold">
                          <span class="fw-bold-text  mb-3">  Le service Robus reprend demain le  </span>
                            <span class="badge bg-success text-white bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 mx-1">
                                {{ getAppCloseTomorowWeb()->day_of_week ?? 'N/A' }}
                            </span>
                         <span class="fw-bold-text">de </span> 
                            <span class="badge bg-primary text-white bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 mx-1">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseTomorowWeb()->time_start ?? '00:00:00')->format('H:i') }}
                            </span>
                              <span class="fw-bold-text">à </span> 
                            <span class="badge bg-primary text-white bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 mx-1">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseTomorowWeb()->time_end ?? '00:00:00')->format('H:i') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Compte à rebours optionnel -->
            <div class="text-center mt-4 pt-3 border-top">
                <div class="countdown-container">
                    <h6 class="fw-bold text-muted mb-3">Prochaine ouverture dans :</h6>
                    <div class="d-flex justify-content-center gap-3">
                        <div class="countdown-item text-center">
                            <div class="bg-primary bg-opacity-10 rounded-2 p-3">
                                <div class="countdown-value text-white fw-bold fs-4" id="countdown-hours">--</div>
                                <small class="text-muted-service">Heures</small>
                            </div>
                        </div>
                        <div class="countdown-item text-center">
                            <div class="bg-primary bg-opacity-10 rounded-2 p-3">
                                <div class="countdown-value text-white fw-bold fs-4" id="countdown-minutes">--</div>
                                <small class="text-muted-service">Minutes</small>
                            </div>
                        </div>
                        <div class="countdown-item text-center">
                            <div class="bg-primary bg-opacity-10 rounded-2 p-3">
                                <div class="countdown-value text-white fw-bold fs-4" id="countdown-seconds">--</div>
                                <small class="text-muted-service">Secondes</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pied de carte -->
        <div class="card-footer bg-white border-0">
            <div class="text-center">
                <small class="text-muted">
                    Pour toute urgence, contactez notre service client
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compte à rebours jusqu'à la prochaine ouverture
    function updateCountdown() {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        tomorrow.setHours({{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseTomorowWeb()->time_start ?? '00:00:00')->format('H') }}, 
                         {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseTomorowWeb()->time_start ?? '00:00:00')->format('i') }}, 0, 0);
        
        const now = new Date();
        const diff = tomorrow - now;
        
        if (diff > 0) {
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            document.getElementById('countdown-hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('countdown-minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('countdown-seconds').textContent = seconds.toString().padStart(2, '0');
        }
    }
    
    // Mettre à jour toutes les secondes
    setInterval(updateCountdown, 1000);
    updateCountdown(); // Initial call
});
</script>
@endpush