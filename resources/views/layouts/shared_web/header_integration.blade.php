<nav class="navbar navbar-expand-lg fixed-top navbar-white style_header shadow-sm mb-6" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); backdrop-filter: blur(10px);">
    <div class="container">
        <!-- Logo avec effet hover -->
        <a class="navbar-brand" href="{{ url('/') }}" style="transition: all 0.3s ease;">
            <img src="https://robus.africa/wp-content/uploads/2023/07/LOGO_ROBUS-web.png" alt="Robus Africa"
                title="Robus Africa" height="50" class="navbar-logo" style="transition: transform 0.3s ease;">
            {{-- <span class="ms-2 fw-bold text-dark d-none d-md-inline" style="font-size: 1.2rem;">ROBUS</span> --}}
        </a>

        <!-- Bouton toggle mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu central (optionnel) -->
            <ul class="navbar-nav mx-auto d-none d-lg-flex">
                <li class="nav-item mx-2">
                    <a class="nav-link text-dark fw-medium hover-primary" href="#features">
                        <i class="fas fa-rocket me-1"></i>Fonctionnalités
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-dark fw-medium hover-primary" href="#pricing">
                        <i class="fas fa-tags me-1"></i>Tarifs
                    </a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-dark fw-medium hover-primary" href="#contact">
                        <i class="fas fa-headset me-1"></i>Support
                    </a>
                </li>
            </ul>

            <!-- Actions utilisateur -->
            <ul class="navbar-nav ms-auto align-items-center">               

                <!-- Séparateur -->
                <li class="nav-item me-3 d-none d-md-block">
                    <div class="vr" style="height: 24px; opacity: 0.3;"></div>
                </li>

                <!-- Bouton Connexion -->
                <li class="nav-item me-2">
                    <a href="{{ route('admin.login_integration') }}" 
                       class="btn btn-outline-primary btn-sm fw-medium d-flex align-items-center"
                       style="border-radius: 8px; padding: 8px 16px; transition: all 0.3s ease;">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        <span>Connexion</span>
                    </a>
                </li>

                <!-- Bouton Inscription -->
                <li class="nav-item">
                    <a href="{{ route('web.prospect_registers') }}" 
                       class="btn btn-primary btn-sm fw-medium d-flex align-items-center shadow-sm"
                       style="border-radius: 8px; padding: 8px 20px; transition: all 0.3s ease; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fas fa-user-plus me-2"></i>
                        <span>Inscription</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Style CSS amélioré -->
<style>
.navbar-brand:hover img {
    transform: scale(1.05);
}

.hover-primary {
    position: relative;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 6px;
}

.hover-primary:hover {
    color: #667eea !important;
    background-color: rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.btn-outline-primary:hover {
    background-color: #667eea;
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Animation pour le menu mobile */
@media (max-width: 991.98px) {
    .navbar-collapse {
        background: white;
        border-radius: 12px;
        margin-top: 1rem;
        padding: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .nav-item {
        margin: 0.5rem 0;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        margin: 0.25rem 0;
    }
}

/* Effet de soulignement pour les liens */
.nav-link {
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after {
    width: 80%;
}

/* Animation de la navbar au scroll */
.navbar-scrolled {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px);
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}
</style>

<!-- Script pour l'effet au scroll -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
});
</script>