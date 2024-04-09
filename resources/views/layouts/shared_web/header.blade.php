<header class="site-navbar js-sticky-header site-navbar-target style_header" role="banner">

    <div class="container">
        <div class="row align-items-center position-relative">


            <div class="site-logo">
            
                <a href="index.html" class="text-black"><img src="https://robus.africa/wp-content/uploads/2023/07/LOGO_ROBUS-web.png" alt="Brighton" title="Brighton" height="70"></a>
            </div>

            <div class="col-12 margin-menu">
                <nav class="site-navigation text-right ml-auto" role="navigation">

                    <ul class="site-menu main-menu js-clone-nav ml-auto d-none d-lg-block">
                        <li><a href="https://robus.africa/" class="nav-link">Acceuil</a></li>

                        <li class="has-children">
                            <a href="#about-section" class="nav-link">Réchargement de carte  </a>
                            <ul class="dropdown arrow-top">
                                <li><a href="{{ route('web.transactions_reloads') }}" class="nav-link">Visa/Master Carte</a></li>
                                <li><a href="{{ route('web.transactions_pushs') }}" class="nav-link">Carte PUSH</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('web.transactions_deposits') }}" class="nav-link">Transfert et paiment de credit</a></li>

                        <li><a href="{{ route('web.transactions_robus') }}" class="nav-link">Récharger mon compte ROBUS</a></li>
                        <li><a href="{{ route('web.transactions_xbets') }}" class="nav-link">Récharger mon compte 1XBET</a></li>
                        <li><a href="https://robus.africa/#contacts" class="nav-link">Contact</a></li>
                    </ul>
                </nav>

            </div>

            <div class="toggle-button d-inline-block d-lg-none"><a href="#"
                    class="site-menu-toggle py-5 js-menu-toggle text-black"><span class="icon-menu h3"></span></a>
            </div>

        </div>
    </div>

</header>