<form class="form-vertical row" action="{{ route('admin.update_email', ['id' => $user->id]) }}" method="post"
    enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-12 gap-6 mt-3 items-center">
        <div class="col-span-12 lg:col-span-12">
            <label class="form-label">Ancien email <span class="text-danger"> *</label>
            <span
                class="alert alert-secondary mr-10 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                Email :{{ $user->email ?? null }}
            </span>

        </div>
        <div class="col-span-12 lg:col-span-8">
            <label class="form-label">Nouveau <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('new_email') is-invalid @enderror" name="new_email"
                id="new_email" maxlength="50">
        </div>
        <div class="col-span-12 lg:col-span-2">
            <div class="text-center w-20">
                <button type="submit" class="btn btn-primary w-32 mb-2 mt-6 ">Enregistrer</button>
            </div>

        </div>

    </div>

</form>
