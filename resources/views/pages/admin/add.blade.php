@extends('layouts.master')

@section('title', 'Creer un administrateur')

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
                    {{-- <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                <label class="form-check-label ml-0" for="show-example-5"> Nouveau administrateur</label>
            </div> --}}
                </div>

                <div class="p-5">
                    <form class="form-vertical row" action="{{ route('admin.store') }}" method="post"
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
                                        src="{{ old('image_recto') ?? asset('public/dist/images/food-beverage-1.jpg') }}" alt="image"
                                        id="imageCaptureId">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUploadId" type="file" name="image_recto" placeholder="Photo" capture
                                            style="display: none" accept="image/png, image/jpeg">
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
                                        src="{{ old('image_verso') ?? asset('public/dist/images/food-beverage-1.jpg') }}" alt="image"
                                        id="imageCaptureForm">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUploadForm" type="file" name="image_verso" placeholder="Photo" capture
                                            style="display: none" accept="image/png, image/jpeg">
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
                            <label class="form-label">Prénom<span class="text-danger"> *</span></label>
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
                            <label class="form-label">Nom<span class="text-danger"> *</span></label>
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
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" maxlength = "10"
                                name="phone" placeholder="" required />
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

                        <div class="crud-form-1 intro-y mt-3">
                            <label class="form-label" for="country">Pays <span class="text-danger"> * </span></label>
                            <select required id="country_id" name="country_id"
                                class="select2 form-select @error('country_id') is-invalid @enderror">
                                <option disabled selected>-</option>
                                @foreach ($countries as $countrie)
                                    <option value="{{ $countrie->id }}" @if ($countrie->id === $countrie->country_id) selected @endif>
                                        {{ $countrie->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-3">
                            <label class="form-label">Ville </label>
                            <select id="city" name="city"
                                class="select2 form-select @error('city') is-invalid @enderror">

                            </select>
                            @error('city')
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
                    CI23JT5S
                    1358
                    <div class="crud-form-1 mt-3">
                        <label class="form-label">Rôle<span class="text-danger"> *</span></label>
                        <select name="roles[]" data-placeholder="Selectionne les roles"
                            class="tom-select w-full @error('roles') is-invalid @enderror">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->description ?? $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roles')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

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

                    <div class="grid grid-cols-12 gap-6 mt-6 items-center">

                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-3" id="switch_distributer">

                            <label class="form-label" for="switch">Operatrice</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" class="form-check-input" name="is_distributer">

                                @error('is_distributer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label class="input-switch-label" for="switch"></label>
                            </div>
                            <div class="alert alert-secondary show mb-2 mt-3" role="alert">Activer Operatrice
                            </div>
                        </div>

                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-3" id="switch_under_distributer">

                            <label class="form-label" for="switch">Sous-distributeur</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" class="form-check-input" name="is_under_distributer">

                                @error('is_under_distributer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label class="input-switch-label" for="switch"></label>
                            </div>
                            <div class="alert alert-secondary show mb-2 mt-3" role="alert">Activer 
                                sous-distributeur
                            </div>
                        </div>
                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-3" id="switch_supervisor">

                            <label class="form-label" for="switch">Superviseur</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" class="form-check-input" name="is_supervisor">

                                @error('is_supervisor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label class="input-switch-label" for="switch"></label>
                            </div>
                            <div class="alert alert-secondary show mb-2 mt-3" role="alert">Activer 
                               Superviseur
                            </div>
                        </div>
                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-3" id="switch_company">

                            <label class="form-label" for="switch">Societé</label>
                            <div class="form-switch mt-2">
                                <input type="checkbox" class="form-check-input" name="is_company">

                                @error('is_company')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label class="input-switch-label" for="switch"></label>
                            </div>
                            <div class="alert alert-secondary show mb-2 mt-3" role="alert">Activer  societé
                            </div>
                        </div>
                        


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

@push('scripts')
    {{-- Choise actor --}}
    <script type="text/javascript">
        $(document).ready(function() {

            $('input[name="is_distributer"]').click(function() {
                if ($(this).is(":checked")) {
                    $("#switch_under_distributer").hide();
                    $("#switch_under_admin").hide();
                    $("#switch_supervisor").hide();
                    $("#switch_company").hide();
                } else {
                    $("#switch_under_distributer").show();
                    $("#switch_distributer").show();
                    $("#switch_under_admin").show();
                    $("#switch_supervisor").show();
                    $("#switch_company").show();
                }

            });

            $('input[name="is_under_distributer"]').click(function() {
                if ($(this).is(":checked")) {
                    $("#switch_distributer").hide();
                    $("#switch_under_admin").hide();
                    $("#switch_supervisor").hide();
                    $("#switch_company").hide();

                } else {
                    $("#switch_under_distributer").show();
                    $("#switch_distributer").show();
                    $("#switch_under_admin").show();
                    $("#switch_supervisor").show();
                    $("#switch_company").show();
                }

            });

            $('input[name="is_admin"]').click(function() {
                if ($(this).is(":checked")) {
                    $("#switch_distributer").hide();
                    $("#switch_under_distributer").hide();
                    $("#switch_supervisor").hide();
                    $("#switch_company").hide();
                } else {
                    $("#switch_under_distributer").show();
                    $("#switch_distributer").show();
                    $("#switch_under_admin").show();
                    $("#switch_supervisor").show();
                    $("#switch_company").show();
                }

            });

            $('input[name="is_supervisor"]').click(function() {
                if ($(this).is(":checked")) {
                    $("#switch_distributer").hide();
                    $("#switch_under_distributer").hide();
                    $("#switch_under_admin").hide();
                    $("#switch_company").hide();
                } else {
                    $("#switch_under_distributer").show();
                    $("#switch_distributer").show();
                    $("#switch_under_admin").show();
                    $("#switch_supervisor").show();
                    $("#switch_company").show();
                }

            });

            $('input[name="is_company"]').click(function() {
                if ($(this).is(":checked")) {
                    $("#switch_distributer").hide();
                    $("#switch_under_distributer").hide();
                    $("#switch_under_admin").hide();
                    $("#switch_supervisor").hide();
                } else {
                    $("#switch_company").hide();
                    $("#switch_under_distributer").show();
                    $("#switch_distributer").show();
                    $("#switch_under_admin").show();
                    $("#switch_supervisor").show();
                }

            });

        });
    </script>
    {{-- Changes country --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#country_id').on('change', function() {
                var country_id = $('#country_id').val();
                city_id = false;
                cityUpdate(country_id);
            });

            // Requête Ajax pour les villes
            function cityUpdate(countryId) {
                $.get('{{ url('countries/cities') }}/' + countryId + "'", function(data) {
                    $('#city').empty();
                    $.each(data, function(index, cities) {
                        $('#city').append($('<option>', {
                            value: cities.id,
                            text: cities.name
                        }));
                    });
                    if (city_id) {
                        $('#city').val(city_id).prop('selected', true);
                    }
                });
            }


        });
    </script>
@endpush