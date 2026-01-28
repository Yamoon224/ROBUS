<div id="inter" class="tab-pane" role="tabpanel" aria-labelledby="inter-tab">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="input-group input-group-md hidden" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" class="form-control  w-56 box pr-10"
                    style="height: 3rem; background-color: #eff3f7;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-6">
        <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
            <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    Liste des transfers internationnaux
                </h2>
            </div>
            <div class="py-5">
                <div class="px-5">
                    <div class="overflow-x-auto">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                    </th>

                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Montant demandé
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Info Transaction
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Statut </th>
                                    @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                        <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                            Actions
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($Internationals->count() > 0)

                                    @foreach ($Internationals as $key => $item)
                                        <tr class="intro-x">
                                            <td class="text-center w-40">

                                                @if ($item->provider_type == 1)
                                                    <span
                                                        class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                   text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Paiement Mooney
                                                    </span>
                                                @elseif ($item->provider_type == 5)
                                                    <span
                                                        class="alert alert-success  inline-flex items-center justify-center px-2 py-1
                                               mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Paiement Facture
                                                    </span>
                                                @elseif ($item->provider_type == 6)
                                                    <span
                                                        class="alert alert-success  inline-flex items-center justify-center px-2 py-1 
                                              mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Credit telephonique
                                                    </span>
                                                @elseif ($item->provider_type == 7)
                                                    <span
                                                        class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Versement bancaire
                                                    </span>
                                                @elseif($item->provider_type == 8)
                                                    <span
                                                        class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Rechargement carte
                                                    </span>
                                                @elseif($item->provider_type == 10)
                                                    <span
                                                        class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Rechargement Bank send


                                                    </span>
                                                @endif
                                                <div
                                                    class="px-3 py-2 text-dark btn-pink dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                    <div class="fc-event-title-container" style="color: white;">
                                                        <div class="fc-event-title fc-sticky">
                                                            {{ $item->provider->name ?? null }}

                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td class="user-info-card">
                                                @include('components.infoUser')

                                            </td>
                                            @php
                                                if ($item->provider_type == 1) {
                                                    //  Convertion of amount valeur
                                                    if (
                                                        sabdy()->country->currencie->value >
                                                        $item->country->currencie->value
                                                    ) {
                                                        //   Convertion  in CFA
                                                        $valeur_amount_receveid =
                                                            (float) $item->amount_received *
                                                                (float) $item->country->currencie->value ??
                                                            null;
                                                        //   $valeur_amount_receveid =  * $item->wallet->currencie->value;
                                                    } elseif (
                                                        sabdy()->country->currencie->value ==
                                                        $item->country->currencie->value
                                                    ) {
                                                        $valeur_amount_receveid = $item->amount_received ?? null;
                                                    } elseif (
                                                        sabdy()->country->currencie->value <
                                                        $item->country->currencie->value
                                                    ) {
                                                        //   Convertion in Country send
                                                        $valeur_amount_receveid =
                                                            (float) $item->amount_received *
                                                                (float) $item->country->currencie->value ??
                                                            null;
                                                    }
                                                } else {
                                                    if ($item->country->id == 107) {
                                                        //  Convertion of amount valeur
                                                        if (
                                                            sabdy()->country->currencie->value >
                                                            $item->country->currencie->value
                                                        ) {
                                                            //   Convertion  in CFA
                                                            $valeur_amount_receveid =
                                                                (float) $item->amount_received *
                                                                    (float) sabdy()->country->currencie->value ??
                                                                null;

                                                            //   $valeur_amount_receveid = (float) $commission_transaction_val_inter_convertion_cfa / (float) $item->country->currencie->value;
                                                        } elseif (
                                                            sabdy()->country->currencie->value ==
                                                            $item->country->currencie->value
                                                        ) {
                                                            $valeur_amount_receveid = $item->amount_received ?? null;
                                                        } elseif (
                                                            sabdy()->country->currencie->value <
                                                            $item->country->currencie->value
                                                        ) {
                                                            //   Convertion  in CFA
                                                            $commission_transaction_val_inter_convertion_cfa =
                                                                (float) $item->amount_received *
                                                                    (float) sabdy()->country->currencie->value ??
                                                                null;

                                                            //   Convertion in Country send
                                                            $valeur_amount_receveid =
                                                                (float) $item->amount_received *
                                                                    (float) $item->country->currencie->value ??
                                                                null;
                                                        }
                                                    } else {
                                                        //  Convertion of amount valeur
                                                        if (
                                                            sabdy()->country->currencie->value >
                                                            $item->country->currencie->value
                                                        ) {
                                                            //   Convertion  in CFA
                                                            $valeur_amount_receveid =
                                                                (float) $item->amount_received *
                                                                    (float) sabdy()->country->currencie->value ??
                                                                null;
                                                        } elseif ($item->country->id == 107) {
                                                            //   dd('ici');
                                                            if (
                                                                sabdy()->country->currencie->value >
                                                                $item->country->currencie->value
                                                            ) {
                                                                //   Convertion  in CFA
                                                                $valeur_amount_receveid =
                                                                    (float) $item->amount_received *
                                                                        (float) $item->wallet->country->currencie
                                                                            ->value ??
                                                                    null;
                                                            } else {
                                                                $valeur_amount_receveid =
                                                                    (float) $item->amount_received *
                                                                        (float) $item->wallet->country->currencie
                                                                            ->value ??
                                                                    null;
                                                            }
                                                        } elseif (
                                                            sabdy()->country->currencie->value <
                                                            $item->country->currencie->value
                                                        ) {
                                                            //   Convertion  in CFA
                                                            $commission_transaction_val_inter_convertion_cfa =
                                                                (float) $item->amount_received *
                                                                    (float) sabdy()->country->currencie->value ??
                                                                null;

                                                            //   Convertion in Country send
                                                            $valeur_amount_receveid =
                                                                (float) $item->amount_received *
                                                                    (float) $item->country->currencie->value ??
                                                                null;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <td>
                                                <div class="mb-3">
                                                    <div
                                                        class="px-3 py-2 text-dark bg-blue-900 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                        <div class="fc-event-title-container" style="color: white;">
                                                            <div class="fc-event-title fc-sticky">
                                                                {{ $item->country->name ?? null }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @include('components.walletBalanceInter')

                                            </td>

                                            <td class="w-40">
                                                <div class="mb-3">
                                                    @include('components.info')

                                                </div>
                                                @if ($item->updated_by == null)
                                                    @if ($item->provider_type == 8)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Rechargement carte</div>
                                                            </div>
                                                        </div>
                                                        {{ substr($item->number_card ?? null, 0, 5) }}
                                                    @elseif($item->provider_type == 5)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Paiement Facture</div>

                                                            </div>
                                                        </div>
                                                        @if ($item->factureChoix_type == 1)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Facture
                                                                prépayée</div>
                                                            {{ substr($item->id_invoice ?? null, 0, 5) }}
                                                        @elseif($item->factureChoix_type == 2)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Facture
                                                                Normale</div>
                                                            {{ substr($item->id_invoice ?? null, 0, 5) }}
                                                        @elseif($item->paiementChoix_phone == 1)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Numero de telephone</div>
                                                            {{ substr($item->number_phone ?? null, 0, 6) }}
                                                        @elseif ($item->paiementChoix_phone == 2)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Numero du decodeur</div>
                                                            {{ substr($item->number_decoder ?? null, 0, 5) }}
                                                        @elseif($item->paiementChoix_phone == null && $item->factureChoix_type == null)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Numero du contrat</div>
                                                            {{ substr($item->number_contract ?? null, 0, 5) }}
                                                            {{ substr($item->id_invoice ?? null, 0, 5) }}
                                                        @endif
                                                    @elseif ($item->provider_type == 1)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Paiement Mooney</div>
                                                            </div>
                                                        </div>
                                                        {{ substr($item->phone ?? null, 0, 6) }}
                                                        <p class="font-medium whitespace-nowrap">
                                                            Nom& Prenom(S) :
                                                            {{ $item->name_complet ?? $item->wallet->sidUser->firstname }}

                                                        </p>
                                                        <p class="font-medium whitespace-nowrap mt-1">
                                                            N0 : {{ $item->num_piece ?? null }}
                                                        </p>
                                                        <p class="font-medium whitespace-nowrap">
                                                            Date validité :
                                                            {{ $item->validated_date ?? null }}
                                                        </p>
                                                        @if ($item->provider_id == 99)
                                                            <p class="font-medium whitespace-nowrap">
                                                                Nom du beneficiaire : {{ $item->name_benef ?? null }}
                                                                Numero Compte banque :
                                                                {{ $item->number_compte ?? null }}
                                                                Nom banque : {{ $item->name ?? null }}

                                                            </p>
                                                        @endif
                                                    @elseif ($item->provider_type == 6)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Credit telephonique</div>
                                                            </div>
                                                        </div>
                                                        {{ substr($item->phone ?? null, 0, 6) }}
                                                    @elseif ($item->provider_type == 7)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Versement bancaire</div>
                                                            </div>
                                                        </div>
                                                        {{ substr($item->number_compte ?? null, 0, 5) }}
                                                    @elseif ($item->provider_type == 10)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Rechargement Bank send</div>
                                                            </div>
                                                        </div>
                                                        <p>Numéro Swift :
                                                            {{ substr($item->num_authers ?? null, 0, 5) }}</p>

                                                        <p>Banque : {{ substr($item->name ?? null, 0, 5) }}</p>
                                                    @endif

                                                    <div class="flex items-center justify-center text-success mt-3">

                                                        @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                            <x-take-action-button action="ChooseUpdateInter"
                                                                :itemId="$item->id" label="Je prends en charge"
                                                                speed="ultra-fast" color="warning" />
                                                        @endif

                                                    </div>
                                                @else
                                                    @if ($item->provider_type == 8)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container"
                                                                style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Rechargement carte</div>
                                                            </div>
                                                        </div>
                                                        {{ $item->number_card ?? null }}
                                                    @elseif($item->provider_type == 5)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container"
                                                                style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Paiement Facture</div>

                                                            </div>
                                                        </div>
                                                        @if ($item->factureChoix_type == 1)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Facture
                                                                prépayée</div>
                                                            {{ $item->id_invoice ?? null }}
                                                        @elseif($item->factureChoix_type == 2)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Facture
                                                                Normale</div>
                                                            {{ $item->id_invoice ?? null }}
                                                        @elseif($item->paiementChoix_phone == 1)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Numero de telephone</div>
                                                            {{ $item->number_phone ?? null }}
                                                        @elseif ($item->paiementChoix_phone == 2)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Numero du decodeur</div>
                                                            {{ $item->number_decoder ?? null }}
                                                        @elseif($item->paiementChoix_phone == null && $item->factureChoix_type == null)
                                                            <div class="alert alert-dark show mb-2" role="alert">
                                                                Numero du contrat</div>
                                                            {{ $item->number_contract ?? $item->id_invoice }}
                                                        @endif
                                                    @elseif ($item->provider_type == 1)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container"
                                                                style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Paiement Mooney</div>
                                                            </div>
                                                        </div>
                                                        {{ $item->phone ?? null }}
                                                        <p class="font-medium whitespace-nowrap">
                                                            Nom& Prenom(S) :
                                                            {{ $item->name_complet ?? $item->wallet->sidUser->firstname }}

                                                        </p>
                                                        <p class="font-medium whitespace-nowrap mt-1">
                                                            N0 : {{ $item->num_piece ?? null }}
                                                        </p>
                                                        <p class="font-medium whitespace-nowrap">
                                                            Date validité :
                                                            {{ $item->validated_date ? date('d-M-y', strtotime($item->validated_date)) : null }}
                                                        </p>
                                                        @if ($item->provider_id == 99)
                                                            <p class="font-medium whitespace-nowrap">
                                                                Nom du bénéficiaire : {{ $item->name_benef ?? null }}

                                                            </p>
                                                            <p class="font-medium whitespace-nowrap">
                                                                Numero Compte banque :
                                                                {{ $item->number_compte ?? null }}

                                                            </p>
                                                            <p class="font-medium whitespace-nowrap">
                                                                Nom banque : {{ $item->name ?? null }}

                                                            </p>
                                                        @endif
                                                    @elseif ($item->provider_type == 6)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container"
                                                                style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Credit telephonique</div>
                                                            </div>
                                                        </div>
                                                        {{ $item->phone ?? null }}
                                                    @elseif ($item->provider_type == 7)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container"
                                                                style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Versement bancaire</div>
                                                            </div>
                                                        </div>
                                                        {{ $item->number_compte ?? null }}
                                                    @elseif ($item->provider_type == 10)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container"
                                                                style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Rechargement Bank send</div>
                                                            </div>
                                                        </div>
                                                        <p>Numéro Swift :
                                                            {{ substr($item->num_authers ?? null, 0, 5) }}</p>

                                                        <p>Banque : {{ substr($item->name ?? null, 0, 5) }}</p>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="w-40">
                                                @if ($item->status == 'PENDING')
                                                    <div class="flex items-center justify-center text-pending">
                                                        <span
                                                            class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            En-attente
                                                        </span>

                                                    </div>
                                                @elseif ($item->status == 'ERROR')
                                                    <div class="flex items-center justify-center text-danger">
                                                        <div class="flex items-center justify-center text-pending">
                                                            <span
                                                                class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                Refusé
                                                            </span>

                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-center text-pending">
                                                        <span
                                                            class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                            style="color: white;">
                                                            Traiter
                                                        </span>

                                                    </div>
                                                @elseif($item->status == 'REFUND')
                                                    <div class="flex items-center justify-center text-primary">
                                                        <div class="flex items-center justify-center text-pending">
                                                            <span
                                                                class="alert alert-primary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                Remboursé
                                                            </span>

                                                        </div>
                                                    </div>
                                                    <div class="flex items-center justify-center text-pending">
                                                        <span
                                                            class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                            style="color: white;">
                                                            Traiter
                                                        </span>

                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-center text-success">
                                                        <div class="flex items-center justify-center text-pending">
                                                            <span
                                                                class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Accepté
                                                            </span>

                                                        </div>

                                                    </div>
                                                    <div class="flex items-center justify-center text-pending">
                                                        <span
                                                            class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                            style="color: white;">
                                                            Traiter
                                                        </span>

                                                    </div>
                                                @endif
                                            </td>
                                            <td class="w-56">
                                                <div class="flex justify-center items-center">
                                                    @if ($item->updated_by != null)
                                                        @if ($item->status == 'PENDING' && $item->updated_by == Auth::guard('web')->user()->id)
                                                            @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                <a wire:click="editInter({{ $item->id }}, 'update-interTransfers-modal')"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    href="javascript:;" title="Modifier">
                                                                    Modifier
                                                                </a>
                                                            @endif
                                                        @elseif ($item->status == 'SUCCESS' && $item->updated_by == Auth::guard('web')->user()->id)
                                                            @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                <a target="_bank"
                                                                    href="{{ route('inters.invoice.print', $item->id) }}"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    title="Facture">
                                                                    Facture
                                                                </a>
                                                            @endif
                                                        @else
                                                            <div class="flex items-center justify-center text-dark">
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-warning inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                                    text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Déjà choisir
                                                                    </span>

                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="flex items-center justify-center text-dark">
                                                            <div class="flex items-center justify-center text-warring">
                                                                <span
                                                                    class="alert alert-dark show mb-2 inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        icon-name="alert-octagon"
                                                                        data-lucide="alert-octagon"
                                                                        class="lucide lucide-alert-octagon w-6 h-6 mr-2">
                                                                        <polygon
                                                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                                        </polygon>
                                                                        <line x1="12" y1="8"
                                                                            x2="12" y2="12"></line>
                                                                        <line x1="12" y1="16"
                                                                            x2="12.01" y2="16"></line>
                                                                    </svg>
                                                                    Pas encore choisir
                                                                </span>

                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: Pagination -->

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $Internationals->links('vendor.livewire.tailwind-inters') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
</div>
