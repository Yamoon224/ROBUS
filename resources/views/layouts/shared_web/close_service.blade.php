@php
    $svcToday = $availability['today'] ?? null;
    $svcNext = $availability['next'] ?? null;
    $svcFmt = fn ($t) => $t ? \Carbon\Carbon::createFromFormat('H:i:s', $t)->format('H:i') : '--:--';
    $svcIsManual = ($availability['reason'] ?? null) === 'manual';
@endphp

<style>
    .svc-close { border-radius: 22px; overflow: hidden; background: #fff; box-shadow: 0 24px 70px rgba(15,23,42,.16); }
    .svc-close-head { padding: clamp(24px,4vw,40px); color: #fff; background: linear-gradient(135deg,#7a155f 0%,#a30071 52%,#f8a21f 100%); }
    .svc-close-head h2 { margin: 0 0 10px; font-size: clamp(1.5rem,3.5vw,2.4rem); font-weight: 850; color: #fff; }
    .svc-close-head p { margin: 0; color: rgba(255,255,255,.86); line-height: 1.7; }
    .svc-close-body { padding: clamp(20px,4vw,34px); }
    .svc-close-chips { display: flex; flex-wrap: wrap; gap: 10px; }
    .svc-close-chip { padding: 9px 12px; border-radius: 12px; background: #f8fafc; color: #2b2030; font-weight: 800; }
    .svc-close-chip strong { color: #a30071; }
    .svc-close-note { margin-top: 16px; padding: 14px 16px; border-radius: 14px; background: #f0f9ff; border: 1px solid #e0f2fe; color: #344054; }
    .svc-close-title { margin-bottom: 12px; font-weight: 850; color: #2b2030; }
</style>

<div class="svc-close">
    <div class="svc-close-head">
        <h2>{{ $serviceLabel ?? 'Ce service' }} est momentanément indisponible.</h2>
        <p>
            @if ($svcIsManual)
                Le service a été temporairement suspendu. Merci de revenir plus tard.
            @else
                Le service est fermé en dehors de ses horaires d'ouverture. Consultez les horaires ci-dessous.
            @endif
        </p>
    </div>

    <div class="svc-close-body">
        @if (!empty($availability['message']))
            <div class="svc-close-note" style="margin-top:0;margin-bottom:18px">
                <strong>Information :</strong> {{ $availability['message'] }}
            </div>
        @endif

        @if (!$svcIsManual && $svcToday)
            <div class="svc-close-title">Horaires d'aujourd'hui ({{ $svcToday->day_of_week }})</div>
            <div class="svc-close-chips">
                <span class="svc-close-chip">De <strong>{{ $svcFmt($svcToday->time_start) }}</strong></span>
                <span class="svc-close-chip">à <strong>{{ $svcFmt($svcToday->time_end) }}</strong></span>
            </div>

            @if ($svcNext)
                <div class="svc-close-title" style="margin-top:20px">Prochaine ouverture ({{ $svcNext->day_of_week }})</div>
                <div class="svc-close-chips">
                    <span class="svc-close-chip">De <strong>{{ $svcNext->status ? $svcFmt($svcNext->time_start) : '00:00' }}</strong></span>
                    <span class="svc-close-chip">à <strong>{{ $svcNext->status ? $svcFmt($svcNext->time_end) : '23:59' }}</strong></span>
                </div>
            @endif
        @endif

        <div class="svc-close-note">Pour une urgence ou une question, notre support reste joignable.
            <a href="https://robus.africa/#contacts"><strong>Contacter le support</strong></a>
        </div>
    </div>
</div>
