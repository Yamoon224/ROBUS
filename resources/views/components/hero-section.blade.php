<section class="page-title animate__animated animate__slideInDown position-relative overflow-hidden"
        style="background: linear-gradient(rgba(163, 0, 113, 0.8), rgba(248, 162, 31, 0.8)), url('{{ $backgroundImage }}') center/cover;">
        <div class="container text-center text-white">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3 animate__animated animate__fadeInUp">{{ $title }}</h1>
                    <p class="lead text-white mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                        {{ $subtitle }}
                    </p>
                    @if(isset($badgeText) && $badgeText)
                    <div
                        class="d-flex justify-content-center align-items-center animate__animated animate__fadeInUp animate__delay-2s">
                        <div class="badge bg-white text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                            <i class="{{ $badgeIcon ?? 'bi-shield-check' }} me-2 text-primary"></i>
                            {{ $badgeText }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>