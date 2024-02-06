<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les transactions de
    </h2>


    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Liste des commissions des differentes transactions d'un distributeur vers un client de son pays
            </h2>

            <table class="table table-bordered table-hover -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage S-agent depot</th>
                        <th class="whitespace-nowrap text-center">Pourcentage S-agent retrait</th>
                        <th class="whitespace-nowrap text-center">Pourcentage MASTERS</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($centre_afriques->count() > 0)
                        @foreach ($centre_afriques->where('distributer_custumer', true) as $item)
                            <tr class="intro-x">
                                <td class="w-65">
                                    {{-- <p class="font-medium whitespace-nowrap"> --}}
                                    {{ $item->name ?? null }}
                                    {{-- </p> --}}

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_admin * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_deposit * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_with_drawal * 100 ?? null }} %</p>
                                </td>

                                <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_masters * 100 ?? null }} %</p>
                                </td>


                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-trans-cafrique-modal')"
                                                class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Modifier
                                            </a>
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
        <!-- END: Data List -->
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Envoie d'un compte d'un compte client vers un client de son pays
            </h2>

            <table class="table table-bordered table-hover -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage S-agent retrait</th>
                        <th class="whitespace-nowrap text-center">Pourcentage MASTERS</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($centre_afriques->count() > 0)
                        @foreach ($centre_afriques->where('custumer_custumer', true) as $item)
                            <tr class="intro-x">
                                <td class="w-65">
                                    {{-- <p class="font-medium whitespace-nowrap"> --}}
                                    {{ $item->name ?? null }}
                                    {{-- </p> --}}

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_admin * 100 ?? null }} %
                                    </p>

                                </td>

                                <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_with_drawal * 100 ?? null }} %</p>
                                </td>
                                <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_masters * 100 ?? null }} %</p>
                                </td>

                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit_caf_caf({{ $item->id }}, 'update-trans-cafrique-modal')"
                                                class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Modifier
                                            </a>
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
        <!-- END: Data List -->
    </div>
    @include('livewire.commissions.modals.transactions.transactionCosts.updateTransactionCAF')

</div>
