<div>


    <section class="page-title"
        style="background-image:url(https://robus.home.ci/public/dist/images/rechargement_carte.jpeg);">
        <div class="auto-container">
            <h1>Réchargement de carte VISA/MASTER CARTE</h1>
        </div>
        </div>
    </section>

    <section class="contact-info-section" style="margin-top:-6%" id="form">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-md-8 col-sm-10 col-xs-12">
                    <!-- Contact Form -->
                    <div class="default-form contact-form">
                        <form method="POST" action="https://maboutik.ci/admin/mobile/store" 
                            style="margin-bottom:5%" novalidate="novalidate">
                            <input type="hidden" name="_token" value="NallzZ805XfNwwZZldLmFrwqG0SQFigv3Ni1iNLL">
                            <div class="row clearfix">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black">Identifiant ou Num de la carte *</label>
                                    <input type="text" name="MO_NOMCOMPLET" value=""
                                        placeholder="Ex: KONAN Franck ..." required="">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black">Nom sur la carte *</label>
                                    <input type="text" name="MO_NOMCOMPLET" value=""
                                        placeholder="Ex: KONAN Franck ..." required="">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black">Numero de téléphone *</label>
                                    <input type="text" name="MO_TEL" value="" placeholder="Ex: 072045014520"
                                        required="">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black">Carte a recharger *</label>
                                    <input type="text" name="MO_TEL" value="" placeholder="Ex: 072045014520"
                                        required="">
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label class="mb-2 font-14 bold black">Montant(min : 2000Fcfa / max : 500.000Fcfa ) *</label>
                                    <input type="number" name="MO_MONTANT" value="" placeholder="Ex: 4500"
                                        required="">
                                </div>



                                <div class="form-group col-md-12 col-sm-12 col-xs-12"><button type="submit"
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

</div>
