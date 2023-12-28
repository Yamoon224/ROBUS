@extends('layouts.master')

@section('title', 'Details de la zone')

@section('content')
    <h2 class="intro-y text-lg font-medium mt-8">
        Details zone {{ $zones->name ?? null }}
    </h2>
    <h2 class="font-medium text-base mr-auto mt-3">
        <a href="{{ URL::previous() }}" class="btn btn-sm">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
    </h2>

    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">

                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                        {{ $zones->name ?? null }}</div>
                </div>
                <div class="ml-5">
                    @if ($zones->status == 1)
                        <div class="flex items-center justify-center text-success">

                            <a class="flex items-center mr-3"
                                data-message="Etes vous sûr de vouloir effectuer cette action ?">
                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                            </a>
                        </div>
                    @else
                        <div class="flex items-center justify-center text-danger">

                            <a class="flex items-center mr-3"
                                data-message="Etes vous sûr de vouloir effectuer cette action ?">
                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Desactivé
                            </a>
                        </div>
                    @endif

                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0">
                <div class="text-center rounded-md w-20 py-3">
                    <div class="font-medium text-primary text-xl">{{ $zones->custumers->count() ?? '0' }}</div>
                    <div class="text-slate-500">Clients</div>
                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0">
                <div class="text-center rounded-md w-20 py-3">
                    <div class="font-medium text-primary text-xl">{{ $zones->custumers->count() ?? '0' }}</div>
                    <div class="text-slate-500">Commandes</div>
                </div>
            </div>
        </div>
        {{-- @include('components.account-nav') --}}
        {{-- <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
            <li id="custumer-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center active" data-tw-target="#custumer"
                    aria-controls="custumer" aria-selected="true" role="tab"> <i class="w-4 h-4 mr-2"
                        data-feather="user"></i>Clients </a>
            </li>
            <li id="profile-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#profile"
                    aria-controls="profile" aria-selected="true" role="tab"> <i class="w-4 h-4 mr-2"
                        data-feather="shopping-bag"></i>Commandes </a>
            </li> --}}
            {{-- <li id="account-tab" class="nav-item" role="presentation">
                <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#account"
                    aria-controls="account" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                        data-feather="shield"></i> Paiements </a>
            </li> --}}
        {{-- </ul> --}}
    </div>
    <!-- END: Profile Info -->

    <div class="tab-content mt-5">

        <div id="custumer" class="tab-pane active" role="tabpanel" aria-labelledby="custumer-tab">
            <div class="grid grid-cols-12 gap-6">
                <div class="intro-y box col-span-12">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des clients
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_plus" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">#</th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Date</th>
                                            <th class="whitespace-nowrap">Nom & prenom</th>
                                            <th class="whitespace-nowrap">Email</th>
                                            <th class="text-center whitespace-nowrap">Numero</th>
                                            <th class="text-center whitespace-nowrap">Status</th>
                                            <th class="text-center whitespace-nowrap">Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($zones->custumers as $key => $item)
                                            <tr class="intro-x">
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <p href="" class="font-medium whitespace-nowrap">
                                                        {{ $item->created_at ?? null }}</p>

                                                </td>
                                                <td class="w-40">
                                                    <p href="" class="font-medium whitespace-nowrap">
                                                        {{ $item->firstname }}
                                                        {{ $item->lastname }}</p>
                                                </td>
                                                <td>
                                                    <p href="" class="font-medium whitespace-nowrap">
                                                        {{ $item->email ?? null }}</p>

                                                </td>
                                                <td>
                                                    <p href="" class="font-medium whitespace-nowrap">
                                                        {{ $item->phone ?? null }}</p>

                                                </td>
                                                <td class="w-40">
                                                    @if ($item->status == '1')
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('active-clients'))
                                                            <div class="flex items-center justify-center text-success">

                                                                <a class="flex items-center mr-3"
                                                                    data-message="Etes vous sûr de vouloir effectuer cette action ?">
                                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('desactive-client'))
                                                            <div class="flex items-center justify-center text-danger">

                                                                <a class="flex items-center mr-3"
                                                                    data-message="Etes vous sûr de vouloir effectuer cette action ?">
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
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- <div id="profile" class="tab-pane" role="tabpanel" aria-labelledby="profile-tab">
            <div class="grid grid-cols-12 gap-6">
                <div class="intro-y box col-span-12">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des commandes
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">#</th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Code</th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Date</th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut de
                                                paiement</th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut de
                                                livraison</th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users->orders()->latest()->get()
    as $key => $order)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                 <td class="text-center border-b dark:border-darkmode-400 w-32">
                                                    {{ $order->uniq_id ?? null }}</td>
                                                <td class="text-center border-b dark:border-darkmode-400 w-32">
                                                    {{ $order->uniq_id ?? null }}</td>
                                                <td class="text-center border-b dark:border-darkmode-400 w-32">
                                                    {{ $order->created_at ?? null }}
                                                <td class="text-center border-b dark:border-darkmode-400 w-32">
                                                    {{ $order->grand_total ?? null }}
                                                </td>

                                                <td class="text-center border-b dark:border-darkmode-400 w-32">
                                                    @if ($order->payment_status == 'paid')
                                                        <div class="report-box-2__indicator bg-success"
                                                            style="color: white;">
                                                            {{ 'Payer' }}
                                                        </div>
                                                    @else
                                                        <div class="report-box__indicator bg-danger"
                                                            style="color: white;">
                                                            {{ 'Impayer' }}
                                                        </div>
                                                    @endif
                                                </td>

                                                <td class="text-center border-b dark:border-darkmode-400 w-32">
                                                    @if ($order->progress_order == 'In-progress')
                                                        <div class="report-box-2__indicator bg-pending"
                                                            style="color: white;">
                                                            {{ 'En-cours' }}
                                                        </div>
                                                    @elseif($order->progress_order == 'Pendding')
                                                        <div class="report-box-2__indicator bg-success"
                                                            style="color: white;">
                                                            {{ 'En-attente de livraison' }}
                                                        </div>
                                                    @elseif($order->progress_order == 'Out_for_delivery')
                                                        <div class="report-box-2__indicator bg-success"
                                                            style="color: white;">
                                                            {{ 'En-cours de livraison' }}
                                                        </div>
                                                    @elseif($order->progress_order == 'Finished')
                                                        <div class="report-box-2__indicator bg-success"
                                                            style="color: white;">
                                                            {{ 'Livrer' }}
                                                        </div>
                                                    @endif
                                                </td>

                                                <td class="table-report__action w-56">
                                                    <div class="flex justify-center items-center">
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-commandes'))
                                                            <a class="flex items-center  mr-3"
                                                                href="{{ route('orders.show', ['id' => $order->id]) }}">
                                                                <i data-feather="eye" class="h-15 mr-1"></i> </a>
                                                        @endif
                                                        @if (Auth::guard('web')->user()->hasPermissionTo('imprimer-commande'))
                                                            <a class="flex items-center mr-3" href="javascript:void(0)"
                                                                onclick="print_invoice('{{ route('orders.invoice.print', $order->id) }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                    height="16" fill="currentColor"
                                                                    class="bi bi-printer-fill w-4 h-4 mr-1 "
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
                                                                onclick="promptAlert(this)" data-tw-toggle="modal"
                                                                data-tw-target="#delete-confirmation-modal"> <i
                                                                    data-feather="trash-2" class="h-15 mr-1"></i> </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                    </tbody>
                                    @endforeach

                                </table>
                            </div>
                        </div>
                    </div>
                </div>=
            </div>
        </div> --}}
        {{-- <div id="account" class="tab-pane" role="tabpanel" aria-labelledby="account-tab">
            <div class="grid grid-cols-12 gap-6">
                <div class="intro-y box col-span-12">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des paiements
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_paiements" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">#</th>
                                            <th class="text-center whitespace-nowrap">Date</th>
                                            <th class="text-center whitespace-nowrap">Commandes</th>
                                            <th class="text-center whitespace-nowrap">Status</th>
                                            <th class="text-center whitespace-nowrap">Methode</th>
                                            <th class="text-center whitespace-nowrap">Id Transaction</th>
                                            <th class="text-center whitespace-nowrap">Id operateuur</th>
                                            <th class="text-center whitespace-nowrap">Montant</th>
                                        </tr>
                                    </thead>
                                    @foreach ($users->payements()->latest()->get()
    as $key => $paiement)
                                        <tbody>
                                            <tr class="intro-x">
                                                <td>{{ $key + 1 }}</td>
                                                <td class="w-25">
                                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                                        {{ $paiement->payment_date }}</div>
                                                </td>
                                                <td class="w-25">
                                                    @if ($paiement->order != null)
                                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                                            <p class="font-medium whitespace-nowrap">
                                                                {{ $paiement->order->uniq_id }}</p>
                                                        </div>
                                                    @else
                                                        Invitée ({{ $paiement->guest_id }})
                                                    @endif
                                                </td>
                                                <td class="text-center border-b dark:border-darkmode-400 w-32">
                                                    @if ($paiement->status == 'ACCEPTED')
                                                        <div class="report-box-2__indicator bg-success"
                                                            style="color: white;">
                                                            {{ $paiement->status }}
                                                        </div>
                                                    @else
                                                        <div class="report-box-2__indicator bg-success"
                                                            style="color: white;">
                                                            {{ $paiement->status }}
                                                    @endif
                                                </td>
                                                <td class="w-25">
                                                    {{ $paiement->payment_method }}
                                                </td>
                                                <td class="w-25">
                                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $paiement->operator_id }}</p>
                                                    </div>
                                                </td>
                                                <td class="w-25">
                                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $paiement->transaction_id }}</p>
                                                    </div>
                                                </td>
                                                <td class="w-25">
                                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5 mb-2">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $paiement->amount }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script>
        function print_invoice(url) {
            var h = $(window).height();
            var w = $(window).width();
            window.open(url, '_blank', 'height=' + h + ',width=' + w + ',scrollbars=yes,status=no');
        }
        $(document).ready(function() {
            $('#table_plus').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',


                ]
            });
        });

        $(document).ready(function() {
            $('#table_orders').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',


                ]
            });
        });

        $(document).ready(function() {
            $('#table_paiements').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',


                ]
            });
        });
    </script>
@endpush
