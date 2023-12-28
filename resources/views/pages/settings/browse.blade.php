@extends('layouts.master')

@section('title', 'Acceuil')

@section('content')
<h2 class="intro-y text-lg font-medium mt-10">
   Liste des categories
</h2>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <a class="btn btn-primary shadow-md mr-2" href="{{ route('category.add') }}" >Ajouter</a>
        <div class="dropdown">
            <a class="dropdown-toggle btn px-2 box"  href="{{ route('category.add') }}"  aria-expanded="false" data-tw-toggle="dropdown">
                <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
            </a>
            <div class="dropdown-menu w-40">
                <ul class="dropdown-content">
                    {{-- <li>
                        <a href="" class="dropdown-item"> <i data-feather="printer" class="w-4 h-4 mr-2"></i> Print </a>
                    </li>
                    <li>
                        <a href="" class="dropdown-item"> <i data-feather="file-text" class="w-4 h-4 mr-2"></i> Export to Excel </a>
                    </li>
                    <li>
                        <a href="" class="dropdown-item"> <i data-feather="file-text" class="w-4 h-4 mr-2"></i> Export to PDF </a>
                    </li> --}}
                </ul>
            </div>
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
        <table class="table table-report -mt-2">
            <thead>
                <tr>
                    <th class="whitespace-nowrap">Nom</th>
                    <th class="text-center whitespace-nowrap">Description</th>
                    <th class="text-center whitespace-nowrap">Status</th>
                    <th class="text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
           @foreach($category as $item)
           <tbody>
            <tr class="intro-x">
                <td>
                    <a href="" class="font-medium whitespace-nowrap">{{$item->name}}</a>

                </td>
                <td class="text-center">
                    <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">{{$item->description}}</div>
                </td>
                <td class="w-40">
                    @if ($item->status == '1')
                    <div class="flex items-center justify-center text-success">

                        <a class="flex items-center mr-3" href="{{ route('category.activate', ['id'=>$item->id]) }}" data-message="Etes vous sûr de vouloir effectuer cette action ?" onclick="promptAlert(this)">
                          <i data-feather="check-square" class="w-4 h-4 mr-2"></i> Activé
                      </a>
                    </div>
                    @else
                    <div class="flex items-center justify-center text-danger">

                        <a class="flex items-center mr-3" href="{{ route('category.activate', ['id'=>$item->id]) }}" data-message="Etes vous sûr de vouloir effectuer cette action ?" onclick="promptAlert(this)">
                          <i data-feather="check-square" class="w-4 h-4 mr-2"></i>Désactivé
                        </a>

                    </div>

                    @endif
                </td>
                <td class="table-report__action w-56">
                    <div class="flex justify-center items-center">
                        <a class="flex items-center mr-3"  href="{{ route('category.show', ['id'=>$item->id]) }}"> <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                        <a class="flex items-center text-danger" data-message="Etes vous sûr de vouloir effectuer cette action ?" href="{{ route('category.delete', ['id'=>$item->id]) }}"  onclick="promptAlert(this)" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
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
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
        <nav class="w-full sm:w-auto sm:mr-auto">
            <ul class="pagination">
                 {{ $category->links(('vendor.pagination.custum')) }}
            </ul>
        </nav>
    </div>
    <!-- END: Pagination -->
@endsection

@push('scripts')

@endpush
