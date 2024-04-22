@extends('layouts.master_web')

@section('title', 'Rechargement carte')

@section('content')
    <section class="page-title animate__animated animate__slideInDown"
        style="background-image:url(https://robus.home.ci/public/dist/images/transaction_money.jpeg);">
        <div class="container text-center text-white">
            <h1 class="text-black" style="color: black">TRANSFERT MOBILE MONEY ET PAIEMENT DE CREDIT</h1>
        </div>

    </section>

    @include('layouts.shared_web.open_days')

    @if ($appStatusResult['success'])
        <section class="contact-info-section style_percent">

            <div class="container">
                <div class="row ">
                    <div class="col-md-8 col-sm-10 col-xs-12">
                        <!-- Contact Form -->
                        <div class="contact-form">

                            <form method="POST" action="{{ route('web.transactions_deposits_store.stores') }}"
                                method="post" enctype="multipart/form-data"
                                style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                class="p-4 p-md-5 border  rounded-3 bg-light">

                                @csrf

                                <div class="row justify-content-center ">
                                    @foreach (getReloadPaiements() as $carteReloads)
                                        <div class="col-lg-2 col-md-12 mb-4 mb-lg-0 mb-3">
                                            <div class="bg-image hover-overlay image_mobile ripple shadow-1-strong rounded"
                                                data-ripple-color="light">
                                                <img src="{{ $carteReloads->image ?? null }}" class="w-70" />
                                                <a href="#!" data-mdb-modal-init data-mdb-target="#exampleModal1">
                                                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.2);">
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="row g-2 mt-3">

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="mb-2 font-14 bold black" for="name_expeditor">Nom et prénom expéditeur
                                            <span class="text-danger"> *</span></label>

                                        <input type="text" name="name_expeditor" maxlength="80" class="form-control"
                                            placeholder="Ex: Loren Marck ..." required="">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="mb-2 font-14 bold black" for="phone_benef">N° Tel du bénéficiaire
                                            <span class="text-danger"> *</span></label>
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
                                        <label class="mb-2 font-14 bold black" for="provider">Réseau <span
                                                class="text-danger">
                                                *</span></label>
                                        <select required id="provider" name="provider" aria-label="Selectionnez la carte "
                                            class="form-select @error('provider') is-invalid @enderror">
                                            <option disabled selected>Selectionnez le reseau </option>
                                            @foreach ($providers as $provider)
                                                <option value="{{ $provider->id }}">
                                                    {{ $provider->name ?? null }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <label class="mb-2 font-14 bold black" for="operators">Quelle opération
                                            souhaitez-vous
                                            effectuer ?<span class="text-danger">
                                                *</span></label>
                                        <select required name="operators" aria-label="Selectionnez operation "
                                            class="form-select @error('operators') is-invalid @enderror">
                                            <option disabled selected>Sélectionnez le type d'operation</option>
                                            <option value="1">Transfert mobile money</option>
                                            <option value="2">Rechargement téléphonique</option>
                                        </select>
                                    </div>


                                    <div class=" col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <label class="mb-2 font-14 bold black">Montant (min : 500Fcfa / max : 500.000Fcfa )
                                            <span class="text-danger"> *</span></label>
                                        <input type="number" class="form-control" id="montant_deposit" maxlength="10"
                                            name="amount" placeholder="Ex: 2400" required="">

                                        <div class="mt-3">
                                            <span class="badge bg-info text-dark style_span text-white mt-3 fixe_percent"
                                                id="montantInfoDeposit">Montant (min : 500Fcfa / max :
                                                500.000Fcfa)</span>
                                        </div>
                                    </div>


                                    @include('layouts.shared_web.frais_transfert')


                                    @include('layouts.shared_web.frais_credit')


                                    <div class=" col-md-12 col-sm-12 col-xs-12 mt-3"><button type="submit"
                                            class="theme-btn btn-style-three" id="submitButtonDeposit"
                                            disabled>Valider</button></div>

                                </div>
                            </form>

                        </div>
                        <!--End Contact Form -->
                    </div>
                    @include('layouts.shared_web.info-open-transfert')

                </div>

            </div>
        </section>
    @else
        <section class="contact-info-section style_percent">


            <div class="container">
                <div class="row ">
                    <div class="col-md-3 col-sm-10 col-xs-12">

                    </div>

                    <div class="col-md-6 col-sm-10 col-xs-12">
                        @include('layouts.shared_web.close_app')

                    </div>

                    <div class="col-md-3 col-sm-10 col-xs-12">

                    </div>

                </div>

            </div>
        </section>

    @endif

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
