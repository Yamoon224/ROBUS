@extends('layouts.master_web')

@section('title', 'Rechargement ROBUS')

@section('content')
    <section class="page-title animate__animated animate__slideInDown"
        style="background-image:url(https://robus.home.ci/public/dist/images/transactions.jpeg);">
        <div class="container text-center text-white">
            <h1>Réchargement ROBUS</h1>
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

                            <form method="POST" action="{{ route('web.transactions_robus_store.stores') }}" method="post"
                                enctype="multipart/form-data"
                                style="margin-bottom:5% ; background-color: #f0fcff !important;"
                                class="p-4 p-md-5 border rounded-3 bg-light">

                                @csrf

                                <div class="row justify-content-center ">
                                    <div class="col-lg-2 col-md-12 mb-4 mb-lg-0 mb-3">
                                        <div class="bg-image hover-overlay image_mobile ripple shadow-1-strong rounded"
                                            data-ripple-color="light">
                                            <img src="https://robus.home.ci/public/dist/providers/robus.png"
                                                class="w-70" />
                                            <a href="#!" data-mdb-modal-init data-mdb-target="#exampleModal1">
                                                <div class="mask" style="background-color: rgba(251, 251, 251, 0.2);">
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                </div>

                                <div class="row g-2 mt-3">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="mb-2 font-14 bold black" for="num_robus">ID du compte ROBUS
                                            <span class="text-danger"> *</span></label>
                                        <input type="text" name="num_robus" id="walletId" class="form-control"
                                            placeholder="Ex: CIZW7480 ..." required="">
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="mb-2 font-14 bold black" for="all_name">Saisie le Wallet pour afficher
                                            Nom
                                            et prenom ROBUS<span class="text-danger"> *</span></label>
                                        <span id="resultSearch" class="badge bg-info text-dark style_span text-white"
                                            style="
                                    width: 100%;height:5à%"></span>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <label class="mb-2 font-14 bold black" for="my_phone">Numero de téléphone <span
                                                class="text-danger">
                                                *</span></label>
                                        <input type="text" name="my_phone" maxlength="10" class="form-control"
                                            placeholder="Ex: 0101010101" required="">
                                    </div>


                                    <div class=" col-md-6 col-sm-6 col-xs-12 mt-3">
                                        <label class="mb-2 font-14 bold black">Montant (min : 2000Fcfa / max : 500.000Fcfa )
                                            <span class="text-danger"> *</span></label>
                                        <input type="number" class="form-control" id="montant_robus" maxlength="10"
                                            name="amount" placeholder="Ex: 2400" required="">

                                        <div class="mt-3">
                                            <span class="badge bg-info text-dark style_span text-white mt-3 fixe_percent"
                                                id="montantInfoRobus">Montant (min : 2000Fcfa / max :
                                                500.000Fcfa)</span>
                                        </div>
                                    </div>

                                    @include('layouts.shared_web.frais')

                                    <div class=" col-md-12 col-sm-12 col-xs-12 mt-3"><button type="submit"
                                            class="theme-btn btn-style-three" id="submitButtonRobus"
                                            disabled>Valider</button>
                                    </div>


                                </div>
                            </form>

                        </div>
                        <!--End Contact Form -->
                    </div>
                    @include('layouts.shared_web.info-open-robus')

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
        const walletInput = document.querySelector('input[name="num_robus"]');
        const resultSearch = document.getElementById('resultSearch');

        resultSearch.innerText = 'Entrez un numéro de wallet pour afficher ';

        walletInput.addEventListener('input', function() {
            searchResult();
        });

        function searchResult() {
            const walletSearch = walletInput.value;
            if (walletSearch.trim() !== '') {
                fetch('{{ route('search.wallets') }}?num_robus=' + walletSearch)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erreur lors de la recherche du wallet');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.user_firstname) {
                            resultSearch.innerText = `${data.user_firstname}  ${data.user_lastname}`;
                        } else {
                            resultSearch.innerText = 'Aucun utilisateur trouvé pour ce wallet';
                        }
                    })
                    .catch(error => {
                        resultSearch.innerText = 'Le numero wallet incorrecte';
                    });


            } else {
                resultSearch.innerText = 'Saisissez le numéro du wallet';
            }
        }
    </script>
@endpush
