<div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card text-white bg-primary mb-3 style_info animate__animated animate__backInRight">
            <div class="card-header text-center" style="font-weight: bold; background-color: #2F4F4F; padding: 15px;">
                <h4 class="mb-0">Informations Importantes</h4>
            </div>
            <div class="card-body text-center p-4">
                <p class="card-text fs-5 mb-3" style="font-weight: 500; line-height: 1.6;">
                    Désolé, le service Robus était disponible ce
                    <span class="badge bg-info text-dark" style="font-size: 0.9em; padding: 0.4em 0.7em;">
                        {{ getAppCloseZoneWeb()->day_of_week ?? 'Jour Indisponible' }}
                    </span>
                    de
                    <span class="badge bg-info text-dark" style="font-size: 0.9em; padding: 0.4em 0.7em;">
                        {{ getAppCloseZoneWeb()->time_start ?? 'Heure Indisponible' }}
                    </span>
                    MIN à
                    <span class="badge bg-info text-dark" style="font-size: 0.9em; padding: 0.4em 0.7em;">
                        {{ getAppCloseZoneWeb()->time_end ?? 'Heure Indisponible' }}
                    </span>
                    MIN.
                </p>
                <p class="card-text fs-5 mb-4" style="font-weight: 500; line-height: 1.6;">
                    Raison de la fermeture : {{ getAppCloseZoneWeb()->description ?? 'Information non disponible.' }}
                </p>
    
                <hr class="my-4 border-light opacity-50">
                <p class="card-text fs-5 mt-4" style="font-weight: 500; line-height: 1.6;">
                    Le service Robus reprendra demain, le
                    <span class="badge bg-success text-dark" style="font-size: 0.9em; padding: 0.4em 0.7em;">
                        {{ getAppCloseTomorowWeb()->day_of_week ?? 'Jour Indisponible' }}
                    </span>
                    de
                    <span class="badge bg-info text-dark" style="font-size: 0.9em; padding: 0.4em 0.7em;">
                        {{ getAppCloseTomorowWeb()->time_start ?? 'Heure Indisponible' }}
                    </span>
                    MIN à
                    <span class="badge bg-info text-dark" style="font-size: 0.9em; padding: 0.4em 0.7em;">
                        {{ getAppCloseTomorowWeb()->time_end ?? 'Heure Indisponible' }}
                    </span>
                    MIN.
                </p>
            </div>
        </div>
    </div>
    