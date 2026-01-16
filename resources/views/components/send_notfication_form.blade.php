<form class="form-vertical row" action="{{ route('messages.one.store', ['id' => $user->id]) }}" method="post"
    enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-12 gap-6 mt-3 items-center">
        <div class="col-span-12 lg:col-span-12">
            <label class="form-label">Titre<span class="text-danger"> *</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title') }}" required>
            @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
        <div class="col-span-12 lg:col-span-8">
            <label class="form-label" for="message">Message <span class="text-danger"> *</span></label>
            <textarea name="message" id="" cols="48" rows="3">

                             </textarea>
            @error('message')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-span-12 lg:col-span-12 mt-6">
            <div class="mx-auto xl:mr-0 xl:ml-6">
                <img class="object-none object-center bg-yellow-300 w-30 h-30"
                    src="{{ old('image') ?? asset_url('dist/images/food-beverage-1.jpg') }}" alt="image"
                    id="imageCaptureId">
                <div class="mx-auto cursor-pointer relative mt-5">
                    <input id="imageUploadId" type="file" name="image" placeholder="Image du message" capture
                        style="display: none" accept="image/png, image/jpeg">
                    <h6 class="text-center ">Image du message</h6>
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

    </div>
    <div class="col-span-12 lg:col-span-2">
        <div class="text-center w-20">
            <button type="submit" class="btn btn-primary w-32 mb-2 mt-6 ">Envoyé</button>
        </div>

    </div>

</form>
