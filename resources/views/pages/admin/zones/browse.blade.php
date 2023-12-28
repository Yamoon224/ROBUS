@extends('layouts.master')

@section('title', 'Liste des zones')

@section('content')
    @if (count($zones) == 0)
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
                    <img src="{{ asset('public/dist/images/empty.svg') }}" alt="empty" />
                    <h4 class="mt-1">Pas de zones</h4>
                    <p>
                        Vous n'avez pas encore de zone.
                        <br>
                        Veuillez créer une zone
                    </p>
                    @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-zone'))
                        <a class="btn btn-primary mt-3" href="{{ route('zones.add') }}">Créer une zone</a>
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
        <h2 class="intro-y text-lg font-medium mt-10">
            Liste des zones
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                @if (Auth::user()->hasPermissionTo('ajouter-zone'))
                    <a class="btn btn-primary shadow-md mr-2" href="{{ route('zones.add') }}">Ajouter</a>
                @endif
                <div class="dropdown">
                    @if (Auth::user()->hasPermissionTo('ajouter-zone'))
                        <a class="dropdown-toggle btn px-2 box" href="{{ route('zones.add') }}" aria-expanded="false"
                            data-tw-toggle="dropdown">
                            <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4"
                                    data-feather="plus"></i> </span>
                        </a>
                    @endif
                </div>
                <div class="hidden md:block mx-auto text-slate-500"></div>

                <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                    <div class="w-56 relative text-slate-500">
                        <form id="sort_products" action="" method="GET">
                            @csrf
                            <input type="text" class="form-control w-56 box pr-10" id="search" name="search"
                                @isset($sort_search) value="{{ $sort_search }}" @endisset
                                placeholder="Rechercher par nom">
                            <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>

                        </form>
                    </div>
                </div>
            </div>
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-bordered table-hover -mt-2">
                    <thead class="table-dark">
                        <tr>
                            <th class="whitespace-nowrap">Nom</th>
                             <th class="whitespace-nowrap">Nombre clients</th>
                            <th class="text-center whitespace-nowrap">Description</th>
                            <th class="text-center whitespace-nowrap">Status</th>
                            <th class="text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    @foreach ($zones as $item)
                        @php
                            $custumers = DB::table('users')
                                ->where('zone_id', $item->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

                          
                        @endphp
                        <tbody>
                            <tr class="intro-x">
                                <td>
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                        {{ $item->name ?? 'inconnu' }}</div>
                                 
                                </td>
                                 <td class="text-center border-b dark:border-darkmode-400 w-32">
                                    <div class="progress-bar  w-3/4 mb-1" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100">
                                        {{ $custumers->count() ?? '0' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">
                                        {{ $item->description ?? 'inconnu' }}</div>
                                </td>
                                <td class="w-40">
                                    @if ($item->status == '1' ?? 'inconnu')
                                        @if (Auth::user()->hasPermissionTo('active-zone'))
                                            <div class="flex items-center justify-center text-success">

                                                <a class="flex items-center mr-3"
                                                    href="{{ route('zones.activate', ['id' => $item->id]) }}"
                                                    data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                    onclick="promptAlert(this)">
                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        @if (Auth::user()->hasPermissionTo('desactive-zone'))
                                            <div class="flex items-center justify-center text-danger">

                                                <a class="flex items-center mr-3"
                                                    href="{{ route('zones.activate', ['id' => $item->id]) }}"
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
                                        @if (Auth::guard('web')->user()->hasPermissionTo('voir-zones'))
                                            <a class="flex items-center mr-3"
                                                href="{{ route('zones.show_detail', ['id' => $item->id]) }}"><i
                                                    data-feather="eye" class="h-15 mr-1"></i> </a>
                                        @endif
                                        @if (Auth::user()->hasPermissionTo('modifier-zone'))
                                            <a class="flex items-center mr-3"
                                                href="{{ route('zones.show', ['id' => $item->id]) }}">
                                                <i data-feather="edit-2" class="h-15 mr-1"></i>
                                            </a>
                                        @endif
                                        @if (Auth::user()->hasPermissionTo('supprimer-zone'))
                                            <a class="flex items-center text-danger"
                                                data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                href="{{ route('zones.delete', ['id' => $item->id]) }}"
                                                onclick="promptAlert(this)" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal"> <i data-feather="trash-2"
                                                    class="h-15 mr-1"></i> </a>
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
                    {{ $zones->links('vendor.pagination.custum') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    @endif
@endsection

@push('scripts')
@endpush
