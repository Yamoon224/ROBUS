@extends('layouts.master')
<script src="https://cdn.ckeditor.com/4.19.0/standard-all/ckeditor.js"></script>

@section('title', 'Mettre a jour')

@section('content')

    <div class="grid grid-cols-12 gap-6 mt-20">

        <div class="intro-y col-span-12 lg:col-span-12">
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
                        <label class="form-check-label ml-0" for="show-example-5"> Modifier a propos</label>
                    </div>
                </div>
                <div class="p-5">
                    <form class="form-vertical" action="{{ route('pages.update', ['id' => $page->id]) }}" method="post">
                        @csrf
                        <div class="crud-form-1 mt-3">
                            <label class="control-label">Titre</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                required value="{{ $page->title }}">
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-3">

                            <label for="content" class="form-label"> Contenu </label>
                            <textarea type="text" id="body" class="form-control @error('content') is-invalid @enderror" name="content">{{ old('content') ?? $page->content }} </textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="crud-form-1 mt-3">

                            <label for="meta_description" class="form-label"> Description </label>

                            <textarea name="meta_description" id="content" class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description') ?? $page->meta_description }}</textarea>
                            @error('meta_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div style="text-align: center;">

                            <button type="submit" class="btn btn-primary mt-5">Mettre a jour</button>
                        </div>
                    </form>
                    <!-- END: Validation Form -->
                </div>
            </div>
            <!-- END: Form Validation -->
        </div>
    </div>


@endsection

@push('scripts')
    <script type="text/javascript">
        CKEDITOR.replace('body', {
            filebrowserUploadUrl: "{{ route('about.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            extraPlugins: 'embed,autoembed,image2',
            height: 500,

            // Load the default contents.css file plus customizations for this sample.
            contentsCss: [
                'http://cdn.ckeditor.com/4.19.0/full-all/contents.css',
                'https://ckeditor.com/docs/ckeditor4/4.19.0/examples/assets/css/widgetstyles.css'
            ],
            // Setup content provider. See https://ckeditor.com/docs/ckeditor4/latest/features/media_embed
            embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',

            // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
            // resizer (because image size is controlled by widget styles or the image takes maximum
            // 100% of the editor width).

        });

        CKEDITOR.replace('meta_description', {
            filebrowserUploadUrl: "{{ route('about.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form',
            extraPlugins: 'embed,autoembed,image2',
            height: 500,

            // Load the default contents.css file plus customizations for this sample.
            contentsCss: [
                'http://cdn.ckeditor.com/4.19.0/full-all/contents.css',
                'https://ckeditor.com/docs/ckeditor4/4.19.0/examples/assets/css/widgetstyles.css'
            ],
            // Setup content provider. See https://ckeditor.com/docs/ckeditor4/latest/features/media_embed
            embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',

            // Configure the Enhanced Image plugin to use classes instead of styles and to disable the
            // resizer (because image size is controlled by widget styles or the image takes maximum
            // 100% of the editor width).

        });
    </script>
@endpush
