@extends('layouts.master')

@section('title', 'Modifier un service')

@section('content')


    <div class="grid grid-cols-12 gap-6 mt-10">
        <div class="intro-y col-span-2 lg:col-span-2">
            <!-- BEGIN: Form Validation -->

            <!-- END: Form Validation -->
        </div>
        <div class="intro-y col-span-12 lg:col-span-8">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">
                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        <a href="{{ URL::previous() }}" class="btn btn-sm">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                        </a>
                    </h2>
                    <div class="form-check form-switch w-full sm:w-auto sm:ml-auto mt-3 sm:mt-0">
                        <label class="form-check-label ml-0" for="show-example-5"> Modification du service</label>
                    </div>
                </div>
                <div class="p-5">
                    <form class="validate-form" method="POST"
                        action="{{ route('services.update', ['id' => $provider->id]) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="crud-form-1">
                            <div class="mx-auto xl:mr-0 xl:ml-6">
                                <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                    src="{{ asset($provider->image ?? 'public/dist/images/food-beverage-1.jpg') }}"
                                    alt="image" id="profileImage">
                                <div class="mx-auto cursor-pointer relative mt-5 mb-6">
                                    <label for="imageUpload" class="btn btn-outline-secondary w-full cursor-pointer">Changer le logo</label>
                                    <input id="imageUpload" type="file" name="image" style="display: none"
                                        accept="image/png, image/jpeg, image/webp">
                                    @error('image')
                                        <span class="text-danger" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="crud-form-1 mt-3">
                            <label class="form-label" for="name">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="" rows="3" cols="5"
                                value="{{ $provider->name }}">{{ old('name') }}
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-6 mt-3">
                            <label class="form-label" for="countries">Pays <span class="text-danger">
                                    *</span></label>
                            @php
                                $old_countrys = \App\Models\CountryService::where('provider_id', $provider->id)
                                    ->pluck('country_id')
                                    ->toArray();

                            @endphp
                            <select id="country_id" name="country_id[]" data-placeholder="Selectionne les produits"
                                class="tom-select w-full @error('country_id') is-invalid @enderror" multiple
                                data-selected="{{ json_encode($old_countrys) }}">
                                <option disabled selected></option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @if (in_array($country->id, $old_countrys)) selected @endif>
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="crud-form-1 intro-y mt-3">
                            <label class="form-label" for="brands">Type operateur </label>
                            <select required id="provider_type_id" name="provider_type_id"
                                class="select2 form-select @error('provider_type_id') is-invalid @enderror">
                                <option disabled selected>-</option>
                                @foreach ($providerTypes as $provider_type)
                                    <option value="{{ $provider_type->id }}"
                                        @if ($provider_type->id === $provider->provider_type_id) selected @endif>
                                        {{ $provider_type->name }}</option>
                                @endforeach
                            </select>
                            @error('provider_type_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        @if ($provider->is_order)
                            <div class="crud-form-1 intro-y mt-3">

                                {{-- <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-12"> --}}
                                <label for="Montant" class="form-label">Montant</label>
                                <input name="amount" id="Montant" type="text" class="form-control"
                                    placeholder="Montant" value="{{ $provider->amount }}">
                                @error('amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                {{-- </div>
                                </div> --}}
                            </div>
                            <div class="crud-form-1 intro-y mt-3">

                                {{-- <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-12"> --}}
                                <label for="percent_merchands" class="form-label">Pourcentage s-distributeur</label>
                                <input name="percent_merchands" id="percent_merchands" type="text" class="form-control"
                                    placeholder="Montant s-distributeur" value="{{ $provider->percent_merchands }}">
                                @error('percent_merchands')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                {{-- </div>
                                </div> --}}
                            </div>
                        @endif
                        <div class="crud-form-1 mt-3">
                            <label for="description" class="form-label"> Description </label>
                            <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" placeholder="" rows="10" cols="5" value="{{ $provider->description }}">{{ old('description') ?? $provider->description }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <!-- END: Validation Form -->

                </div>
                <button type="submit" class="btn btn-primary w-24 mr-1 mb-2 ml-6">Mettre a jour</button>
                </form>
            </div>
            <!-- END: Form Validation -->
        </div>

    </div>


@endsection

@push('scripts')
    <script>
        document.getElementById('imageUpload')?.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (file) {
                document.getElementById('profileImage').src = URL.createObjectURL(file);
            }
        });
    </script>
@endpush
