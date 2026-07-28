<table id="table_orders" class="table table-bordered">
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
            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                Actions
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($inter_historicals->count() > 0)
            @foreach ($inter_historicals as $key => $item)
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
                            if (sabdy()->country->currencie->value > $item->country->currencie->value) {
                                //   Convertion  in CFA
                                $valeur_amount_receveid =
                                    (float) $item->amount_received * (float) $item->country->currencie->value ?? null;
                                //   $valeur_amount_receveid =  * $item->wallet->currencie->value;
                            } elseif (sabdy()->country->currencie->value == $item->country->currencie->value) {
                                $valeur_amount_receveid = $item->amount_received ?? null;
                            } elseif (sabdy()->country->currencie->value < $item->country->currencie->value) {
                                //   Convertion in Country send
                                $valeur_amount_receveid =
                                    (float) $item->amount_received * (float) $item->country->currencie->value ?? null;
                            }
                        } else {
                            if ($item->country->id == 107) {
                                //  Convertion of amount valeur
                                if (sabdy()->country->currencie->value > $item->country->currencie->value) {
                                    //   Convertion  in CFA
                                    $valeur_amount_receveid =
                                        (float) $item->amount_received * (float) sabdy()->country->currencie->value ??
                                        null;

                                    //   $valeur_amount_receveid = (float) $commission_transaction_val_inter_convertion_cfa / (float) $item->country->currencie->value;
                                } elseif (sabdy()->country->currencie->value == $item->country->currencie->value) {
                                    $valeur_amount_receveid = $item->amount_received ?? null;
                                } elseif (sabdy()->country->currencie->value < $item->country->currencie->value) {
                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =
                                        (float) $item->amount_received * (float) sabdy()->country->currencie->value ??
                                        null;

                                    //   Convertion in Country send
                                    $valeur_amount_receveid =
                                        (float) $item->amount_received * (float) $item->country->currencie->value ??
                                        null;
                                }
                            } else {
                                //  Convertion of amount valeur
                                if (sabdy()->country->currencie->value > $item->country->currencie->value) {
                                    //   Convertion  in CFA
                                    $valeur_amount_receveid =
                                        (float) $item->amount_received * (float) sabdy()->country->currencie->value ??
                                        null;
                                } elseif ($item->country->id == 107) {
                                    //   dd('ici');
                                    if (sabdy()->country->currencie->value > $item->country->currencie->value) {
                                        //   Convertion  in CFA
                                        $valeur_amount_receveid =
                                            (float) $item->amount_received *
                                                (float) $item->wallet->country->currencie->value ??
                                            null;
                                    } else {
                                        $valeur_amount_receveid =
                                            (float) $item->amount_received *
                                                (float) $item->wallet->country->currencie->value ??
                                            null;
                                    }
                                } elseif (sabdy()->country->currencie->value < $item->country->currencie->value) {
                                    //   Convertion  in CFA
                                    $commission_transaction_val_inter_convertion_cfa =
                                        (float) $item->amount_received * (float) sabdy()->country->currencie->value ??
                                        null;

                                    //   Convertion in Country send
                                    $valeur_amount_receveid =
                                        (float) $item->amount_received * (float) $item->country->currencie->value ??
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

                        @if ($item->image)
                            @php
                                // Correction de l'URL de l'image si nécessaire
                                $imageUrl = str_replace('/image_inters/', '/public/image_inters/', $item->image ?? asset_url('dist/images/profil.png'));

                                $imageUrlBackend = str_replace(
                                    '/image_inters/',
                                    '/public/image_inters/',
                                    $item->image_bakend ?? asset_url('dist/images/profil.png'),
                                );
                            @endphp

                            <div class="mt-3 grid grid-cols-2 gap-4">

                                <!-- Image 1 -->
                                <div class="flex flex-col items-center">
                                    <img src="{{ $imageUrl }}" alt="{{ $item->country->name ?? 'Image' }}"
                                        class="w-24 h-24 object-cover rounded">

                                    <a href="{{ $imageUrl }}" download
                                        class="mt-2 text-sm text-blue-600 hover:underline btn-primary">
                                        Télécharger
                                    </a>
                                </div>

                                <!-- Image 2 -->
                                <div class="flex flex-col items-center">
                                    <img src="{{ $imageUrlBackend ?? "" }}" alt="{{ $item->country->name ?? 'Image' }}"
                                        class="w-24 h-24 object-cover rounded">
                                    <a href="{{ $imageUrlBackend }}" download
                                        class="mt-2 text-sm text-blue-600 hover:underline btn-primary">
                                        Télécharger
                                    </a>
                                </div>

                            </div>
                        @endif

                    </td>

                    <td class="w-40">
                        <div class="mb-3">
                            @include('components.info')

                        </div>
                        @if ($item->provider_type == 8)
                            <div
                                class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                <div class="fc-event-title-container" style="color: white;">
                                    <div class="fc-event-title fc-sticky">
                                        Rechargement carte</div>
                                </div>
                            </div>
                            {{ $item->number_card ?? null }}
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
                                <div class="fc-event-title-container" style="color: white;">
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
                                {{ $item->validated_date ?? null }}
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
                                <div class="fc-event-title-container" style="color: white;">
                                    <div class="fc-event-title fc-sticky">
                                        Credit telephonique</div>
                                </div>
                            </div>
                            {{ $item->phone ?? null }}
                        @elseif ($item->provider_type == 7)
                            <div
                                class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                <div class="fc-event-title-container" style="color: white;">
                                    <div class="fc-event-title fc-sticky">
                                        Versement bancaire</div>
                                </div>
                            </div>
                            {{ $item->number_compte ?? null }}
                        @endif
                    </td>
                    <td class="w-40">
                        <div class="mb-3">
                            @include('components.infoValidtedUser')
                        </div>

                        @include('components.info_status')

                    </td>
                    <td class="w-56">
                        <div class="flex justify-center items-center">
                            @if ($item->status == 'PENDING')
                                    <span
                                        class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        En attente
                                    </span>
                            @elseif (
                                $item->status == 'SUCCESS' &&
                                    ($item->provider_id == 48 ||
                                        $item->provider_id == 49 ||
                                        $item->provider_id == 50 ||
                                        $item->provider_id == 51 ||
                                        $item->provider_id == 55 ||
                                        $item->provider_id == 71 ||
                                        $item->provider_id == 72 ||
                                        $item->provider_id == 73 ||
                                        $item->provider_id == 74 ||
                                        $item->provider_id == 75 ||
                                        $item->provider_id == 76 ||
                                        $item->provider_id == 77 ||
                                        $item->provider_id == 78 ||
                                        $item->provider->id == 93 ||
                                        $item->provider->id == 94 ||
                                        $item->provider->id == 95 ||
                                        $item->provider->id == 96 ||
                                        $item->provider->id == 97 ||
                                        $item->provider->id == 49 ||
                                        $item->provider->id == 50 ||
                                        $item->provider->id == 55 ||
                                        $item->provider->id == 98 ||
                                        $item->provider->id == 99 ||
                                        $item->provider->id == 100 ||
                                        $item->provider->id == 101 ||
                                        $item->provider->id == 102))
                                <a target="_bank" href="{{ route('inters.invoice.print', $item->id) }}"
                                    class="btn btn-primary mr-1 mb-2 btn-sm" title="Facture">
                                    Facture
                                </a>
                            @else
                                <a target="_bank" href="{{ route('inters.invoice.print', $item->id) }}"
                                    class="btn btn-primary mr-1 mb-2 btn-sm" title="Facture">
                                    Facture
                                </a>
                                <div class="flex items-center justify-center text-pending">
                                    <span
                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                        style="color: white;">
                                        Traiter
                                    </span>

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
