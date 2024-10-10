<div class="col-span-6 mt-3">
        <label for="{{ $id }}" class="form-label">{{ $label }}<span class="text-danger"> *</span></label>
        <input wire:model.debounce.500ms="{{ $model }}" id="{{ $id }}" type="number" class="form-control" placeholder="{{ $label }}">
        @error($error)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    