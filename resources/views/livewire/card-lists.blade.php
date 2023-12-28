<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des commandes de cartes
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
        @if (Auth::guard('web')->user()->wallet->sidUser->is_under_distributer == true ||
                Auth::guard('web')->user()->wallet->sidUser->is_admin == true)

            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-cartes'))
                <a class="btn btn-primary shadow-md" style="width: 100px;" href="{{ route('cards.add') }}">Commander </a>
            @endif
            <div class="dropdown">
                @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-cartes'))
                    <a class="dropdown-toggle btn px-2 box" href="{{ route('cards.add') }}" aria-expanded="false"
                        data-tw-toggle="dropdown">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4"
                                data-feather="plus"></i>
                        </span>
                    </a>
                @endif
            </div>

        @endif
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <label for="countriesFilter">Filtrer par pays</label>
                <select id="countriesFilter" wire:model="countriesFilter"
                    class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                    aria-placeholder="Rechercher par pays">
                    <option value="null" disabled>Rechercher par pays</option>
                    @foreach ($countries as $coutrie)
                        <option value="{{ $coutrie->id }}">{{ $coutrie->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500">

                    <input type="text" name="table_search" wire:model.debounce.250ms="search"
                        class="form-control  w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                        data-feather="search"></i>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date</th>
                        <th class="text-center whitespace-nowrap">Client</th>
                        <th class="text-center whitespace-nowrap">Type</th>
                        <th class="text-center whitespace-nowrap">Pays/Ville</th>
                        <th class="text-center whitespace-nowrap">Status</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($cards->count() > 0)
                        @foreach ($cards as $item)
                            <tr class="intro-x">
                                <td class="w-40">
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->created_at ?? null }}</p>
                                </td>

                                <td>

                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->wallet->sidUser->firstname ?? null }}
                                        {{ $item->wallet->sidUser->lastname ?? null }}
                                    </span>
                                    <div class="text-slate-500  mt-0.5">
                                        @if ($item->is_either == 1)
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ 'Commander : pour soit' }}
                                            </span>
                                        @elseif ($item->is_other == 1)
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ 'Commander : pour autre' }}
                                            </span>
                                        @endif
                                    </div>

                                </td>
                                <td>
                                    <p href="" class="font-medium whitespace-nowrap">
                                        {{ $item->typeCard->name ?? null }}
                                    </p>

                                </td>
                                <td>
                                    <span
                                        class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        {{ $item->country->name ?? null }} Ville :{{ $item->citie->name ?? null }}
                                    </span>
                                </td>
                                <td class="w-40">
                                    @if ($item->status == '1')
                                        @if (Auth::guard('web')->user()->hasPermissionTo('activer-carte'))
                                            <div class="flex items-center justify-center text-success">



                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-card-modal'); $emit('show-disabled-card-modal')"
                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        @if (Auth::guard('web')->user()->hasPermissionTo('activer-carte'))
                                            <div class="flex items-center justify-center text-danger">

                                                {{-- <a class="flex items-center mr-3"
                                                    href="{{ route('cards.activate', ['id' => $item->id]) }}"
                                                    data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                    onclick="promptAlert(this)">
                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                </a> --}}

                                                <a wire:click="edit({{ $item->id }}, 'show-disabled-card-modal'); $emit('show-disabled-card-modal')"
                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                    href="javascript:;"
                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                </a>


                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="table-report__action w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-cartes'))
                                            <a class="flex items-center mr-3"
                                                href="{{ route('cards.show', ['id' => $item->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    icon-name="eye" data-lucide="eye"
                                                    class="lucide lucide-eye block mx-auto h-15 mr-1">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg></a>
                                        @endif

                                        @if (Auth::guard('web')->user()->hasPermissionTo('activer-carte'))
                                            <a class="flex items-center mr-3"
                                                href="{{ route('cards.edit', ['id' => $item->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    icon-name="edit-2" data-lucide="edit-2"
                                                    class="lucide lucide-edit-2 block mx-auto h-15 mr-1">
                                                    <path d="M17 3a2.828 2.828 0 114 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                    </path>
                                                </svg>
                                            </a>
                                        @endif
                                        @if (Auth::guard('web')->user()->hasPermissionTo('supprimer-cartes'))
                                            <a class="flex items-center text-danger"
                                                data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                href="{{ route('cards.delete', ['id' => $item->id]) }}"
                                                onclick="promptAlert(this)" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal"> <svg
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    icon-name="trash-2" data-lucide="trash-2"
                                                    class="lucide lucide-trash-2 block mx-auto  h-15 mr-1">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2">
                                                    </path>
                                                    <line x1="10" y1="11" x2="10"
                                                        y2="17"></line>
                                                    <line x1="14" y1="11" x2="14"
                                                        y2="17"></line>
                                                </svg> </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center">Aucune carte pour le moment</td>
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
                    {{ $cards->links('vendor.pagination.custum') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->

    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('card-bank-created').listen(
                '.Created_card_bank',
                (e) => {
                    window.livewire.emit('notifyNewOrderCard:' + e.id);
                    // console.log('created');
                },
            )

        });
    </script>

    {{-- @include('livewire.commissions.modals.updateCard') --}}

</div>
