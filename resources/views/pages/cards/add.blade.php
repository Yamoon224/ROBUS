@extends('layouts.master')

@section('title', 'Creer une carte')

@section('content')

    <div class="grid grid-cols-12 gap-6 mt-10">

        <div class="intro-y col-span-12 lg:col-span-2">

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

                <div class="p-5">
                    <form class="form-vertical row" action="{{ route('cards.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-12 gap-6 mt-6 items-center">
                            <div class="intro-y col-span-12 lg:col-span-6">
                                <div class="mx-auto xl:mr-0 xl:ml-6">
                                    <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                        src="{{ old('identity_doc') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                        alt="Piece" id="profileImage">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUpload" type="file" name="identity_doc" placeholder="Piece"
                                            capture style="display: none" accept="image/png, image/jpeg">
                                        <h6 class="text-center ">Piece d'identité</h6>
                                        @error('identity_doc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="intro-y col-span-12 lg:col-span-6">
                                <div class="mx-auto xl:mr-0 xl:ml-6">
                                    <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                        src="{{ old('identity_form') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                        alt="Piece" id="imageCaptureForm">
                                    <div class="mx-auto cursor-pointer relative mt-5">
                                        <input id="imageUploadForm" type="file" name="identity_form" placeholder="Piece"
                                            capture style="display: none" accept="image/png, image/jpeg">
                                        <h6 class="text-center ">Formulaire</h6>
                                        @error('identity_form')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-6 mt-3 items-center mt-6">
                            <div class="crud-form-1 intro-y col-span-12 lg:col-span-6">
                                <label class="form-label" for="phone">Téléphone <span class="text-danger">
                                        *</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" placeholder="" required />
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="crud-form-1 intro-y col-span-12 lg:col-span-6">
                                <label class="form-label">Adresse email
                                    <span class="text-danger"> *</span>
                                </label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="crud-form-1 intro-y col-span-12 lg:col-span-6">
                                <label class="form-label">Adresse
                                    <span class="text-danger"> *</span>
                                </label>
                                <input type="text" name="address"
                                    class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}"
                                    required>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="crud-form-1 intro-y col-span-12 lg:col-span-6">
                                <label class="form-label">Type de carte <span class="text-danger"> * </span></label>

                                <select required id="type_card_id" name="type_card_id"
                                    class="select2 form-select @error('type_card_id') is-invalid @enderror">
                                    <option disabled selected>-</option>
                                    @foreach ($type_reload as $type_card)
                                        <option value="{{ $type_card->id }}" data-amount="{{ formatDisplayAmount($type_card->amount) }}"
                                            @if ($type_card->id === $type_card->type_card_id) selected @endif>
                                            {{ $type_card->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('type_card_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-13">
                            <div class="alert alert-dark show text-center mt-3" role="alert" id="amount-display">
                                Prix de la carte : 0 Frcfa
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-24  mb-2 mt-6 ml-6"
                                style="margin-left: 1rem;">Envoyer la demande</button>

                        </div>
                    </form>

                </div>



            </div>
            <!-- END: Form Validation -->
        </div>
        <!-- END: Form Validation -->
    </div>

    </div>


@endsection

@push('scripts')
    {{-- Changes country --}}
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const typeCardSelect = document.getElementById('type_card_id');
            const amountDisplay = document.getElementById('amount-display');

            typeCardSelect.addEventListener('change', function() {
                const selectedOption = typeCardSelect.options[typeCardSelect.selectedIndex];
                const amount = selectedOption ? selectedOption.getAttribute('data-amount') : 0;
                amountDisplay.textContent = `Prix de la carte : ${amount} Frcfa`;
            });

            // Pour initialiser le montant lors du chargement de la page
            if (typeCardSelect.selectedIndex > 0) {
                const selectedOption = typeCardSelect.options[typeCardSelect.selectedIndex];
                const amount = selectedOption.getAttribute('data-amount');
                amountDisplay.textContent = `Prix de la carte : ${amount} Frcfa`;
            }
        });
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
