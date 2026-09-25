@php
    $normalizedTitle = strtolower($title ?? '');

    $variant = 'default';
    if (str_contains($normalizedTitle, '1xbet') || str_contains($normalizedTitle, 'xbet')) {
        $variant = 'xbet';
    } elseif (str_contains($normalizedTitle, 'international')) {
        $variant = 'international';
    } elseif (str_contains($normalizedTitle, 'carte')) {
        $variant = 'card';
    } elseif (str_contains($normalizedTitle, 'téléphonique') || str_contains($normalizedTitle, 'mooney') || str_contains($normalizedTitle, 'money')) {
        $variant = 'mobile';
    } elseif (str_contains($normalizedTitle, 'robus')) {
        $variant = 'robus';
    }

    $heroThemes = [
        'card' => [
            'eyebrow'  => 'Cartes prépayées',
            'icon'     => 'bi-credit-card-2-front',
            'class'    => 'hero-card',
            'meta'     => ['Recharge carte', 'Validation sécurisée', 'Suivi ROBUS'],
            'parent'   => ['label' => 'Cartes', 'icon' => 'bi-credit-card-2-front', 'href' => route('web.transactions_reloads')],
            'stats'    => [['val' => 'Visa / MC', 'lbl' => 'Cartes acceptées'], ['val' => '24h/24', 'lbl' => 'Disponibilité'], ['val' => 'Immédiat', 'lbl' => 'Traitement']],
            'feature'  => ['title' => 'Rechargez vos cartes', 'items' => [['icon' => 'bi-credit-card', 'text' => 'Visa & MasterCard'], ['icon' => 'bi-lightning-charge', 'text' => 'Carte Push disponible'], ['icon' => 'bi-check-circle', 'text' => 'Confirmation immédiate']]],
        ],
        'international' => [
            'eyebrow'  => 'Transferts internationaux',
            'icon'     => 'bi-globe2',
            'class'    => 'hero-international',
            'meta'     => ['Multi-pays', 'Traitement assisté', 'Support ROBUS'],
            'parent'   => ['label' => 'International', 'icon' => 'bi-globe2', 'href' => route('web.transactions_inters')],
            'stats'    => [['val' => '+15', 'lbl' => 'Pays couverts'], ['val' => '24h', 'lbl' => 'Délai maximum'], ['val' => '100%', 'lbl' => 'Traçable']],
            'feature'  => ['title' => 'Vers toute l\'Afrique', 'items' => [['icon' => 'bi-geo-alt', 'text' => 'CI, SN, ML, BF et plus'], ['icon' => 'bi-activity', 'text' => 'Suivi en temps réel'], ['icon' => 'bi-person-check', 'text' => 'Assisté par ROBUS']]],
        ],
        'xbet' => [
            'eyebrow'  => 'Comptes de jeu',
            'icon'     => 'bi-trophy',
            'class'    => 'hero-xbet',
            'meta'     => ['Compte 1XBet', 'Recharge rapide', 'Reçu de demande'],
            'parent'   => ['label' => 'Compte 1XBET', 'icon' => 'bi-trophy', 'href' => route('web.transactions_xbets')],
            'stats'    => [['val' => '1XBET', 'lbl' => 'Plateforme officielle'], ['val' => 'Rapide', 'lbl' => 'Traitement'], ['val' => 'Reçu', 'lbl' => 'Garanti']],
            'feature'  => ['title' => 'Recharge 1XBET', 'items' => [['icon' => 'bi-trophy', 'text' => 'Compte officiel 1XBet'], ['icon' => 'bi-bell', 'text' => 'Confirmation SMS'], ['icon' => 'bi-clock-history', 'text' => 'Historique ROBUS']]],
        ],
        'mobile' => [
            'eyebrow'  => 'Mobile money',
            'icon'     => 'bi-phone',
            'class'    => 'hero-mobile',
            'meta'     => ['Dépôts', 'Transferts', 'Opérateurs locaux'],
            'parent'   => ['label' => 'Transfert & Paiement', 'icon' => 'bi-send-check', 'href' => route('web.transactions_transactions')],
            'stats'    => [['val' => '+10', 'lbl' => 'Opérateurs'], ['val' => 'FCFA', 'lbl' => 'Devise locale'], ['val' => 'SMS', 'lbl' => 'Confirmation']],
            'feature'  => ['title' => 'Votre Mobile Money', 'items' => [['icon' => 'bi-phone-fill', 'text' => 'Orange, MTN, Wave…'], ['icon' => 'bi-calculator', 'text' => 'Calcul des frais en direct'], ['icon' => 'bi-shield-check', 'text' => 'Paiement sécurisé']]],
        ],
        'robus' => [
            'eyebrow'  => 'Wallet ROBUS',
            'icon'     => 'bi-wallet2',
            'class'    => 'hero-robus',
            'meta'     => ['Achat UV', 'Solde ROBUS', 'Demande sécurisée'],
            'parent'   => ['label' => 'Compte ROBUS', 'icon' => 'bi-wallet2', 'href' => route('web.transactions_robus')],
            'stats'    => [['val' => 'UV', 'lbl' => 'Achat direct'], ['val' => 'Immédiat', 'lbl' => 'Crédit compte'], ['val' => 'Sécurisé', 'lbl' => 'Processus']],
            'feature'  => ['title' => 'Achat UV ROBUS', 'items' => [['icon' => 'bi-person-badge', 'text' => 'Saisie de l\'ID ROBUS'], ['icon' => 'bi-phone', 'text' => 'Choix de l\'opérateur'], ['icon' => 'bi-wallet2', 'text' => 'Crédit instantané']]],
        ],
        'default' => [
            'eyebrow'  => 'Services ROBUS',
            'icon'     => 'bi-shield-check',
            'class'    => 'hero-default',
            'meta'     => ['Transaction sécurisée', 'Traitement rapide', 'Assistance disponible'],
            'parent'   => null,
            'stats'    => [['val' => '100%', 'lbl' => 'Sécurisé'], ['val' => 'Rapide', 'lbl' => 'Traitement'], ['val' => '7j/7', 'lbl' => 'Support']],
            'feature'  => ['title' => 'Services ROBUS', 'items' => [['icon' => 'bi-shield-check', 'text' => 'Transactions sécurisées'], ['icon' => 'bi-lightning-charge', 'text' => 'Traitement rapide'], ['icon' => 'bi-headset', 'text' => 'Assistance disponible']]],
        ],
    ];

    $theme          = $heroThemes[$variant] ?? $heroThemes['default'];
    $background     = $backgroundImage ?? asset('dist/images/transactions.jpeg');
    $themeIcon      = str_contains($theme['icon'], 'bi ') ? $theme['icon'] : 'bi ' . $theme['icon'];
    $badgeIconClass = $badgeIcon ?? 'bi-shield-check';
    $badgeIconClass = str_contains($badgeIconClass, 'bi ') ? $badgeIconClass : 'bi ' . $badgeIconClass;
    $breadcrumb     = $theme['parent'];
    $heroStats      = $theme['stats'];
    $heroFeature    = $theme['feature'];
@endphp

<style>
    /* ── Variables & base ───────────────────────────── */
    .robus-hero {
        position: relative;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        isolation: isolate;
        background-position: center;
        background-size: cover;
    }

    /* Overlay principal */
    .robus-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 0;
        background: var(--hero-overlay);
    }

    /* Grain/texture subtil */
    .robus-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        pointer-events: none;
        opacity: .04;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        background-size: 180px;
    }

    /* Tout le contenu interne doit être au-dessus */
    .robus-hero > * { position: relative; z-index: 2; }

    /* ── Orbes décoratifs animés ────────────────────── */
    .robus-hero-orb {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 1;
    }

    .robus-hero-orb-1 {
        width: clamp(220px, 30vw, 420px);
        aspect-ratio: 1;
        top: -15%;
        right: -8%;
        background: radial-gradient(circle, rgba(255,255,255,.13), transparent 70%);
        animation: heroOrbFloat 8s ease-in-out infinite;
    }

    .robus-hero-orb-2 {
        width: clamp(140px, 18vw, 260px);
        aspect-ratio: 1;
        bottom: 10%;
        left: -4%;
        background: radial-gradient(circle, var(--hero-accent-raw, rgba(248,162,31,.18)), transparent 70%);
        animation: heroOrbFloat 11s ease-in-out infinite reverse;
    }

    .robus-hero-orb-3 {
        width: clamp(80px, 10vw, 140px);
        aspect-ratio: 1;
        top: 30%;
        right: 28%;
        background: radial-gradient(circle, rgba(255,255,255,.08), transparent 70%);
        animation: heroOrbFloat 14s ease-in-out infinite 2s;
    }

    @keyframes heroOrbFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50%       { transform: translateY(-18px) scale(1.04); }
    }

    /* Forme décorative principale */
    .robus-hero-shape {
        position: absolute;
        right: clamp(20px, 6vw, 100px);
        top: 50%;
        transform: translateY(-50%) rotate(12deg);
        width: clamp(130px, 18vw, 240px);
        aspect-ratio: 1;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 28px;
        background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.04));
        backdrop-filter: blur(10px);
        animation: heroShapeFloat 9s ease-in-out infinite;
        z-index: 1;
    }

    .robus-hero-shape i {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255,255,255,.55);
        font-size: clamp(3.5rem, 9vw, 7rem);
        transform: rotate(-12deg);
    }

    @keyframes heroShapeFloat {
        0%, 100% { transform: translateY(-50%) rotate(12deg);      }
        50%       { transform: translateY(calc(-50% - 14px)) rotate(14deg); }
    }

    /* ── Barre nav / breadcrumb ─────────────────────── */
    .robus-hero-topbar {
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,.1);
        backdrop-filter: blur(4px);
    }

    .robus-hero-topbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        min-height: 36px;
    }

    .robus-hero-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0;
        flex-wrap: nowrap;
        list-style: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    .robus-hero-breadcrumb li { display: inline-flex; align-items: center; }

    .robus-hero-breadcrumb .crumb-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        font-size: .78rem;
        font-weight: 700;
        color: rgba(255,255,255,.7);
        border-radius: 7px;
        text-decoration: none;
        white-space: nowrap;
        transition: color .18s, background .18s;
    }

    .robus-hero-breadcrumb .crumb-link:hover { color: #fff; background: rgba(255,255,255,.1); }
    .robus-hero-breadcrumb .crumb-link i     { font-size: .78rem; }

    .robus-hero-breadcrumb .crumb-sep {
        padding: 0 1px;
        color: rgba(255,255,255,.3);
        font-size: .68rem;
    }

    .robus-hero-breadcrumb .crumb-current {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        font-size: .78rem;
        font-weight: 800;
        color: #fff;
        border-radius: 7px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.2);
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .robus-hero-breadcrumb .crumb-current i { font-size: .76rem; color: var(--hero-accent); flex: 0 0 auto; }

    /* Liens rapides desktop */
    .robus-hero-quicknav {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
    }

    .robus-hero-quicknav-label {
        font-size: .7rem;
        font-weight: 700;
        color: rgba(255,255,255,.45);
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
        padding-right: 2px;
    }

    .robus-hero-quicknav a {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        font-size: .76rem;
        font-weight: 700;
        color: rgba(255,255,255,.68);
        border-radius: 7px;
        border: 1px solid rgba(255,255,255,.13);
        text-decoration: none;
        white-space: nowrap;
        transition: background .18s, color .18s, border-color .18s;
    }

    .robus-hero-quicknav a:hover,
    .robus-hero-quicknav a.active {
        color: #fff;
        background: rgba(255,255,255,.16);
        border-color: rgba(255,255,255,.28);
    }

    .robus-hero-quicknav a.active { pointer-events: none; }
    .robus-hero-quicknav a i      { font-size: .76rem; }

    /* Bouton retour mobile */
    .robus-hero-back-btn {
        display: none;
        align-items: center;
        gap: 5px;
        padding: 5px 11px;
        font-size: .78rem;
        font-weight: 700;
        color: rgba(255,255,255,.82);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 9px;
        background: rgba(255,255,255,.1);
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background .18s, color .18s;
    }

    .robus-hero-back-btn:hover { color: #fff; background: rgba(255,255,255,.18); }

    /* ── Contenu central ────────────────────────────── */
    .robus-hero-inner {
        flex: 1;
        padding: clamp(28px, 4.5vw, 64px) 0 clamp(20px, 3vw, 40px);
    }

    .robus-hero-row {
        display: flex;
        align-items: center;
        gap: clamp(24px, 4vw, 56px);
    }

    /* Colonne gauche — texte */
    .robus-hero-content { flex: 1; min-width: 0; }

    /* Eyebrow */
    .robus-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 7px 14px 7px 8px;
        margin-bottom: 16px;
        color: #fff;
        font-size: .84rem;
        font-weight: 700;
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 999px;
        background: rgba(255,255,255,.13);
        backdrop-filter: blur(10px);
    }

    .robus-hero-eyebrow-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--hero-accent);
        box-shadow: 0 0 0 3px rgba(255,255,255,.2);
        animation: heroEyebrowPulse 2.4s ease-in-out infinite;
        flex: 0 0 auto;
    }

    @keyframes heroEyebrowPulse {
        0%, 100% { box-shadow: 0 0 0 3px rgba(255,255,255,.2); }
        50%       { box-shadow: 0 0 0 6px rgba(255,255,255,.0); }
    }

    .robus-hero-eyebrow-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .86rem;
        color: var(--hero-accent);
        border-radius: 9px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,.18);
        flex: 0 0 auto;
    }

    /* Titre */
    .robus-hero h1 {
        max-width: 680px;
        color: #fff;
        font-size: clamp(1.85rem, 4.5vw, 3.8rem);
        line-height: 1.06;
        font-weight: 900;
        letter-spacing: -.01em;
        text-wrap: balance;
        text-shadow: 0 12px 36px rgba(8,13,30,.32);
        margin-bottom: 14px;
    }

    /* Subtitle */
    .robus-hero-subtitle {
        max-width: 580px;
        color: rgba(255,255,255,.84);
        font-size: clamp(.95rem, 1.6vw, 1.14rem);
        line-height: 1.7;
        margin-bottom: 0;
    }

    /* Badge action */
    .robus-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .robus-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        color: #111827;
        font-size: .86rem;
        font-weight: 800;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15,23,42,.18);
    }

    .robus-hero-badge i { color: var(--hero-accent); }

    /* Meta chips */
    .robus-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 18px;
    }

    .robus-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        color: rgba(255,255,255,.88);
        font-size: .82rem;
        font-weight: 600;
        border-radius: 9px;
        border: 1px solid rgba(255,255,255,.18);
        background: rgba(255,255,255,.11);
        backdrop-filter: blur(8px);
    }

    .robus-hero-meta span i { font-size: .78rem; color: var(--hero-accent); }

    /* ── Carte feature droite (desktop) ─────────────── */
    .robus-hero-feature {
        flex: 0 0 auto;
        width: clamp(220px, 28vw, 320px);
    }

    .robus-hero-feature-card {
        padding: clamp(18px, 2.5vw, 28px);
        border-radius: 22px;
        border: 1px solid rgba(255,255,255,.22);
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(18px);
        box-shadow: 0 20px 48px rgba(0,0,0,.22), inset 0 1px 0 rgba(255,255,255,.22);
    }

    .robus-hero-feature-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: var(--hero-accent);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(0,0,0,.18);
        margin-bottom: 14px;
    }

    .robus-hero-feature-title {
        font-size: .96rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 14px;
        line-height: 1.25;
    }

    .robus-hero-feature-list {
        display: grid;
        gap: 9px;
    }

    .robus-hero-feature-item {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: .82rem;
        font-weight: 600;
        color: rgba(255,255,255,.88);
    }

    .robus-hero-feature-item-icon {
        flex: 0 0 auto;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .76rem;
        color: var(--hero-accent);
        border-radius: 8px;
        background: rgba(255,255,255,.15);
    }

    /* ── Bande de stats ─────────────────────────────── */
    .robus-hero-stats {
        border-top: 1px solid rgba(255,255,255,.1);
        padding: 16px 0 18px;
    }

    .robus-hero-stats-inner {
        display: flex;
        align-items: stretch;
        gap: 0;
    }

    .robus-hero-stat {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 4px 16px;
        border-right: 1px solid rgba(255,255,255,.1);
    }

    .robus-hero-stat:first-child { padding-left: 0; }
    .robus-hero-stat:last-child  { border-right: none; }

    .robus-hero-stat-icon {
        flex: 0 0 auto;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .88rem;
        color: var(--hero-accent);
        border-radius: 10px;
        background: rgba(255,255,255,.12);
    }

    .robus-hero-stat-val {
        font-size: clamp(.9rem, 1.6vw, 1.15rem);
        font-weight: 900;
        color: #fff;
        line-height: 1.1;
    }

    .robus-hero-stat-lbl {
        font-size: .72rem;
        font-weight: 600;
        color: rgba(255,255,255,.55);
        margin-top: 2px;
    }

    /* ── Vague de transition ────────────────────────── */
    .robus-hero-wave {
        display: block;
        line-height: 0;
        margin-bottom: -2px;
    }

    .robus-hero-wave svg { width: 100%; height: auto; display: block; }

    /* ── Thèmes ─────────────────────────────────────── */
    .hero-card {
        --hero-accent:     #f8a21f;
        --hero-accent-raw: rgba(248,162,31,.2);
        --hero-overlay:    linear-gradient(115deg, rgba(91,24,83,.92), rgba(163,0,113,.76) 48%, rgba(248,162,31,.62));
        --hero-wave-fill:  #fff;
    }
    .hero-international {
        --hero-accent:     #22c55e;
        --hero-accent-raw: rgba(34,197,94,.18);
        --hero-overlay:    linear-gradient(115deg, rgba(8,65,92,.92), rgba(29,118,127,.76) 50%, rgba(34,197,94,.56));
        --hero-wave-fill:  #fff;
    }
    .hero-xbet {
        --hero-accent:     #facc15;
        --hero-accent-raw: rgba(250,204,21,.18);
        --hero-overlay:    linear-gradient(115deg, rgba(20,83,45,.94), rgba(22,101,52,.78) 48%, rgba(250,204,21,.54));
        --hero-wave-fill:  #fff;
    }
    .hero-mobile {
        --hero-accent:     #06b6d4;
        --hero-accent-raw: rgba(6,182,212,.18);
        --hero-overlay:    linear-gradient(115deg, rgba(49,46,129,.92), rgba(14,116,144,.76) 48%, rgba(6,182,212,.56));
        --hero-wave-fill:  #fff;
    }
    .hero-robus,
    .hero-default {
        --hero-accent:     #f8a21f;
        --hero-accent-raw: rgba(248,162,31,.2);
        --hero-overlay:    linear-gradient(115deg, rgba(92,15,88,.92), rgba(163,0,113,.76) 48%, rgba(248,162,31,.56));
        --hero-wave-fill:  #fff;
    }

    /* ── Responsive ─────────────────────────────────── */
    @media (max-width: 1199.98px) {
        .robus-hero-feature { width: clamp(200px, 24vw, 280px); }
        .robus-hero-shape   { display: none; }
    }

    @media (max-width: 991.98px) {
        .robus-hero-feature         { display: none; }
        .robus-hero-quicknav-label  { display: none; }
        .robus-hero-shape           { display: none; }
    }

    @media (max-width: 767.98px) {
        .robus-hero-quicknav   { display: none; }
        .robus-hero-back-btn   { display: inline-flex; }

        .robus-hero-breadcrumb .crumb-current {
            max-width: 150px;
            font-size: .74rem;
        }

        .robus-hero-stat { padding: 4px 10px; gap: 7px; }
        .robus-hero-stat-icon { width: 28px; height: 28px; font-size: .78rem; border-radius: 8px; }
        .robus-hero-stat-val  { font-size: .88rem; }
        .robus-hero-stat-lbl  { font-size: .66rem; }
    }

    @media (max-width: 575.98px) {
        .robus-hero-breadcrumb .crumb-link span { display: none; }
        .robus-hero-breadcrumb .crumb-link      { padding: 4px 7px; }

        .robus-hero-stat:nth-child(3) { display: none; }
        .robus-hero-stat:first-child  { padding-left: 0; }
    }
</style>

<section class="robus-hero {{ $theme['class'] }} animate__animated animate__slideInDown"
         style="background-image:url('{{ $background }}');"
         aria-label="En-tête de page — {{ $title }}">

    {{-- Orbes décoratifs ──────────────────────────── --}}
    <div class="robus-hero-orb robus-hero-orb-1" aria-hidden="true"></div>
    <div class="robus-hero-orb robus-hero-orb-2" aria-hidden="true"></div>
    <div class="robus-hero-orb robus-hero-orb-3" aria-hidden="true"></div>

    {{-- Forme décorative ─────────────────────────── --}}
    <div class="robus-hero-shape" aria-hidden="true">
        <i class="{{ $themeIcon }}"></i>
    </div>

    {{-- ── Barre breadcrumb / nav rapide ── --}}
    <div class="robus-hero-topbar">
        <div class="container">
            <div class="robus-hero-topbar-inner">

                <nav aria-label="Fil d'Ariane">
                    <ol class="robus-hero-breadcrumb">
                        <li>
                            <a href="https://robus.africa/" class="crumb-link">
                                <i class="bi bi-house-door-fill"></i>
                                <span>Accueil</span>
                            </a>
                        </li>
                        @if ($breadcrumb)
                            <li class="crumb-sep" aria-hidden="true"><i class="bi bi-chevron-right"></i></li>
                            <li>
                                <a href="{{ $breadcrumb['href'] }}" class="crumb-link">
                                    <i class="bi {{ $breadcrumb['icon'] }}"></i>
                                    <span>{{ $breadcrumb['label'] }}</span>
                                </a>
                            </li>
                        @endif
                        <li class="crumb-sep" aria-hidden="true"><i class="bi bi-chevron-right"></i></li>
                        <li>
                            <span class="crumb-current" title="{{ $title }}">
                                <i class="{{ $themeIcon }}"></i>
                                {{ $title }}
                            </span>
                        </li>
                    </ol>
                </nav>

                <div class="robus-hero-quicknav" aria-label="Navigation rapide">
                    <span class="robus-hero-quicknav-label">Aller à</span>
                    @php
                        $quickLinks = [
                            ['label' => 'Transfert',   'icon' => 'bi-send-check', 'route' => 'web.transactions_transactions'],
                            ['label' => 'ROBUS',        'icon' => 'bi-wallet2',   'route' => 'web.transactions_robus'],
                            ['label' => '1XBET',        'icon' => 'bi-trophy',    'route' => 'web.transactions_xbets'],
                            ['label' => 'International','icon' => 'bi-globe2',    'route' => 'web.transactions_inters'],
                        ];
                        $currentRoute = Route::current()->getName();
                    @endphp
                    @foreach ($quickLinks as $ql)
                        <a href="{{ route($ql['route']) }}"
                           class="{{ $currentRoute === $ql['route'] ? 'active' : '' }}"
                           @if ($currentRoute === $ql['route']) aria-current="page" @endif>
                            <i class="bi {{ $ql['icon'] }}"></i>
                            {{ $ql['label'] }}
                        </a>
                    @endforeach
                </div>

                @if ($breadcrumb)
                    <a href="{{ $breadcrumb['href'] }}" class="robus-hero-back-btn">
                        <i class="bi bi-arrow-left"></i>Retour
                    </a>
                @endif

            </div>
        </div>
    </div>

    {{-- ── Contenu central ── --}}
    <div class="robus-hero-inner">
        <div class="container">
            <div class="robus-hero-row">

                {{-- Colonne texte ────────────────────── --}}
                <div class="robus-hero-content">

                    <div class="robus-hero-eyebrow animate__animated animate__fadeInUp">
                        <span class="robus-hero-eyebrow-dot" aria-hidden="true"></span>
                        <span class="robus-hero-eyebrow-icon">
                            <i class="{{ $themeIcon }}"></i>
                        </span>
                        <span>{{ $eyebrow ?? $theme['eyebrow'] }}</span>
                    </div>

                    <h1 class="animate__animated animate__fadeInUp animate__delay-1s">
                        {{ $title }}
                    </h1>

                    <p class="robus-hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                        {{ $subtitle }}
                    </p>

                    @if (isset($badgeText) && $badgeText)
                        <div class="robus-hero-actions animate__animated animate__fadeInUp animate__delay-2s">
                            <div class="robus-hero-badge">
                                <i class="{{ $badgeIconClass }}"></i>
                                <span>{{ $badgeText }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="robus-hero-meta animate__animated animate__fadeInUp animate__delay-2s">
                        @foreach (($metaItems ?? $theme['meta']) as $metaItem)
                            <span>
                                <i class="bi bi-check2-circle"></i>
                                {{ $metaItem }}
                            </span>
                        @endforeach
                    </div>

                </div>

                {{-- Carte feature droite (desktop ≥ lg) ──── --}}
                <div class="robus-hero-feature animate__animated animate__fadeInRight animate__delay-1s">
                    <div class="robus-hero-feature-card">
                        <div class="robus-hero-feature-icon">
                            <i class="{{ $themeIcon }}"></i>
                        </div>
                        <div class="robus-hero-feature-title">{{ $heroFeature['title'] }}</div>
                        <div class="robus-hero-feature-list">
                            @foreach ($heroFeature['items'] as $item)
                                <div class="robus-hero-feature-item">
                                    <span class="robus-hero-feature-item-icon">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </span>
                                    <span>{{ $item['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ── Bande de stats ── --}}
    <div class="robus-hero-stats animate__animated animate__fadeInUp animate__delay-2s">
        <div class="container">
            <div class="robus-hero-stats-inner">
                @php
                    $statIcons = ['bi-stars', 'bi-lightning-charge-fill', 'bi-shield-check-fill'];
                @endphp
                @foreach ($heroStats as $i => $stat)
                    <div class="robus-hero-stat">
                        <div class="robus-hero-stat-icon">
                            <i class="bi {{ $statIcons[$i] ?? 'bi-check-circle-fill' }}"></i>
                        </div>
                        <div>
                            <div class="robus-hero-stat-val">{{ $stat['val'] }}</div>
                            <div class="robus-hero-stat-lbl">{{ $stat['lbl'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── Vague bas ── --}}
    <div class="robus-hero-wave" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 52" preserveAspectRatio="none">
            <path fill="var(--hero-wave-fill, #fff)"
                  d="M0,22 C180,48 360,8 540,26 C720,44 900,10 1080,28 C1260,46 1380,18 1440,22 L1440,52 L0,52 Z"/>
        </svg>
    </div>

</section>
