@extends('layouts.master')

@section('title', 'Entreprise')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('components.account-nav')
            <!-- profile -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h4 class="card-title">Conformité de l'entreprise</h4>
                </div>
                <div class="card-body py-2 my-25">
                    <!-- form -->
                    <form class="pt-50" method="POST" action="{{ route('compliance.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="name">Nom de l'entreprise <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="" required
                                    value="{{ old('name') ?? $company->name }}" />
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="description">Description de l'activité <span
                                        class="text-danger">*</span></label>
                                <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" placeholder="" required rows="3" cols="5">{{ old('description') ?? $company->description }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="firstname">Site internet</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website" placeholder=""
                                    value="{{ old('website') ?? $company->website }}" />
                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="phone">Numéro de téléphone de l'entreprise <span
                                        class="text-danger">*</span><span id="valid_phone"></span></label>
                                @include('components.phone', ['value' => $company->phone ?? ''])
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="country_id">Pays (siège) <span
                                        class="text-danger">*</span></label>
                                <select id="country_id" name="country_id"
                                    class="select2 form-select @error('country_id') is-invalid @enderror">
                                    <option disabled selected>-</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            @if ($company->country_id === $country->id) selected @endif>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-6 mb-1">
                                <label class="form-label" for="city">Ville (siège) <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                    id="city" name="city" placeholder="" required
                                    value="{{ old('city') ?? $company->city }}" />
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="address">Adresse <span class="text-danger">*</span></label>
                                <textarea type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                                    placeholder="" required rows="3" cols="5">{{ old('address') ?? $company->address }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="currency_id">Dévise du compte <span
                                        class="text-danger">*</span></label>
                                <select id="currency_id" name="currency_id"
                                    class="select2 form-select @error('currency_id') is-invalid @enderror">
                                    <option disabled selected>-</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}"
                                            @if ($company->currency_id === $currency->id) selected @endif>{{ $currency->code }} -
                                            {{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary mt-1 me-1">Mettre à jour</button>
                            </div>
                        </div>
                    </form>
                    <!--/ form -->
                </div>
            </div>


        </div>
    </div>
@endsection

@push('scripts')
 
@endpush
