<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Code — {{ env('APP_NAME') }} — Site Web</title>
    <link rel="stylesheet" href="{{ asset_url('css/invoice.css') }}?v={{ filemtime(public_path('css/invoice.css')) }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style>
        @page { size: A4; margin: 15mm 12mm; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; background: #fff; color: #2c3e50; font-size: 10pt; }

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

        .website-box {
            background: #f0f4ff;
            border: 1.5px solid #c7d2fe;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 18px;
            overflow: hidden;
        }
        .website-label { font-size: 7pt; color: #6366f1; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 6px; }
        .website-name { font-size: 16pt; font-weight: 700; color: #1e293b; }
        .website-meta { font-size: 8.5pt; color: #64748b; margin-top: 4px; }
        .website-badge {
            float: right;
            background: #3b5bdb;
            color: #fff;
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 8.5pt;
            font-weight: 700;
            text-align: center;
        }
        .website-badge span { display: block; font-size: 7pt; font-weight: 400; opacity: 0.85; }

        .qr-section {
            text-align: center;
            padding: 24px 0 20px;
            border-top: 1.5px dashed #c7d2fe;
            border-bottom: 1.5px dashed #c7d2fe;
            margin-bottom: 18px;
        }
        .qr-call-to-action {
            font-size: 17pt;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }
        .qr-subtitle { font-size: 9pt; color: #64748b; margin-bottom: 18px; }
        .qr-image {
            width: 210px;
            height: 210px;
            object-fit: contain;
            border: 3px solid #e0e7ff;
            border-radius: 10px;
            padding: 6px;
        }
        .qr-url { font-size: 7.5pt; color: #94a3b8; margin-top: 10px; word-break: break-all; }

        .globe-icon { font-size: 28pt; margin-bottom: 6px; }

        .info-banner {
            border-left: 4px solid #3b5bdb;
            background: #f8faff;
            padding: 10px 14px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 16px;
        }
        .info-banner strong { color: #3b5bdb; font-size: 10pt; }
        .info-banner p { color: #475569; font-size: 8.5pt; margin-top: 3px; line-height: 1.5; }

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

        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 10px;
        }
        .footer strong { color: #64748b; }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="invoice">

        {{-- Header --}}
        <div class="header">
            <div class="logo-area">
                <img src="{{ asset('dist/images/logo_orange.png') }}" alt="{{ env('APP_NAME') }}">
                <div class="company-info">
                    {{ env('APP_ADDRESS', '') }}<br>
                    Tél&nbsp;: {{ env('PHONE_USE', 'N/A') }} &nbsp;|&nbsp; Email&nbsp;: {{ env('EMAIL_USER', 'N/A') }}
                </div>
            </div>
            <div class="title-area">
                <h1>QR Code Site Web</h1>
                <p>Accédez à nos services en ligne</p>
            </div>
            <div class="clearfix"></div>
        </div>

        {{-- Site info box --}}
        <div class="website-box">
            <div class="website-badge">
                <span>Site web</span>
                robus.africa
            </div>
            <div class="website-label">Destination</div>
            <div class="website-name">{{ env('APP_NAME', 'ROBUS') }}</div>
            <div class="website-meta">
                Plateforme de transfert d'argent &amp; services financiers &mdash;
                <strong>{{ env('WEBSITE_URL', 'https://robus.africa') }}</strong>
            </div>
            <div class="clearfix"></div>
        </div>

        {{-- QR Code --}}
        <div class="qr-section">
            <div class="globe-icon">🌍</div>
            <div class="qr-call-to-action">Scannez pour accéder au site web !</div>
            <div class="qr-subtitle">Effectuez vos opérations directement depuis votre smartphone</div>
            <img src="{{ asset($qrPath) }}" alt="QR Code Site Web" class="qr-image">
            <div class="qr-url">{{ env('WEBSITE_URL', 'https://robus.africa') }}</div>
        </div>

        {{-- Info message --}}
        <div class="info-banner">
            <strong>Accédez à tous nos services en ligne !</strong>
            <p>
                En scannant ce QR code avec votre smartphone, vous accédez directement au site web de {{ env('APP_NAME', 'ROBUS') }}
                pour effectuer des transferts d'argent, consulter votre solde, et profiter de tous nos services financiers —
                sans file d'attente, disponible 24h/24.
            </p>
        </div>

        {{-- Steps --}}
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Ouvrez l'appareil photo</strong> de votre smartphone et pointez-le vers le QR code ci-dessus.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>Appuyez sur le lien</strong> qui apparaît automatiquement — vous serez redirigé vers le site web.</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Effectuez vos opérations</strong> en toute sécurité depuis notre plateforme en ligne.</div>
            </div>
        </div>

        {{-- Store badges --}}
        <div class="stores-section">
            <div class="stores-title">Téléchargez aussi notre application mobile</div>
            <div class="stores-row">
                @if(env('PLAYSTORE_URL'))
                <a class="store-btn android" href="{{ env('PLAYSTORE_URL') }}">
                    <span class="store-icon">▶</span>
                    <span class="store-sub">Disponible sur</span>
                    <span class="store-name">Google Play</span>
                    <span class="store-url">{{ env('PLAYSTORE_URL') }}</span>
                    <div class="clearfix"></div>
                </a>
                @else
                <span class="store-btn android" style="opacity:0.5;">
                    <span class="store-icon">▶</span>
                    <span class="store-sub">Bientôt sur</span>
                    <span class="store-name">Google Play</span>
                    <div class="clearfix"></div>
                </span>
                @endif

                @if(env('APPSTORE_URL'))
                <a class="store-btn apple" href="{{ env('APPSTORE_URL') }}">
                    <span class="store-icon"></span>
                    <span class="store-sub">Disponible sur</span>
                    <span class="store-name">App Store</span>
                    <span class="store-url">{{ env('APPSTORE_URL') }}</span>
                    <div class="clearfix"></div>
                </a>
                @else
                <span class="store-btn apple" style="opacity:0.5;">
                    <span class="store-icon"></span>
                    <span class="store-sub">Bientôt sur</span>
                    <span class="store-name">App Store</span>
                    <div class="clearfix"></div>
                </span>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <strong>{{ env('APP_NAME', 'ROBUS') }}</strong> &mdash; {{ env('APP_ADDRESS', '') }} &mdash;
            {{ env('PHONE_USE', '') }} &mdash; {{ env('EMAIL_USER', '') }}<br>
            <span>{{ env('WEBSITE_URL', 'https://robus.africa') }}</span>
        </div>

    </div>

    <script>
        window.onload = function () { window.print(); };
    </script>
</body>
</html>
