@props(['transaction'])
@php($operationLabel = \App\Support\ReceiptOperation::label($transaction))
@if ($operationLabel)
    <p style="font-size: 12pt; font-weight: bold; margin: 6px 0; color: #232323;">
        Nature de la transaction : {{ $operationLabel }}
    </p>
@endif
