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
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">

            </div>
            <div class="col-span-12 sm:col-span-8 xl:col-span-8 intro-y">
                <div class="alert alert-secondary show mb-2 font-medium text-lg text-center" role="alert">Les
                    paiements
                    entrants</div>
            </div>
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">

            </div>
        </div>
        {{-- @php
            
            $collection = new Illuminate\Database\Eloquent\Collection([]);
            
            $data = [];
            
            $data = $usersCountries;
            
            foreach ($data as $value) {
                $data1 = [
                    'sumAmountUsers' => $value->wallet->amount,
                ];
                $collection->push($data1);
            }
            
            $cumul_amountUsers = [];
            
            for ($i = 0; $i < $collection->count(); $i++) {
                $cumul_amountUsers[] = $collection[$i]['sumAmountUsers'];
            }
            
        @endphp --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table2">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ array_sum($adminCountries) ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde réel Admin
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ array_sum($cumul_amountUsers) ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde réel Clients
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table1">
                        <div class="flex">
                            <i data-feather="users" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $usersCountries->count() ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Clients</div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5">
                        <div class="flex">
                            <i data-feather="users" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $usersAdminCountries->count() ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Utilisateurs
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <h2 class="intro-y text-lg font-medium mt-10">
            Liste de utilisateurs
        </h2>
        {{-- <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

                <div class="hidden md:block mx-auto text-slate-500"></div>
            </div>
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap">Date</th>
                            <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                            <th class="text-center whitespace-nowrap">Téléphone</th>
                            <th class="text-center whitespace-nowrap">Type</th>
                            <th class="text-center whitespace-nowrap">Email</th>
                            <th class="text-center whitespace-nowrap">Rôle</th>
                            <th class="text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($usersAdmin->count() > 0)

                            @foreach ($usersAdmin as $item)
                                <tr class="intro-x">
                                    <td class="w-35">
                                        <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}</div>

                                    </td>
                                    <td class="w-50">
                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                            <div class="text-slate-300 text-xs  mt-0.5">{{ $item->firstname }}
                                                {{ $item->lastname }}</div>
                                        </div>
                                        <div class="text-slate-500  mt-0.5">
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->country->name ?? null }} ville :
                                                {{ $item->city->name ?? null }}
                                            </span>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            {{ $item->phone }}
                                        </div>

                                    </td>
                                    <td>

                                        @if ($item->is_distributer == '1')
                                            <div class="font-medium whitespace-nowrap">Distributeur</div>
                                        @elseif($item->is_under_distributer == '1')
                                            <div class="font-medium whitespace-nowrap">Sous-distributeur</div>
                                        @else
                                            <div class="font-medium whitespace-nowrap">Admin</div>
                                        @endif

                                    </td>
                                    <td>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            {{ $item->email }}
                                        </div>

                                    </td>
                                    <td class="text-center">
                                        @foreach ($item->get_role() as $role)
                                            <span class="m-1 badge badge-info"> {{ $role->name }} </span>
                                        @endforeach
                                    </td>
                                    <td class="table-report__action w-30">
                                        <div class="flex justify-center items-center">
                                            @if ($item->deleted_at)
                                                @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                                                    <a class="flex items-center mr-3"
                                                        href="{{ route('admin.activate', ['id' => $item->id]) }}">
                                                        <i data-feather="user-check" class="block mx-auto"></i>Activé
                                                    </a>
                                                @endif
                                            @else
                                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur'))
                                                    <a class="flex items-center mr-3"
                                                        href="{{ route('admin.ShowProfil', ['id' => $item->id]) }}">
                                                        <i data-feather="eye" class="h-15 mr-1"></i>
                                                    </a>
                                                @endif
                                                @if (Auth::guard('web')->user()->hasPermissionTo('supprimer-administrateur'))
                                                    <a class="flex items-center text-danger"
                                                        data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                        href="{{ route('admin.delete', ['id' => $item->id]) }}"
                                                        onclick="promptAlert(this)" data-tw-toggle="modal"
                                                        data-tw-target="#delete-confirmation-modal"> <i
                                                            data-feather="trash-2" class="h-15 mr-1"></i> </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Aucun client pour le moment</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
            </div>
            <!-- END: Data List -->

        </div>
        <h2 class="intro-y text-lg font-medium mt-10">
            Liste de clients
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

                <div class="hidden md:block mx-auto text-slate-500"></div>
            </div>
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap">Date</th>
                            <th class="text-center whitespace-nowrap">Prénom(s) & nom</th>
                            <th class="text-center whitespace-nowrap">Téléphone</th>
                            <th class="text-center whitespace-nowrap">Type</th>
                            <th class="text-center whitespace-nowrap">Email</th>
                            <th class="text-center whitespace-nowrap">Rôle</th>
                            <th class="text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->count() > 0)

                            @foreach ($users as $item)
                                <tr class="intro-x">
                                    <td class="w-35">
                                        <div class="text-slate-500 text-xs  mt-0.5">{{ $item->created_at }}</div>

                                    </td>
                                    <td class="w-50">
                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                            <div class="text-slate-300 text-xs  mt-0.5">{{ $item->firstname }}
                                                {{ $item->lastname }}</div>
                                        </div>
                                        <div class="text-slate-500  mt-0.5">
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $item->country->name ?? null }} ville :
                                                {{ $item->city->name ?? null }}
                                            </span>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            {{ $item->phone }}
                                        </div>

                                    </td>
                                    <td>

                                        @if ($item->is_distributer == '1')
                                            <div class="font-medium whitespace-nowrap">Distributeur</div>
                                        @elseif($item->is_under_distributer == '1')
                                            <div class="font-medium whitespace-nowrap">Sous-distributeur</div>
                                        @else
                                            <div class="font-medium whitespace-nowrap">Admin</div>
                                        @endif

                                    </td>
                                    <td>
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                            {{ $item->email }}
                                        </div>

                                    </td>
                                    <td class="text-center">
                                        @foreach ($item->get_role() as $role)
                                            <span class="m-1 badge badge-info"> {{ $role->name }} </span>
                                        @endforeach
                                    </td>
                                    <td class="table-report__action w-30">
                                        <div class="flex justify-center items-center">
                                            @if ($item->deleted_at)
                                                @if (Auth::guard('web')->user()->hasPermissionTo('activer-administrateur'))
                                                    <a class="flex items-center mr-3"
                                                        href="{{ route('admin.activate', ['id' => $item->id]) }}">
                                                        <i data-feather="user-check" class="block mx-auto"></i>Activé
                                                    </a>
                                                @endif
                                            @else
                                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-administrateur'))
                                                    <a class="flex items-center mr-3"
                                                        href="{{ route('admin.ShowProfil', ['id' => $item->id]) }}">
                                                        <i data-feather="eye" class="h-15 mr-1"></i>
                                                    </a>
                                                @endif
                                                @if (Auth::guard('web')->user()->hasPermissionTo('supprimer-administrateur'))
                                                    <a class="flex items-center text-danger"
                                                        data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                        href="{{ route('admin.delete', ['id' => $item->id]) }}"
                                                        onclick="promptAlert(this)" data-tw-toggle="modal"
                                                        data-tw-target="#delete-confirmation-modal"> <i
                                                            data-feather="trash-2" class="h-15 mr-1"></i> </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">Aucun client pour le moment</td>
                            </tr>
                        @endif
                    </tbody>

                </table>
            </div>
            <!-- END: Data List -->

        </div>
        <div class="grid grid-cols-12 gap-6 mt-6">
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">

            </div>
            <div class="col-span-12 sm:col-span-8 xl:col-span-8 intro-y">
                <div class="alert alert-secondary show mb-2 font-medium text-lg text-center" role="alert">Les
                    paiements
                    sortants</div>
            </div>
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">

            </div>
        </div> --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table3">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            100000 FRCFA
                        </div>
                        <div class="text-base text-slate-500 mt-1">Total paiements</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
