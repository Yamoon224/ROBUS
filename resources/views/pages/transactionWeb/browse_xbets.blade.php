@extends('layouts.master_web')

@section('title', 'Rechargement Xbet')

@section('content')
    <section class="page-title animate__animated animate__slideInDown"
        style="background-image:url(https://robus.home.ci/public/dist/images/transactions.jpeg);">
        <div class="container text-center text-white">
            <h1>Réchargement 1XBet</h1>
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

                            <form method="POST" action="{{ route('web.transactions_xbets_store.stores') }}" method="post"
                                enctype="multipart/form-data"
                                style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                class="p-4 p-md-5 border rounded-3 bg-light">

                                @csrf

                                <div class="row justify-content-center ">
                                    <div class="col-lg-2 col-md-12 mb-4 mb-lg-0 mb-3">
                                        <div class="bg-image hover-overlay image_mobile ripple shadow-1-strong rounded"
                                            data-ripple-color="light">
                                            <img src="https://robus.home.ci/public/dist/providers/xbet.png"
                                                class="w-70" />

                                        </div>
                                    </div>

                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="mb-2 font-14 bold black" for="num_xbet">ID du compte 1XBET
                                            <span class="text-danger"> *</span></label>

                                        <input type="text" name="num_xbet" class="form-control"
                                            placeholder="Ex: CIZW7480 ..." required="">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="mb-2 font-14 bold black" for="all_name">Nom et prénom 1XBET<span
                                                class="text-danger"> *</span></label>
                                        <input type="text" name="all_name" class="form-control"
                                            placeholder="Ex: KONAN Franck ..." required="">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <label class="mb-2 font-14 bold black" for="my_phone">Numero de téléphone <span
                                                class="text-danger">
                                                *</span></label>
                                        <input type="text" name="my_phone" maxlength="10" class="form-control"
                                            placeholder="Ex: 0101010101" required="">
                                    </div>


                                    <div class=" col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <label class="mb-2 font-14 bold black">Montant (min : 500Fcfa / max : 500.000Fcfa )
                                            <span class="text-danger"> *</span></label>
                                        <input type="number" class="form-control" id="montant_fixe" maxlength="10"
                                            name="amount" placeholder="Ex: 2400" required="">

                                        <div class="mt-3">
                                            <span class="badge bg-info text-dark style_span text-white mt-3 fixe_percent"
                                                id="montantInfo">Montant (min : 500Fcfa / max :
                                                500.000Fcfa)</span>
                                        </div>

                                    </div>


                                    @include('layouts.shared_web.frais')

                                    <div class=" col-md-12 col-sm-12 col-xs-12 mt-3"><button type="submit"
                                            class="theme-btn btn-style-three" id="submitButton" disabled>Valider</button>
                                    </div>


                                </div>
                            </form>

                        </div>
                        <!--End Contact Form -->
                    </div>
                    @include('layouts.shared_web.info-open-xbet')

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
  
@endpush
