<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        QR Code — Site Web ROBUS
    </h2>

    <div class="intro-y box mt-6 p-6 max-w-xl mx-auto">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 border-b border-slate-200 pb-4 mb-6">
            <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#4f46e5,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            </div>
            <div>
                <div style="font-weight:700;font-size:15px;color:#1e293b;">Génération du QR Code site web</div>
                <div style="font-size:12px;color:#64748b;margin-top:2px;">
                    Redirige les visiteurs vers
                    <strong style="color:#4f46e5;">{{ env('WEBSITE_URL', 'https://robus.africa') }}</strong>
                </div>
            </div>
        </div>

        @if($qrPath)
            {{-- QR déjà généré --}}
            <div class="flex flex-col items-center">
                <div style="border:3px solid #e0e7ff;border-radius:14px;padding:14px;background:#fafbff;margin-bottom:20px;display:inline-block;">
                    <img src="{{ asset($qrPath) }}?v={{ time() }}"
                         alt="QR Code {{ env('WEBSITE_URL') }}"
                         style="width:220px;height:220px;object-fit:contain;display:block;">
                </div>

                <div style="font-size:11px;color:#94a3b8;margin-bottom:20px;text-align:center;">
                    {{ env('WEBSITE_URL', 'https://robus.africa') }}
                </div>

                <div class="flex gap-3 w-full">
                    <a href="{{ route('qr.website.print') }}" target="_blank"
                        style="flex:1;display:flex;align-items:center;justify-content:center;padding:10px 14px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-size:12px;font-weight:600;border-radius:8px;text-decoration:none;gap:6px;box-shadow:0 2px 8px rgba(22,163,74,0.28);">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Imprimer / PDF
                    </a>
                    <button wire:click="generate" wire:loading.attr="disabled"
                        style="flex:1;display:flex;align-items:center;justify-content:center;padding:10px 14px;background:#f1f5f9;color:#475569;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e2e8f0;gap:6px;cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span wire:loading.remove wire:target="generate">Régénérer</span>
                        <span wire:loading wire:target="generate">Génération...</span>
                    </button>
                </div>
            </div>

        @else
            {{-- Pas encore généré --}}
            <div class="flex flex-col items-center py-8">
                <div style="width:100px;height:100px;border-radius:16px;background:#f0f4ff;border:2px dashed #c7d2fe;display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:40px;height:40px;color:#a5b4fc;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                </div>
                <p style="font-size:13px;color:#374151;font-weight:600;margin-bottom:6px;">Aucun QR code généré</p>
                <p style="font-size:12px;color:#94a3b8;text-align:center;margin-bottom:24px;max-width:280px;">
                    Cliquez sur le bouton ci-dessous pour générer le QR code qui redirige vers
                    <strong>{{ env('WEBSITE_URL', 'https://robus.africa') }}</strong>.
                </p>
                <button wire:click="generate" wire:loading.attr="disabled"
                    style="display:flex;align-items:center;justify-content:center;padding:11px 28px;background:linear-gradient(135deg,#4f46e5,#6366f1);color:#fff;font-size:13px;font-weight:600;border-radius:8px;border:none;gap:8px;cursor:pointer;box-shadow:0 3px 10px rgba(79,70,229,0.30);">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:17px;height:17px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span wire:loading.remove wire:target="generate">Générer le QR Code</span>
                    <span wire:loading wire:target="generate">Génération en cours...</span>
                </button>
            </div>
        @endif

    </div>
</div>
