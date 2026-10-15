<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les credits telephoniques
    </h2>

    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2 gap-3">
        <div class="w-56 relative text-slate-500">
            <input type="text" name="table_search" wire:model="search" class="form-control w-56 box pr-10"
                placeholder="Rechercher">
            <i class="w-4 h-4 absolute my-auto inset-y-0 right-3" data-feather="search"></i>
        </div>
        <div class="flex gap-2 ml-auto">
            <a href="{{ route('commissions.browse_phones.export.excel') }}"
                class="btn btn-outline-secondary btn-sm flex items-center gap-1">
                <i data-feather="file-text" class="w-4 h-4"></i> Excel
            </a>
            <a href="{{ route('commissions.browse_phones.export.pdf') }}"
                class="btn btn-outline-danger btn-sm flex items-center gap-1">
                <i data-feather="file" class="w-4 h-4"></i> PDF
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-12 gap-6 mt-5">
       
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <h2 class="intro-y text-lg font-medium mt-10">
                Commissions pour les credits telephoniques mobiles
            </h2>
        
        </div>
    
        <div class="intro-y col-span-12 overflow-x-auto">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Commission</th>
                        <th class="whitespace-nowrap text-center">Comission Compagnie</th>
                        <th class="text-center whitespace-nowrap">Status</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($phone_credits->count() > 0)
                        @foreach ($phone_credits->where('is_web' , false) as $item)
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
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                </td>
                                  <td class="text-center">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                     <p href="" class="font-medium whitespace-nowrap">
                                      SD :  {{ $item->percent_merchands * 100 ?? null }} %
                                    </p>
                                      <p href="" class="font-medium whitespace-nowrap">
                                      Compagnie :  {{ $item->percent_companie * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->percent_banque * 100 ?? null }} %
                                    </p>

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
                                            <a wire:click="edit({{ $item->id }}, 'update-phone-modal')"
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


        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <h2 class="intro-y text-lg font-medium mt-10">
                Commissions pour les credits telephoniques visiteurs
            </h2>
        
        </div>

        <div class="intro-y col-span-12 overflow-x-auto">

        
            <table class="table table-bordered table-hover -mt-2 -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage admin</th>
                        <th class="text-center whitespace-nowrap">Status</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($phone_credits->count() > 0)
                        @foreach ($phone_credits->where('is_web' , true) as $item)
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
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->percent_banque * 100 ?? null }} %
                                    </p>

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
                                            <a wire:click="edit({{ $item->id }}, 'update-phone-modal')"
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
    @include('livewire.commissions.modals.addPhone')
    @include('livewire.commissions.modals.disabledPhone')
    @include('livewire.commissions.modals.updatePhone')

</div>
