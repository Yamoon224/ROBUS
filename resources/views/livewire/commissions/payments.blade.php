<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les paiements de facture
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Montant canal</th>
                        <th class="whitespace-nowrap text-center">Montant CIE/SODECI</th>
                        <th class="whitespace-nowrap text-center">Comission S-D Canal</th>
                        <th class="whitespace-nowrap text-center">Comission S-D CIE/SODECI</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($payments->count() > 0)
                        @foreach ($payments as $item)
                            <tr class="intro-x">

                                <td>

                                    {{ $item->name ?? null }}

                                </td>
                                <td class="text-center">

                                    {{ $item->amount_canal ?? null }} Frcfa


                                </td>
                                <td class="text-center">

                                    {{ $item->amount_auther ?? null }} Frcfa


                                </td>

                                <td class="text-center">

                                    {{ $item->comission_canal ?? null }} Frcfa


                                </td>
                                <td class="text-center">

                                    {{ $item->comission_auther ?? null }} Frcfa


                                </td>

                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-payment-modal')"
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
    @include('livewire.commissions.modals.addPayment')
    @include('livewire.commissions.modals.disabledPayment')
    @include('livewire.commissions.modals.updatePayment')

</div>
