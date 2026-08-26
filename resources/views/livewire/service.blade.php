<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des operateurs
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
        {{-- @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-operateurs'))
            <a class="btn btn-primary mt-3" href="{{ route('services.add') }}">Créer</a>
        @endif --}}

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

            <div class="hidden md:block mx-auto text-slate-500"></div>
            <a class="btn btn-primary mt-3 mr-2" href="{{ route('services.pixel_innove') }}">Pixel Innove</a>
            <a class="btn btn-primary mt-3" href="{{ route('services.offers.browse') }}">Liste des offres</a>
            <div class="hidden md:block ml-8 text-slate-500">
            </div>
            <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="table_search" wire:model.debounce.250ms="search"
                        class="form-control  w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                        data-feather="search">
                    </i>

                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap text-center">Image</th>
                        <th class="whitespace-nowrap text-center">Nom</th>
                        <th class="whitespace-nowrap text-center">Type</th>
                        <th class="text-center whitespace-nowrap text-center">Pays</th>
                        <th class="text-center whitespace-nowrap text-center">Description</th>
                        <th class="text-center whitespace-nowrap text-center">Status</th>
                        <th class="text-center whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($providers->count() > 0)
                        @foreach ($providers as $item)
                            <tr class="intro-x">

                                <td class="w-40">
                                    <div class="flex">
                                        <div class="imw-10 imh-10 image-fit zoom-in">
                                            <img alt="image" class="max-w-sm h-auto shadow-lg"
                                                src="{{ asset($item->image ?? 'public/dist/images/preview-8.jpg') }}"
                                                title="Logo">
                                        </div>
                                    </div>
                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->name ?? null }}</div>

                                </td>
                                <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->providerType->name ?? null }}
                                    </div>

                                </td>
                                <td class="w-35 text-center">

                                    @foreach ($item->country_services as $service)
                                        <span
                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                            {{ $service->country->name ?? null }}</span>
                                    @endforeach
                                </td>
                                {{-- <td class="w-35 text-center">
                                    <div class="text-slate-500 text-xs  mt-0.5">{{ $item->country->name ?? null }}</div>

                                </td> --}}
                                <td class="w-35 text-center">
                                    @if ($item->provider_type_id == 5)
                                        @if ($item->is_order == '1')
                                            @if (Auth::guard('web')->user()->hasPermissionTo('active-operateurs'))
                                                <div class="flex items-center justify-center text-success">

                                                    <a wire:click="edit({{ $item->id }}, 'show-disabledOrder-modal'); $emit('show-disabledOrder-modal')"
                                                        class="flex items-center mr-3 @if ($item->is_order == 1) @else @endif"
                                                        href="javascript:;"
                                                        title="@if ($item->is_order == 1) Désactiver @else Activer @endif">
                                                        <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé la  carte
                                                    </a>

                                                </div>
                                            @endif
                                        @else
                                            @if (Auth::guard('web')->user()->hasPermissionTo('active-operateurs'))
                                                <div class="flex items-center justify-center text-danger">
                                                    <a wire:click="edit({{ $item->id }}, 'show-disabledOrder-modal'); $emit('show-disabledOrder-modal')"
                                                        class="flex items-center mr-3 @if ($item->is_order == 1) @else @endif"
                                                        href="javascript:;"
                                                        title="@if ($item->is_order == 1) Désactiver @else Activer @endif">
                                                        <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé la  carte
                                                    </a>

                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="text-slate-500 text-xs  mt-1">{{ $item->description ?? null }}</div>

                                </td>
                                <td class="w-40 ">
                                    @if ($item->status == '1')
                                        @if (Auth::guard('web')->user()->hasPermissionTo('active-operateurs'))
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
                                        @if (Auth::guard('web')->user()->hasPermissionTo('active-operateurs'))
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
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-operateur'))
                                            <a href="{{ route('services.edit', ['id' => $item->id]) }}"
                                                class="btn btn-warning   mr-1 mb-2 btn-sm" href="javascript:;"
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
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $providers->links() }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
    @include('livewire.commissions.modals.providers.addProvider')
    @include('livewire.commissions.modals.providers.disabledProvider')
    @include('livewire.commissions.modals.providers.desabledOrderCard')
    @include('livewire.commissions.modals.providers.updateProvider')
    @include('livewire.commissions.modals.providers.deleteProvider')

</div>
