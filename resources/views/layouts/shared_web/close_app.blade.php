@php
    $todayClose = getAppCloseWeb();
    $tomorrowOpen = getAppCloseTomorowWeb();

    $todayStart = \Carbon\Carbon::createFromFormat('H:i:s', $todayClose->time_start ?? '00:00:00')->format('H:i');
    $todayEnd = \Carbon\Carbon::createFromFormat('H:i:s', $todayClose->time_end ?? '00:00:00')->format('H:i');
    $tomorrowStart = \Carbon\Carbon::createFromFormat('H:i:s', $tomorrowOpen->time_start ?? '00:00:00')->format('H:i');
    $tomorrowEnd = \Carbon\Carbon::createFromFormat('H:i:s', $tomorrowOpen->time_end ?? '00:00:00')->format('H:i');
@endphp

<style>
    .robus-close-service {
        width: 100%;
        overflow: hidden;
        border: 0;
        border-radius: 22px;
        background: #ffffff;
        box-shadow: 0 24px 70px rgba(15, 23, 42, 0.16);
    }

    .robus-close-head {
        position: relative;
        padding: clamp(26px, 4vw, 42px);
        color: #ffffff;
        background: linear-gradient(135deg, #7a155f 0%, #a30071 52%, #f8a21f 100%);
    }

    .robus-close-head::after {
        content: "";
        position: absolute;
        right: -42px;
        bottom: -54px;
        width: 170px;
        aspect-ratio: 1;
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 28px;
        transform: rotate(10deg);
        background: rgba(255, 255, 255, 0.12);
    }

    .robus-close-status {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-height: 42px;
        padding: 9px 14px;
        margin-bottom: 18px;
        border: 1px solid rgba(255, 255, 255, 0.26);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(10px);
        font-weight: 800;
    }

    .robus-close-title {
        position: relative;
        max-width: 720px;
        margin: 0;
        font-size: clamp(1.8rem, 4vw, 3.2rem);
        line-height: 1.06;
        font-weight: 850;
        letter-spacing: 0;
    }

    .robus-close-subtitle {
        position: relative;
        max-width: 680px;
        margin: 14px 0 0;
        color: rgba(255, 255, 255, 0.86);
        line-height: 1.7;
    }

    .robus-close-body {
        padding: clamp(22px, 4vw, 38px);
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }

    .robus-close-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .robus-close-panel {
        min-height: 100%;
        padding: 20px;
        border: 1px solid #eef2f7;
        border-radius: 18px;
        background: #ffffff;
    }

    .robus-close-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        color: #2b2030;
        font-size: 1rem;
        font-weight: 850;
    }

    .robus-close-panel-title i {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #a30071;
        border-radius: 12px;
        background: #fff4e2;
    }

    .robus-close-times {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .robus-close-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 12px;
        color: #2b2030;
        border-radius: 12px;
        background: #f8fafc;
        font-weight: 800;
    }

    .robus-close-chip strong {
        color: #a30071;
    }

    .robus-close-note {
        margin-top: 18px;
        padding: 16px 18px;
        color: #344054;
        border: 1px solid #e0f2fe;
        border-radius: 16px;
        background: #f0f9ff;
        line-height: 1.65;
    }

    .robus-countdown {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 22px;
    }

    .robus-countdown-box {
        padding: 16px 10px;
        text-align: center;
        border-radius: 16px;
        color: #ffffff;
        background: linear-gradient(135deg, #7a155f, #a30071);
    }

    .robus-countdown-value {
        display: block;
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        line-height: 1;
        font-weight: 900;
    }

    .robus-countdown-label {
        display: block;
        margin-top: 6px;
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.82rem;
        font-weight: 700;
    }

    .robus-close-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #eef2f7;
    }

    .robus-close-footer span {
        color: #667085;
        font-weight: 650;
    }

    .robus-close-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 16px;
        color: #ffffff;
        font-weight: 850;
        text-decoration: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #a30071, #f8a21f);
        box-shadow: 0 14px 34px rgba(163, 0, 113, 0.18);
    }

    .robus-close-action:hover {
        color: #ffffff;
    }

    @media (max-width: 767.98px) {
        .robus-close-grid,
        .robus-countdown {
            grid-template-columns: 1fr;
        }

        .robus-close-footer {
            align-items: stretch;
        }

        .robus-close-action {
            justify-content: center;
            width: 100%;
        }
    }
</style>

<div class="col-12">
    <div class="robus-close-service animate__animated animate__fadeInUp">
        <div class="robus-close-head">
            <div class="robus-close-status">
                <i class="bi bi-clock-history"></i>
                <span>Service temporairement indisponible</span>
            </div>
            <h2 class="robus-close-title">Les demandes ROBUS reprendront bientôt.</h2>
            <p class="robus-close-subtitle">
                Nos équipes restent mobilisées. Vous pouvez consulter les horaires ci-dessous et revenir à la prochaine ouverture.
            </p>
        </div>

        <div class="robus-close-body">
            <div class="robus-close-grid">
                <div class="robus-close-panel">
                    <div class="robus-close-panel-title">
                        <i class="bi bi-moon-stars"></i>
                        <span>Fermeture actuelle</span>
                    </div>
                    <div class="robus-close-times">
                        <span class="robus-close-chip">
                            Jour <strong>{{ $todayClose->day_of_week ?? 'N/A' }}</strong>
                        </span>
                        <span class="robus-close-chip">
                            De <strong>{{ $todayStart }}</strong>
                        </span>
                        <span class="robus-close-chip">
                            à <strong>{{ $todayEnd }}</strong>
                        </span>
                    </div>

                    @if ($todayClose->description ?? null)
                        <div class="robus-close-note">
                            <strong>Information :</strong> {{ $todayClose->description }}
                        </div>
                    @endif
                </div>

                <div class="robus-close-panel">
                    <div class="robus-close-panel-title">
                        <i class="bi bi-sunrise"></i>
                        <span>Prochaine reprise</span>
                    </div>
                    <div class="robus-close-times">
                        <span class="robus-close-chip">
                            Jour <strong>{{ $tomorrowOpen->day_of_week ?? 'N/A' }}</strong>
                        </span>
                        <span class="robus-close-chip">
                            De <strong>{{ $tomorrowStart }}</strong>
                        </span>
                        <span class="robus-close-chip">
                            à <strong>{{ $tomorrowEnd }}</strong>
                        </span>
                    </div>

                    <div class="robus-countdown" aria-label="Compte à rebours jusqu'à la prochaine ouverture">
                        <div class="robus-countdown-box">
                            <span class="robus-countdown-value" id="countdown-hours">--</span>
                            <span class="robus-countdown-label">Heures</span>
                        </div>
                        <div class="robus-countdown-box">
                            <span class="robus-countdown-value" id="countdown-minutes">--</span>
                            <span class="robus-countdown-label">Minutes</span>
                        </div>
                        <div class="robus-countdown-box">
                            <span class="robus-countdown-value" id="countdown-seconds">--</span>
                            <span class="robus-countdown-label">Secondes</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="robus-close-footer">
                <span>Pour une urgence ou une question, notre support reste joignable.</span>
                <a href="https://robus.africa/#contacts" class="robus-close-action">
                    <i class="bi bi-headset"></i>
                    Contacter le support
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hoursEl = document.getElementById('countdown-hours');
    const minutesEl = document.getElementById('countdown-minutes');
    const secondsEl = document.getElementById('countdown-seconds');

    if (!hoursEl || !minutesEl || !secondsEl) {
        return;
    }

    const nextOpening = new Date();
    nextOpening.setDate(nextOpening.getDate() + 1);
    nextOpening.setHours(
        {{ \Carbon\Carbon::createFromFormat('H:i:s', $tomorrowOpen->time_start ?? '00:00:00')->format('H') }},
        {{ \Carbon\Carbon::createFromFormat('H:i:s', $tomorrowOpen->time_start ?? '00:00:00')->format('i') }},
        0,
        0
    );

    function updateCountdown() {
        const diff = nextOpening - new Date();

        if (diff <= 0) {
            hoursEl.textContent = '00';
            minutesEl.textContent = '00';
            secondsEl.textContent = '00';
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        hoursEl.textContent = hours.toString().padStart(2, '0');
        minutesEl.textContent = minutes.toString().padStart(2, '0');
        secondsEl.textContent = seconds.toString().padStart(2, '0');
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
@endpush
