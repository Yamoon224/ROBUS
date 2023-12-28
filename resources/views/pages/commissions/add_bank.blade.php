@extends('layouts.master')

@section('title', 'Modifier une commission')

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
                        <label class="form-check-label ml-0" for="show-example-5"> Ajouter du service</label>
                    </div>
                </div>
                <div class="p-5">
                    <form class="validate-form" method="POST" action="{{ route('commissions.store_banks') }}"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage<span class="text-danger">
                            *</span></label>
                    <input wire:model="bankPercent" id="modal-form-1" type="text" class="form-control"
                        placeholder="Pourcentage">
                    @error('bankPercent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-6">
                    <label for="modal-form-1" class="form-label">Pourcentage marchants<span class="text-danger">
                            *</span></label>
                    <input wire:model="orderCardMerchant" id="modal-form-1" type="text" class="form-control"
                        placeholder="Pourcentage marchant">
                    @error('orderCardMerchant')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}

                        {{-- </div> --}}
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
                        <div class="crud-form-1 intro-y mt-3" id="is_type_percent">
                            <label class="form-label" for="brands">Type commission </label>
                            <select required id="type_percent_change" name="type_percent"
                                class="select2 form-select @error('type_percent') is-invalid @enderror">
                                <option value="null" selected>-Selectionnez-</option>
                                <option value="1">En pourcentage</option>
                                <option value="2">Par palier</option>
                            </select>
                            @error('type_percent')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="crud-form-1 intro-y col-span-12 mt-3" id="info_percent">
                            <div class="col-span-6">
                                <label for="modal-form-1" class="form-label">Pourcentage<span class="text-danger">
                                        *</span></label>
                                <input id="modal-form-1" type="text" name="bankPercent" class="form-control"
                                    placeholder="Pourcentage">
                                @error('bankPercent')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 mt-3">
                                <label for="modal-form-1" class="form-label">Pourcentage marchants<span class="text-danger">
                                        *</span></label>
                                <input name="orderCardMerchant" id="modal-form-1" type="text" class="form-control"
                                    placeholder="Pourcentage marchant">
                                @error('orderCardMerchant')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="crud-form-1 intro-y col-span-12 lg:col-span-6 mt-3" id="country_info">
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
        $("#country_info").hide();
        $("#info_percent").hide();

        function myCreateFunction() {
            var table = document.getElementById("myTable");
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
        }

        function myDeleteFunction() {
            document.getElementById("myTable").deleteRow(0);
        }

        $(document).ready(function() {


            $('#type_percent_change').on('change', function() {
                var val_type = $('#type_percent_change').val();
                if (val_type == 1) {
                    $("#country_info").hide();
                    $("#info_percent").show();
                } else if (val_type == 2) {
                    $("#country_info").show();
                    $("#info_percent").hide();
                }
            });

            $('#country_id').on('change', function() {
                var country_ids = $('#country_id').val();
                if (country_ids.length > 0) {
                    $.post('{{ route('costs.countrys_costs') }}', {
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
