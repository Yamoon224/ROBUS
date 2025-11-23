@extends('layouts.master')

@section('title', 'Modification du SD')

@section('content')

    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details sur le SD {{ $user->firstname }}
        </h2>
        <h2 class="font-medium text-base mr-auto mt-3 mb-3">
            <a href="{{ URL::previous() }}" class="btn btn-sm">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                    stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </h2>
        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Midone - HTML Admin Template"
                        src="{{ asset($user->image ?? 'public/dist/images/profil.png') }}">
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg mb-3">
                        {{ $user->lastname ?? null }} {{ $user->firstname ?? null }}
                    </div>
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                        Code wallet : <br> {{ $user->wallet->wallet_id ?? null }}
                    </div>
                </div>

            </div>
            <div class="ml-5 mr-6">
                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">

                    <p for="silver_ceiling" class="mr-3">Le plafond
                        {{ $user->silver_ceiling ?? null }} {{ $user->country->currencie->code ?? null }}
                    </p>

                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Les details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail"
                            class="w-4 h-4 mr-2"></i> {{ $user->email ?? null }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="mic" data-lucide="mic"
                            class="lucide lucide-mic w-4 h-4 text-slate-500 ml-auto mr-2">
                            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"></path>
                            <path d="M19 10v2a7 7 0 01-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                        {{ $user->phone ?? null }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Creer : {{ $user->created_at ?? null }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Date connexion : {{ $user->last_login ?? null }}
                    </div>

                    <div class="truncate sm:whitespace-normal flex items-center mt-3">

                        @if ($user->last_logout == null)
                            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                                    class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg> Toujours connecté
                            </div>
                        @else
                            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                                    class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg> Date deconnexion : {{ $user->last_logout ?? null }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet">
                <div class="text-center rounded-md  py-3" style="color: white; font-size: 16px;">
                    <div class="font-medium text-lg mb-3">Solde Wallet</div>
                    <div class="font-medium text-primary text-xl" style="color: white;">
                        {{ number_format($user->wallet()->sum('amount'), 2, ',', ' ') ?? null }}
                        {{ $user->country->currencie->code ?? null }}
                    </div>
                </div>

            </div>

        </div>
        @if ($user->is_active == true)
            <div class="sm:w-40 truncate sm:whitespace-normal font-medium text-lg mt-3 text-success">
                <a class="flex items-center mr-3 mb-3 mt-3">
                    <i class="fas fa-unlock-alt w-4 h-4 mr-2 "></i>
                    Carte Validez
                </a>
            </div>
        @else
            <div class="sm:w-40 truncate sm:whitespace-normal font-medium text-lg mt-3">
                <form class="validate-form" method="POST"
                    action="{{ route('custumers.update_custumers', ['id' => $user->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <p>Valider le compte </p>
                    <button type="submit" class="btn btn-primary mb-2 mt-6" style="height: 2rem;">Validez</button>
                </form>
            </div>
        @endif
        <div class="p-5">
            <div class="grid grid-cols-12 gap-6 mt-3">

                <div class="intro-y col-span-12 lg:col-span-6">
                    <!-- BEGIN: Form Validation -->
                    <div class="intro-y box">

                        <div class="p-5">
                            <form class="form-vertical row"
                                action="{{ route('unders.edit_unders', ['id' => $user->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mx-auto xl:mr-0 xl:ml-6">
                                    <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                        src="{{ asset($user->image ?? 'public/dist/images/food-beverage-1.jpg') }}"
                                        alt="image" id="profileImage">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUpload" type="file" name="image" placeholder="Photo"
                                            capture style="display: none" accept="image/png, image/jpeg">
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
                                                <input id="imageUploadId" type="file" name="image_recto"
                                                    placeholder="Photo" capture style="display: none"
                                                    accept="image/png, image/jpeg">
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
                                                <input id="imageUploadForm" type="file" name="image_verso"
                                                    placeholder="Photo" capture style="display: none"
                                                    accept="image/png, image/jpeg">
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
                                        class="form-control @error('firstname') is-invalid @enderror"
                                        value="{{ $user->firstname ?? null }}">
                                    @error('firstname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                                <div class="crud-form-1 mt-3">
                                    <label class="form-label">Nom<span class="text-danger"> *</span></label>
                                    <input type="text" name="lastname"
                                        class="form-control @error('lastname') is-invalid @enderror"
                                        value="{{ $user->lastname ?? null }}">
                                    @error('lastname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="crud-form-1 intro-y mt-3">
                                    <label class="form-label" for="country">Ville <span class="text-danger"> *
                                        </span></label>
                                    <select required name="city"
                                        class="select2 form-select @error('city') is-invalid @enderror">
                                        <option disabled selected>-</option>
                                        @foreach ($cities as $citie)
                                            <option value="{{ $citie->id }}"
                                                @if ($user->city_id == $citie->id) selected @endif>
                                                {{ $citie->name }}
                                            </option>
                                        @endforeach
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
                                <label class="form-label" for="phone">Téléphone <span class="text-danger">
                                        *</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ $user->phone ?? null }}" />
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
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ $user->email ?? null }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class="crud-form-1 mt-3">
                                <label class="form-label">Zone </label>
                                <input type="text" class="form-control @error('zone') is-invalid @enderror"
                                    name="zone" id="zone" value="{{ $user->zone ?? null }}">
                                @error('zone')
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
                                <label class="form-label">Confirmer Mot de passe <span
                                        class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    name="password_confirmation" id="password_confirmation" required maxlength="4">
                            </div>

                        </div>
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary w-24  mb-2 mt-6 ml-6"
                                style="margin-left: 1rem;">Modifier</button>

                        </div>
                        </form>
                        <!-- END: Validation Form -->

                    </div>
                </div>
                <!-- END: Form Validation -->
            </div>
        </div>


    </div>
    <!-- END: Profile Info -->

@endsection

@push('scripts')
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
