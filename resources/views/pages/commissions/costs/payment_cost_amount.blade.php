@if (count($country_ids) > 0)
    <div class="alert alert-secondary show mb-2 " role="alert">Le montant</div>
    <div class="col-sm-9 mt-3">
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <td class="" width="50%">
                        <label class="control-label">Nom</label>
                    </td>
                    <td class="text-center">
                        <label class="control-label">Montant</label>
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($country_ids as $key => $id)
                    @php
                        $country = App\Models\Custumers\Country::findOrFail($id);
                    @endphp
                    <tr>
                        <td class="w-50">
                            <div class="flex">

                                <div class="ml-3 col imw-10 imh-10">
                                    <div class="">{{ $country->name }}</div>
                                </div>

                            </div>
                        </td>

                        <td>
                            <input type="number" name="amount_{{ $id }}" value="" min="0"
                                class="form-control" required>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
