<div class="col-md-4 col-sm-2 col-xs-12">
    <div class="card text-white bg-primary mb-3 style_info animate__animated animate__backInRight">
        <div class="card-header text-center" style="font-weight: bold;background-color: darkslategrey;">Information</div>
        <div class="card-body">
            <p class="card-text text-white" style="font-weight: 450;">
               Transfert et paiement de credit. Service de paiement disponible ce 
               <span class="badge bg-success text-dark style_span text-white">{{ getAppCloseWeb()->day_of_week ?? null}} </span>  de
               <span class="badge bg-success text-dark style_span text-white">
                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? null)->format('H:i') }}
            </span>
            MIN a <span class="badge bg-success text-dark style_span text-white">
                {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? null)->format('H:i') }}
            </span> MIN . </p>
            <p class="card-text text-white" style="font-weight: 450;">
                Tarif Transfert:<span class="badge bg-info text-dark style_span text-white">{{ $transaction_percent ?? null }}%</span> frais de paiement
                en ligne + <span class="badge bg-info text-dark style_span text-white">{{ $robus_percent ?? null }}%</span>frais de
                l'opérateur(Transactions)
            </p>

            <p class="card-text text-white" style="font-weight: 450;">
                Tarif paiement de credit telephonique :<span class="badge bg-info text-dark style_span text-white">{{ $phone_percent ?? null }}%</span> frais de paiement
                en ligne + <span class="badge bg-info text-dark style_span text-white">{{ $robus_percent_phone ?? null }}%</span>frais de
                l'opérateur(Crédit téléphonique)
            </p>
        </div>
    </div>
</div>
