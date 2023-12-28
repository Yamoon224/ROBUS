@extends('layouts.master')

@section('title', 'Ajouter une page')

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
                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        <a href="{{ URL::previous() }}" class="btn btn-sm">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                        </a>
                    </h2>
                    <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                        <label class="form-check-label ml-0" for="show-example-5"> A propos</label>
                    </div>
                </div>
                <div class="p-5">
                    <form class="form-vertical" action="{{ route('pages.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="crud-form-1 mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="crud-form-1 mt-3 mb-3 ">
                            <label for="content" class="form-label"> Contenu </label>
                            <div id="classic-editor">
                                <textarea tyle="text" class="editor" name="content  @error('content') is-invalid @enderror" rows="10000"
                                    cols="70">{{ old('content') }}</textarea>
                            </div>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="crud-form-1 mt-3 ">
                            <label for="meta_description" class="form-label"> Description </label>
                            <textarea type="text" tyle="text" class="editor  @error('meta_description') is-invalid @enderror" id="meta_description"
                                name="meta_description" placeholder="" rows="20"
                                cols="20">{{ old('meta_title') }}</textarea>
                            @error('meta_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

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
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script src="{{ asset('dist/js/ckeditor-classic.js') }}"></script>
    <script>
        $(".editor").each(function() {
            const el = this;
            ClassicEditor.create(el).catch((error) => {
                console.error(error);
            });
        });
    </script>
@endpush
    