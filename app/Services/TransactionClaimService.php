<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionClaimService
{
    /** Claim only the selected row, regardless of other countries or pending claims. */
    public function claim(string $model, int $id, int $userId, bool $isWeb, string $status): Model
    {
        return DB::transaction(function () use ($model, $id, $userId, $isWeb, $status) {
            $transaction = $model::whereKey($id)
                ->where('is_web', $isWeb)
                ->where('status', 'PENDING')
                ->whereNull('updated_by')
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                throw new \DomainException('Transaction déjà prise en charge, traitée ou indisponible.');
            }

            $transaction->update(['updated_by' => $userId, 'status' => $status]);

            return $transaction;
        });
    }

    /** A notification failure must not cancel an already recorded claim. */
    public function notify(string $event, Model $transaction): void
    {
        try {
            event(new $event($transaction));
        } catch (\Throwable $error) {
            report($error);
        }
    }
}
