<?php

namespace App\Models\Wallets;

use Illuminate\Database\Eloquent\Builder;

/**
 * Opérations Pixel Innov de la table wallet_reloads (référence PIX_*).
 * Permet de traiter leurs commissions comme celles des autres services dans les remontées.
 */
class WalletPixelReload extends WalletReload
{
    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('pixel_innov', function (Builder $query) {
            $query->where('wallet_reloads.reference', 'LIKE', 'PIX\_%');
        });
    }
}
