<div>
    <div class="intro-y box px-5 pt-1 mt-1">

        <h2 class="font-medium text-base mr-auto mt-3">
            <a href="{{ URL::previous() }}" class="btn btn-sm">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                    stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </h2>

        <h2 class="intro-y text-lg font-medium mt-10">
            Liste des contacts de : {{ $contactKey->name }} <br>
        </h2>
        <h4 class="m-0 p-0 mt-6">Nombre de Contacts: {{ count($contacts) }}</h4>
        <div class="grid grid-cols-12 gap-6 mt-5">
            {{-- <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <label for="countriesFilter">Filtrer par pays</label>
                <select id="countriesFilter" wire:model="countriesFilter"
                    class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto"
                    aria-placeholder="Rechercher par pays">
                    <option selected disabled>Rechercher par pays</option>
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
        </div> --}}
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                @if ($contacts->count() == 0)
                    <div class="row justify-content-center">
                        <div class="col-md-5 text-center mt-5">
                            <img src="{{ asset('images/empty.svg') }}" alt="empty" />
                            <h4 class="mt-1">Pas de contact dans cette liste</h4>
                            <p>
                                Vous n'avez pas encore de contact dans cette liste.
                                <br>
                                Veuillez créer un contact ou importer un fichier de contact
                            </p>
                            @if (Auth::guard('web')->user()->hasPermissionTo('voir-contacts'))
                                <button wire:click="$emit('modal-show')" class="btn btn-primary shadow-md mr-2 mt-3">
                                    Créer
                                    un
                                    nouveau contact</button>
                            @endif
                            <br>
                        </div>
                    </div>
                @else
                    <table class="table table-bordered table-hover -mt-2 -intro-x ">
                        <thead class="table-dark">
                            <tr>
                                <th class="whitespace-nowrap">Nom & prenom</th>
                                <th class="whitespace-nowrap">Email</th>
                                <th class="text-center whitespace-nowrap">Numero</th>
                                <th class="text-center whitespace-nowrap">Status</th>
                                <th class="text-center whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $item)
                                <tr class="intro-x">
                                    <td class="w-40">
                                        <p href="" class="font-medium whitespace-nowrap">{{ $item->firstname }}
                                            {{ $item->lastname }}</p>
                                    </td>
                                    <td>
                                        <p href="" class="font-medium whitespace-nowrap">
                                            {{ $item->email ?? null }}
                                        </p>

                                    </td>
                                    <td>
                                        <p href="" class="font-medium whitespace-nowrap">
                                            {{ $item->phone ?? null }}
                                        </p>

                                    </td>
                                    <td class="w-40">
                                        @if ($item->status == '1')
                                            @if (Auth::guard('web')->user()->hasPermissionTo('active-clients'))
                                                <div class="flex items-center justify-center text-success">

                                                    <a class="flex items-center mr-3"
                                                        href="{{ route('custumers.activate', ['id' => $item->id]) }}"
                                                        data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                        onclick="promptAlert(this)">
                                                        <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            @if (Auth::guard('web')->user()->hasPermissionTo('desactive-client'))
                                                <div class="flex items-center justify-center text-danger">

                                                    <a class="flex items-center mr-3"
                                                        href="{{ route('custumers.activate', ['id' => $item->id]) }}"
                                                        data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                        onclick="promptAlert(this)">
                                                        <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                                    </a>

                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            @if (Auth::guard('web')->user()->hasPermissionTo('voir-clients'))
                                                <a class="flex items-center mr-3"
                                                    href="{{ route('custumers.show', ['id' => $item->id]) }}"><i
                                                        data-feather="eye" class="h-15 mr-1"></i> </a>
                                            @endif
                                            @if (Auth::guard('web')->user()->hasPermissionTo('supprimer-clients'))
                                                <a class="flex items-center text-danger"
                                                    data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                    href="{{ route('custumers.delete', ['id' => $item->id]) }}"
                                                    onclick="promptAlert(this)" data-tw-toggle="modal"
                                                    data-tw-target="#delete-confirmation-modal"> <i
                                                        data-feather="trash-2" class="h-15 mr-1"></i> </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                @endif

                </tbody>

                </table>
            </div>
            <!-- END: Data List -->

        </div>

        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $contacts->links() }}
                </ul>
            </nav>
        </div>
    </div>
    <!-- END: Pagination -->
    @include('livewire.commissions.modals.addContactList')
    {{-- @include('livewire.commissions.modals.disabledContact')
    @include('livewire.commissions.modals.updateContact')
    @include('livewire.commissions.modals.deleteContact') --}}
</div>
