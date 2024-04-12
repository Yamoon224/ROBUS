@extends('layouts.master_web')

@section('title', 'Transfert a international')

@section('content')
    <section class="page-title animate__animated animate__slideInDown"
        style="background-image:url(https://robus.home.ci/public/dist/images/transaction_money.jpeg);">
        <div class="container text-center text-white">
            <h1 class="text-black">TRANSFERT A L'INTERNATIONAL</h1>
        </div>

    </section>


    <section class="contact-info-section" style="margin-top:-8%">

        <div class="container">
            <div class="row ">
                <div class="col-md-8 col-sm-10 col-xs-12">
                    <!-- Contact Form -->
                    <div class="contact-form">

                        <form method="POST" action="{{ route('web.transactions_inters_store.stores') }}" method="post"
                            enctype="multipart/form-data" style="margin-bottom:5% ; background-color: #f0fcff !important;"
                            class="p-4 p-md-5 border  rounded-3 bg-light">

                            @csrf

                            <div class="row g-2">

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="name_expeditor">Nom et prénom expéditeur
                                        <span class="text-danger"> *</span></label>

                                    <input type="text" name="name_expeditor" maxlength="80" class="form-control"
                                        placeholder="Ex: Loren Marck ..." required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="name_benef">Nom et prénom bénéficiaire
                                        <span class="text-danger"> *</span></label>

                                    <input type="text" name="name_benef" maxlength="80" class="form-control"
                                        placeholder="Ex: Loren Marck ..." required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="phone_benef">N° Tel du bénéficiaire <span
                                            class="text-danger"> *</span></label>
                                    <input type="text" maxlength="10" name="phone_benef" class="form-control"
                                        placeholder="Ex: 0909090909 ..." required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="my_phone">N° Téléphone expéditeur <span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="text" name="my_phone" maxlength="10" class="form-control"
                                        placeholder="Ex: 0101010101" required="">
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="country_id">Pays <span class="text-danger">
                                            *</span></label>
                                    <select required id="country_id" name="country_id" aria-label="Selectionnez la carte "
                                        class="form-select @error('country_id') is-invalid @enderror">
                                        <option disabled selected>Selectionnez le reseau </option>
                                        @foreach ($countries as $countrie)
                                            <option value="{{ $countrie->id }}">
                                                {{ $countrie->name ?? null }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="provider">Réseau <span class="text-danger">
                                            *</span></label>
                                    <select required id="provider" name="provider" aria-label="Selectionnez la carte "
                                        class="form-select @error('provider') is-invalid @enderror">
                                        <option disabled selected>Selectionnez le reseau </option>
                                        @foreach ($countries as $provider)
                                            <option value="{{ $provider->id }}">
                                                {{ $provider->name ?? null }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}


                                <div class=" col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black">Montant(min : 2000Fcfa / max : 500.000Fcfa )
                                        <span class="text-danger"> *</span></label>
                                    <input type="number" class="form-control" maxlength="10" name="amount"
                                        placeholder="Ex: 2400" required="">
                                </div>


                                @include('layouts.shared_web.frais_transfert')


                                <div class=" col-md-12 col-sm-12 col-xs-12 mt-3"><button type="submit"
                                        class="theme-btn btn-style-three">Valider</button></div>

                            </div>
                        </form>

                    </div>
                    <!--End Contact Form -->
                </div>
                @include('layouts.shared_web.info-open-transfert')

            </div>

        </div>
    </section>


@endsection

@push('scripts')
    <script type="text/javascript">
        // Sélection de l'élément <select> par son attribut name
        const selectElement = document.querySelector('select[name="operators"]');

        const elementToToggle = document.querySelector('.active_phone');
        const elementToToggle2 = document.querySelector('.active_transfert');

        selectElement.addEventListener('change', function() {
            const selectedValue = selectElement.value;
            if (selectedValue === '1') {
                // Si la valeur sélectionnée est "1", supprimer la classe d-none
                elementToToggle2.classList.remove('d-none');
                elementToToggle.classList.add('d-none');

            } else if (selectedValue === '2') {
                elementToToggle.classList.remove('d-none');
                elementToToggle2.classList.add('d-none');

            } else {
                // Sinon, ajouter la classe d-none
                elementToToggle.classList.add('d-none');
                elementToToggle2.classList.add('d-none');
            }
        });
    </script>
@endpush
