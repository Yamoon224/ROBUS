<div class="company-login">
    <style>
        .company-login {
            --company-navy: #12365c;
            --company-navy-dark: #0b2745;
            --company-orange: #ff9810;
            --company-orange-dark: #e47c00;
            --company-text: #172033;
            --company-muted: #697386;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 36px 0;
            color: var(--company-text);
        }

        .company-login *,
        .company-login *::before,
        .company-login *::after {
            box-sizing: border-box;
        }

        .company-login__shell {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1.02fr) minmax(420px, .98fr);
            width: min(1060px, 100%);
            min-height: 640px;
            overflow: hidden;
            border: 1px solid rgba(18, 54, 92, .09);
            border-radius: 28px;
            background: #fff;
            box-shadow: 0 30px 80px rgba(16, 42, 70, .16);
        }

        .company-login__visual {
            position: relative;
            isolation: isolate;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 52px;
            color: #fff;
            background:
                radial-gradient(circle at 18% 13%, rgba(255, 152, 16, .28), transparent 30%),
                linear-gradient(145deg, var(--company-navy) 0%, var(--company-navy-dark) 100%);
        }

        .company-login__visual::before,
        .company-login__visual::after {
            position: absolute;
            z-index: -1;
            content: "";
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 50%;
        }

        .company-login__visual::before {
            right: -155px;
            bottom: -145px;
            width: 430px;
            height: 430px;
            box-shadow:
                0 0 0 55px rgba(255, 255, 255, .025),
                0 0 0 110px rgba(255, 255, 255, .018);
        }

        .company-login__visual::after {
            top: 165px;
            right: 54px;
            width: 80px;
            height: 80px;
            background: rgba(255, 152, 16, .08);
        }

        .company-login__brand {
            display: inline-flex;
            align-items: center;
            gap: 13px;
            width: fit-content;
            color: #fff;
            text-decoration: none;
        }

        .company-login__logo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, .85);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
        }

        .company-login__brand-name {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .company-login__brand-name small {
            display: block;
            margin-top: 1px;
            color: rgba(255, 255, 255, .65);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .company-login__visual-content {
            max-width: 410px;
            margin: 58px 0;
        }

        .company-login__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: #ffc266;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .company-login__eyebrow::before {
            width: 24px;
            height: 2px;
            content: "";
            border-radius: 999px;
            background: var(--company-orange);
        }

        .company-login__visual h2 {
            margin: 0;
            color: #fff;
            font-size: clamp(34px, 4vw, 48px);
            font-weight: 700;
            line-height: 1.12;
            letter-spacing: -.035em;
        }

        .company-login__visual p {
            max-width: 380px;
            margin: 22px 0 0;
            color: rgba(255, 255, 255, .72);
            font-size: 15px;
            line-height: 1.75;
        }

        .company-login__trust {
            display: flex;
            align-items: center;
            gap: 11px;
            color: rgba(255, 255, 255, .68);
            font-size: 12px;
            font-weight: 500;
        }

        .company-login__trust-icon {
            display: grid;
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 10px;
            background: rgba(255, 255, 255, .07);
        }

        .company-login__form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 58px clamp(38px, 5vw, 72px);
            background:
                radial-gradient(circle at 100% 0%, rgba(255, 152, 16, .07), transparent 27%),
                #fff;
        }

        .company-login__mobile-brand {
            display: none;
        }

        .company-login__heading {
            margin-bottom: 36px;
        }

        .company-login__heading h1 {
            margin: 0;
            color: var(--company-text);
            font-size: 31px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -.03em;
        }

        .company-login__heading p {
            margin: 10px 0 0;
            color: var(--company-muted);
            font-size: 14px;
            line-height: 1.65;
        }

        .company-login__field {
            margin-bottom: 22px;
        }

        .company-login__label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 9px;
        }

        .company-login__label {
            margin: 0;
            color: #263249;
            font-size: 13px;
            font-weight: 650;
        }

        .company-login__forgot {
            color: var(--company-orange-dark);
            font-size: 12px;
            font-weight: 650;
            text-decoration: none;
            transition: color .2s ease;
        }

        .company-login__forgot:hover {
            color: var(--company-navy);
            text-decoration: underline;
        }

        .company-login__input-wrap {
            position: relative;
        }

        .company-login__input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            width: 19px;
            height: 19px;
            color: #9aa5b5;
            pointer-events: none;
            transform: translateY(-50%);
            transition: color .2s ease;
        }

        .company-login__input {
            width: 100%;
            height: 52px;
            padding: 0 48px 0 47px;
            border: 1px solid #dde3eb;
            outline: 0;
            border-radius: 13px;
            background: #f9fafc;
            color: var(--company-text);
            font-family: inherit;
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .company-login__input::placeholder {
            color: #a2abba;
        }

        .company-login__input:hover {
            border-color: #c4ccd8;
        }

        .company-login__input:focus {
            border-color: var(--company-orange);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(255, 152, 16, .12);
        }

        .company-login__input-wrap:focus-within .company-login__input-icon {
            color: var(--company-orange-dark);
        }

        .company-login__input--invalid {
            border-color: #e05252;
            background: #fffafa;
        }

        .company-login__toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            display: grid;
            width: 38px;
            height: 38px;
            padding: 0;
            place-items: center;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #7d8899;
            cursor: pointer;
            transform: translateY(-50%);
            transition: color .2s ease, background .2s ease;
        }

        .company-login__toggle:hover,
        .company-login__toggle:focus-visible {
            outline: none;
            background: #eef2f6;
            color: var(--company-navy);
        }

        .company-login__error {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 7px 0 0;
            color: #c83f3f;
            font-size: 12px;
            line-height: 1.4;
        }

        .company-login__submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            height: 53px;
            margin-top: 9px;
            border: 0;
            border-radius: 13px;
            background: linear-gradient(135deg, #ffac2c, var(--company-orange));
            box-shadow: 0 12px 24px rgba(228, 124, 0, .22);
            color: #fff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
        }

        .company-login__submit:hover {
            box-shadow: 0 15px 28px rgba(228, 124, 0, .28);
            filter: saturate(1.08);
            transform: translateY(-1px);
        }

        .company-login__submit:focus-visible {
            outline: 3px solid rgba(255, 152, 16, .28);
            outline-offset: 3px;
        }

        .company-login__submit:disabled {
            cursor: wait;
            opacity: .78;
            transform: none;
        }

        .company-login__spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, .4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: company-login-spin .75s linear infinite;
        }

        .company-login__help {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin: 28px 0 0;
            color: #8a94a5;
            font-size: 11px;
            text-align: center;
        }

        [x-cloak] {
            display: none !important;
        }

        @keyframes company-login-spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 900px) {
            .company-login__shell {
                grid-template-columns: 1fr;
                width: min(520px, 100%);
                min-height: auto;
            }

            .company-login__visual {
                display: none;
            }

            .company-login__mobile-brand {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                width: fit-content;
                margin-bottom: 36px;
                color: var(--company-navy);
                font-size: 18px;
                font-weight: 700;
                text-decoration: none;
            }

            .company-login__mobile-brand img {
                width: 42px;
                height: 42px;
                object-fit: cover;
                border: 1px solid #f1dfca;
                border-radius: 13px;
            }
        }

        @media (max-width: 640px) {
            .company-login {
                min-height: 100dvh;
                padding: 0;
            }

            .company-login__shell {
                min-height: 100dvh;
                border: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .company-login__form-panel {
                justify-content: flex-start;
                padding: 36px 24px 30px;
            }

            .company-login__heading {
                margin-bottom: 30px;
            }

            .company-login__heading h1 {
                font-size: 27px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .company-login__submit,
            .company-login__input,
            .company-login__toggle {
                transition: none;
            }
        }
    </style>

    <main class="company-login__shell">
        <section class="company-login__visual" aria-label="Présentation de ROBUS">
            <a href="{{ route('/') }}" class="company-login__brand" aria-label="Retour à l'accueil ROBUS">
                <img
                    src="{{ asset_url('dist/images/logo_orange.png') }}"
                    class="company-login__logo"
                    alt="Logo ROBUS"
                >
                <span class="company-login__brand-name">
                    ROBUS
                    <small>Espace entreprise</small>
                </span>
            </a>

            <div class="company-login__visual-content">
                <span class="company-login__eyebrow">Portail sécurisé</span>
                <h2>Pilotez votre activité en toute simplicité.</h2>
                <p>
                    Retrouvez vos opérations, vos rapports et vos outils de gestion
                    dans un espace unique pensé pour votre entreprise.
                </p>
            </div>

            <div class="company-login__trust">
                <span class="company-login__trust-icon" aria-hidden="true">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none">
                        <path d="M12 3 5 6v5c0 4.55 2.95 8.79 7 10 4.05-1.21 7-5.45 7-10V6l-7-3Z"
                            stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <path d="m9.5 12 1.7 1.7 3.6-3.7" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                Connexion protégée et données sécurisées
            </div>
        </section>

        <section class="company-login__form-panel">
            <a href="{{ route('/') }}" class="company-login__mobile-brand" aria-label="Retour à l'accueil ROBUS">
                <img src="{{ asset_url('dist/images/logo_orange.png') }}" alt="Logo ROBUS">
                <span>ROBUS</span>
            </a>

            <div class="company-login__heading">
                <h1>Bienvenue</h1>
                <p>Connectez-vous à votre espace entreprise ROBUS.</p>
            </div>

            <form wire:submit.prevent="login" novalidate>
                <div class="company-login__field">
                    <div class="company-login__label-row">
                        <label for="company-email" class="company-login__label">Adresse e-mail</label>
                    </div>

                    <div class="company-login__input-wrap">
                        <svg class="company-login__input-icon" viewBox="0 0 24 24" fill="none"
                            aria-hidden="true">
                            <path d="M4 6.5h16v11H4v-11Z" stroke="currentColor" stroke-width="1.7"
                                stroke-linejoin="round" />
                            <path d="m5 8 7 5 7-5" stroke="currentColor" stroke-width="1.7"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <input
                            wire:model="email"
                            id="company-email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            inputmode="email"
                            class="company-login__input @error('email') company-login__input--invalid @enderror"
                            placeholder="nom@entreprise.com"
                            aria-describedby="@error('email') company-email-error @enderror"
                            @error('email') aria-invalid="true" @enderror
                            autofocus
                        >
                    </div>

                    @error('email')
                        <p id="company-email-error" class="company-login__error" role="alert">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                                <path d="M12 7.5v5M12 16.5h.01" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="company-login__field" x-data="{ passwordVisible: false }">
                    <div class="company-login__label-row">
                        <label for="company-password" class="company-login__label">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="company-login__forgot">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <div class="company-login__input-wrap">
                        <svg class="company-login__input-icon" viewBox="0 0 24 24" fill="none"
                            aria-hidden="true">
                            <rect x="5" y="10" width="14" height="10" rx="2" stroke="currentColor"
                                stroke-width="1.7" />
                            <path d="M8.5 10V7.5a3.5 3.5 0 1 1 7 0V10M12 14v2.5" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" />
                        </svg>
                        <input
                            wire:model="password"
                            id="company-password"
                            name="password"
                            :type="passwordVisible ? 'text' : 'password'"
                            autocomplete="current-password"
                            class="company-login__input @error('password') company-login__input--invalid @enderror"
                            placeholder="Saisissez votre mot de passe"
                            aria-describedby="@error('password') company-password-error @enderror"
                            @error('password') aria-invalid="true" @enderror
                        >
                        <button
                            type="button"
                            class="company-login__toggle"
                            @click="passwordVisible = !passwordVisible"
                            :aria-label="passwordVisible ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                            :title="passwordVisible ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
                        >
                            <svg x-show="!passwordVisible" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true">
                                <path d="M3 12s3.5-5 9-5 9 5 9 5-3.5 5-9 5-9-5-9-5Z"
                                    stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                <circle cx="12" cy="12" r="2.4" stroke="currentColor" stroke-width="1.7" />
                            </svg>
                            <svg x-cloak x-show="passwordVisible" width="19" height="19" viewBox="0 0 24 24"
                                fill="none" aria-hidden="true">
                                <path d="m4 4 16 16M10.7 7.1A9.4 9.4 0 0 1 12 7c5.5 0 9 5 9 5a14.8 14.8 0 0 1-2.1 2.4M15.1 16.5A8.8 8.8 0 0 1 12 17c-5.5 0-9-5-9-5a15.7 15.7 0 0 1 3.1-3.5"
                                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p id="company-password-error" class="company-login__error" role="alert">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                                <path d="M12 7.5v5M12 16.5h.01" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="company-login__submit"
                    wire:loading.attr="disabled"
                    wire:target="login"
                >
                    <span wire:loading.remove wire:target="login">Se connecter</span>
                    <span wire:loading.flex wire:target="login" style="align-items: center; gap: 10px;">
                        <span class="company-login__spinner" aria-hidden="true"></span>
                        Vérification en cours…
                    </span>
                    <svg wire:loading.remove wire:target="login" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" aria-hidden="true">
                        <path d="M5 12h14m-5-5 5 5-5 5" stroke="currentColor" stroke-width="1.9"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </form>

            <p class="company-login__help">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                    <path d="M9.8 9.2a2.3 2.3 0 1 1 3.3 2.1c-.8.4-1.1.9-1.1 1.7M12 16.5h.01"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
                Besoin d’aide ? Contactez votre administrateur ROBUS.
            </p>
        </section>
    </main>
</div>
