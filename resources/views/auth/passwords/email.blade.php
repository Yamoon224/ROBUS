@extends('layouts.app')

@section('title', 'Renitialisation du mot de passe')

<style>
    body {
        background-color: #282828 !important;
    }
</style>
@section('content')
    <div class="grid grid-cols-12 gap-6 mt-20">
        <div class="intro-y col-span-12 lg:col-span-4">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

            </div>
            <!-- END: Form Validation -->
        </div>
        <div class="intro-y col-span-12 lg:col-span-4 my-2">
            <!-- BEGIN: Input -->
            <div class="intro-y box">
                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        Renitialiser
                    </h2>
                </div>
                <div id="input" class="p-5">
                    <div class="preview">
                        <form method="POST" action="{{ route('adminRecover.password') }}" aria-label="{{ __('Recover') }}">
                            @csrf

                            @if (Session::has('message'))
                                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                    {{ Session::get('message') }}</p>
                            @endif
                            <div class="mb-1">

                                <label for="email" class="form-label">Adresse email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" aria-describedby="email" tabindex="1"
                                    autofocus />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button
                                    class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Renitialiser</button>
                            </div>
                            {{-- <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                            <a href="{{ route('admin.login') }}" class="btn btn-outline-secondary py-3 px-4 w-full xl:w-32 mt-3 xl:mt-0 align-top">Se connectez</a>
                        </div> --}}
                        </form>
                    </div>
                </div>
            </div>
            <!-- END: Input -->

        </div>

        <div class="intro-y col-span-12 lg:col-span-4">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

            </div>
            <!-- END: Form Validation -->
        </div>

    </div>
 
@endsection
