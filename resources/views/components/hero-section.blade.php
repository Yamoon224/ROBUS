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
            'eyebrow' => 'Cartes prépayées',
            'icon' => 'bi-credit-card-2-front',
            'class' => 'hero-card',
            'meta' => ['Recharge carte', 'Validation sécurisée', 'Suivi ROBUS'],
        ],
        'international' => [
            'eyebrow' => 'Transferts internationaux',
            'icon' => 'bi-globe2',
            'class' => 'hero-international',
            'meta' => ['Multi-pays', 'Traitement assisté', 'Support ROBUS'],
        ],
        'xbet' => [
            'eyebrow' => 'Comptes de jeu',
            'icon' => 'bi-trophy',
            'class' => 'hero-xbet',
            'meta' => ['Compte 1XBet', 'Recharge rapide', 'Reçu de demande'],
        ],
        'mobile' => [
            'eyebrow' => 'Mobile money',
            'icon' => 'bi-phone',
            'class' => 'hero-mobile',
            'meta' => ['Dépôts', 'Transferts', 'Opérateurs locaux'],
        ],
        'robus' => [
            'eyebrow' => 'Wallet ROBUS',
            'icon' => 'bi-wallet2',
            'class' => 'hero-robus',
            'meta' => ['Achat UV', 'Solde ROBUS', 'Demande sécurisée'],
        ],
        'default' => [
            'eyebrow' => 'Services ROBUS',
            'icon' => 'bi-shield-check',
            'class' => 'hero-default',
            'meta' => ['Transaction sécurisée', 'Traitement rapide', 'Assistance disponible'],
        ],
    ];

    $theme = $heroThemes[$variant] ?? $heroThemes['default'];
    $background = $backgroundImage ?? asset('dist/images/transactions.jpeg');
    $themeIcon = str_contains($theme['icon'], 'bi ') ? $theme['icon'] : 'bi ' . $theme['icon'];
    $badgeIconClass = $badgeIcon ?? 'bi-shield-check';
    $badgeIconClass = str_contains($badgeIconClass, 'bi ') ? $badgeIconClass : 'bi ' . $badgeIconClass;
@endphp

<style>
    .robus-hero {
        position: relative;
        min-height: clamp(300px, 42vw, 460px);
        display: flex;
        align-items: center;
        overflow: hidden;
        isolation: isolate;
        background-position: center;
        background-size: cover;
    }

    .robus-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -2;
        background: var(--hero-overlay);
    }

    .robus-hero::after {
        content: "";
        position: absolute;
        inset: auto 0 0;
        height: 42%;
        z-index: -1;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0), #f0fcff 92%);
    }

    .robus-hero-inner {
        padding: clamp(64px, 8vw, 112px) 0 clamp(54px, 7vw, 92px);
    }

    .robus-hero-content {
        max-width: 850px;
    }

    .robus-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        margin-bottom: 18px;
        color: #fff;
        font-size: 0.86rem;
        font-weight: 700;
        letter-spacing: 0;
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(10px);
    }

    .robus-hero-icon {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--hero-accent);
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 12px 32px rgba(20, 24, 42, 0.22);
    }

    .robus-hero h1 {
        max-width: 760px;
        color: #fff;
        font-size: clamp(2.15rem, 5vw, 4.5rem);
        line-height: 1.02;
        font-weight: 800;
        letter-spacing: 0;
        text-wrap: balance;
        text-shadow: 0 18px 42px rgba(8, 13, 30, 0.34);
    }

    .robus-hero-subtitle {
        max-width: 620px;
        color: rgba(255, 255, 255, 0.88);
        font-size: clamp(1rem, 1.7vw, 1.22rem);
        line-height: 1.65;
    }

    .robus-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 26px;
    }

    .robus-hero-badge,
    .robus-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 14px;
        color: #111827;
        font-weight: 700;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.18);
    }

    .robus-hero-badge i {
        color: var(--hero-accent);
    }

    .robus-hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 22px;
    }

    .robus-hero-meta span {
        min-height: 36px;
        padding: 8px 12px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.86rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.13);
        box-shadow: none;
        backdrop-filter: blur(8px);
    }

    .robus-hero-shape {
        position: absolute;
        right: clamp(18px, 7vw, 120px);
        bottom: clamp(18px, 5vw, 70px);
        width: clamp(150px, 20vw, 260px);
        aspect-ratio: 1;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 22px;
        transform: rotate(8deg);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0.04));
        backdrop-filter: blur(10px);
    }

    .robus-hero-shape i {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.66);
        font-size: clamp(4rem, 10vw, 8rem);
        transform: rotate(-8deg);
    }

    .hero-card {
        --hero-accent: #f8a21f;
        --hero-overlay: linear-gradient(115deg, rgba(91, 24, 83, 0.9), rgba(163, 0, 113, 0.72) 48%, rgba(248, 162, 31, 0.66));
    }

    .hero-international {
        --hero-accent: #22c55e;
        --hero-overlay: linear-gradient(115deg, rgba(8, 65, 92, 0.9), rgba(29, 118, 127, 0.72) 50%, rgba(34, 197, 94, 0.58));
    }

    .hero-xbet {
        --hero-accent: #facc15;
        --hero-overlay: linear-gradient(115deg, rgba(20, 83, 45, 0.92), rgba(22, 101, 52, 0.76) 48%, rgba(250, 204, 21, 0.56));
    }

    .hero-mobile {
        --hero-accent: #06b6d4;
        --hero-overlay: linear-gradient(115deg, rgba(49, 46, 129, 0.9), rgba(14, 116, 144, 0.74) 48%, rgba(6, 182, 212, 0.58));
    }

    .hero-robus,
    .hero-default {
        --hero-accent: #f8a21f;
        --hero-overlay: linear-gradient(115deg, rgba(92, 15, 88, 0.9), rgba(163, 0, 113, 0.74) 48%, rgba(248, 162, 31, 0.58));
    }

    @media (max-width: 767.98px) {
        .robus-hero {
            min-height: 360px;
        }

        .robus-hero-inner {
            padding-top: 56px;
            padding-bottom: 76px;
        }

        .robus-hero-content {
            text-align: left;
            margin-left: 14px;
            margin-right: 8px;
            margin-top: 8px;
        }

        .robus-hero-actions,
        .robus-hero-meta {
            gap: 8px;
        }

        .robus-hero-shape {
            right: -44px;
            bottom: 14px;
            opacity: 0.38;
        }
    }
</style>

<section class="robus-hero {{ $theme['class'] }} animate__animated animate__slideInDown"
    style="background-image: url('{{ $background }}');">
    <div class="robus-hero-shape" aria-hidden="true">
        <i class="{{ $themeIcon }}"></i>
    </div>

    <div class="container robus-hero-inner">
        <div class="robus-hero-content">
            <div class="robus-hero-eyebrow animate__animated animate__fadeInUp mt-6">
                <span class="robus-hero-icon">
                    <i class="{{ $themeIcon }}"></i>
                </span>
                <span>{{ $eyebrow ?? $theme['eyebrow'] }}</span>
            </div>

            <h1 class="mb-3 animate__animated animate__fadeInUp">
                {{ $title }}
            </h1>

            <p class="robus-hero-subtitle mb-0 animate__animated animate__fadeInUp animate__delay-1s">
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
                        <i class="bi-check2-circle"></i>
                        {{ $metaItem }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</section>
