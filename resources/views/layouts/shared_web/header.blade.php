@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp

<nav class="navbar navbar-expand-lg navbar-dark fixed-top custom-navbar style_header">
    <div class="container-fluid margin-menu-all">
        <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="https://robus.africa/wp-content/uploads/2023/07/LOGO_ROBUS-web.png" 
                 alt="Robus Africa" 
                 title="Robus Africa"
                 height="60"
                 class="navbar-logo">
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Items -->
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Accueil -->
                <li class="nav-item">
                    <a class="nav-link nav-item-custom" href="https://robus.africa/">
                        <i class="nav-icon">🏠</i>
                        Accueil
                    </a>
                </li>

                <!-- Récharger ma carte - Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-item-custom {{ $route == 'web.transactions_reloads' || $route == 'web.transactions_pushs' ? 'active show' : '' }}" 
                       href="#" 
                       id="rechargeDropdown"
                       data-bs-toggle="dropdown" 
                       aria-expanded="{{ $route == 'web.transactions_reloads' || $route == 'web.transactions_pushs' ? 'true' : 'false' }}">
                        <i class="nav-icon">💳</i>
                        Récharger ma carte
                    </a>
                    <ul class="dropdown-menu custom-dropdown {{ $route == 'web.transactions_reloads' || $route == 'web.transactions_pushs' ? 'show' : '' }}" 
                        aria-labelledby="rechargeDropdown">
                        <li>
                            <a class="dropdown-item dropdown-item-custom {{ $route == 'web.transactions_reloads' ? 'active' : '' }}" 
                               href="{{ route('web.transactions_reloads') }}">
                                <i class="dropdown-icon">🔄</i>
                                Carte Visa/MasterCard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom {{ $route == 'web.transactions_pushs' ? 'active' : '' }}" 
                               href="{{ route('web.transactions_pushs') }}">
                                <i class="dropdown-icon">⚡</i>
                                Carte Push
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Transfert et paiement -->
                <li class="nav-item">
                    <a class="nav-link nav-item-custom {{ $route == 'web.transactions_transactions' ? 'active' : '' }}"
                       href="{{ route('web.transactions_transactions') }}">
                        <i class="nav-icon">💰</i>
                        Transfert & Paiement
                    </a>
                </li>

                <!-- Récharger compte ROBUS -->
                <li class="nav-item">
                    <a class="nav-link nav-item-custom {{ $route == 'web.transactions_robus' ? 'active' : '' }}"
                       href="{{ route('web.transactions_robus') }}">
                        <i class="nav-icon">🏦</i>
                        Compte ROBUS
                    </a>
                </li>

                <!-- Récharger compte 1XBET -->
                <li class="nav-item">
                    <a class="nav-link nav-item-custom {{ $route == 'web.transactions_xbets' ? 'active' : '' }}"
                       href="{{ route('web.transactions_xbets') }}">
                        <i class="nav-icon">🎰</i>
                        Compte 1XBET
                    </a>
                </li>

                <!-- Transfert international -->
                <li class="nav-item">
                    <a class="nav-link nav-item-custom {{ $route == 'web.transactions_inters' ? 'active' : '' }}"
                       href="{{ route('web.transactions_inters') }}">
                        <i class="nav-icon">🌍</i>
                        Transfert International
                    </a>
                </li>

                <!-- Contact -->
                <li class="nav-item">
                    <a class="nav-link nav-item-custom" href="https://robus.africa/#contacts">
                        <i class="nav-icon">📞</i>
                        Contact
                    </a>
                </li>
            </ul>

           
        </div>
    </div>
</nav>
