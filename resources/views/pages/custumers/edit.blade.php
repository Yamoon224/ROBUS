@extends('layouts.master')

@section('title', 'Editer la marque')

@section('content')

<div class="grid grid-cols-12 gap-6 mt-20">

    <div class="intro-y col-span-12 lg:col-span-2">
        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">

        </div>
        <!-- END: Form Validation -->
    </div>

    <div class="intro-y col-span-12 lg:col-span-8">
        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                <h2 class="font-medium text-base mr-auto">
                    <a href="{{ URL::previous() }}" class="btn btn-sm">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                </h2>
                <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                    <label class="form-check-label ml-0" for="show-example-5">  Modifier la marque</label>
                </div>
            </div>
            <div  class="p-5">

                <form class="validate-form" method="POST" action="{{route('brands.update', ['id'=> $brands->id ])}}" enctype="multipart/form-data">
                    @csrf
                    <div class="crud-form-1">
                        <div class="mx-auto xl:mr-0 xl:ml-6">
                            <div class="border-2 border-dashed shadow-sm border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
                                <div class="h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                    <img class="rounded-md" alt="Rubick Tailwind HTML Admin Template"src="{{ asset( $brands->logo ?? 'dist/images/food-beverage-1.jpg') }}">
                                    <div title="Remove this profile photo?" class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2"> <i data-feather="x" class="w-4 h-4"></i> </div>
                                </div>
                                <div class="mx-auto cursor-pointer relative mt-5">
                                    <button type="button" class="btn btn-primary w-full">Logo de la marque</button>
                                    <input type="file"  id="logo" name="logo" class="w-full h-full top-0 left-0 absolute opacity-0">
                                    @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="crud-form-1 mt-3">
                        <label class="control-label">Nom de la marque<span class="text-danger">*</span></label>
                        <input  type="text" name="name"
                         class="form-control @error('name') is-invalid @enderror"
                         value="{{ $brands->name?? 'Application' }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                  </div>
                  <div class="crud-form-1 mt-3">
                    <label for="description" class="form-label"> Description </label>
                    <textarea type="text" class="form-control @error('description') is-invalid @enderror"
                        id="description" name="description" placeholder="" rows="3"
                        cols="5" value="{{ $brands->description }}">{{ old('description') ?? $brands->description }}</textarea>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                  <div class="crud-form-1 mt-3">
                    @if ($brands->status)

                    <div class="form-switch mt-2">
                        Desactivé
                        <label  class="form-label" for="switch">Statut</label>
                        <input type="checkbox" class="form-check-input"  id="switch" name="status" @if ($brands->status) checked @endif>
                        <label class="input-switch-label" for="switch"></label>
                    </div>
                    @else
                    <div class="form-switch mt-2">
                        Activé
                        <label  class="form-label" for="switch">Statut</label>
                        <input type="checkbox" class="form-check-input" id="switch" name="status" @if ($brands->status) checked @endif>
                        <label class="input-switch-label" for="switch"></label>
                    </div>
                    @endif

            </div>
                    <button type="submit" class="btn btn-primary mt-5">Mettre a jour</button>
                </form>
                <!-- END: Validation Form -->
            </div>
        </div>
        <!-- END: Form Validation -->
    </div>
</div>


@endsection

@push('scripts')

@endpush
