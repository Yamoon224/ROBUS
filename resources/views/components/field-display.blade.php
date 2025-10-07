<div class="field">
    <label class="form-label {{ $required ?? false ? 'required' : '' }}">
        {{ $label }}
    </label>

    <div class="field-value">
        @if(!empty($icon))
            <i class="{{ $icon }}"></i>
        @endif
        <span>{{ $value ?? '—' }}</span>
    </div>
</div>
