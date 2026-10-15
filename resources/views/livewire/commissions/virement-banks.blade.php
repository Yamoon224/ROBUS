<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions service Versements
    </h2>


    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>

        <div class="intro-y col-span-12 overflow-x-auto">

            <table class="table table-bordered table-hover -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage </th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Banque</th>
                        <th class="whitespace-nowrap text-center">Commission SD</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Compagnie</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($virementbanks->count() > 0)
                        @foreach ($virementbanks->where('is_web', false) as $item)
                            <tr class="intro-x">
                                <td class="w-65">
                                    {{-- <p class="font-medium whitespace-nowrap"> --}}
                                    {{ $item->name ?? null }}
                                    {{-- </p> --}}

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        SD : {{ $item->percent * 100 ?? null }} %
                                    </p>
                                    <p class="font-medium whitespace-nowrap mt-3">
                                        Client : {{ $item->percent_custumer * 100 ?? null }} %
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        SD : {{ $item->percent_admin * 100 ?? null }} %
                                    </p>
                                    <p class="font-medium whitespace-nowrap mt-3">
                                        Client : {{ $item->percent_admin_custumer * 100 ?? null }} %
                                    </p>
                                </td>
                                 <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                       SD :  {{ $item->percent_bank_custumer * 100 ?? null }} %</p>

                                       <p class="font-medium whitespace-nowrap mt-3">
                                        Client : {{ $item->percent_bank_custumer * 100 ?? null }} %
                                    </p>
                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                      {{ $item->percent_merchands * 100 ?? null }} %
                                    </p>
                                </td>

                                <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_companie * 100 ?? null }} %</p>
                                </td>


                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'show-onexbetrans-modal')"
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
    @include('livewire.commissions.modals.transactions.transactionCosts.updateVersment')

</div>
