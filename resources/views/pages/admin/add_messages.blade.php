@extends('layouts.master')

@section('title', 'Ajouter un message')

@section('content')
    <div class="grid grid-cols-12 gap-6 mt-20">

        <div class="intro-y col-span-12 lg:col-span-4">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

            </div>
        </div>

        <div class="intro-y col-span-12 lg:col-span-6">
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
                </div>

                <div class="p-5">
                    <form class="form-vertical row" action="{{ route('messages.admin.store') }}" method="post"
                    enctype="multipart/form-data">
                        @csrf

                        <div class="crud-form-1 mt-3">
                            <label class="form-label">Titre<span class="text-danger"> *</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-6">
                            <label class="form-label"> Type utilisateur <span class="text-danger"> *</span></label>
                            <select required id="typ_user" name="typ_user"
                                class="select2 form-select @error('typ_user') is-invalid @enderror">
                                <option disabled selected>-</option>
                                <option value="under">Sous distributeurs</option>
                                <option value="custumers">Clients</option>
                                <option value="companies">Compagnies</option>
                            </select>
                            @error('typ_user')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-6">
                            <label class="form-label" for="message">Message <span class="text-danger"> *</span></label>
                            <textarea name="message" id="" cols="75" rows="10">

                             </textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="col-span-12 lg:col-span-12 mt-6">
                            <div class="mx-auto xl:mr-0 xl:ml-6">
                                <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                    src="{{ old('image') ?? asset_url('dist/images/food-beverage-1.jpg') }}"
                                    alt="image" id="imageCaptureId">
                                <div class="mx-auto cursor-pointer relative mt-5">
                                    <input id="imageUploadId" type="file" name="image" placeholder="Image du message" capture
                                        style="display: none" accept="image/png, image/jpeg">
                                    <h6 class="text-center ">Image du message</h6>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-left mt-6">
                            <button type="submit" class="btn btn-primary w-24  mb-2 mt-6 ml-6"
                                style="margin-left: 1rem;">Envoyer le message</button>

                        </div>
                    </form>

                </div>



            </div>
            <!-- END: Form Validation -->
        </div>


        <!-- END: Form Validation -->
    </div>
@endsection
