@props([
    'action',
    'itemId', 
    'label' => 'Je prends en charge',
    'color' => 'warning',
    'instant' => true // Nouvelle prop pour mode instantané
])

@php
    $colors = [
        'warning' => 'from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600',
        'success' => 'from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700',
        'danger' => 'from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700',
        'primary' => 'from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700',
    ];
@endphp

<div x-data="{ 
    processing: false,
    done: false,
    error: false,
    
    async handleClick() {
        if (this.processing) return;
        
        this.processing = true;
        this.error = false;
        
        @if($instant)
            // Feedback visuel instantané
            setTimeout(() => {
                this.processing = false;
                this.done = true;
                setTimeout(() => this.done = false, 1500);
            }, 400);
        @endif
        
        try {
            // Appel Livewire asynchrone
            await $wire.{{ $action }}({{ $itemId }});
            
            @if(!$instant)
                this.processing = false;
                this.done = true;
                setTimeout(() => this.done = false, 1500);
            @endif
        } catch (error) {
            this.processing = false;
            this.error = true;
            setTimeout(() => this.error = false, 2000);
            console.error(error);
        }
    }
}">
    <button 
        @click="handleClick()"
        :disabled="processing || done"
        class="relative overflow-hidden bg-gradient-to-r {{ $colors[$color] }} px-4 py-2 text-sm text-white rounded-lg font-medium transition-all duration-150 shadow-md hover:shadow-lg"
        :class="{ 
            'scale-95 opacity-75': processing,
            'bg-gradient-to-r from-green-500 to-emerald-600 !opacity-100': done,
            'bg-gradient-to-r from-red-500 to-rose-600': error
        }">
        
        <span class="flex items-center gap-2 justify-center min-w-[140px]">
            <!-- État normal -->
            <span x-show="!processing && !done && !error" class="flex items-center gap-2">
                {{ $label }}
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </span>

            <!-- Loading -->
            <span x-show="processing" class="flex items-center gap-2">
                <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                Traitement...
            </span>

            <!-- Succès -->
            <span x-show="done" class="flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Succès !
            </span>

            <!-- Erreur -->
            <span x-show="error" class="flex items-center gap-2">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Erreur
            </span>
        </span>
    </button>
</div>