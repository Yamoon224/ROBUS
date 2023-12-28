@extends('layouts.master')

@section('title', 'A propos')

@section('content')

    <div class="grid grid-cols-12 gap-6 mt-5">

    

        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y news xl:w-4/5 p-5 box  mt-8">
                <!-- BEGIN: Blog Layout -->
                @foreach ($pages as $page)
                    <h2 class="intro-y font-medium text-xl sm:text-2xl">
                        {{ $page->title ?? null }}
                    </h2>

                    <div class="intro-y text-justify leading-relaxed mt-6">
                        <p class="mb-5 mt-6">
                            {!! $page->content !!}
                        </p>
                        <p class="mb-5 mt-6">
                            {!! $page->meta_description !!}
                        </p>
                    </div>
                    <div
                        class="intro-y flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pt-5 border-t border-slate-200/60 dark:border-darkmode-400">

                        <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                            <a href="{{ route('pages.show', ['slug' => $page->slug]) }}"
                                class="btn btn-primary shadow-md mr-2"
                                title="Modifier"> Modifier </a>
                        </div>
                    </div>
                    <!-- END: Blog Layout -->
                @endforeach

            </div>
        </div>
        <!-- END: Data List -->

    </div>


@endsection

@push('scripts')
@endpush
