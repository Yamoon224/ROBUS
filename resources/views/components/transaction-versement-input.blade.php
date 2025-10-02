<div class="col-span-6 mt-1 form-group required">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <input wire:model.debounce.500ms="{{ $model }}" id="{{ $id }}" type="text" class="form-control"
        placeholder="{{ $label }}" required>
    @error($error)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
