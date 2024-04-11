@extends('layouts.master_web')

@section('title', 'Rechargement carte')

@section('content')
    <section class="page-title"
        style="background-image:url(https://robus.home.ci/public/dist/images/rechargement_carte.jpeg);">
        <div class="container text-center text-white">
            <h1>Réchargement de carte VISA/MASTER CARTE</h1>
        </div>

    </section>


    <section class="contact-info-section" style="margin-top:-8%">

        <div class="container">
            <div class="row ">
                <div class="col-md-8 col-sm-10 col-xs-12">
                    <!-- Contact Form -->
                    <div class="contact-form">

                        <form method="POST" action="{{ route('web.transactions_reloads.stores') }}" method="post"
                            enctype="multipart/form-data" style="margin-bottom:5%" class="p-4 p-md-5 border rounded-3 bg-light animate__animated animate__backInRight">

                            @csrf

                            <div class="row g-2">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="num_carte">Identifiant ou Numero de la carte <span
                                            class="text-danger"> *</span></label>
                                  
                                    <input type="text" name="num_carte" class="form-control border-warning"
                                        placeholder="Ex: 12345566776 ..." required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black"  for="all_name">Nom et prenom sur la carte <span
                                            class="text-danger"> *</span></label>
                                    <input type="text" name="all_name" class="form-control border-warning"
                                        placeholder="Ex: KONAN Franck ..." required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="my_phone">Numero de téléphone <span class="text-danger">
                                            *</span></label>
                                    <input type="text" name="my_phone" class="form-control border-warning"
                                        placeholder="Ex: 0101010101" required="">
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="provider">Carte a recharger <span class="text-danger">
                                            *</span></label>

                                    <select required id="provider" name="provider" aria-label="Selectionnez la carte "
                                        class="form-select border-warning @error('provider') is-invalid @enderror">
                                        <option disabled selected>Selectionnez la carte</option>
                                        @foreach ($carte_reloads as $card)
                                            <option value="{{ $card->id }}">
                                                {{ $card->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class=" col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black">Montant(min : 2000Fcfa / max : 500.000Fcfa )
                                        <span class="text-danger"> *</span></label>
                                    <input type="number" class="form-control border-warning" name="amount"
                                        placeholder="Ex: 2400" required="">
                                </div>

                                <div class="card bg-secondary mx-auto style_info mr-10 ml-10"
                                    style="width: 30%; margin-top: 25px;">
                                    <div class="card-header text-white" id="total-percent"
                                        data-total-percent="{{ $total_percent }}">
                                        Les Frais {{ $total_percent ?? null }} %
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-white" id="result"></h5>

                                    </div>
                                </div>

                                <div class=" col-md-12 col-sm-12 col-xs-12 mt-3"><button type="submit"
                                        class="theme-btn btn-style-three">Valider</button></div>


                            </div>
                        </form>

                    </div>
                    <!--End Contact Form -->
                </div>
                @include('layouts.shared_web.info-open')

            </div>

        </div>
    </section>


@endsection
