@php
    $balanceCurrency = optional(optional($item->wallet ?? null)->currencie)->code ?? 'CFA';
    $fmtBalance = fn($v) => $v === null || $v === '' ? '—' : number_format((float) $v, 2, '.', ' ') . ' ' . $balanceCurrency;
@endphp
<div class="text-xs whitespace-nowrap">
    <div><span class="text-slate-500">Avant :</span> <strong>{{ $fmtBalance($item->balance_before ?? null) }}</strong></div>
    <div><span class="text-slate-500">Après :</span> <strong>{{ $fmtBalance($item->amount_solde ?? null) }}</strong></div>
</div>
