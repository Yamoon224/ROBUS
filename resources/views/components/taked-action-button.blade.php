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
        'warning' => 'take-warning',
        'success' => 'take-success',
        'danger' => 'take-danger',
        'primary' => 'take-primary',
        'purple' => 'take-purple',
    ];

    $sizeClasses = [
        'default' => 'px-4 py-2 text-sm',
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-6 py-3 text-base',
    ];

    $selectedColor = $colorClasses[$color] ?? $colorClasses['warning'];
    $selectedSize = $sizeClasses[$size] ?? $sizeClasses['default'];
@endphp

<button type="button" wire:target="{{ $action }}({{ $itemId }})" class="btn-take-action {{ $selectedColor }} {{ $selectedSize }} {{ $icon ? 'with-icon' : '' }}"
    wire:click="{{ $action }}({{ $itemId }})" wire:loading.attr="disabled" wire:loading.class="loading"
    {{ $attributes }}>
    <span class="flex items-center gap-2">
        <span>{{ $label }}</span>

        @if ($loadingDots)
        
            <div class="loading-dots transition-opacity duration-150" wire:target="{{ $action }}({{ $itemId }})" wire:loading.class="opacity-100"
                wire:loading.remove.class="opacity-0">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        @endif

        @if ($icon)
            <!-- Icône de validation -->
            <svg wire:loading.remove wire:target="{{ $action }}({{ $itemId }})" class="btn-icon" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd" />
            </svg>
        @endif

        <!-- Animation pendant le loading -->
        <svg wire:loading wire:target="{{ $action }}({{ $itemId }})" class="w-4 h-4 animate-spin" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    </span>
</button>
