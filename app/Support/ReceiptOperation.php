<?php

namespace App\Support;

use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletXbet;

class ReceiptOperation
{
    public static function label($transaction): ?string
    {
        if (!$transaction) {
            return null;
        }

        $type = mb_strtolower(trim((string) ($transaction->type_transaction ?? '')));
        if (in_array($type, ['depot', 'dépôt', 'deposit'], true)) {
            return 'Dépôt';
        }
        if (in_array($type, ['retrait', 'withdrawal'], true)) {
            return 'Retrait';
        }
        // Payments and balancing operations must not be mislabeled as deposits.
        if ($type !== '') {
            return null;
        }

        if ($transaction instanceof WalletXbet) {
            return self::choice($transaction->choose_type_transaction);
        }
        if ($transaction instanceof WalletTransaction || $transaction instanceof WalletPush
            || $transaction instanceof WalletDjamo || $transaction instanceof WalletTresortMoney) {
            return self::choice($transaction->choose_transaction);
        }

        return null;
    }

    private static function choice($value): ?string
    {
        $value = mb_strtolower(trim((string) $value));
        if (in_array($value, ['2', 'retrait'], true)) {
            return 'Retrait';
        }
        if (in_array($value, ['1', 'transfert', 'depot', 'dépôt'], true)) {
            return 'Dépôt';
        }
        return null;
    }
}
