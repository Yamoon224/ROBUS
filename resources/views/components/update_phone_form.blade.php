<form class="form-vertical row"
action="{{ route('admin.update_phone', ['id' => $user->id]) }}" method="post"
enctype="multipart/form-data">
@csrf

<div class="grid grid-cols-12 gap-6 mt-3 items-center">
    <div class="col-span-12 lg:col-span-12">
        <label class="form-label">Ancien numero <span class="text-danger"> *</label>
            <span
            class="alert alert-secondary mr-10 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
            Phone :{{ $user->phone  ?? null}}
        </span> 
           
    </div>
    <div class="col-span-12 lg:col-span-8">
        <label class="form-label">Nouveau numero <span
                class="text-danger">*</span></label>
        <input type="text"
            class="form-control @error('new_phone') is-invalid @enderror"
            name="new_phone" id="new_phone" 
            maxlength="15">
    </div>
    <div class="col-span-12 lg:col-span-2">
        <div class="text-center w-20">
            <button type="submit"
                class="btn btn-primary w-32 mb-2 mt-6 ">Enregistrer</button>
        </div>

    </div>

</div>

</form>