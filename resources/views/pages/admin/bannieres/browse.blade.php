@extends('layouts.master')

@section('title', 'Liste des bannieres')

@section('content')

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des bannière
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Images</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
               
                @foreach ($banners as $item)
                    <tbody>
                        <tr class="intro-x">
                            <td class="w-40">
                                <div class="flex">
                                    <div class="imw-10 imh-10 image-fit zoom-in">
                                        <img alt="image" class="max-w-sm h-auto shadow-lg"
                                            src="{{ asset($item->original_filename ?? 'dist/images/preview-8.jpg') }}"
                                            title="Logo">
                                    </div>
                                </div>
                            </td>
                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    @if (Auth::user()->hasPermissionTo('modifier-kit'))
                                        <a class="flex items-center mr-3"
                                            href="{{ route('banners.show', ['id' => $item->id]) }}">
                                            <i data-feather="edit-2" class="h-15 mr-1"></i>
                                        </a>
                                    @endif
                                    {{-- @if (Auth::user()->hasPermissionTo('supprimer-banniere'))
                                            <a class="flex items-center text-danger"
                                                data-message="Etes vous sûr de vouloir effectuer cette action ?"
                                                  href="{{ route('banners.delete', ['id' => $item->id]) }}"
                                                onclick="promptAlert(this)" data-tw-toggle="modal"
                                                data-tw-target="#delete-confirmation-modal"> <i data-feather="trash-2"
                                                    class="h-15 mr-1"></i> </a>
                                        @endif --}}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @endforeach

            </table>
        </div>
        <!-- END: Data List -->

    </div>


@endsection

@push('scripts')
@endpush
