@extends('layouts.master')

@section('title', 'Fonctionnalité')

@section('content')
    @if (count($features) == 0)
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
                    <h4 class="mt-1">Pas de fonctionnalité</h4>
                    <p>
                        Vous n'avez pas encore de fonctionnalité.
                        <br>
                        Veuillez créer une fonctionnalité
                    </p>
                    <a class="btn btn-primary mt-3" href="{{ route('features.add') }}">Créer une fonction</a>
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
            Liste des fonctionnalités
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                @if (Auth::user()->hasPermissionTo('ajouter-fonctionnalite'))
                    <a class="btn btn-primary shadow-md mr-2" href="{{ route('features.add') }}">Ajouter</a>
                    <div class="dropdown">
                        <a class="dropdown-toggle btn px-2 box" href="{{ route('features.add') }}" aria-expanded="false"
                            data-tw-toggle="dropdown">
                            <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4"
                                    data-feather="plus"></i>
                            </span>
                        </a>
                @endif
            </div>
            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="w-full sm:w-auto mt-3 sm:mt-0 sm:ml-auto md:ml-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Search...">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-feather="search"></i>
                </div>
            </div>
        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap">Date de creation</th>
                        <th class="text-center whitespace-nowrap">Description</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                @foreach ($features as $item)
                    <tbody>
                        <tr class="intro-x">
                            <td>
                                <a href="" class="font-medium whitespace-nowrap">{{ $item->name ?? null }}</a>

                            </td>
                            <td>
                                <a href="" class="font-medium whitespace-nowrap">{{ $item->created_at }}</a>

                            </td>
                            <td class="text-center">
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $item->description }}</div>
                            </td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    @if (Auth::user()->hasPermissionTo('modifier-fonctionnalite'))
                                        <a class="flex items-center mr-3"
                                            href="{{ route('features.showUpdate', ['id' => $item->id]) }}">
                                            <i data-feather="check-square" class="h-15 mr-1"></i> Modifier
                                        </a>
                                    @endif
                                    @if (Auth::user()->hasPermissionTo('supprimer-fonctionnalite'))
                                        <a class="flex items-center text-danger"
                                            href="{{ route('features.delete', ['id' => $item->id]) }}"
                                            data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                            onclick="promptAlert(this)">
                                            <i data-feather="trash-2" class="h-15 mr-1"></i>
                                        </a>
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
                    {{ $features->links('vendor.pagination.custum') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    @endif
@endsection

@push('scripts')
@endpush
