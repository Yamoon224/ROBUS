<div class="col-span-6 mt-3 form-group required">
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
        <input wire:model.debounce.500ms="{{ $model }}" id="{{ $id }}" type="number" class="form-control" placeholder="{{ $label }}" required>
        @error($error)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    