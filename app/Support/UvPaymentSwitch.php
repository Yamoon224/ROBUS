<?php

namespace App\Support;

use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Achat d'UV : Paystack et Pixel Innov ne sont jamais actifs en même temps.
 * Quand Paystack est activé (opérateur ou pays), Pixel Innov est désactivé automatiquement.
 */
final class UvPaymentSwitch
{
    private const PROVIDER_TYPE_UV = 30;

    /**
     * Applique la règle et retourne true si Pixel Innov a été désactivé quelque part.
     * Utilise le query builder (aucun événement de modèle) pour ne pas se rappeler elle-même.
     */
    public static function enforce(): bool
    {
        $paystack = self::findProvider('paystack');
        $pixel = self::findProvider('pixel innov');

        if (!$paystack || !$pixel) {
            return false;
        }

        $changed = false;

        // 1) Paystack actif : Pixel Innov est désactivé.
        if ($paystack->status && $pixel->status) {
            Provider::whereKey($pixel->id)->update(['status' => false]);
            $changed = true;
        }

        // 2) Paystack actif dans un pays : Pixel Innov est désactivé dans ce pays.
        $countries = DB::table('country_services')
            ->where('provider_id', $paystack->id)
            ->where('status', true)
            ->pluck('country_id');

        if ($countries->isNotEmpty()) {
            $updated = DB::table('country_services')
                ->where('provider_id', $pixel->id)
                ->whereIn('country_id', $countries)
                ->where('status', true)
                ->update(['status' => false, 'updated_at' => now()]);

            $changed = $changed || $updated > 0;
        }

        if ($changed) {
            // La liste des services envoyée aux applications est mise en cache.
            Cache::flush();
        }

        return $changed;
    }

    private static function findProvider(string $namePrefix): ?Provider
    {
        return Provider::where('provider_type_id', self::PROVIDER_TYPE_UV)
            ->whereRaw('LOWER(name) LIKE ?', [$namePrefix . '%'])
            ->first();
    }
}
