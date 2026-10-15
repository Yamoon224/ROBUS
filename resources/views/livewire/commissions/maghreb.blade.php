<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les transactions au Maghreb
    </h2> 
    <div class="col-span-12 lg:col-span-12">
                @foreach ($countrie_africa as $item)
                    <span
                        class="alert alert-primary mt-3 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                        {{ $item->name ?? null }}</span>
                @endforeach
     </div>
     <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        <div class="hidden md:block mx-auto text-slate-500"></div>
        
        <div class="hidden md:block ml-8 text-slate-500"></div>
        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" wire:model="search" class="form-control  w-56 box pr-10"
                    style="height: 3rem;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-x-auto">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Liste des commissions des differentes transactions vers les Pays du Maghreb et autres
            </h2>
           
        
            <table class="table table-bordered table-hover -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">%<br/>client/S-Distibuteur </th>
                        <th class="whitespace-nowrap w-20">% Admin <br/>client/S-Distibuteur</th>
                        <th class="whitespace-nowrap w-20">% S-agent depot <br/>client/S-Distibuteur</th>
                        <th class="whitespace-nowrap w-20">% S-agent retrait<br/> client/S-Distibuteur</th>
                        <th class="whitespace-nowrap w-20">% <br />  banque</th>
                        <th class="whitespace-nowrap w-20">% <br />  Compagnie</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                 
                    @if ($centres_africa->count() > 0)
                        @foreach ($centres_africa->where('is_maghreb' , true) as $item)
                        <tr class="intro-x">
                            <td class="w-65">
                                {{ $item->name ?? null }}
                            </td>
                            <td class="text-center">
                                <p class="font-medium whitespace-nowrap">
                                    {{ floatval($item->percent) * 100 ?? null }} %
                                </p>
                                <p class="font-medium whitespace-nowrap mt-1">
                                    {{ floatval($item->percent_under_distributer) * 100 ?? null }} %
                                </p>

                            </td>
                            <td class="text-center">
                                <p class="font-medium whitespace-nowrap">
                                    {{floatval($item->percent_admin) * 100 ?? null }} %
                                </p>

                                <p class="font-medium whitespace-nowrap mt-1">
                                    {{ floatval($item->percent_admin_under_distributer) * 100 ?? null }} %
                                </p>
                            </td>
                            <td class="text-center">
                                <p class="font-medium whitespace-nowrap">
                                    {{ floatval( $item->percent_deposit) * 100 ?? null }} %
                                </p>
                                <p class="font-medium whitespace-nowrap mt-1">
                                    {{floatval($item->percent_deposit_under_distributer) * 100 ?? null }} %
                                </p>


                            </td>
                            <td class="w-40">
                                <p class="font-medium whitespace-nowrap">
                                    {{ floatval($item->percent_with_drawal) * 100 ?? null }} %</p>
                                <p class="font-medium whitespace-nowrap mt-1">
                                    {{ floatval($item->percent_with_drawal_under_distributer)	 * 100 ?? null }} %</p>
                            </td>

                            <td class="w-40">
                                <p class="font-medium whitespace-nowrap">
                                    {{floatval($item->percent_banque) * 100 ?? null }} %</p>
                                <p class="font-medium whitespace-nowrap mt-1">
                            </td>
                            <td class="w-40">
                                <p class="font-medium whitespace-nowrap">
                                    {{floatval($item->percent_companie) * 100 ?? null }} %</p>
                                <p class="font-medium whitespace-nowrap mt-1">
                            </td>

                            <td class="w-56">
                                <div class="flex justify-center items-center">
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                        <a wire:click="edit({{ $item->id }}, 'update-trans-level_money-modal')"
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
    @include('livewire.commissions.modals.mobiles.updateMobileMoney')

</div>
