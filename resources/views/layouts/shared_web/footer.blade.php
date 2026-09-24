@php
    $footerLinks = [
        [
            'label' => 'Récharger Visa / MasterCard',
            'href' => route('web.transactions_reloads'),
            'icon' => 'bi-credit-card',
        ],
        [
            'label' => 'Récharger une carte Push',
            'href' => route('web.transactions_pushs'),
            'icon' => 'bi-lightning-charge',
        ],
        [
            'label' => 'Transfert et paiement',
            'href' => route('web.transactions_transactions'),
            'icon' => 'bi-send-check',
        ],
        [
            'label' => 'Compte ROBUS',
            'href' => route('web.transactions_robus'),
            'icon' => 'bi-wallet2',
        ],
        [
            'label' => 'Compte 1XBET',
            'href' => route('web.transactions_xbets'),
            'icon' => 'bi-trophy',
        ],
    ];
@endphp

<style>
    .robus-site-footer {
        position: relative;
        overflow: hidden;
        color: #ffffff;
        background: linear-gradient(135deg, #41113f 0%, #7a155f 48%, #a30071 100%);
    }

    .robus-site-footer::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0)),
            radial-gradient(circle at 82% 18%, rgba(248, 162, 31, 0.28), transparent 32%);
    }

    .robus-footer-main {
        position: relative;
        padding: clamp(44px, 6vw, 76px) 0 34px;
    }

    .robus-footer-brand {
        max-width: 380px;
    }

    .robus-footer-logo {
        display: inline-flex;
        align-items: center;
        padding: 10px 14px;
        margin-bottom: 18px;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
    }

    .robus-footer-logo img {
        height: 46px;
        width: auto;
        object-fit: contain;
    }

    .robus-footer-text {
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.96rem;
        line-height: 1.75;
    }

    .robus-footer-title {
        margin-bottom: 16px;
        color: #ffffff;
        font-size: 1rem;
        font-weight: 800;
    }

    .robus-footer-links {
        display: grid;
        gap: 9px;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .robus-footer-links a {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.82);
        font-weight: 650;
        text-decoration: none;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .robus-footer-links a:hover {
        color: #ffffff;
        transform: translateX(3px);
    }

    .robus-footer-links i {
        color: #f8a21f;
    }

    .robus-footer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(74px, 1fr));
        gap: 10px;
    }

    .robus-footer-logo-card {
        min-height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 9px;
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.92);
    }

    .robus-footer-logo-card img {
        max-width: 100%;
        max-height: 38px;
        object-fit: contain;
    }

    .robus-footer-contact {
        display: grid;
        gap: 12px;
    }

    .robus-footer-contact a,
    .robus-footer-contact span {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.84);
        font-weight: 700;
        text-decoration: none;
    }

    .robus-footer-contact i {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #7a155f;
        border-radius: 10px;
        background: #ffffff;
    }

    .robus-footer-bottom {
        position: relative;
        padding: 18px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
        color: rgba(255, 255, 255, 0.74);
        font-size: 0.92rem;
    }

    .robus-footer-bottom a {
        color: #ffffff;
        font-weight: 800;
        text-decoration: none;
    }

    @media (max-width: 767.98px) {
        .robus-footer-brand {
            max-width: none;
        }

        .robus-footer-main {
            padding-top: 40px;
            padding-left: 14px;
        }

        .robus-footer-bottom .container {
            padding-left: 14px;
        }
    }
</style>

<footer class="robus-site-footer">
    <div class="container robus-footer-main">
        <div class="row g-4 g-lg-5">
            <div class="col-lg-4 col-md-6">
                <div class="robus-footer-brand">
                    <a href="https://robus.africa/" class="robus-footer-logo" aria-label="Robus Africa">
                        <img src="https://robus.africa/wp-content/uploads/2023/07/LOGO_ROBUS-web.png"
                            alt="Robus Africa">
                    </a>
                    <p class="robus-footer-text mb-0">
                        ROBUS accompagne vos rechargements, transferts et paiements avec des parcours simples,
                        sécurisés et suivis par notre équipe.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h3 class="robus-footer-title">Services rapides</h3>
                <ul class="robus-footer-links">
                    @foreach ($footerLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">
                                <i class="bi {{ $link['icon'] }}"></i>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-lg-2 col-md-6">
                <h3 class="robus-footer-title">Nos cartes</h3>
                <div class="robus-footer-grid">
                    @foreach (getReloadCartes() as $carteReloads)
                        <div class="robus-footer-logo-card">
                            <img src="{{ $carteReloads->image ?? null }}" alt="{{ $carteReloads->name ?? 'Carte ROBUS' }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <h3 class="robus-footer-title">Paiement & contact</h3>
                <div class="robus-footer-grid mb-4">
                    @foreach (getReloadLimitPaiements() as $carteReloads)
                        <div class="robus-footer-logo-card">
                            <img src="{{ $carteReloads->image ?? null }}" alt="{{ $carteReloads->name ?? 'Paiement ROBUS' }}">
                        </div>
                    @endforeach
                </div>

                <div class="robus-footer-contact">
                    <a href="https://robus.africa/#contacts">
                        <i class="bi bi-headset"></i>
                        <span>Contacter ROBUS</span>
                    </a>
                    <a href="https://robus.africa/">
                        <i class="bi bi-globe2"></i>
                        <span>Robus</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="robus-footer-bottom">
        <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>Copyright &copy; {{ date('Y') }} Robus. Tous les droits sont réservés.</span>
            <a href="https://robus.africa/">ROBUS Africa</a>
        </div>
    </div>
</footer>
