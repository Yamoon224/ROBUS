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
                                        Info 1XBETS
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Statut </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($xbet_historicals->count() > 0)
                                    @foreach ($xbet_historicals as $key => $item)
                                        <tr class="intro-x">
                                            <td class="text-center w-40">
                                                @if ($item->choose_type_transaction == 1)
                                                    <span
                                                        class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                               text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Demande de depot
                                                    </span>
                                                @else
                                                    <span
                                                        class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                          text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                        style="color: white;">
                                                        Demande de retrait
                                                    </span>
                                                @endif

                                                <div
                                                    class="px-3 py-2 text-dark btn-orange dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
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
                                                <div class="p-4  rounded-lg shadow-sm">

                                                    <dl class="space-y-2 text-sm text-gray-700 mr-2">
                                                        @if ($item->choose_type_transaction == 1)
                                                            <div class="flex justify-between">
                                                                <dt class="font-medium text-gray-900"> ID compte :
                                                                </dt>
                                                                <dd>{{ $item->num_acompte ?? '—' }}</dd>
                                                            </div>
                                                            <div class="flex justify-between mt-3">
                                                                <dt class="font-medium text-gray-900"> Nom & Prénom
                                                                    :
                                                                </dt>
                                                                <dd>{{ $item->full_name ?? '—' }}</dd>
                                                            </div>
                                                        @else
                                                            <div class="flex justify-between">
                                                                <dt class="font-medium text-gray-900"> ID compte
                                                                    :
                                                                </dt>
                                                                <dd>{{ $item->num_acompte ?? '—' }}</dd>
                                                            </div>
                                                            <div class="flex justify-between mt-3">
                                                                <dt class="font-medium text-gray-900"> Ville
                                                                    :
                                                                </dt>
                                                                <dd>{{ $item->citie_name ?? '—' }}</dd>
                                                            </div>
                                                            <div class="flex justify-between mt-3">
                                                                <dt class="font-medium text-gray-900"> Rue
                                                                    :
                                                                </dt>
                                                                <dd>{{ $item->area_name ?? '—' }}</dd>
                                                            </div>
                                                            <div class="flex justify-between mt-3">
                                                                <dt class="font-medium text-gray-900">
                                                                    Code demande :
                                                                </dt>
                                                                <dd>{{ $item->code_acompte ?? '—' }}</dd>
                                                            </div>
                                                        @endif


                                                    </dl>


                                                </div>
                                            </td>
                                            <td class="w-40">
                                                 <div class="mb-3">
                               @include('components.infoValidtedUser')
                           </div>

                                                @include('components.info_status')

                                            </td>
                                            <td class="w-56">
                                                <div class="flex justify-center items-center">
                                                    @if ($item->updated_by != null)
                                                        @if ($item->status == 'PENDING' && $item->updated_by == Auth::guard('web')->user()->id)
                                                            @if (!firm())
                                                                <div
                                                                    class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        En-attente
                                                                    </span>

                                                                </div>
                                                            @endif
                                                        @elseif ($item->status == 'SUCCESS' && $item->updated_by == Auth::guard('web')->user()->id)
                                                            @if (!firm())
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a target="_bank"
                                                                        href="{{ route('paiements.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                @endif
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