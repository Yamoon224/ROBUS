@extends('layouts.master')

@section('title', 'Details pays')

@section('content')
    <div class="intro-y  px-5 pt-1 mt-1">

        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">
                <h2 class="font-medium text-base mr-auto mt-3">
                    <a href="{{ URL::previous() }}" class="btn btn-sm">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                            fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                </h2>
            </div>
            <div class="col-span-12 sm:col-span-8 xl:col-span-8 intro-y">
                <div class="alert alert-secondary show mb-2 font-medium text-lg text-center" role="alert">Les
                    paiements
                    entrants en/au {{ $countrie->name ?? null }} </div>
            </div>
            <div class="col-span-12 sm:col-span-2 xl:col-span-2 intro-y">

            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-5">

            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table2">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">

                            {{ number_format($sum_wallet_custumers, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde cliens
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ number_format($sum_wallet_supervisors, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde superviseurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ number_format($sum_wallet_companies, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde Compagnie
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ number_format($sum_wallet_under_distributors, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde Sous distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ number_format($sum_wallet_distributors, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde  distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ number_format($sum_wallet_masters, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde  masters
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ number_format($sum_wallet_comZones, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde COM ZONES
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">

            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table2">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                           {{ $custumers_countrie_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Clients
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_supervisor_count ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1">Superviseurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_company_count ?? null }}

                        </div>
                        <div class="text-base text-slate-500 mt-1"> Compagnie
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $users_distri_Countrie_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_under_distributor_count ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1"> Sous distributeurs
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_master_count    ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">Solde  masters
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
                <div class="report-box zoom-in">
                    <div class="box p-5 style_table4">
                        <div class="flex">
                            <i data-feather="credit-card" class="report-box__icon text-secondary"></i>
                        </div>
                        <div class="text-3xl font-medium leading-8 mt-6">
                            {{ $user_comzone_count    ?? null }}
                        </div>
                        <div class="text-base text-slate-500 mt-1">COM ZONES
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <form class="" id="sort_status" action="" method="GET">
                    <div class="w-56 relative text-slate-500">
                        <label for="countriesFilter">Filtrer par utilisateurs</label>
                        <select class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                            name="users_status" onchange="sort_status()" data-selected="{{ $users_status }}">
                            <option value="">Filtrer par utilisateurs</option>
                            <option value="is_admin">Administrateurs</option>
                            <option value="is_distributer">Distributeurs</option>
                            <option value="is_under_distributer	">Sous-distributeurs</option>
                            <option value="is_custumers">Clients</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="hidden md:block mx-auto text-slate-500"></div>
        </div>
        <h2 class="intro-y text-lg font-medium mt-10 style-country-info">
            Liste des utilisateurs
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5 mb-3">


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
                        @if ($get_users->count() > 0)

                            @foreach ($get_users as $item)
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

                                        @if ($item->is_distributer == true)
                                            <div class="font-medium whitespace-nowrap">Distributeur</div>
                                        @elseif($item->is_under_distributer == true)
                                            <div class="font-medium whitespace-nowrap">Sous-distributeur</div>
                                        @elseif($item->is_custumers == true)
                                            <div class="font-medium whitespace-nowrap">Client</div>
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
                                        @if ($item->is_distributer == true)
                                            <div class="font-medium whitespace-nowrap">Distributeur</div>
                                        @elseif($item->is_under_distributer == true)
                                            <div class="font-medium whitespace-nowrap">Sous-distributeur</div>
                                        @elseif($item->is_custumers == true)
                                            <div class="font-medium whitespace-nowrap">Client</div>
                                        @else
                                            <div class="font-medium whitespace-nowrap">Admin</div>
                                        @endif
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

            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 lg:col-span-6 mt-8">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Activités d'aujourd'hui en {{ $usersCountry }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum_todays" virement_todays="{{ json_encode($sum_bank_todays) }}"
                        deposit_todays="{{ json_encode($sum_deposit_todays) }}"
                        payment_todays="{{ json_encode($sum_payment_todays) }}"
                        reload_todays="{{ json_encode($sum_reload_todays) }}"
                        transaction_todays="{{ json_encode($sum_transaction_todays) }}"
                        inter_todays="{{ json_encode($sum_inter_todays) }}"
                        phone_todays="{{ json_encode($sum_phoneCredit_todays) }}"></canvas>

                </div>
            </div>
            <!-- END: Sales Report -->
            <!-- BEGIN: Sales Report -->
            <div class="col-span-12 sm:col-span-6 lg:col-span-6 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        Les activités des 30 derniers jours en {{ $usersCountry }}
                    </h2>
                </div>
                <div class="intro-y box p-5 mt-12 sm:mt-5">
                    <canvas id="sum" virement="{{ json_encode($sum_bank_month) }}"
                        deposit="{{ json_encode($sum_deposit_month) }}" payment="{{ json_encode($sum_payment_month) }}"
                        reload="{{ json_encode($sum_reload_month) }}"
                        transaction="{{ json_encode($sum_transaction_month) }}"
                        inter="{{ json_encode($sum_inter_month) }}"
                        phone="{{ json_encode($sum_phoneCredit_month) }}"></canvas>
                </div>
            </div>
            <!-- END: Sales Report -->
        </div>



    </div>

    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var sumVirement = JSON.parse($('#sum').attr('virement'));
        var sumDeposit = JSON.parse($('#sum').attr('deposit'));
        var sumPayment = JSON.parse($('#sum').attr('payment'));
        var sumReload = JSON.parse($('#sum').attr('reload'));
        var sumTransaction = JSON.parse($('#sum').attr('transaction'));
        var sumInter = JSON.parse($('#sum').attr('inter'));
        var sumPhone = JSON.parse($('#sum').attr('phone'));
        var _ctx = $('#sum');

        var sumVirement_todays = JSON.parse($('#sum_todays').attr('virement_todays'));
        var sumDeposit_todays = JSON.parse($('#sum_todays').attr('deposit_todays'));
        var sumPayment_todays = JSON.parse($('#sum_todays').attr('payment_todays'));
        var sumReload_todays = JSON.parse($('#sum_todays').attr('reload_todays'));
        var sumTransaction_todays = JSON.parse($('#sum_todays').attr('transaction_todays'));
        var sumInter_todays = JSON.parse($('#sum_todays').attr('inter_todays'));
        var sumPhone_todays = JSON.parse($('#sum_todays').attr('phone_todays'));
        var _ctx2 = $('#sum_todays ');

        new Chart(_ctx, {
            type: 'bar',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits'
                ],
                datasets: [{

                    label: 'Activites',
                    data: [sumVirement, sumDeposit, sumPayment, sumReload, sumTransaction, sumInter,
                        sumPhone
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['white', 'white'],
                    }
                },
            }

        });

        new Chart(_ctx2, {
            type: 'bar',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits'
                ],
                datasets: [{

                    label: 'Activites',
                    data: [sumVirement_todays, sumDeposit_todays, sumPayment_todays, sumReload_todays,
                        sumTransaction_todays, sumInter_todays,
                        sumPhone_todays
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['white', 'white'],
                    }
                },
            }


        });
    </script>
    <script type="text/javascript">
        function sort_status(el) {
            $('#sort_status').submit();
        }
    </script>
@endsection
