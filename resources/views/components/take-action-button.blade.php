@props([
    'action', // Action Livewire (obligatoire)
    'itemId', // ID de l'item (obligatoire)
    'label' => 'Je prends en charge', // Texte du bouton
    'color' => 'warning', // Couleur du bouton
    'size' => 'default', // Taille
    'icon' => true, // Afficher l'icône
    'loadingDots' => true, // Afficher les points de chargement
])

@php
    // Classes de couleur selon la prop
    $colorClasses = [
        'warning' => 'bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600',
        'success' => 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700',
        'danger' => 'bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700',
        'primary' => 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700',
        'purple' => 'bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700',
    ];

    $sizeClasses = [
        'default' => 'px-4 py-2 text-sm',
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-6 py-3 text-base',
    ];

    $selectedColor = $colorClasses[$color] ?? $colorClasses['warning'];
    $selectedSize = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<div x-data="{ 
    processing: false,
    success: false,
    error: false,
    errorMessage: ''
}"
>

<button 
    type="button"
    x-on:click="
        if (processing || success) return;
        processing = true;
        error = false;
        errorMessage = '';
        try {
            const result = await $wire.{{ $action }}({{ $itemId }});
            if (result &amp;&amp; result.success === true) {
                success = true;
                setTimeout(() => success = false, 2000);
            } else {
                error = true;
                errorMessage = result?.message || 'Prise en charge impossible. Veuillez réessayer.';
            }
        } catch (failure) {
            error = true;
            errorMessage = 'Connexion interrompue. Vérifiez la transaction avant de réessayer.';
        } finally {
            processing = false;
        }
    "
    x-bind:disabled="processing || success"
    class="btn-take-action {{ $selectedColor }} {{ $selectedSize }} {{ $icon ? 'with-icon' : '' }} relative overflow-hidden transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
    x-bind:class="{
        'loading scale-95 opacity-75': processing,
        'bg-gradient-to-r from-green-500 to-emerald-600 !opacity-100 border-0': success,
        'bg-gradient-to-r from-red-500 to-rose-600': error
    }"
    {{ $attributes }}>
    
    <span class="flex items-center justify-center gap-2 min-w-[140px]">
        <!-- État NORMAL (idle) -->
        <span x-show="!processing && !success && !error" class="flex items-center gap-2">
            <span>{{ $label }}</span>
            
            @if ($icon)
                <svg class="btn-icon w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            @endif
        </span>

        <!-- État LOADING (traitement) -->
        <span x-show="processing" class="flex items-center gap-2">
            @if ($loadingDots)
                <div class="loading-dots flex gap-1">
                    <div class="loading-dot w-1.5 h-1.5 bg-white rounded-full animate-bounce"></div>
                    <div class="loading-dot w-1.5 h-1.5 bg-white rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="loading-dot w-1.5 h-1.5 bg-white rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            @else
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            @endif
            <span>Traitement...</span>
        </span>

        <!-- État SUCCESS (succès) -->
        <span x-show="success" class="flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span>Succès !</span>
        </span>

        <!-- État ERROR (erreur) -->
        <span x-show="error" class="flex items-center gap-2">
            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span x-text="errorMessage || 'Erreur'"></span>
        </span>
    </span>
</button>
</div>
