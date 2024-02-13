@extends('layouts.master_comzone')

@section('title', 'Creer un distributeur')

@section('content')

    <div class="grid grid-cols-12 gap-6 mt-20">

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
                    <form class="form-vertical row" action="{{ route('comzones.underdistributors.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mx-auto xl:mr-0 xl:ml-6">
                            <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                src="{{ old('image') ?? asset('public/dist/images/food-beverage-1.jpg') }}" alt="image"
                                id="profileImage">
                            <div class="mx-auto cursor-pointer relative mt-5">
                                <input id="imageUpload" type="file" name="image" placeholder="Photo" capture
                                    style="display: none" accept="image/png, image/jpeg">
                                <h6 class="text-center ">Image de profile</h6>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-6 mt-3 items-center">

                            <div class="col-span-6 lg:col-span-6">
                                <div class="mx-auto xl:mr-0 xl:ml-6">
                                    <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                        src="{{ old('image_recto') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                        alt="image" id="imageCaptureId">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUploadId" type="file" name="image_recto" placeholder="Photo"
                                            capture style="display: none" accept="image/png, image/jpeg">
                                        <h6 class="text-center ">Carte recto</h6>
                                        @error('image_recto')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-6 lg:col-span-6">
                                <div class="mx-auto xl:mr-0 xl:ml-6">
                                    <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                        src="{{ old('image_verso') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                        alt="image" id="imageCaptureForm">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUploadForm" type="file" name="image_verso" placeholder="Photo"
                                            capture style="display: none" accept="image/png, image/jpeg">
                                        <h6 class="text-center ">Carte verso</h6>
                                        @error('image_verso')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="crud-form-1 mt-3">
                            <label class="form-label">Nom<span class="text-danger"> *</span></label>
                            <input type="text" name="firstname"
                                class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}"
                                required>
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-3">
                            <label class="form-label">Prénom<span class="text-danger"> *</span></label>
                            <input type="text" name="lastname"
                                class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}"
                                required>
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="crud-form-1 mt-3">
                            <label class="form-label" for="phone">Téléphone <span class="text-danger"> *</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                maxlength="10" name="phone" placeholder="" required />
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-3">
                            <label class="form-label">Adresse email
                                <span class="text-danger"> *</span>
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>


                </div>



            </div>
            <!-- END: Form Validation -->
        </div>

        <div class="intro-y col-span-12 lg:col-span-6">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">

                </div>
                <div class="p-5">
                    <div class="crud-form-1 intro-y mt-3">
                        <label class="form-label" for="country">Pays <span class="text-danger"> * </span></label>
                        <h2 class="intro-y text-lg font-medium">
                            <span
                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                {{ $countrie->name ?? null }} </span>
                        </h2>
                    </div>
                    <div class="crud-form-1 intro-y mt-3">
                        <label class="form-label" for="city">Ville <span class="text-danger"> * </span></label>
                        <select required id="city_id" name="city_id"
                            class="select2 form-select @error('city_id') is-invalid @enderror">
                            <option disabled selected>-</option>
                            @foreach ($cities as $citie)
                                <option value="{{ $citie->id }}" @if ($citie->id === $citie->country_id) selected @endif>
                                    {{ $citie->name }}</option>
                            @endforeach
                        </select>
                        @error('city_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="crud-form-1 mt-3">
                        <label class="form-label">Zone </label>
                        <input type="text" class="form-control @error('zone') is-invalid @enderror" name="zone"
                            id="zone" required>
                        @error('zone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="crud-form-1 intro-y mt-3">
                        <label class="form-label" for="country">Role <span class="text-danger"> * </span></label>
                        <h2 class="intro-y text-lg font-medium">
                            <span
                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                {{ $role->name ?? null }} </span>
                        </h2>
                    </div>
                    <div class="crud-form-1 mt-3">
                        <label class="form-label">Mot de passe <span class="text-danger"> *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" id="password" required maxlength="4">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="crud-form-1 mt-3">
                        <label class="form-label">Confirmer Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" id="password_confirmation" required maxlength="4">
                    </div>
                </div>
                <div class="text-left">
                    <button type="submit" class="btn btn-primary w-24  mb-2 mt-6 ml-6"
                        style="margin-left: 1rem;">Ajouter</button>

                </div>
                </form>
                <!-- END: Validation Form -->

            </div>
        </div>
        <!-- END: Form Validation -->
    </div>

    </div>


@endsection

