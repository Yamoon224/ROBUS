<div>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>



        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">
                <input type="text" name="table_search" wire:model="search" class="form-control  w-56 box pr-10"
                    style="height: 3rem;" placeholder="Rechercher">
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
                                        Envoyer le
                                    </th>

                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Numero
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Statut </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($all_inters->count() > 0)
                                    @foreach ($all_inters as $key => $item)
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
                                                {{ $item->provider->name ?? null }}

                                            </td>
                                            <td class="w-50">
                                                <div class="mt-0.5 mb-2">
                                                    <div class="mt-0.5">
                                                        @if ($item->wallet->sidUser->is_under_distributer == 1)
                                                            <div
                                                                class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                <div class="fc-event-title-container">
                                                                    <div class="fc-event-title fc-sticky">
                                                                        Sous-distributeur</div>
                                                                </div>
                                                            </div>
                                                        @elseif($item->wallet->sidUser->is_custumers == 1)
                                                            <div
                                                                class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                <div class="fc-event-title-container">
                                                                    <div class="fc-event-title fc-sticky">Client</div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                <div class="fc-event-title-container">
                                                                    <div class="fc-event-title fc-sticky">Admin</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400  rounded font-medium"
                                                            style="background-color: #b5048f; color:white;">
                                                            {{-- <div class="fc-event-title-container">
                                                            <div class="fc-event-title fc-sticky"> --}}
                                                            Numero : {{ $item->wallet->sidUser->phone ?? null }}
                                                            {{-- </div>
                                                        </div> --}}
                                                        </div>
                                                        {{ $item->wallet->sidUser->firstname ?? null }}
                                                        {{ $item->wallet->sidUser->lastname ?? null }}
                                                    </div>
                                                </div>
                                                <div class="mt-0.5">
                                                    <span
                                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                        {{ $item->wallet->sidUser->country->name ?? null }} ville :
                                                        {{ $item->wallet->sidUser->city->name ?? null }}
                                                    </span>
                                                </div>

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
                                                    //  Convertion of amount valeur
                                                }
                                            @endphp
                                            <td class="w-40">

                                                @if ($item->provider_type == 1)
                                                    <p class="mb-3">
                                                        Montant envoyé :
                                                    </p>
                                                    <div class="mt-0.5 mb-2">
                                                        <div class="mt-0.5">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->country->currencie->code ?? null }}
                                                        </div>
                                                    </div>
                                                    <p>
                                                        Montant reçu
                                                    </p>
                                                    <div class="mt-0.5 mb-2">
                                                        <div class="mt-0.5">
                                                            {{ number_format($item->amount_received, 2, ',', ' ') ?? null }}
                                                            {{ $item->country->currencie->code ?? null }}
                                                        </div>
                                                    </div>
                                                    <div class="mt-0.5">
                                                        <span class="font-bold ">
                                                            Montant en {{ $usersCountry ?? null }} : <br>


                                                            @isset($valeur_amount_receveid)
                                                                @if ($valeur_amount_receveid !== null)
                                                                    {{ number_format($valeur_amount_receveid, 2, ',', ' ') }}
                                                                    {{ $usersCountry ?? '' }}
                                                                @else
                                                                    Non défini
                                                                @endif
                                                            @else
                                                                Non défini
                                                            @endisset
                                                            {{ $usersCountry ?? null }}
                                                        </span>
                                                    </div>
                                                    <p class="mt-3">
                                                        Solde :
                                                    </p>
                                                    <span
                                                        class="alert alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                        {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                        {{ $item->wallet->currencie->code ?? null }}
                                                    </span>
                                                @else
                                                    <div class="mt-0.5 mb-2">
                                                        <div class="mt-0.5">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}
                                                        </div>
                                                    </div>
                                                    <div class="mt-0.5">
                                                        <span class="font-bold ">
                                                            Montant en {{ $usersCountry ?? null }} : <br>


                                                            @isset($valeur_amount_receveid)
                                                                @if ($valeur_amount_receveid !== null)
                                                                    {{ number_format($valeur_amount_receveid, 2, ',', ' ') }}
                                                                    {{ $usersCountry ?? '' }}
                                                                @else
                                                                    Non défini
                                                                @endif
                                                            @else
                                                                Non défini
                                                            @endisset
                                                            {{ $usersCountry ?? null }}
                                                        </span>
                                                    </div>
                                                    <p class="mt-3">
                                                        Solde :
                                                    </p>
                                                    <span
                                                        class="alert alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                        {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                        {{ $item->wallet->currencie->code ?? null }}
                                                    </span>
                                                @endif

                                            </td>
                                            <td class="w-40">

                                                {{ $item->created_at ?? null }}
                                                <div
                                                    class="px-3 py-2 text-dark bg-blue-900 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                    <div class="fc-event-title-container" style="color: white;">
                                                        <div class="fc-event-title fc-sticky">
                                                            {{ $item->country->name ?? null }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="w-40">
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
                                                @include('components.info_status')

                                            </td>
                                            <td class="w-56">
                                                <div class="flex justify-center items-center">
                                                    @if ($item->status == 'PENDING')
                                                        <div class="flex items-center justify-center text-pending">
                                                            <span
                                                                class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                En-attente
                                                            </span>

                                                        </div>
                                                    @elseif ($item->status == 'SUCCESS')
                                                        @if (!firm())
                                                            @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                <a target="_bank"
                                                                    href="{{ route('inters.invoice.print', $item->id) }}"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    title="Facture">
                                                                    Facture
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <div class="flex items-center justify-center text-dark">
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Aucune
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
                    {{ $all_inters->links('vendor.pagination.custum') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>

</div>
