<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Nouveau Prospect - Notification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 650px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            border: 1px solid #e8f1ff;
        }

        .header {
            background: linear-gradient(135deg, #004080 0%, #0066cc 100%);
            color: white;
            padding: 35px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #00c6ff 0%, #0072ff 100%);
        }

        .header h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header p {
            margin: 8px 0 0 0;
            font-size: 15px;
            font-weight: 400;
            opacity: 0.9;
        }

        .content {
            padding: 40px 35px;
            color: #333;
            font-size: 15px;
        }

        .info-card {
            background: #f8fbff;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e1eeff;
            position: relative;
        }

        .info-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #004080 0%, #0066cc 100%);
            border-radius: 4px 0 0 4px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin: 20px 0;
        }

        .detail-item {
            display: flex;
            align-items: flex-start;
            padding: 12px 0;
            border-bottom: 1px solid #f0f4f8;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 160px;
            font-weight: 600;
            color: #004080;
            font-size: 14px;
            flex-shrink: 0;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 500;
            flex: 1;
        }

        .badge {
            display: inline-block;
            background: #e3f2fd;
            color: #004080;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #bbdefb;
        }

        .description-section {
            margin: 30px 0;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #004080;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .section-title::before {
            content: "📋";
            margin-right: 8px;
            font-size: 18px;
        }

        .description-box {
            background: #f8fbff;
            border: 1px solid #e1eeff;
            border-radius: 12px;
            padding: 20px;
            color: #2c3e50;
            line-height: 1.7;
            white-space: pre-line;
            font-size: 14.5px;
        }

        .priority-indicator {
            display: inline-flex;
            align-items: center;
            background: #fff3e0;
            color: #e65100;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 10px;
            border: 1px solid #ffcc80;
        }

        .footer {
            text-align: center;
            padding: 25px;
            font-size: 13px;
            color: #666;
            background: #f8fbff;
            border-top: 1px solid #e8f1ff;
        }

        .footer p {
            margin: 5px 0;
        }

        .mt-6 {

            margin-top: 12px !important;
        }

        .mb-6 {
            margin-bottom: 12px !important
        }

        .mr-3 {
            margin-left: 20px !important;
            margin-right: 20px !important
        }

        .text-center {
            text-align: center
        }

        .app-name {
            color: #004080;
            font-weight: 700;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px auto;
                border-radius: 12px;
            }

            .header {
                padding: 25px 20px;
            }

            .content {
                padding: 25px 20px;
            }

            .info-card {
                padding: 20px;
            }

            .detail-item {
                flex-direction: column;
                gap: 5px;
            }

            .detail-label {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header mt-6">
            <a href="https://robus.home.ci/"
                style="box-sizing:border-box;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color:#3d4852;font-size:19px;font-weight:bold;text-decoration:none;
                    display:inline-block ; padding-top: 12px;width:10%;"
                target="_blank">
                <img src="https://robus.home.ci/public/dist/images/logo_orange.png" alt="" style="width:80%;">
            </a>
            <h2 class="text-center">🎯 Nouveau Prospect</h2>
            <p class="text-center">Un nouveau ticket a été créé dans votre système</p>
        </div>

        <!-- CONTENT -->
        <div class="content mb-6">

            <!-- Carte d'information principale -->
            <div class="info-card">
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Ticket ID</span>
                        <span class="detail-value">
                            <span class="badge">{{ $ticket }}</span>
                            <span class="priority-indicator">🔄 À traiter</span>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">🏢 Entreprise</span>
                        <span class="detail-value">{{ $name_firm }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">✉️ Email</span>
                        <span class="detail-value">{{ $email }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">📞 Téléphone</span>
                        <span class="detail-value">{{ $phone }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">📊 Secteur d'activité</span>
                        <span class="detail-value">{{ $sector }}</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="description-section">
                <div class="section-title mr-3">Description du besoin</div>
                <div class="description-box">
                    {{ $description }}
                </div>
            </div>

            <p style="margin-top: 30px; text-align: center; color: #666; font-size: 14px;">
                Envoyé depuis : <strong class="app-name">{{ config('app.name') }}</strong>
            </p>

        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }} — Tous droits réservés.</p>
            <p>Cet email a été généré automatiquement, merci de ne pas y répondre.</p>
        </div>

    </div>

</body>

</html>
