<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Avis — {{ $wallet->sidUser->firstname ?? '' }} {{ $wallet->sidUser->lastname ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset_url('css/invoice.css') }}?v={{ filemtime(public_path('css/invoice.css')) }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style>
        @page { size: A4; margin: 15mm 12mm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; background: #fff; color: #2c3e50; font-size: 10pt; }

        /* ── Header entreprise ── */
        .header {
            border-bottom: 3px solid #3b5bdb;
            padding-bottom: 10px;
            margin-bottom: 16px;
            overflow: hidden;
        }
        .logo-area { float: left; width: 55%; }
        .logo-area img { height: 38px; }
        .company-info { font-size: 7.5pt; color: #64748b; margin-top: 4px; line-height: 1.6; }
        .title-area { float: right; width: 43%; text-align: right; }
        .title-area h1 { font-size: 15pt; color: #3b5bdb; margin: 0; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .title-area p { font-size: 8pt; color: #94a3b8; margin-top: 3px; }
        .clearfix::after { content: ''; display: table; clear: both; }

        /* ── Infos agent ── */
        .agent-box {
            background: #f0f4ff;
            border: 1.5px solid #c7d2fe;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 18px;
            overflow: hidden;
        }
        .agent-label { font-size: 7pt; color: #6366f1; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px; }
        .agent-name { font-size: 14pt; font-weight: 700; color: #1e293b; }
        .agent-meta { font-size: 8pt; color: #64748b; margin-top: 3px; }
        .agent-id-badge {
            float: right;
            background: #3b5bdb;
            color: #fff;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 9pt;
            font-weight: 700;
            text-align: center;
        }
        .agent-id-badge span { display: block; font-size: 7pt; font-weight: 400; opacity: 0.85; }

        /* ── Zone QR ── */
        .qr-section {
            text-align: center;
            padding: 20px 0 16px;
            border-top: 1.5px dashed #c7d2fe;
            border-bottom: 1.5px dashed #c7d2fe;
            margin-bottom: 18px;
        }
        .qr-call-to-action {
            font-size: 16pt;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }
        .qr-subtitle {
            font-size: 9pt;
            color: #64748b;
            margin-bottom: 18px;
        }
        .qr-image {
            width: 200px;
            height: 200px;
            object-fit: contain;
            border: 3px solid #e0e7ff;
            border-radius: 10px;
            padding: 6px;
        }
        .qr-url {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 10px;
            word-break: break-all;
        }

        /* ── Message informatif ── */
        .info-banner {
            border-left: 4px solid #3b5bdb;
            background: #f8faff;
            padding: 10px 14px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 16px;
        }
        .info-banner strong { color: #3b5bdb; font-size: 10pt; }
        .info-banner p { color: #475569; font-size: 8.5pt; margin-top: 3px; line-height: 1.5; }

        /* ── Étapes ── */
        .steps { margin-bottom: 16px; }
        .step { display: flex; align-items: flex-start; margin-bottom: 8px; font-size: 8.5pt; }
        .step-num {
            min-width: 20px; height: 20px;
            border-radius: 50%;
            background: #3b5bdb;
            color: #fff;
            font-weight: 700;
            font-size: 7.5pt;
            display: flex; align-items: center; justify-content: center;
            margin-right: 8px;
            margin-top: 1px;
        }
        .step-text { color: #374151; line-height: 1.4; }

        /* ── Stars déco ── */
        .stars { text-align: center; font-size: 18pt; color: #f59e0b; margin: 4px 0 12px; letter-spacing: 4px; }

        /* ── Stores ── */
        .stores-section {
            border: 1.5px solid #e0e7ff;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 14px;
            text-align: center;
            background: #fafbff;
        }
        .stores-title {
            font-size: 8pt;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 10px;
        }
        .stores-row { overflow: hidden; }
        .store-btn {
            display: inline-block;
            width: 46%;
            margin: 0 1.5%;
            border-radius: 8px;
            padding: 8px 10px;
            text-align: left;
            text-decoration: none;
            overflow: hidden;
        }
        .store-btn.android { background: #1a1a1a; color: #fff; }
        .store-btn.apple   { background: #000;    color: #fff; }
        .store-icon { float: left; font-size: 20pt; line-height: 1; margin-right: 8px; margin-top: 2px; }
        .store-sub  { font-size: 6.5pt; opacity: 0.75; display: block; }
        .store-name { font-size: 9.5pt; font-weight: 700; display: block; margin-top: 1px; }
        .store-url  { font-size: 6pt; color: #94a3b8; margin-top: 6px; word-break: break-all; display: block; }
        .clearfix::after { content:''; display:table; clear:both; }

        /* ── Footer ── */
        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 10px;
        }
        .footer strong { color: #64748b; }
    </style>
</head>
<body>
    <div class="invoice">

        {{-- ── Header entreprise ── --}}
        <div class="header">
            <div class="logo-area">
                <img src="{{ asset('dist/images/logo_orange.png') }}" alt="{{ env('APP_NAME') }}">
                <div class="company-info">
                    {{ env('APP_ADDRESS', '') }}<br>
                    Tél&nbsp;: {{ env('PHONE_USE', 'N/A') }} &nbsp;|&nbsp; Email&nbsp;: {{ env('EMAIL_USER', 'N/A') }}
                </div>
            </div>
            <div class="title-area">
                <h1>Avis Client</h1>
                <p>Donnez votre opinion</p>
            </div>
            <div class="clearfix"></div>
        </div>

        {{-- ── Infos sous-distributeur ── --}}
        <div class="agent-box">
           
            <div class="agent-label">Sous-distributeur</div>
            {{-- <div class="agent-name">
                {{ $wallet->sidUser->firstname ?? '' }} {{ $wallet->sidUser->lastname ?? '' }}
            </div> --}}
            {{-- <div class="agent-meta">
                @if($wallet->sidUser->phone ?? false) Tél&nbsp;: {{ $wallet->sidUser->phone }} &nbsp;·&nbsp; @endif
                {{ $wallet->sidUser->country->name ?? '' }}
                @if($wallet->sidUser->city ?? false) — {{ $wallet->sidUser->city->name }} @endif
            </div> --}}
            <div class="clearfix"></div>
        </div>

        {{-- ── QR Code ── --}}
        <div class="qr-section">
            <div class="qr-call-to-action">Scannez &amp; donnez votre avis !</div>
            <div class="qr-subtitle">Votre opinion nous aide à améliorer la qualité de service</div>
            <div class="stars">★ ★ ★ ★ ★</div>
            <img src="{{ asset($wallet->qr_comment) }}" alt="QR Code Avis" class="qr-image">
            <div class="qr-url">{{ url('/avis/' . $wallet->wallet_id) }}</div>
        </div>

        {{-- ── Message client ── --}}
        <div class="info-banner">
            <strong>Votre avis compte !</strong>
            <p>
                En scannant ce QR code, vous pouvez nous faire part de votre expérience avec cet agent directement, sans avoir besoin de télécharger
                une application ou de créer un compte.
            </p>
        </div>

        {{-- ── Comment ça marche ── --}}
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Ouvrez l'appareil photo</strong> de votre smartphone et pointez-le vers le QR code ci-dessus.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Appuyez sur le lien</strong> qui apparaît automatiquement sur votre écran.</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Rédigez votre commentaire</strong> et partagez votre expérience en quelques secondes.</div>
            </div>
        </div>

        {{-- ── Télécharger l'application ── --}}
        @php
            $playstoreUrl = env('PLAYSTORE_URL');
            $appstoreUrl  = env('APPSTORE_URL');
        @endphp
        @if($playstoreUrl || $appstoreUrl)
        <div class="stores-section">
            <div class="stores-title">Téléchargez l'application {{ env('APP_NAME', 'ROBUS') }}</div>
            <div class="stores-row">
                @if($playstoreUrl)
                <a href="{{ $playstoreUrl }}" class="store-btn android">
                    <div class="store-icon">&#9654;</div>
                    <div>
                        <span class="store-sub">Disponible sur</span>
                        <span class="store-name">Google Play</span>
                    </div>
                </a>
                @endif
                @if($appstoreUrl)
                <a href="{{ $appstoreUrl }}" class="store-btn apple">
                    <div class="store-icon">&#xf8ff;</div>
                    <div>
                        <span class="store-sub">Télécharger dans l'</span>
                        <span class="store-name">App Store</span>
                    </div>
                </a>
                @endif
                <div class="clearfix"></div>
            </div>
            @if($playstoreUrl)
                <span class="store-url">Android : {{ $playstoreUrl }}</span>
            @endif
            @if($appstoreUrl)
                <span class="store-url">iOS : {{ $appstoreUrl }}</span>
            @endif
        </div>
        @else
        <div class="stores-section">
            <div class="stores-title">Téléchargez l'application {{ env('APP_NAME', 'ROBUS') }}</div>
            <div class="stores-row">
                <span class="store-btn android" style="display:inline-block;">
                    <div class="store-icon">&#9654;</div>
                    <div>
                        <span class="store-sub">Disponible sur</span>
                        <span class="store-name">Google Play</span>
                    </div>
                </span>
                <span class="store-btn apple" style="display:inline-block;">
                    <div class="store-icon">&#xf8ff;</div>
                    <div>
                        <span class="store-sub">Télécharger dans l'</span>
                        <span class="store-name">App Store</span>
                    </div>
                </span>
                <div class="clearfix"></div>
            </div>
            <span class="store-url" style="color:#c7d2fe;">Liens disponibles prochainement</span>
        </div>
        @endif

        {{-- ── Footer ── --}}
        <div class="footer">
            <strong>{{ env('APP_NAME', 'ROBUS') }}</strong> — Plateforme de transfert d'argent &amp; services financiers<br>
            Document généré le {{ now()->format('d/m/Y') }} &nbsp;·&nbsp; Confidentiel
        </div>

    </div>

    <script type="text/javascript">
        try {
            this.print();
        } catch (e) {
            window.onload = window.print;
        }
        window.onbeforeprint = function () {
            setTimeout(function () { window.close(); }, 1500);
        };
    </script>
</body>
</html>
