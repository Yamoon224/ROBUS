<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Animate.css pour les animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --success-color: #198754;
            --warning-color: #fd7e14;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center; /* AJOUTÉ */
            padding: 20px;
            margin: 0;
        }

        .payment-card {
            max-width: 500px; /* RÉDUIT pour mieux centrer */
            width: 100%;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .payment-card:hover {
            transform: translateY(-5px);
        }

        .success-header {
            background: linear-gradient(135deg, var(--success-color), #2ea44f);
            padding: 40px 20px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .success-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.3;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0);
            }

            100% {
                transform: translateY(-30px) translateX(-30px);
            }
        }

        .success-icon {
            font-size: 4rem; /* RÉDUIT */
            margin-bottom: 20px;
            animation: bounceIn 1s ease;
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: gold;
            border-radius: 50%;
            animation: confetti-fall 5s linear infinite;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100px) rotate(0deg);
                opacity: 1;
            }

            100% {
                transform: translateY(800px) rotate(720deg);
                opacity: 0;
            }
        }

        .amount-display {
            font-size: 2.5rem; /* RÉDUIT */
            font-weight: 800;
            margin: 20px 0;
            color: #212529;
            letter-spacing: -1px;
        }

        .info-card {
            border: none;
            border-radius: 15px;
            background: white;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .info-icon {
            width: 40px; /* RÉDUIT */
            height: 40px; /* RÉDUIT */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px; /* RÉDUIT */
            font-size: 1.2rem; /* RÉDUIT */
            margin-right: 12px; /* RÉDUIT */
        }

        .timeline {
            position: relative;
            padding-left: 25px; /* RÉDUIT */
            margin: 20px 0; /* RÉDUIT */
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px; /* RÉDUIT */
            top: 0;
            bottom: 0;
            width: 2px; /* RÉDUIT */
            background: linear-gradient(to bottom, var(--success-color), var(--primary-color));
            border-radius: 2px; /* RÉDUIT */
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px; /* RÉDUIT */
            padding-left: 15px; /* RÉDUIT */
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -25px; /* RÉDUIT */
            top: 5px;
            width: 16px; /* RÉDUIT */
            height: 16px; /* RÉDUIT */
            border-radius: 50%;
            border: 3px solid white; /* RÉDUIT */
            background: var(--success-color);
            box-shadow: 0 0 0 2px var(--success-color); /* RÉDUIT */
        }

        .btn-payment {
            padding: 12px 24px; /* RÉDUIT */
            font-weight: 600;
            border-radius: 10px; /* RÉDUIT */
            transition: all 0.3s ease;
            font-size: 1rem; /* RÉDUIT */
        }

        .btn-download {
            background: linear-gradient(135deg, var(--primary-color), #0b5ed7);
            color: white;
            border: none;
            width: 100%; /* AJOUTÉ */
            max-width: 300px; /* AJOUTÉ */
        }

        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }

        /* Animation pour les éléments */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            animation: slideInUp 0.6s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .delay-4 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-card {
                margin: 0 15px;
            }
            
            .amount-display {
                font-size: 2rem;
            }

            .success-icon {
                font-size: 3.5rem;
            }
            
            body {
                padding: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .success-header {
                padding: 30px 15px;
            }
            
            .payment-card .p-4.p-md-5 {
                padding: 20px !important;
            }
            
            .amount-display {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- SOLUTION SIMPLIFIÉE -->
    <div class="container">
        <div class="row justify-content-center">
            <!-- UNE SEULE COLONNE CENTRÉE -->
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="payment-card bg-white animate__animated animate__fadeIn">
                    <!-- En-tête de succès -->
                    <div class="success-header position-relative">
                        <div id="confetti-container"></div>
                        <div class="position-relative z-1 text-center">
                            <div class="success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h1 class="display-6 fw-bold mb-3">Paiement Réussi !</h1>
                            <p class="fs-6 opacity-90 mb-3">
                                Votre transaction a été traitée avec succès
                            </p>
                            <div class="d-flex justify-content-center flex-wrap gap-2">
                                <span class="badge bg-light text-success fs-7 py-2 px-3">
                                    <i class="fas fa-shield-alt me-2"></i>Sécurisé
                                </span>
                                <span class="badge bg-light text-primary fs-7 py-2 px-3">
                                    <i class="fas fa-bolt me-2"></i>Instantané
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="p-4 p-md-4">
                        <!-- Montant -->
                        <div class="text-center mb-4 animate-slide-up">
                            <div class="text-muted mb-2">Montant payé</div>
                            <div class="amount-display text-success">
                                {{ number_format($transaction->amount, 2, ',', ' ') }} FCFA
                            </div>
                            <div class="text-muted mt-2 small">
                                <i class="fas fa-calendar-check me-1"></i>
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                        <!-- Détails transaction -->
                        <div class="animate-slide-up delay-1">
                            <div class="info-card p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="info-icon bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold fs-5">Détails transaction</h5>
                                </div>
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <h6 class="fw-bold mb-1 fs-6">Référence</h6>
                                        <p class="text-muted mb-0 small">{{ $transaction->reference }}</p>
                                    </div>
                                    <div class="timeline-item">
                                        <h6 class="fw-bold mb-1 fs-6">Type</h6>
                                        <p class="text-muted mb-0 small">{{ $transaction->payment_method }}</p>
                                    </div>
                                    <div class="timeline-item">
                                        <h6 class="fw-bold mb-1 fs-6">Statut</h6>
                                        <p class="text-success mb-0 small">
                                            <i class="fas fa-check-circle me-1"></i> Complétée
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 text-center animate-slide-up delay-2">
                            <button class="btn btn-download btn-payment" onclick="downloadReceipt()">
                                <i class="fas fa-download me-2"></i> Télécharger le reçu
                            </button>
                            
                            <!-- Bouton retour (optionnel) -->
                            <div class="mt-3">
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Retour à l'accueil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Créer des confettis
        function createConfetti() {
            const container = document.getElementById('confetti-container');
            if (!container) return;
            
            const colors = ['#FFD700', '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7'];

            for (let i = 0; i < 30; i++) { // Réduit le nombre
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.width = Math.random() * 8 + 3 + 'px';
                confetti.style.height = confetti.style.width;

                container.appendChild(confetti);

                // Supprimer après animation
                setTimeout(() => {
                    confetti.remove();
                }, 3000);
            }
        }

        // Télécharger le reçu
        function downloadReceipt() {
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Génération...';
            btn.disabled = true;

            // Simuler le téléchargement
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check me-2"></i> Téléchargé';
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                }, 2000);

                // Ici, vous ajouteriez la logique réelle de téléchargement
                // Exemple: window.open('/download/receipt/{{ $transaction->id }}', '_blank');
                console.log('Téléchargement du reçu pour la transaction #{{ $transaction->id }}');
            }, 1500);
        }

        // Animation d'entrée des éléments
        document.addEventListener('DOMContentLoaded', function() {
            // Créer des confettis
            createConfetti();
            
            // Réduire la fréquence des confettis
            setInterval(createConfetti, 5000);

            // Animer les éléments
            const animatedElements = document.querySelectorAll('.animate-slide-up');
            animatedElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>