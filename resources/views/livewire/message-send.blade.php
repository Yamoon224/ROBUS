<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des messages envoyés
    </h2>


    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-administrateur'))
                <a class="btn btn-primary shadow-md mr-2" href="{{ route('messages.add') }}">Envoyer message</a>
            @endif

            <div class="hidden md:block mx-auto text-slate-500"></div>



            <div class="hidden md:block mx-auto text-slate-500"></div>


            <div class="hidden md:block ml-8 text-slate-500"></div>
            <div class="mt-3 input-group input-group-md" style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500">

                    <input type="text" name="table_search" wire:model="search" class="form-control  w-56 box pr-10"
                        style="height: 3rem;" placeholder="Rechercher">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                        data-feather="search"></i>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Date de d'envoie</th>
                        <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                        <th class="text-center whitespace-nowrap">Message</th>
                    </tr>
                </thead>

                @foreach ($messages as $item)
                    <tbody>

                        <tr class="intro-x">
                            <td class="w-35">
                                <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}</div>

                            </td>
                            <td class="w-60">

                                @if ($item->type_send == 'under')
                                    <div class="user-badge user-badge--distributor">
                                        Sous-distributeur
                                    </div>
                                @else
                                    <div class="user-badge user-badge--customer">
                                        Client
                                    </div>
                                @endif
                                <div class="user-name">
                                    {{ $item->sidUser->firstname ?? '' }}
                                    {{ $item->sidUser->lastname ?? '' }}
                                </div>
                                <div class="user-location">
                                    {{ $item->sidUser->country->name ?? 'Pays inconnu' }}
                                    •
                                    {{ $item->sidUser->city->name ?? 'Ville inconnue' }}
                                </div>


                            </td>
                            <td class="table-report__action w-35">
                                <div class="text-slate-500  mt-0.5">
                                    {{ $item->messages }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @endforeach

            </table>
        </div>
        <!-- END: Data List -->

    </div>

    <!-- BEGIN: Pagination -->
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
        <nav class="w-full sm:w-auto sm:mr-auto">
            <ul class="pagination">
                {{ $messages->links() }}
            </ul>
        </nav>
    </div>


</div>
