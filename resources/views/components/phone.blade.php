    <input type="tel" class="form-control  form-control-merge @error('phone') is-invalid @enderror" id="phone" name="phone" placeholder=""
        @if ($required ?? false) required @endif value="{{ $value }}"
        style="padding-left: inherit !important;" @if ($readonly ?? false) readonly @endif
        value="{{ old('phone') }}" />
    {{-- <strong id="invalid_phone" class="intl_error"></strong> --}}
    @error('full_phone')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    {{-- <input id="password" type="password"
                                            class="form-control  form-control-merge @error('password') is-invalid @enderror"
                                            aria-describedby="password" name="password" required
                                            autocomplete="current-password"> --}}
