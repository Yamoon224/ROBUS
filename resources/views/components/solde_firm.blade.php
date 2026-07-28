@once
    <style>
        .firm-balance-card {
            --firm-card-navy: #0d2d4d;
            --firm-card-blue: #174d7c;
            --firm-card-orange: #ff9d16;
            position: relative;
            isolation: isolate;
            overflow: hidden;
            margin-top: 1.25rem;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 22px;
            background:
                radial-gradient(circle at 9% 0%, rgba(255, 157, 22, .22), transparent 30%),
                linear-gradient(135deg, var(--firm-card-blue) 0%, var(--firm-card-navy) 72%);
            box-shadow: 0 18px 45px rgba(13, 45, 77, .18);
            color: #fff;
        }

        .firm-balance-card::before,
        .firm-balance-card::after {
            position: absolute;
            z-index: -1;
            content: "";
            border-radius: 50%;
            pointer-events: none;
        }

        .firm-balance-card::before {
            right: -82px;
            bottom: -145px;
            width: 310px;
            height: 310px;
            border: 1px solid rgba(255, 255, 255, .08);
            box-shadow:
                0 0 0 38px rgba(255, 255, 255, .025),
                0 0 0 76px rgba(255, 255, 255, .018);
        }

        .firm-balance-card::after {
            top: 24px;
            right: 32%;
            width: 7px;
            height: 7px;
            background: rgba(255, 197, 112, .65);
            box-shadow:
                35px 28px 0 rgba(255, 255, 255, .1),
                -18px 54px 0 rgba(255, 255, 255, .08);
        }

        .firm-balance-card__content {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 32px;
            min-height: 178px;
            padding: 30px 34px;
        }

        .firm-balance-card__main {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 20px;
        }

        .firm-balance-card__icon {
            display: grid;
            flex: 0 0 60px;
            width: 60px;
            height: 60px;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 18px;
            background: rgba(255, 255, 255, .09);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .08);
            color: #ffc66f;
            backdrop-filter: blur(8px);
        }

        .firm-balance-card__label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 7px;
            color: rgba(255, 255, 255, .68);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .025em;
        }

        .firm-balance-card__status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #49d89f;
            box-shadow: 0 0 0 4px rgba(73, 216, 159, .12);
        }

        .firm-balance-card__amount {
            display: flex;
            min-width: 0;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 8px;
            color: #fff;
            font-size: clamp(28px, 4vw, 39px);
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -.035em;
        }

        .firm-balance-card__currency {
            padding: 5px 9px;
            border: 1px solid rgba(255, 181, 74, .24);
            border-radius: 8px;
            background: rgba(255, 157, 22, .12);
            color: #ffc46d;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .04em;
            line-height: 1;
        }

        .firm-balance-card__meta {
            width: min(100%, 260px);
            padding: 18px 20px;
            border: 1px solid rgba(255, 255, 255, .11);
            border-radius: 16px;
            background: rgba(5, 25, 44, .2);
            backdrop-filter: blur(8px);
        }

        .firm-balance-card__meta-label {
            margin-bottom: 7px;
            color: rgba(255, 255, 255, .5);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .firm-balance-card__wallet {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #fff;
            font-size: 14px;
            font-weight: 650;
            letter-spacing: .025em;
            overflow-wrap: anywhere;
        }

        .firm-balance-card__wallet svg {
            flex: 0 0 auto;
            color: var(--firm-card-orange);
        }

        .firm-balance-card__footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 11px 34px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            background: rgba(4, 22, 39, .16);
            color: rgba(255, 255, 255, .52);
            font-size: 10px;
            font-weight: 500;
        }

        .firm-balance-card__footer-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 640px) {
            .firm-balance-card {
                border-radius: 18px;
            }

            .firm-balance-card__content {
                grid-template-columns: 1fr;
                gap: 22px;
                padding: 24px;
            }

            .firm-balance-card__main {
                align-items: flex-start;
                gap: 15px;
            }

            .firm-balance-card__icon {
                flex-basis: 48px;
                width: 48px;
                height: 48px;
                border-radius: 14px;
            }

            .firm-balance-card__amount {
                font-size: 27px;
            }

            .firm-balance-card__meta {
                width: 100%;
            }

            .firm-balance-card__footer {
                align-items: flex-start;
                flex-direction: column;
                padding: 12px 24px;
            }
        }
    </style>
@endonce

<section class="intro-y firm-balance-card" aria-labelledby="firm-balance-title">
    <div class="firm-balance-card__content">
        <div class="firm-balance-card__main">
            <div class="firm-balance-card__icon" aria-hidden="true">
                <svg width="29" height="29" viewBox="0 0 24 24" fill="none">
                    <rect x="2.5" y="5" width="19" height="14.5" rx="3" stroke="currentColor"
                        stroke-width="1.6" />
                    <path d="M2.5 9h15.8M16.2 11.2h5.3v4.5h-5.3a2.25 2.25 0 0 1 0-4.5Z"
                        stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                    <circle cx="16.4" cy="13.45" r=".65" fill="currentColor" />
                </svg>
            </div>

            <div>
                <div id="firm-balance-title" class="firm-balance-card__label">
                    <span class="firm-balance-card__status-dot" aria-hidden="true"></span>
                    Solde disponible de la compagnie
                </div>
                <div class="firm-balance-card__amount">
                    <span>{{ number_format((float) ($walletSolde ?? 0), 2, ',', ' ') }}</span>
                    <span class="firm-balance-card__currency">{{ $usersCountry ?? 'Devise' }}</span>
                </div>
            </div>
        </div>

        <div class="firm-balance-card__meta">
            <div class="firm-balance-card__meta-label">Compte principal</div>
            <div class="firm-balance-card__wallet">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor"
                        stroke-width="1.7" />
                    <path d="M7 10h6M7 14h3" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" />
                </svg>
                <span>{{ ($walletNumber ?? null) ?: 'Numéro non disponible' }}</span>
            </div>
        </div>
    </div>

    <div class="firm-balance-card__footer">
        <span class="firm-balance-card__footer-item">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 3 5 6v5c0 4.5 2.9 8.7 7 10 4.1-1.3 7-5.5 7-10V6l-7-3Z"
                    stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                <path d="m9.5 12 1.6 1.6 3.5-3.6" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Compte sécurisé ROBUS
        </span>
        <span class="firm-balance-card__footer-item">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 11a8 8 0 1 0-2.3 5.7M20 5v6h-6" stroke="currentColor"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Solde actualisé automatiquement
        </span>
    </div>
</section>
