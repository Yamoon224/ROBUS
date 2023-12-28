@extends('layouts.master')

@section('title', 'Ajouter un payment facture')

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
                    <form class="validate-form" method="POST" action="{{ route('commissions.store_payments') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="crud-form-1 mt-3">
                            <label class="form-label" for="name">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="" rows="3" cols="5">{{ old('name') }}
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-6 mt-3">
                            <label class="form-label" for="countries">Pays <span class="text-danger">
                                    *</span></label>

                            <select id="country_id" name="country_id[]" data-placeholder="Selectionne les produits"
                                class="tom-select w-full @error('country_id') is-invalid @enderror" multiple>
                                <option disabled selected></option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-12" id="countrys_table">

                        </div>
                        <div class="crud-form-1 intro-y mt-3">
                            <label class="form-label" for="brands">Type paiement<span class="text-danger">
                                    *</span> </label>
                            <select required id="payment_type" name="payment_type"
                                class="select2 form-select @error('payment_type') is-invalid @enderror">
                                <option disabled selected>Selectionnez le type</option>
                                <option value="inter">Inter</option>
                                <option value="ivoire">Cote d'ivoire</option>
                            </select>
                            @error('payment_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="crud-form-1 mt-3">
                            <label for="description" class="form-label"> Description </label>
                            <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" placeholder="" rows="10" cols="5">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <!-- END: Validation Form -->

                </div>
                <button type="submit" class="btn btn-primary w-24 mr-1 mb-2 ml-6"> Ajouter</button>
                </form>
            </div>
            <!-- END: Form Validation -->
        </div>

    </div>


@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#country_id').on('change', function() {
                var country_ids = $('#country_id').val();
                if (country_ids.length > 0) {
                    $.post('{{ route('commissions.country_amounts.payments') }}', {
                        _token: '{{ csrf_token() }}',
                        country_ids: country_ids
                    }, function(data) {
                        $('#countrys_table').html(data);
                    });
                } else {
                    $('#countrys_table').html(null);
                }
            });

        });
    </script>
@endpush
