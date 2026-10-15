@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
    $cardRoutes = ['web.transactions_reloads', 'web.transactions_pushs', 'web.transactions_phone_credits'];

    $mainLinks = [
        [
            'label' => 'Transfert & Paiement',
            'href' => route('web.transactions_transactions'),
            'icon' => 'bi-send-check',
            'active' => $route == 'web.transactions_transactions',
        ],
        [
            'label' => 'Compte ROBUS',
            'href' => route('web.transactions_robus'),
            'icon' => 'bi-wallet2',
            'active' => $route == 'web.transactions_robus',
        ],
        [
            'label' => 'Compte 1XBET',
            'href' => route('web.transactions_xbets'),
            'icon' => 'bi-trophy',
            'active' => $route == 'web.transactions_xbets',
        ],
        [
            'label' => 'International',
            'href' => route('web.transactions_inters'),
            'icon' => 'bi-globe2',
            'active' => $route == 'web.transactions_inters',
        ],
    ];
@endphp

<style>
    .robus-web-navbar {
        min-height: 78px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(148, 0, 102, 0.12);
        background: rgba(255, 255, 255, 0.96) !important;
        box-shadow: 0 14px 38px rgba(15, 23, 42, 0.12);
        backdrop-filter: blur(16px);
    }

    .robus-web-navbar .navbar-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 14px;
        background: transparent;
        box-shadow: none;
    }

    .robus-web-navbar .navbar-logo {
        width: auto;
        height: 48px;
        object-fit: contain;
    }

    .robus-web-navbar .navbar-toggler {
        width: 46px;
        height: 46px;
        border: 1px solid rgba(163, 0, 113, 0.22);
        border-radius: 12px;
        box-shadow: none;
    }

    .robus-menu {
        gap: 6px;
    }

    .robus-menu-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 12px !important;
        color: #2b2030 !important;
        font-size: 0.92rem;
        font-weight: 700;
        letter-spacing: 0;
        border-radius: 12px;
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .robus-menu-link i {
        font-size: 1rem;
        color: #a30071;
    }

    .robus-menu-link:hover,
    .robus-menu-link.active,
    .robus-menu-link.show {
        color: #7a155f !important;
        background: #fff4e2;
        transform: translateY(-1px);
    }

    .robus-menu-link.active::after {
        content: "";
        position: absolute;
        left: 14px;
        right: 14px;
        bottom: 5px;
        height: 3px;
        border-radius: 999px;
        background: #a30071;
    }

    .robus-card-dropdown {
        min-width: 260px;
        padding: 10px;
        border: 0;
        border-radius: 16px;
        box-shadow: 0 22px 54px rgba(15, 23, 42, 0.2);
    }

    .robus-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 48px;
        padding: 10px 12px;
        border-radius: 12px;
        color: #1f2937;
        font-weight: 700;
    }

    .robus-dropdown-item i {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #a30071;
        border-radius: 10px;
        background: #fff2df;
    }

    .robus-dropdown-item:hover,
    .robus-dropdown-item.active {
        color: #7a155f;
        background: #fff7ed;
    }

    .robus-contact-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 14px;
        color: #ffffff !important;
        font-weight: 800;
        border-radius: 12px;
        background: linear-gradient(135deg, #a30071, #f8a21f);
        box-shadow: 0 12px 30px rgba(163, 0, 113, 0.18);
    }

    .robus-contact-link i {
        color: #ffffff;
    }

    @media (max-width: 991.98px) {
        .robus-web-navbar {
            background: rgba(255, 255, 255, 0.98) !important;
        }

        .robus-web-navbar .navbar-collapse {
            margin-top: 12px;
            padding: 12px;
            border: 1px solid rgba(163, 0, 113, 0.12);
            border-radius: 18px;
            background: #ffffff;
        }

        .robus-menu-link,
        .robus-contact-link {
            width: 100%;
            justify-content: flex-start;
        }

        .robus-menu-link.active::after {
            left: auto;
            right: 12px;
            top: 12px;
            bottom: 12px;
            width: 4px;
            height: auto;
        }

        .robus-card-dropdown {
            margin: 6px 0 8px;
            box-shadow: none;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light fixed-top robus-web-navbar style_header">
    <div class="container">
        <a class="navbar-brand" href="https://robus.africa/" aria-label="Robus Africa">
            <img src="https://robus.africa/wp-content/uploads/2023/07/LOGO_ROBUS-web.png"
                alt="Robus Africa"
                title="Robus Africa"
                class="navbar-logo">
        </a>

        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav robus-menu mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link robus-menu-link" href="https://robus.africa/">
                        <i class="bi bi-house-door"></i>
                        <span>Accueil</span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle robus-menu-link {{ in_array($route, $cardRoutes, true) ? 'active' : '' }}"
                        href="#"
                        id="rechargeDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Cartes</span>
                    </a>
                    <ul class="dropdown-menu robus-card-dropdown" aria-labelledby="rechargeDropdown">
                        <li>
                            <a class="dropdown-item robus-dropdown-item {{ $route == 'web.transactions_reloads' ? 'active' : '' }}"
                                href="{{ route('web.transactions_reloads') }}">
                                <i class="bi bi-credit-card"></i>
                                <span>Visa / MasterCard</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item robus-dropdown-item {{ $route == 'web.transactions_pushs' ? 'active' : '' }}"
                                href="{{ route('web.transactions_pushs') }}">
                                <i class="bi bi-lightning-charge"></i>
                                <span>Carte Push</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item robus-dropdown-item {{ $route == 'web.transactions_phone_credits' ? 'active' : '' }}"
                                href="{{ route('web.transactions_phone_credits') }}">
                                <i class="bi bi-phone"></i>
                                <span>Crédit téléphonique CI - Cameroun</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @foreach ($mainLinks as $link)
                    <li class="nav-item">
                        <a class="nav-link robus-menu-link {{ $link['active'] ? 'active' : '' }}" href="{{ $link['href'] }}">
                            <i class="bi {{ $link['icon'] }}"></i>
                            <span>{{ $link['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="d-flex ms-lg-3">
                <a class="robus-contact-link" href="https://robus.africa/#contacts">
                    <i class="bi bi-headset"></i>
                    <span>Contact</span>
                </a>
            </div>
        </div>
    </div>
</nav>
