@extends('layouts.master')

@section('title', 'Roles')

@section('content')
    @if (count($roles) == 0)
        <div class="grid grid-cols-12 gap-6 mt-20">

            <div class="col-span-12 lg:col-span-3 text-center">
                <!-- BEGIN: Form Validation -->

            </div>
            <div class="col-span-12 lg:col-span-6 text-center">
                <!-- BEGIN: Form Validation -->
                <div class="text-center mt-5">
                    <img src="{{ asset('public/dist/images/empty.svg') }}" alt="empty" />
                    <h4 class="mt-1">Pas de roles</h4>
                    <p>
                        Vous n'avez pas encore de roles.
                        <br>
                        Veuillez créer une roles
                    </p>
                    <a class="btn btn-primary mt-3" href="{{ route('roles.add') }}">Créer un role</a>
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
            Liste des roles
        </h2>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                @if (Auth::user()->hasPermissionTo('ajouter-fonctionnalite'))
                    <a class="btn btn-primary shadow-md mr-2" href="{{ route('roles.add') }}">Ajouter</a>
                    <div class="dropdown">
                        <a class="dropdown-toggle btn px-2 box" href="{{ route('roles.add') }}" aria-expanded="false"
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
                @foreach ($roles as $item)
                    <tbody>
                        <tr class="intro-x">
                            <td>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $item->name }}</div>

                            </td>
                            <td>
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $item->created_at }}</div>

                            </td>
                            <td class="text-center">
                                <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{ $item->description }}</div>
                            </td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    @if (Auth::user()->hasPermissionTo('configurer-role'))
                                        <a
                                            class="flex items-center mr-3"href="{{ route('roles.permissions', ['id' => $item->id]) }}">
                                            <i data-feather="settings" class="h-15 mr-1"></i> Config </a>
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
                    {{ $roles->links('vendor.pagination.custum') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    @endif
@endsection

@push('scripts')
@endpush
