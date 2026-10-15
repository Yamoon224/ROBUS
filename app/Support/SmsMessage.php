<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class SmsMessage
{
    public static function normalize(string $message): string
    {
        return trim(preg_replace('/\s+/u', ' ', $message) ?? $message);
    }

    public static function transaction($transaction, string $status, ?string $currency = null, ?string $operation = null): string
    {
        $status = strtoupper($status);
        $operation = ReceiptOperation::label($transaction) ?? $operation ?? 'Transaction';
        $currency = $transaction->wallet->currencie->code ?? $currency ?? '';
        $message = 'ROBUS - ' . $operation . ' de ' . number_format((float) $transaction->amount, 2, ',', ' ');
        if ($currency !== '') {
            $message .= ' ' . $currency;
        }
        $provider = $transaction->provider->name ?? null;
        if ($provider) {
            $message .= ' via ' . $provider;
        }
        $date = in_array($status, ['PENDING', 'PROCESSING'], true)
            ? ($transaction->created_at ?? null)
            : ($transaction->updated_at ?? $transaction->created_at ?? null);
        if ($date) {
            $message .= ', le ' . Carbon::parse($date)->format('d/m/Y à H:i');
        }
        $message .= ' : ' . match ($status) {
            'SUCCESS' => 'opération effectuée avec succès.',
            'ERROR' => 'demande refusée.',
            'REFUND' => 'montant remboursé.',
            'PROCESSING' => 'traitement en cours.',
            default => 'demande reçue, en attente de validation.',
        };
        return self::normalize($message);
    }
}
