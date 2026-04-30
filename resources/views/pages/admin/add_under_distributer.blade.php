    @extends('layouts.registers')

    @section('title', 'Creer un sous distributeur')

    @section('content')

        <div class="grid grid-cols-12 gap-6 mt-3">
            <div class="intro-y col-span-12 lg:col-span-2">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">

                </div>
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

                    </div>
                    <div
                        class="mt-6 relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                        <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                            <a href="#step-1"
                                class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                            <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Information
                                Sous-distributeur
                            </div>
                        </div>
                        <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                            <a href="#step-2"
                                class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 2 ? 'btn-default' : 'btn-primary' }}">2</a>
                            <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600 dark:text-slate-400">
                                Validation et Inscription</div>
                        </div>

                    </div>
                    <div class="p-5">

                        <div class=" {{ $currentStep != 1 ? 'displayNone' : '' }}" id="step-1">
                            <div class="w-full max-w-sm  dark:bg-gray-800  mb-1">

                                <div class="flex flex-col items-center pb-10">
                                    <div class="flex justify-center items-center">
                                        <img class="w-20 h-20 mb-3"
                                            src="{{ asset_url('dist/images/logo_robus.png') }}" alt="Robus" />
                                    </div>

                                    <div>

                                        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">
                                            Inscription sous-distributeur
                                        </h3>

                                        <!-- Messages -->
                                        <div class="space-y-3 mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-start gap-2">
                                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                <span>Vous êtes actuellement sur le service d'inscription des
                                                    sous‑distributeurs
                                                    <strong class="font-semibold">Robus</strong>.</span>
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 flex items-start gap-2">
                                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <span>Merci de saisir votre nom et votre adresse e‑mail pour suivre
                                                    l'inscription.</span>
                                            </p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <form id="send_end" class="form-vertical row mt-6"
                                action="{{ route('admin.store_under_distributer') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="grid grid-cols-12 gap-6 mt-3 items-center mt-3">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Nom & Prénom(Agent) </label>
                                        <input type="text" class="form-control @error('zone') is-invalid @enderror"
                                            name="name_ground" id="name_ground" required>
                                        @error('name_ground')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Email(Agent) pour la validation </label>
                                        <input type="email" name="email_agent"
                                            class="form-control @error('email_agent') is-invalid @enderror"
                                            value="{{ old('email_agent') }}" required>
                                        @error('email_agent')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>


                                <div class="text-center">
                                    <button type="submit" id="submitButton" class="btn btn-primary w-24 mb-2 mt-6">
                                        <span id="buttonText">Suivant</span>
                                        <span id="buttonLoader" class="hidden">
                                            <!-- Utilisez une classe d'icône FontAwesome pour le cercle de chargement -->
                                            <i class="fas fa-circle-notch fa-spin"></i>
                                        </span>


                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class=" {{ $currentStep != 2 ? 'displayNone' : '' }}" id="step-2">
                            <form id="send_one" class="form-vertical row mt-6"
                                action="{{ route('admin.store_under_distributer_two') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="mx-auto xl:mr-0 xl:ml-6">
                                    <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                        src="{{ old('image') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                        alt="image" id="profileImage">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUpload" type="file" name="image" placeholder="Photo"
                                            style="display: none" accept="image/*">
                                        <h6 class="text-center">Image de profile</h6>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="grid grid-cols-12 gap-6 mt-3 items-center">

                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mx-auto xl:mr-0 xl:ml-6">
                                            <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                                src="{{ old('image_recto') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                                alt="image" id="imageCaptureId">
                                            <div class="mx-auto cursor-pointer relative mt-5">
                                                <input id="imageUploadId" type="file" name="image_recto"
                                                    placeholder="Photo" capture style="display: none" accept="image/*"
                                                    required>
                                                <h6 class="text-center ">Carte recto</h6>
                                                @error('image_recto')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 lg:col-span-6">
                                        <div class="mx-auto xl:mr-0 xl:ml-6">
                                            <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                                src="{{ old('image_verso') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                                alt="image" id="imageCaptureForm">
                                            <div class="mx-auto cursor-pointer relative mt-5">
                                                <input id="imageUploadForm" type="file" name="image_verso"
                                                    placeholder="Photo" capture style="display: none" accept="image/*"
                                                    required>
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
                                <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Nom<span class="text-danger"> *</span></label>
                                        <input type="text" name="lastname"
                                            class="form-control @error('lastname') is-invalid @enderror"
                                            value="{{ old('lastname') }}" required>
                                        @error('lastname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Prénom<span class="text-danger"> *</span></label>
                                        <input type="text" name="firstname"
                                            class="form-control @error('firstname') is-invalid @enderror"
                                            value="{{ old('firstname') }}" required>
                                        @error('firstname')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label" for="phone">Téléphone <span class="text-danger">
                                                *</span></label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" placeholder="" required maxlength="15" />
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Adresse email
                                            <span class="text-danger"> *</span>
                                        </label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label" for="country">Pays <span class="text-danger"> *
                                            </span></label>
                                        <select required id="country_id" name="country_id"
                                            class="select2 form-select @error('country_id') is-invalid @enderror">
                                            <option disabled selected>-</option>
                                            @foreach ($countries as $countrie)
                                                <option value="{{ $countrie->id }}"
                                                    @if ($countrie->id === $countrie->country_id) selected @endif>
                                                    {{ $countrie->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Ville<span class="text-danger">
                                                *</span> </label>
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

                                <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Zone<span class="text-danger">
                                                *</span> </label>
                                        <input type="text" name="zone"
                                            class="form-control @error('zone') is-invalid @enderror"
                                            value="{{ old('zone') }}">
                                        @error('zone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-span-12 lg:col-span-6">
                                        <label class="form-label">Code de validation<span class="text-danger">
                                                *</span> </label>
                                        <input type="number" name="validated_code"
                                            class="form-control @error('validated_code') is-invalid @enderror"
                                            value="{{ old('validated_code') }}">
                                        @error('validated_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="text-center">
                                    <button type="submit" id="submitButtonOne" class="btn btn-primary w-24 mb-2 mt-6">
                                        <span id="buttonTextOne">Enregistrer</span>
                                        <span id="buttonLoaderOne" class="hidden">
                                            <i class="fas fa-circle-notch fa-spin"></i>
                                        </span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>



                </div>
                <!-- END: Form Validation -->
            </div>

        </div>


    @endsection

    @push('scripts')
        <script>
            // Attendez que le document soit chargé
            document.addEventListener('DOMContentLoaded', function() {
                // Écoutez l'événement de soumission du formulaire
                document.querySelector('#send_end').addEventListener('submit', function() {
                    // Désactivez le bouton après le premier clic
                    document.querySelector('#submitButton').disabled = true;
                    // Affichez le cercle de chargement
                    document.querySelector('#buttonText').classList.add('hidden');
                    document.querySelector('#buttonLoader').classList.remove('hidden');
                    // Rechargez la page après 2 secondes (ajustez cela selon vos besoins)
                    // setTimeout(function() {
                    //     location.reload();
                    //     // window.location.href = 'https://api2.robus.africa/under_distibuters/registers'; // Remplacez par votre URL
                    // }, 2000);
                });
            });
        </script>
        <script>
            // Attendez que le document soit chargé
            document.addEventListener('DOMContentLoaded', function() {
                // Écoutez l'événement de soumission du formulaire
                document.querySelector('#send_one').addEventListener('submit', function() {
                    // Désactivez le bouton après le premier clic
                    document.querySelector('#submitButtonOne').disabled = true;
                    // Affichez le cercle de chargement
                    document.querySelector('#buttonTextOne').classList.add('hidden');
                    document.querySelector('#buttonLoaderOne').classList.remove('hidden');
                    // Rechargez la page après 2 secondes (ajustez cela selon vos besoins)
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#country_id').on('change', function() {
                    var country_id = $('#country_id').val();
                    city_id = false;
                    cityUpdate(country_id);
                });

                // Requête Ajax pour les villes
                function cityUpdate(countryId) {
                    $.get('{{ url('countrie_distributers/cities') }}/' + countryId + "'", function(data) {
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
