@php
    $footerLinks = [
        ['label' => 'Récharger Visa / MasterCard', 'href' => route('web.transactions_reloads'),     'icon' => 'bi-credit-card'],
        ['label' => 'Récharger une carte Push',     'href' => route('web.transactions_pushs'),       'icon' => 'bi-lightning-charge'],
        ['label' => 'Transfert et paiement',        'href' => route('web.transactions_transactions'),'icon' => 'bi-send-check'],
        ['label' => 'Compte ROBUS',                 'href' => route('web.transactions_robus'),       'icon' => 'bi-wallet2'],
        ['label' => 'Compte 1XBET',                 'href' => route('web.transactions_xbets'),       'icon' => 'bi-trophy'],
        ['label' => 'International',                'href' => route('web.transactions_inters'),      'icon' => 'bi-globe2'],
    ];

    $currentRoute = Route::current()->getName();
@endphp

<style>
    /* ── Variables ──────────────────────────────────── */
    .robus-site-footer {
        --f-accent:  #f8a21f;
        --f-pink:    #a30071;
        --f-dark:    #41113f;
        --f-mid:     #7a155f;
        --f-text:    rgba(255,255,255,.78);
        --f-muted:   rgba(255,255,255,.52);
        --f-border:  rgba(255,255,255,.13);
        position: relative;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(135deg, #41113f 0%, #7a155f 48%, #a30071 100%);
    }

    /* Fond décoratif */
    .robus-site-footer::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            radial-gradient(circle at 85% 10%, rgba(248,162,31,.22), transparent 36%),
            radial-gradient(circle at 10% 90%, rgba(255,255,255,.06), transparent 28%),
            linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,0));
    }

    /* Vague SVG en haut */
    .robus-footer-wave {
        display: block;
        width: 100%;
        line-height: 0;
        margin-bottom: -2px;
    }

    .robus-footer-wave svg { width: 100%; height: auto; display: block; }

    /* ── Bandeau CTA ────────────────────────────────── */
    .robus-footer-cta {
        position: relative;
        padding: 22px 0;
        border-bottom: 1px solid var(--f-border);
    }

    .robus-footer-cta-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .robus-footer-cta-text {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .robus-footer-cta-icon {
        flex: 0 0 auto;
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--f-dark);
        border-radius: 14px;
        background: var(--f-accent);
        box-shadow: 0 8px 24px rgba(248,162,31,.35);
    }

    .robus-footer-cta-label {
        font-size: .98rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
    }

    .robus-footer-cta-sub {
        font-size: .8rem;
        font-weight: 500;
        color: var(--f-text);
        margin-top: 2px;
    }

    .robus-footer-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        font-size: .88rem;
        font-weight: 800;
        color: var(--f-dark);
        border-radius: 12px;
        background: var(--f-accent);
        box-shadow: 0 10px 28px rgba(248,162,31,.3);
        text-decoration: none;
        white-space: nowrap;
        transition: transform .18s, box-shadow .18s;
    }

    .robus-footer-cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 36px rgba(248,162,31,.42);
        color: var(--f-dark);
    }

    /* ── Corps principal ────────────────────────────── */
    .robus-footer-main {
        position: relative;
        padding: clamp(36px, 5vw, 64px) 0 32px;
    }

    /* ── Colonne Brand ──────────────────────────────── */
    .robus-footer-logo-wrap {
        display: inline-flex;
        align-items: center;
        padding: 10px 14px;
        margin-bottom: 16px;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 16px 40px rgba(0,0,0,.18);
        text-decoration: none;
    }

    .robus-footer-logo-wrap img {
        height: 44px;
        width: auto;
        object-fit: contain;
    }

    .robus-footer-tagline {
        color: var(--f-text);
        font-size: .92rem;
        line-height: 1.75;
        margin-bottom: 20px;
    }

    /* Réseaux sociaux */
    .robus-footer-socials {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .robus-footer-social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        font-size: .95rem;
        color: rgba(255,255,255,.8);
        border: 1px solid var(--f-border);
        border-radius: 10px;
        background: rgba(255,255,255,.08);
        text-decoration: none;
        transition: background .18s, color .18s, border-color .18s, transform .18s;
    }

    .robus-footer-social-btn:hover {
        color: #fff;
        background: rgba(255,255,255,.18);
        border-color: rgba(255,255,255,.28);
        transform: translateY(-2px);
    }

    /* Badges sécurité */
    .robus-footer-trust {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .robus-footer-trust-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        font-size: .74rem;
        font-weight: 700;
        color: rgba(255,255,255,.78);
        border: 1px solid var(--f-border);
        border-radius: 8px;
        background: rgba(255,255,255,.07);
    }

    .robus-footer-trust-badge i { color: var(--f-accent); font-size: .78rem; }

    /* ── Titres colonnes ────────────────────────────── */
    .robus-footer-col-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--f-border);
        color: #fff;
        font-size: .9rem;
        font-weight: 800;
        letter-spacing: .01em;
    }

    .robus-footer-col-title i {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        color: var(--f-dark);
        border-radius: 8px;
        background: var(--f-accent);
        flex: 0 0 auto;
    }

    /* ── Liens services ─────────────────────────────── */
    .robus-footer-links {
        display: grid;
        gap: 4px;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .robus-footer-links a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 9px;
        color: var(--f-text);
        font-size: .88rem;
        font-weight: 650;
        border-radius: 9px;
        text-decoration: none;
        transition: background .18s, color .18s, padding-left .18s;
    }

    .robus-footer-links a:hover,
    .robus-footer-links a.active {
        color: #fff;
        background: rgba(255,255,255,.1);
        padding-left: 13px;
    }

    .robus-footer-links a.active { pointer-events: none; }

    .robus-footer-links .fl-icon {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .78rem;
        color: var(--f-accent);
        border-radius: 7px;
        background: rgba(248,162,31,.15);
        flex: 0 0 auto;
        transition: background .18s;
    }

    .robus-footer-links a:hover .fl-icon,
    .robus-footer-links a.active .fl-icon {
        background: rgba(248,162,31,.28);
    }

    /* ── Grille logos cartes/paiement ───────────────── */
    .robus-footer-logo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(68px, 1fr));
        gap: 8px;
    }

    .robus-footer-logo-card {
        min-height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 12px;
        background: rgba(255,255,255,.93);
        transition: transform .18s, box-shadow .18s;
    }

    .robus-footer-logo-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,.22);
    }

    .robus-footer-logo-card img {
        max-width: 100%;
        max-height: 34px;
        object-fit: contain;
    }

    /* ── Colonne contact ────────────────────────────── */
    .robus-footer-contact-list {
        display: grid;
        gap: 8px;
    }

    .robus-footer-contact-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: var(--f-text);
        font-size: .87rem;
        font-weight: 600;
        text-decoration: none;
        transition: color .18s;
    }

    .robus-footer-contact-item:hover { color: #fff; }

    .robus-footer-contact-item i {
        flex: 0 0 auto;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        color: var(--f-dark);
        border-radius: 9px;
        background: #fff;
        margin-top: 1px;
    }

    .robus-footer-contact-label {
        font-size: .7rem;
        font-weight: 700;
        color: var(--f-muted);
        text-transform: uppercase;
        letter-spacing: .05em;
        display: block;
        margin-bottom: 1px;
    }

    /* ── Diviseur ───────────────────────────────────── */
    .robus-footer-divider {
        border: none;
        border-top: 1px solid var(--f-border);
        margin: 0;
    }

    /* ── Barre de bas ───────────────────────────────── */
    .robus-footer-bottom {
        position: relative;
        padding: 16px 0;
    }

    .robus-footer-bottom-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .robus-footer-copyright {
        font-size: .82rem;
        color: var(--f-muted);
    }

    .robus-footer-copyright strong { color: rgba(255,255,255,.7); font-weight: 700; }

    .robus-footer-legal {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .robus-footer-legal a {
        padding: 4px 8px;
        font-size: .78rem;
        font-weight: 600;
        color: var(--f-muted);
        text-decoration: none;
        border-radius: 6px;
        transition: color .18s, background .18s;
    }

    .robus-footer-legal a:hover { color: #fff; background: rgba(255,255,255,.1); }

    .robus-footer-legal-sep {
        color: var(--f-border);
        font-size: .7rem;
        pointer-events: none;
    }

    .robus-back-top {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        font-size: .78rem;
        font-weight: 700;
        color: rgba(255,255,255,.7);
        border: 1px solid var(--f-border);
        border-radius: 10px;
        background: rgba(255,255,255,.07);
        text-decoration: none;
        transition: color .18s, background .18s, transform .18s;
    }

    .robus-back-top:hover {
        color: #fff;
        background: rgba(255,255,255,.15);
        transform: translateY(-2px);
    }

    /* ── Responsive ─────────────────────────────────── */
    @media (max-width: 991.98px) {
        .robus-footer-cta-sub { display: none; }
    }

    @media (max-width: 767.98px) {
        .robus-footer-main { padding-top: 28px; }

        .robus-footer-cta-inner { flex-direction: column; align-items: flex-start; gap: 14px; }

        .robus-footer-bottom-inner { flex-direction: column; align-items: flex-start; gap: 10px; }

        .robus-back-top { align-self: flex-end; }
    }
</style>

{{-- ── Vague de séparation ── --}}
<div class="robus-footer-wave" aria-hidden="true">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 54" preserveAspectRatio="none">
        <path fill="#41113f"
              d="M0,32 C240,54 480,10 720,28 C960,46 1200,14 1440,32 L1440,54 L0,54 Z"/>
    </svg>
</div>

<footer class="robus-site-footer" aria-label="Pied de page ROBUS">

    {{-- ── Bandeau CTA ── --}}
    <div class="robus-footer-cta">
        <div class="container">
            <div class="robus-footer-cta-inner">
                <div class="robus-footer-cta-text">
                    <div class="robus-footer-cta-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div>
                        <div class="robus-footer-cta-label">Besoin d'aide ? Notre équipe est disponible</div>
                        <div class="robus-footer-cta-sub">Assistance pour vos transactions, rechargements et paiements</div>
                    </div>
                </div>
                <a href="https://robus.africa/#contacts" class="robus-footer-cta-btn">
                    <i class="bi bi-chat-dots-fill"></i>
                    Contacter ROBUS
                </a>
            </div>
        </div>
    </div>

    {{-- ── Corps principal ── --}}
    <div class="robus-footer-main">
        <div class="container">
            <div class="row g-4 g-lg-5">

                {{-- Col 1 — Brand ── --}}
                <div class="col-lg-4 col-md-12">
                    <a href="https://robus.africa/" class="robus-footer-logo-wrap" aria-label="Robus Africa — accueil">
                        <img src="https://robus.africa/wp-content/uploads/2023/07/LOGO_ROBUS-web.png"
                             alt="Robus Africa">
                    </a>

                    <p class="robus-footer-tagline">
                        ROBUS accompagne vos rechargements, transferts et paiements mobiles
                        avec des parcours simples, sécurisés et suivis par notre équipe en Afrique de l'Ouest.
                    </p>

                    <div class="robus-footer-socials" aria-label="Réseaux sociaux">
                        <a href="https://robus.africa/" class="robus-footer-social-btn" aria-label="Site web ROBUS" title="Site web">
                            <i class="bi bi-globe2"></i>
                        </a>
                        <a href="https://robus.africa/#contacts" class="robus-footer-social-btn" aria-label="Facebook ROBUS" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://robus.africa/#contacts" class="robus-footer-social-btn" aria-label="WhatsApp ROBUS" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                        <a href="https://robus.africa/#contacts" class="robus-footer-social-btn" aria-label="Instagram ROBUS" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>

                    <div class="robus-footer-trust" aria-label="Certifications">
                        <span class="robus-footer-trust-badge">
                            <i class="bi bi-shield-check-fill"></i>Transaction sécurisée
                        </span>
                        <span class="robus-footer-trust-badge">
                            <i class="bi bi-lock-fill"></i>SSL / HTTPS
                        </span>
                        <span class="robus-footer-trust-badge">
                            <i class="bi bi-patch-check-fill"></i>Service certifié
                        </span>
                    </div>
                </div>

                {{-- Col 2 — Services ── --}}
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <h3 class="robus-footer-col-title">
                        <i class="bi bi-grid-fill"></i>
                        Nos services
                    </h3>
                    <ul class="robus-footer-links" role="list">
                        @foreach ($footerLinks as $link)
                            <li>
                                <a href="{{ $link['href'] }}"
                                   class="{{ $currentRoute && str_contains($currentRoute, str_replace('web.', '', $link['href'])) ? 'active' : '' }}">
                                    <span class="fl-icon"><i class="bi {{ $link['icon'] }}"></i></span>
                                    <span>{{ $link['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Col 3 — Cartes & Paiements ── --}}
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <h3 class="robus-footer-col-title">
                        <i class="bi bi-credit-card-fill"></i>
                        Cartes
                    </h3>
                    <div class="robus-footer-logo-grid mb-4">
                        @foreach (getReloadCartes() as $carteReloads)
                            <div class="robus-footer-logo-card">
                                <img src="{{ $carteReloads->image ?? null }}"
                                     alt="{{ $carteReloads->name ?? 'Carte ROBUS' }}"
                                     loading="lazy">
                            </div>
                        @endforeach
                    </div>

                    <h3 class="robus-footer-col-title">
                        <i class="bi bi-phone-fill"></i>
                        Paiements
                    </h3>
                    <div class="robus-footer-logo-grid">
                        @foreach (getReloadLimitPaiements() as $paiement)
                            <div class="robus-footer-logo-card">
                                <img src="{{ $paiement->image ?? null }}"
                                     alt="{{ $paiement->name ?? 'Paiement ROBUS' }}"
                                     loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Col 4 — Contact ── --}}
                <div class="col-lg-3 col-md-12">
                    <h3 class="robus-footer-col-title">
                        <i class="bi bi-geo-alt-fill"></i>
                        Nous contacter
                    </h3>

                    <div class="robus-footer-contact-list">
                        <a href="https://robus.africa/#contacts" class="robus-footer-contact-item">
                            <i class="bi bi-headset"></i>
                            <div>
                                <span class="robus-footer-contact-label">Support client</span>
                                Assistance en ligne
                            </div>
                        </a>
                        <a href="https://robus.africa/" class="robus-footer-contact-item">
                            <i class="bi bi-globe2"></i>
                            <div>
                                <span class="robus-footer-contact-label">Site web</span>
                                robus.africa
                            </div>
                        </a>
                        <span class="robus-footer-contact-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <div>
                                <span class="robus-footer-contact-label">Zone</span>
                                Afrique de l'Ouest & Centrale
                            </div>
                        </span>
                        <span class="robus-footer-contact-item">
                            <i class="bi bi-clock-fill"></i>
                            <div>
                                <span class="robus-footer-contact-label">Disponibilité</span>
                                Lun – Sam, 8h – 20h
                            </div>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <hr class="robus-footer-divider">

    {{-- ── Barre de bas ── --}}
    <div class="robus-footer-bottom">
        <div class="container">
            <div class="robus-footer-bottom-inner">

                <span class="robus-footer-copyright">
                    &copy; {{ date('Y') }} <strong>ROBUS Africa</strong> — Tous droits réservés.
                </span>

                <nav class="robus-footer-legal" aria-label="Liens légaux">
                    <a href="https://robus.africa/">Accueil</a>
                    <span class="robus-footer-legal-sep" aria-hidden="true">·</span>
                    <a href="https://robus.africa/#contacts">Mentions légales</a>
                    <span class="robus-footer-legal-sep" aria-hidden="true">·</span>
                    <a href="https://robus.africa/#contacts">CGU</a>
                    <span class="robus-footer-legal-sep" aria-hidden="true">·</span>
                    <a href="https://robus.africa/#contacts">Confidentialité</a>
                </nav>

                <a href="#" class="robus-back-top" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;"
                   aria-label="Retour en haut de page">
                    <i class="bi bi-arrow-up"></i>
                    Haut de page
                </a>

            </div>
        </div>
    </div>

</footer>
