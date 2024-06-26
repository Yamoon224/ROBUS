<form class="form-vertical row" action="{{ route('admin.update_password', ['id' => $user->id]) }}" method="post"
    enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-12 gap-6 mt-3 items-center">
        <div class="col-span-12 lg:col-span-4">
            <label class="form-label">Mot de passe <span class="text-danger"> *</span></label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                id="password" required maxlength="4">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-span-12 lg:col-span-4">
            <label class="form-label">Confirmer Mot de passe <span class="text-danger">*</span></label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                name="password_confirmation" id="password_confirmation" required maxlength="4">
        </div>
        <div class="col-span-12 lg:col-span-4">
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-24  mb-2 mt-6 ">Enregistrer</button>
            </div>
        </div>
    </div>
</form>
