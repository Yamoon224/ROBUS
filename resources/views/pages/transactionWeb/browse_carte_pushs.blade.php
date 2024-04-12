@extends('layouts.master_web')

@section('title', 'Rechargement carte')

@section('content')
    <section class="page-title animate__animated animate__slideInDown"
        style="background-image:url(https://robus.home.ci/public/dist/images/rechargement_carte.jpeg);">
        <div class="container text-center text-white">
            <h1>Réchargement de carte PUSH</h1>
        </div>

    </section>


    <section class="contact-info-section" style="margin-top:-8%">

        <div class="container">
            <div class="row ">
                <div class="col-md-8 col-sm-10 col-xs-12">
                    <!-- Contact Form -->
                    <div class="contact-form">

                        <form method="POST" action="{{ route('web.transactions_push.stores') }}" method="post"
                            enctype="multipart/form-data" style="margin-bottom:5% ; background-color: #f0fcff !important;"
                            class="p-4 p-md-5 border rounded-3 bg-light">

                            @csrf

                            <div class="row g-2">
                                <div class="col-md-12 col-sm-12 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="provider">Carte a recharger : <span
                                            class="badge bg-info text-dark style_span text-white">{{ $carte_reloads->name ?? null }}</span></label>

                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="num_carte">N° Téléphone du compte push
                                        <span class="text-danger"> *</span></label>

                                    <input type="text" name="num_carte" maxlength="10" class="form-control"
                                        placeholder="Ex: 0707077777 ..." required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black" for="all_name"> Nom sur le compte push <span
                                            class="text-danger"> *</span></label>
                                    <input type="text" name="all_name" class="form-control"
                                        placeholder="Ex: KONAN Franck ..." required="">
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black" for="my_phone">N° Téléphone personnel <span
                                            class="text-danger">
                                            *</span></label>
                                    <input type="text" name="my_phone" maxlength="10" class="form-control"
                                        placeholder="Ex: 0101010101" required="">
                                </div>

                                <div class=" col-md-6 col-sm-6 col-xs-12 mt-3">
                                    <label class="mb-2 font-14 bold black">Montant(min : 2000Fcfa / max : 500.000Fcfa )
                                        <span class="text-danger"> *</span></label>
                                    <input type="number" class="form-control" maxlength="10" name="amount"
                                        placeholder="Ex: 2400" required="">
                                </div>

                                @include('layouts.shared_web.frais')


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
