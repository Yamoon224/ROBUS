<!-- End Main Content -->
<!--Main Footer-->
<footer class="robus-footer">

    <!--Footer Upper-->
    <div class="footer-upper">
        <div class="auto-container">
            <div class="row clearfix">
                <!--Two 4th column-->
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="sec-title">
                        <h2>Liens utiles</h2>
                    </div>
                    <div class="footer-widget links-widget ">
                        <ul>
                            <li><a href="#" class="style_footer" >Recharger une carte Visa/Master Carte</a></li>
                            <li><a href="#" class="style_footer" >Recharger une carte Push</a></li>
                            <li><a href="#" class="style_footer" >Transfert et paiment de credit</a></li>
                            <li><a href="#" class="style_footer" >Récharger mon compte ROBUS</a></li>
                            <li><a href="#" class="style_footer" >Récharger mon compte 1XBET</a></li>
                        </ul>

                    </div>
                </div>
                <!--Two 4th column End-->

                <!--Two 4th column-->
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="row">
                        <!--Footer Column-->

                        <!--Footer Column-->
                        <div class="col-md-6 col-sm-6 col-xs-12 column">
                            <div class="footer-widget gallery-widget">
                                <div class="sec-title">
                                    <h2>Nos cartes</h2>
                                </div>
                                <div class="row">
                                    @foreach (getReloadCartes() as $carteReloads)
                                        <div class="col-lg-4 col-md-12 mb-4 mb-lg-0 mb-3 mt-3">
                                            <div class="bg-image hover-overlay ripple shadow-1-strong rounded"
                                                data-ripple-color="light">
                                                <img src="{{ $carteReloads->image ?? null }}" class="w-100" />
                                                <a href="#!" data-mdb-modal-init
                                                    data-mdb-target="#exampleModal1">
                                                    <div class="mask"
                                                        style="background-color: rgba(251, 251, 251, 0.2);"></div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                        <!--Footer Column-->

                        <!--Footer Column-->
                        <div class="col-md-6 col-sm-6 col-xs-12 column">
                            <div class="footer-widget gallery-widget">
                                <div class="sec-title">
                                    <h2>Nos modes de paiements</h2>
                                </div>
                                <div class="row">
                                    @foreach (getReloadPaiements() as $carteReloads)
                                        <div class="col-lg-4 col-md-12 mb-4 mb-lg-0 mb-3 mt-3">
                                            <div class="bg-image hover-overlay ripple shadow-1-strong rounded"
                                                data-ripple-color="light">
                                                <img src="{{ $carteReloads->image ?? null }}" class="w-100" />
                                                <a href="#!" data-mdb-modal-init
                                                    data-mdb-target="#exampleModal1">
                                                    <div class="mask"
                                                        style="background-color: rgba(251, 251, 251, 0.2);"></div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!--Footer Bottom-->
    <div class="footer-bottom">
        <div class="auto-container">
            <div class="row clearfix">
                <!--Copyright-->
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="copyright">Copyrights &copy; 2022 Robus. Tous les droits sont réservés.</div>
                </div>

            </div>
        </div>
    </div>

</footer>
</div>
<!--End pagewrapper-->
