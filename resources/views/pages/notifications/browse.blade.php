@extends('layouts.master')

@section('title', 'Liste des commandes')

@section('content')
    @if (count($orders) == 0)
        <div class="grid grid-cols-12 gap-6 mt-20">
            {{-- <div class="intro-y col-span-12 lg:col-span-3">
        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">

        </div>
        <!-- END: Form Validation -->
    </div> --}}
            <div class="col-span-12 lg:col-span-3 text-center">
                <!-- BEGIN: Form Validation -->

            </div>
            <div class="col-span-12 lg:col-span-6 text-center">
                <!-- BEGIN: Form Validation -->
                <div class="text-center mt-5">

                    <h4 class="mt-1">Pas de commande</h4>
                    <p>
                        Vous n'avez pas encore cette commandes.
                        <br>
                    </p>
                    <a class="btn btn-primary mt-3" href="{{ route('orders.browse') }}">Retour</a>
                    @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-paiement'))
                        <a class="btn btn-primary mt-3" href="{{ route('admin.add') }}">Créer une commande</a>
                    @endif
                </div>
                <!-- END: Form Validation -->
            </div>
            <div class="col-span-12 lg:col-span-3 text-center">
                <!-- BEGIN: Form Validation -->


                <!-- END: Form Validation -->
            </div>
        </div>
    @else
        <h2 class="intro-y text-lg font-medium mt-10 ml-l00">
            Liste des commandes
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <form class="" id="sort_status" action="" method="GET">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                name="delivery_status" onchange="sort_status()" data-selected="{{ $delivery_status }}">
                                <option value="">Filtrer par livraison</option>
                                <option value="In-progress">En-cours de paiement</option>
                                <option value="Pendding">En-attente de livraison</option>
                                <option value="Out_for_delivery">En-cours de livraison</option>
                                <option value="Finished">Livré</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <form method="GET" id="sort_zone" action="">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                aria-placeholder="Rechercher par zone" id="zone_id" name="zone_id" onchange="sort_zone()"
                                data-selected="{{ $zones }}">
                                <option selected disabled>Filtrer par zones</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->id }}">
                                        {{ $zone->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <form method="GET" id="sort_progression" action="">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                name="progression" onchange="sort_progression()">
                                <option value="">Filtrer par progression</option>
                                <option value="=0">A 100 % </option>
                                <option value="1">Reste 1 paiement </option>
                                <option value="2">Reste 2 paiements </option>
                                <option value="3">Reste 3 paiements </option>
                                <option value="4">Reste 4 paiements </option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0 ml-6">
                    <form class="" id="sort_orders" action="" method="GET">
                        <div class="w-56 relative text-slate-500">
                            <select
                                class="form-select py-3 px-4 box w-full dark-border-dark-3 w-lg-auto mt-3 mt-lg-0 ms-auto"
                                name="payment_status" onchange="sort_orders()" data-selected="{{ $payment_status }}">
                                <option value="">Filtrer par paiement</option>
                                <option value="1">Payer</option>
                                <option value="0">Impaiyer</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <div class="w-56 relative text-slate-500">
                        <form id="sort_codeUniq" action="" method="GET">
                            @csrf
                            <input type="text" class="form-control w-56 box pr-10 rounded-full" id="search"
                                name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset
                                placeholder="Rechercher ">
                            <i class="w-4 h-4 absolute mysort_codeUniq-auto inset-y-0 mr-3 mt-2 right-0"
                                data-feather="search"></i>

                        </form>
                        {{-- <livewire:search /> --}}

                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-bordered table-hover -mt-2 -intro-x ">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap">Code </th>
                            <th class="text-center whitespace-nowrap">Date</th>
                            <th class="text-center whitespace-nowrap">Nom & prenom</th>
                            <th class="text-center whitespace-nowrap">kits</th>
                            <th class="text-center whitespace-nowrap">Montant</th>
                            <th class="text-center whitespace-nowrap">%</th>
                            <th class="text-center whitespace-nowrap">Prog</th>
                            <th class="text-center whitespace-nowrap">Statut</th>
                            <th class="text-center whitespace-nowrap">Paiement</th>
                            <th class="text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    @foreach ($orders as $key => $order)
                    
                        <tbody>
                            <tr class="intro-x">
                                <td class="w-30">
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $order->uniq_id }}
                                    </div>
                                </td>
                                <td class="w-30">
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $order->created_at }}
                                    </div>
                                </td>
                                <td>
                                    @if ($order->user != null)
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                            <p class="font-medium whitespace-nowrap"> {{ $order->user->firstname }}</p>
                                        </div>
                                        <div class="text-slate-500  mt-0.5">
                                            <span
                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                {{ $order->user->zone->name ?? null }}
                                            </span>
                                        </div>
                                    @else
                                        Invitée ({{ $order->guest_id }})
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ count($order->orderDetails) }}
                                </td>
                                <td>
                                    @foreach ($order->paiements as $key => $orderDetail)
                                        @if ($order->paiements->last() === $orderDetail)
                                            {{ formatDisplayAmount($orderDetail->subamount) }} Frcfa
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center  w-25">
                                    @foreach ($order->paiements as $key => $orderDetail)
                                        @if ($loop->last != null)
                                            <div class="progress-bar  w-3/4 mb-1  bg-success" role="progressbar">


                                                {{ str_replace('.', ',', number_format((($order->paie_sell - $orderDetail->rest_paie_sell) / $order->paie_sell) * 100, 0)) ?? '0' }}
                                                %
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center  w-25">
                                    @foreach ($order->paiements as $key => $orderDetail)
                                        @if ($order->paiements->last() === $orderDetail)
                                            <div class="progress-bar  w-3/4 mb-1  bg-success" role="progressbar">


                                                {{ str_replace('.', ',', number_format((($order->paie_sell - $orderDetail->rest_paie_sell) / $order->paie_sell) * 100, 0)) ?? '0' }}
                                                %
                                            </div>
                                        @endif
                                    @endforeach
                                </td>

                                <td class="text-center w-25">
                                    @foreach ($order->paiements as $key => $orderDetail)
                                        @if ($loop->last)
                                            <div class="progress-bar  w-3/4 mb-1 " role="progressbar" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ formatDisplayAmount($order->paie_sell - $orderDetail->rest_paie_sell ?? '0') }} /
                                                {{ formatDisplayAmount($order->paie_sell ?? '0') }}
                                            </div>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @if ($order->progress_order == 'In-progress')
                                        <div class="report-box-2__indicator bg-pending" style="color: white;">
                                            <a class="flex items-center mr-3 ml-3"
                                                href="{{ route('orders.progressOrder', ['id' => $order->id]) }}"
                                                data-message="Etes vous sûr de vouloir mettre votre status En-attente de livraison  ?"
                                                onclick="promptAlert(this)">
                                                {{ 'En-cours de paiement' }}
                                            </a>
                                        </div>
                                    @elseif($order->progress_order == 'Pendding')
                                        @foreach ($order->paiements as $orderDetail)
                                            @if ($loop->last)
                                                @if ($orderDetail->rest_paie_sell === 0)
                                                    <div class="report-box-2__indicator bg-success" style="color: white;">
                                                        <a class="flex items-center mr-3 ml-3"
                                                            href="{{ route('orders.progressOrder', ['id' => $order->id]) }}"
                                                            data-message="Etes vous sûr de vouloir mettre votre status En-cours de livraison ?"
                                                            onclick="promptAlert(this)">
                                                            {{ 'En-attente de livraison' }}
                                                        </a>
                                                    </div>
                                                @elseif ($orderDetail->rest_paie_sell != 0)
                                                    <div class="report-box-2__indicator bg-success  "
                                                        style="color: white;">
                                                        {{ 'En-attente de livraison' }}
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @elseif($order->progress_order == 'Out_for_delivery')
                                        @foreach ($order->paiements as $orderDetail)
                                            @if ($loop->last)
                                                @if ($orderDetail->rest_paie_sell === 0)
                                                    <div class="report-box-2__indicator bg-success" style="color: white;">
                                                        <a class="flex items-center mr-3 ml-3"
                                                            href="{{ route('orders.progressOrder', ['id' => $order->id]) }}"
                                                            data-message="Etes vous sûr de vouloir mettre votre status livrer ?"
                                                            onclick="promptAlert(this)">
                                                            {{ 'En-cours de livraison' }}
                                                        </a>
                                                    </div>
                                                @elseif ($orderDetail->rest_paie_sell != 0)
                                                    <div class="report-box-2__indicator bg-success disabled "
                                                        style="color: white;">
                                                        {{ 'En-cours de livraison' }}
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    @elseif($order->progress_order == 'Finished')
                                        <div class="report-box-2__indicator bg-success" style="color: white;">

                                            {{ 'Livrer' }}

                                        </div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($order->payment_status == 'paid')
                                        <div class="report-box-2__indicator bg-success" style="color: white;">
                                            {{ 'Soldé' }}
                                        </div>
                                    @else
                                        <div class="report-box-2__indicator bg-danger" style="color: white;">
                                            {{ 'Impayé' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="table-report__action w-39">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-commandes'))
                                            <a class="flex items-center mr-3"
                                                href="{{ route('orders.show', ['id' => $order->id]) }}"> <i
                                                    data-feather="eye" class="h-15 mr-1"></i> </a>
                                        @endif

                                        @if (Auth::guard('web')->user()->hasPermissionTo('imprimer-commande'))
                                            <a class="flex items-center mr-3" href="javascript:void(0)"
                                                onclick="print_invoice('{{ route('orders.invoice.print', $order->id) }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-printer-fill w-4 h-4 mr-1 "
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z" />
                                                    <path
                                                        d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                </svg>
                                            </a>
                                        @endif
                                        @if (Auth::guard('web')->user()->hasPermissionTo('supprimer-commandes'))
                                            <a class="flex items-center text-danger"
                                                data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                href="{{ route('orders.destroy', ['id' => $order->id]) }}"
                                                style="display: none;" onclick="promptAlert(this)" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal"> <i data-feather="trash-2"
                                                    class="w-4 h-15 mr-1"></i> </a>
                                        @endif
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
                    {{ $orders->links('vendor.pagination.custumSingle') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    @endif
@endsection

@push('scripts')
    <script type="text/javascript">
        function sort_orders(el) {
            $('#sort_orders').submit();
        }

        function sort_status(el) {
            $('#sort_status').submit();
        }

        function sort_codeUniq(el) {
            $('#sort_codeUniq').submit();
        }

        function sort_zone(el) {
            $('#sort_zone').submit();
        }

        function sort_progression(el) {
            $('#sort_progression').submit();
        }

        function print_invoice(url) {
            var h = $(window).height();
            var w = $(window).width();
            window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
        }
    </script>
@endpush
