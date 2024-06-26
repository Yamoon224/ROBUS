<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions de commande de carte
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <button wire:click="$emit('modal-show')" class="btn btn-primary shadow-md mr-2">Ajouter</button>

        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Carte</th>
                        <th class="whitespace-nowrap text-center">Montant</th>
                        <th class="whitespace-nowrap text-center">Montant marchants</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($order_cards->count() > 0)
                        @foreach ($order_cards as $item)
                            <tr class="intro-x">
                                <td class="w-40">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->created_at ?? null }}</p>
                                </td>

                                <td>
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->name ?? null }}
                                    </p>

                                </td>
                                <td>
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->provider->name ?? null }}
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->amount ?? null }} Frcfa
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->percent_merchands ?? null }} Frcfa
                                    </p>

                                </td>
                               
                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-order-card-modal')"
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
    @include('livewire.commissions.modals.updateOrderCard')
    @include('livewire.commissions.modals.addOrderCard')

</div>
