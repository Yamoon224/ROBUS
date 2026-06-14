<div class="intro-y box p-5 mt-5">
    <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-medium truncate">Opérateurs et plafonds COM ZONE</h2>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="overflow-auto">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th class="text-center whitespace-nowrap">Actif</th>
                    <th class="text-center whitespace-nowrap">Opérateur</th>
                    <th class="text-center whitespace-nowrap">Numbers</th>
                    <th class="text-center whitespace-nowrap">Amount</th>
                    <th class="text-center whitespace-nowrap">Commission</th>
                    <th class="text-center whitespace-nowrap">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($operators as $operator)
                @php
                $operatorService = $operatorServices->get($operator->id);
                $isSubmittedOperator = (int) old('operator_id') === $operator->id;
                $isChecked = $isSubmittedOperator
                ? old('active') == 1
                : $operatorService !== null;
                $formId = "operator-service-form-{$operator->id}";
                $numbersValue = $isSubmittedOperator ? old('numbers') : ($operatorService?->numbers ?? '');
                $amountValue = $isSubmittedOperator ? old('amount') : ($operatorService?->amount ?? 0);
                $commissionValue = $isSubmittedOperator ? old('commission') : ($operatorService?->commission ?? 0);
                @endphp
                <tr>
                    <td class="text-center align-middle">
                        <input type="checkbox" name="active" value="1" class="form-check-input" form="{{ $formId }}"
                            @checked($isChecked)>
                    </td>
                    <td class="align-middle">
                        <div class="font-medium whitespace-nowrap">
                            {{ $operator->name ?? 'Non renseigné' }}
                        </div>
                    </td>
                    <td class="align-middle">
                        <input type="number" name="numbers" value="{{ $numbersValue }}" min="0" class="form-control"
                            placeholder="0" form="{{ $formId }}">
                    </td>
                    <td class="align-middle">
                        <input type="number" step="0.01" name="amount"
                            value="{{ $amountValue !== null ? $amountValue : '0.00' }}" min="0" class="form-control"
                            placeholder="0" form="{{ $formId }}">
                    </td>
                    <td class="align-middle">
                        <input type="number" step="0.01" name="commission"
                            value="{{ $commissionValue !== null ? $commissionValue : '0.00' }}" min="0"
                            class="form-control" placeholder="0" form="{{ $formId }}">
                    </td>
                    <td class="text-center align-middle">
                        <form action="{{ route('comzones.admins.operators.update', ['id' => $user->id]) }}"
                            method="POST" id="{{ $formId }}" class="inline">
                            @csrf
                            <input type="hidden" name="operator_id" value="{{ $operator->id }}">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-save inline mr-1">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z">
                                    </path>
                                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    <polyline points="7 3 7 8 15 8"></polyline>
                                </svg>
                                Enregistrer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
