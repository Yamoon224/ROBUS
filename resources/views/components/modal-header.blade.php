<div class="modal-header">
    <h2 class="modal-title">
        <i class="{{ $icon ?? 'fas fa-check-circle' }}"></i>
        {{ $title ?? 'Titre du modal' }}
    </h2>
    <div class="close-btn">
        <a wire:click="{{ $onClose ?? 'closeModal' }}">
            <i class="fas fa-times"></i>
        </a>
    </div>
</div>

@if (!empty($service))
    <div class="text-center">
        <div class="service-badge">
            <i class="{{ $serviceIcon ?? 'fas fa-dice' }}"></i>
            Service {{ $service }}
        </div>
    </div>
@endif
