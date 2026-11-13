<?php

namespace App\Models\Wallets;

use Illuminate\Database\Eloquent\Builder;

/**
 * Opérations Pixel Innov de la table wallet_reloads (référence PIX_*).
 * Permet de traiter leurs commissions comme celles des autres services dans les remontées.
 */
class WalletPixelReload extends WalletReload
{
    /**
     * Inclut aussi les opérations Pixel restées sans référence PIX_* (échec avant la réponse de Pixel),
     * pour qu'elles apparaissent dans les historiques et puissent être annulées.
     */
    public function scopeIncludingOrphans(Builder $query): Builder
    {
        return $query->withoutGlobalScope('pixel_innov')->where(function ($q) {
            $q->where('wallet_reloads.reference', 'LIKE', 'PIX\_%')
                ->orWhere(function ($o) {
                    $o->whereNull('wallet_reloads.reference')
                        ->where(function ($m) {
                            $m->where('wallet_reloads.payment_method', 'LIKE', 'pixel\_innov\_%')
                                ->orWhere('wallet_reloads.payment_method', 'robus_wallet');
                        });
                });
        });
    }

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('pixel_innov', function (Builder $query) {
            $query->where('wallet_reloads.reference', 'LIKE', 'PIX\_%');
        });
    }
}
