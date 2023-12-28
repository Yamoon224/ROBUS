<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions a l'internationnal
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        {{-- @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-caution'))
            <button wire:click="$emit('modal-show')" class="btn btn-primary shadow-md mr-2">Ajouter</button>
        @endif --}}
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage marchants</th>
                        <th class="whitespace-nowrap">Description</th>
                        <th class="text-center whitespace-nowrap">Status</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($inters->count() > 0)
                        @foreach ($inters as $item)
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
                                <td class="text-center">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ floatval($item->percent)  * 100  ?? null }} %
                                    </p>

                                </td>
                                  <td class="text-center">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ floatval($item->percent_merchands) * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="w-40">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->walletBank->decription ?? null }}</p>
                                </td>
                                <td class="w-40">
                                    @if ($item->status == '1')
                                        @if (Auth::guard('web')->user()->hasPermissionTo('active-caution'))
                                            <div class="flex items-center justify-center text-success">

                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                </a>

                                            </div>
                                        @endif
                                    @else
                                        @if (Auth::guard('web')->user()->hasPermissionTo('desactive-caution'))
                                            <div class="flex items-center justify-center text-danger">
                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                </a>

                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-inter-modal')"
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
    @include('livewire.commissions.modals.addInter')
    @include('livewire.commissions.modals.disabledInter')
    @include('livewire.commissions.modals.updateInter')

</div>
