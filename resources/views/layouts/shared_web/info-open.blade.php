<div class="col-md-4 col-sm-2 col-xs-12">
    <div class="card text-white bg-primary mb-3 style_info animate__animated animate__backInRight">
        <div class="card-header text-center" style="font-weight: bold;background-color: darkslategrey;">Information</div>
        <div class="card-body">
            <p class="card-text text-white" style="font-weight: 450;">
                Rechargez vos cartes prépayées visa par mobile, Rechargez vos comptes mobiles money avec votre carte
                visa, Achetez des crédits de communication avec votre carte visa. Service de rechargement disponible ce
                <span class="badge bg-success text-dark style_span text-white">{{ getAppCloseWeb()->day_of_week ?? null}} </span>  de
                <span class="badge bg-success text-dark style_span text-white">
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? null)->format('H:i') }}
                </span>
                MIN a <span class="badge bg-success text-dark style_span text-white">
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? null)->format('H:i') }}
                </span> MIN .  MIN .</p>
            <p class="card-text text-white" style="font-weight: 450;">
                Tarif :<span class="badge bg-info text-dark style_span text-white">{{ $carte_percent ?? null }}%</span> frais de paiement
                en ligne + <span class="badge bg-info text-dark style_span text-white">{{ $robus_percent ?? null }}%</span>frais de
                l'opérateur(Réchargement de carte)
            </p>
        </div>
    </div>
</div>
