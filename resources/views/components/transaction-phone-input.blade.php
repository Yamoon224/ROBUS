<div class="col-span-6 mt-3 form-group required">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <input wire:model.debounce.500ms="{{ $model }}" id="{{ $id }}" type="text" class="form-control"
        placeholder="{{ $label }}" maxlength="12" pattern="\d{12}"
        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)" required>
    @error($error)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
