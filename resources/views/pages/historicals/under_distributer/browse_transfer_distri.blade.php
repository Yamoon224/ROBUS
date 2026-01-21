@extends('layouts.master')

@section('title', 'Liste des rechargement')

@section('content')

    <div class="intro-y col-span-12 lg:col-span-12">

        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="intro-y col-span-12 lg:col-span-12">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5"> Les Activites des transferts des sous distributeurs</h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                        <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                            href="{{ route('distri_commissions.transfers.historical.reporting_transfer_historical', ['key' => $countrie->name]) }}"
                            style="color: white;">
                            <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport
                            D'aujourdhui
                        </a>
                    </div>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">

                        <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                            href="{{ route('distri_commissions.transfer_alls.historical.reporting_transfer_historical', ['key' => $countrie->name]) }}"
                            style="color: white;">
                            <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger tout le
                            rapports
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: Item List -->

        @include('components.infoStatus')

        <div class="grid grid-cols-12 gap-6 mt-5">



            <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
                <div class="grid grid-cols-12 gap-6  mt-6">
                    <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                        <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Liste des transactions
                            </h2>
                        </div>
                        <div class="tiny-slider py-5">
                            <div class="px-5">
                                <div class="overflow-x-auto">
                                    <table class="table table-bordered table-hover -mt-2 -intro-x ">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="whitespace-nowrap">Date</th>
                                                <th class="text-center whitespace-nowrap">Montant</th>
                                                <th class="text-center whitespace-nowrap">Nom et prenom </th>
                                                <th class="text-center whitespace-nowrap">Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($wallet_payments->count() > 0)
                                                @foreach ($wallet_payments as $item)
                                                    <tr class="intro-x">
                                                        @include('components.infoTransactionUnderAdmin')

                                                        <td class="w-40">
                                                            @if ($item->walletBank->status == 'PENDING')
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        En-attente
                                                                    </span>

                                                                </div>
                                                            @elseif ($item->walletBank->status == 'ERROR')
                                                                <div class="flex items-center justify-center text-danger">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                            Refusé
                                                                        </span>

                                                                    </div>

                                                                </div>
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Traiter
                                                                    </span>

                                                                </div>
                                                            @elseif($item->walletBank->status == 'REFUND')
                                                                <div class="flex items-center justify-center text-primary">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert alert-primary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                            Remboursé
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Traiter
                                                                    </span>

                                                                </div>
                                                            @else
                                                                <div class="flex items-center justify-center text-success">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Accepté
                                                                        </span>


                                                                    </div>

                                                                </div>
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Traiter
                                                                    </span>

                                                                </div>
                                                            @endif
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
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: Pagination -->
                    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6 mb-6">
                        <nav class="w-full sm:w-auto sm:mr-auto">
                            <ul class="pagination">
                                {{ $wallet_payments->links() }}
                            </ul>
                        </nav>
                    </div>
                    <!-- END: Pagination -->

                </div>


            </div>
        </div>
    </div>
@endsection
