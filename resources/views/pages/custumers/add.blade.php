@extends('layouts.master')

@section('title', 'Ajouter des marques')

@section('content')

<div class="grid grid-cols-12 gap-6 mt-20">

    <div class="intro-y col-span-12 lg:col-span-3">
        <!-- BEGIN: Form Validation -->
        <div class="intro-y box">

        </div>
        <!-- END: Form Validation -->
    </div>

    <div class="intro-y col-span-12 lg:col-span-6">
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
                    <label class="form-check-label ml-0" for="show-example-5">  Nouvelle marque</label>
                </div>
            </div>
            <div  class="p-5">
                <form class="validate-form" method="POST" action="{{ route('brands.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mx-auto xl:mr-0 xl:ml-6">
                                <img class="object-none object-center bg-yellow-300 w-30 h-30"  src="{{ old('logo') ?? asset('public/dist/images/food-beverage-1.jpg') }}" alt="image" id="profileImage">
                                <div class="mx-auto cursor-pointer relative mt-5">
                                    <input id="imageUpload" type="file" name="image" placeholder="Photo" capture style="display: none" accept="image/png, image/jpeg">
                                    <h6 class="text-center ">Logo de la boutique</h6>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror  
                                </div>
                        </div>

                        <div class="crud-form-1 mt-3">
                            <label class="form-label" for="name">Nom de la marque<span class="text-danger">*</span></label>
                            <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            placeholder=""
                             rows="3" cols="5">{{ old('name') }}
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                      </div>
                        <div class="crud-form-1 mt-3">
                            <label for="description" class="form-label"> Description </label>
                            <textarea
                            type="text"
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            placeholder=""
                             rows="3" cols="5">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                      </div>
                      <div class="crud-form-1 mt-3">
                        <label  class="form-label" for="switch">Statut</label>
                        <div class="form-switch mt-2">
                            <input type="checkbox" class="form-check-input" id="switch" name="status">
                            {{ old('status') }}
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                            <label class="input-switch-label" for="switch"></label>
                        </div>
                    </div>
                        <button type="submit" class="btn btn-primary mt-5">Ajouter</button>
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
