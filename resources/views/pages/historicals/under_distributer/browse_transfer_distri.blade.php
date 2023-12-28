@extends('layouts.master')

@section('title', 'Liste des rechargement')

@section('content')

    <div class="intro-y col-span-12 lg:col-span-12">

        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="intro-y col-span-12 lg:col-span-12">
                <div class="intro-y block sm:flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5"> Les Activites des transferts des  sous distributeurs</h2>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
    
                        <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                            href="{{ route('distri_commissions.transfers.historical.reporting_transfer_historical', ['key' => $countrie->name]) }}" style="color: white;">
                            <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger le rapport D'aujourdhui
                        </a>
                    </div>
                    <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
    
                        <a class="ml-3  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary mr-1 mb-2"
                            href="{{ route('distri_commissions.transfer_alls.historical.reporting_transfer_historical', ['key' => $countrie->name]) }}" style="color: white;">
                            <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Telecharger tout le rapports
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: Item List -->
        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6 box p-5 cursor-pointer zoom-in style_table_factures">
                <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-trending-down font-medium-5">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                </div>
                <div class="font-medium text-historique mt-6">Montant total</div>
                <div class="text-slate-500 mt-3">
                    {{ $valeur_payments ?? null }} {{ $countrie->currencie->code  ?? null }}
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-alert-circle font-medium-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="font-medium text-historique mt-6">En attente</div>
                <div class="text-slate-500 mt-3">
                    {{ $valeur_pending ?? null }}
                </div>
            </div>
            <div
                class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-check font-medium-5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div class="font-medium text-historique text-white mt-6">Success</div>
                <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                    {{ $valeur_success ?? null }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-x-circle font-medium-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Echec</div>
                <div class="text-slate-500 mt-3">
                    {{ $valeur_error ?? null }}
                </div>
            </div>
           
        </div>
    
    
        <div class="grid grid-cols-12 gap-6 mt-5">
    
    
            <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">
                {{-- <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Date de debut</label>
    
                    <input type="date" class="form-control  w-56 box pr-10 mt -1" value="{{ $startDate }}"
                        wire:model="startDate" placeholder="Rechercher par date les commandes" id="start_date"
                        aria-label="Date">
    
                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Date de fin</label>
                    <input type="date" class="form-control  w-56 box pr-10 mt-1" value="{{ $endDate }}"
                        wire:model="endDate" placeholder="Rechercher par date" id="end_date" aria-label="Date">
    
                </div>
    
            </div> --}}
    
    
            </div>
            
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
                                                        <td class="w-40">
                                                            <p href="" class="font-medium whitespace-nowrap">
                                                                {{ $item->created_at ?? null }}</p>
                                                        </td>
                                                        <td class="w-56">
                                                            <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                                <div class="text-slate-300 text-xs  mt-0.5"> {{ $item->amount_distributer ?? null }}  {{ $item->sidUser->wallet->currencie->code ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="text-slate-500  mt-0.5">
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ $item->description_distributer ?? null }}
                                                                </span>
                                                            </div>
                        
                                                        </td>
                                                        <td class="w-40">
                                                            <div class="mt-0.5 mb-2">
                                                                <div class="mt-0.5">
                                                                    <div
                                                                    class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                    <div class="fc-event-title-container">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Sous-distributeur</div>
                                                                    </div>
                                                                </div>
                                                                    {{ $item->sidUser->firstname ?? null }}
                                                                    {{ $item->sidUser->lastname ?? null }}
                                                                </div>
                                                            </div>
                                                            <div class="mt-0.5">
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ $item->sidUser->country->name ?? null }} ville :
                                                                    {{ $item->sidUser->city->name ?? null }}
                                                                </span>
                                                            </div>
                                                        </td>
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
