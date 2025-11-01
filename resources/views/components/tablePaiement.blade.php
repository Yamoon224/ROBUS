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
                                        Info Paiement
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($payment_historicals->count() > 0)
                                    @foreach ($payment_historicals as $key => $item)
                                        <tr class="intro-x">
                                            <td class="text-center w-40">

                                                <div
                                                    class="px-3 py-2 text-dark btn-success dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
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
                                            <td>
                                                @include('components.walletBalance')

                                            </td>
                                            <td class="w-60">
                                                <div class="mb-3">
                                                    @include('components.info')
                                                </div>
                                                <p class="font-medium whitespace-nowrap">
                                                    @if ($item->factureChoix_type == 1)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Facture
                                                                    prépayée</div>
                                                            </div>
                                                        </div>
                                                        {{ $item->id_invoice ?? null }}
                                                    @elseif($item->factureChoix_type == 2)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Facture
                                                                    normale</div>
                                                            </div>
                                                        </div>
                                                        {{ $item->id_invoice ?? null }}
                                                    @elseif($item->paiementChoix_phone == 1)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">

                                                                    Numero de telephone</div>
                                                            </div>
                                                        </div>

                                                        {{ $item->number_phone ?? null }}
                                                    @elseif($item->paiementChoix_phone == 2)
                                                        <div
                                                            class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                            <div class="fc-event-title-container" style="color: white;">
                                                                <div class="fc-event-title fc-sticky">
                                                                    Numero du decodeur
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{ $item->number_decoder }}
                                                    @else
                                                        {{ $item->number_contract ?? null }}
                                                    @endif
                                                </p>
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