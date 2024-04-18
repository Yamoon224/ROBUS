<div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card text-white bg-primary mb-3 style_info animate__animated animate__backInRight">
            <div class="card-header text-center" style="font-weight: bold;background-color: darkslategrey;">Informations</div>
            <div class="card-body">
                <p class="card-text text-white" style="font-weight: 450;">
                   Désolé le service Robus etait disponible ce  <span class="badge bg-info text-dark style_span text-white">{{ getAppCloseWeb()->day_of_week ?? null}} </span>  de  <span class="badge bg-info text-dark style_span text-white">{{ getAppCloseWeb()->time_start ?? null}} </span> MIN a <span class="badge bg-info text-dark style_span text-white">{{ getAppCloseWeb()-> 	time_end  ?? null}} </span>  MIN .
                </p>
                <p class="card-text text-white" style="font-weight: 450;">
                    {{ getAppCloseWeb()->description ?? null}}  
                 </p>
               
            </div>
        </div>
    </div>
    