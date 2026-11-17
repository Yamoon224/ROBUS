<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionClaimService
{
    /** Claim only the selected row, regardless of other countries or pending claims. */
    public function claim(string $model, int $id, int $userId, bool $isWeb, string $status): Model
    {
        // Un seul UPDATE conditionnel : atomique quel que soit le moteur (plusieurs tables wallet_*
        // sont en MyISAM, où lockForUpdate() ne verrouille rien). Aucun verrou n'est tenu, donc la
        // prise en charge d'une transaction n'attend ni ne bloque jamais celle d'une autre ;
        // si deux personnes cliquent sur la même ligne, une seule voit 1 ligne modifiée.
        $claimed = $model::whereKey($id)
            ->where('is_web', $isWeb)
            ->where('status', 'PENDING')
            ->whereNull('updated_by')
            ->update(['updated_by' => $userId, 'status' => $status]);

        if ($claimed !== 1) {
            throw new \DomainException('Transaction déjà prise en charge, traitée ou indisponible.');
        }

        return $model::findOrFail($id);
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
