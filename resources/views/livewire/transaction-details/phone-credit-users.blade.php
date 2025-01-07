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
                    Liste des rechargements de credit telephonique
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
                                        Traité le
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
                                @if ($all_phonecredits->count() > 0)
                                    @foreach ($all_phonecredits as $key => $item)
                                        <tr class="intro-x">
                                            <td>
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->provider->name ?? null }}
                                                </p>

                                            </td>
                                            <td class="w-50">
                                                <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                    <div class="text-slate-300 text-xs  mt-0.5">
                                                        {{ $item->wallet->sidUser->firstname ?? null }}
                                                        {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                </div>
                                                <div class="text-slate-500  mt-0.5">
                                                    <span
                                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                        {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                        :
                                                        {{ $item->wallet->sidUser->city->name ?? null }}
                                                    </span>
                                                </div>

                                            </td>
                                            <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                    {{ $item->wallet->currencie->code ?? null }}</p>
                                                    <p class="font-medium whitespace-nowrap mt-3">
                                                        Solde déposant : {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                         {{ $item->wallet->currencie->code ?? null }}</p>
                                            </td>
                                            <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->created_at ?? null }}</p>
                                            </td>
                                            <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->paymentPhone->created_at ?? null }}</p>
                                            </td>

                                            <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->phone ?? null }}
                                                </p>
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
                                                    @if ($item->status == 'PENDING')
                                                        @if (!firm())
                                                            @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                <a wire:click="editPhoneCredit({{ $item->id }}, 'update-phoneCredit-modal')"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    href="javascript:;" title="Modifier">
                                                                    Modifier
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @elseif ($item->status == 'SUCCESS')
                                                        @if (!firm())
                                                            @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                <a target="_bank"
                                                                    href="{{ route('phone.invoice.print', $item->id) }}"
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
                    {{ $all_phonecredits->links('vendor.pagination.custum') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->

    </div>

</div>
