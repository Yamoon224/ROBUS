<?php

namespace App\Support;

use App\Models\Providers\Provider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Achat d'UV : Paystack et Pixel Innov ne sont jamais actifs en même temps, et l'un
 * des deux l'est toujours : activer l'un désactive l'autre, désactiver l'un active l'autre
 * (au niveau de l'opérateur comme au niveau du pays, ex. Côte d'Ivoire).
 * Les écritures passent par le query builder (aucun événement de modèle) pour
 * ne pas se rappeler elles-mêmes.
 */
final class UvPaymentSwitch
{
    private const PROVIDER_TYPE_UV = 30;

    /**
     * Un opérateur Paystack / Pixel Innov a changé de statut : l'autre prend le statut inverse,
     * dans les pays où l'un des deux était proposé.
     */
    public static function providerChanged(Provider $provider): void
    {
        $other = self::counterpart($provider);
        if (!$other) {
            return;
        }

        $winner = $provider->status ? $provider : $other;
        $loser = $provider->status ? $other : $provider;

        self::setProviderStatus($winner->id, true);
        self::setProviderStatus($loser->id, false);

        $countries = DB::table('country_services')
            ->whereIn('provider_id', [$winner->id, $loser->id])
            ->where('status', true)
            ->pluck('country_id')
            ->unique();

        foreach ($countries as $countryId) {
            self::setCountryStatus($winner->id, (int) $countryId, true);
            self::setCountryStatus($loser->id, (int) $countryId, false);
        }

        Cache::flush();
    }

    /**
     * Paystack / Pixel Innov activé ou désactivé dans un pays : l'autre prend le statut inverse
     * dans ce pays (et son opérateur est réactivé si besoin).
     */
    public static function countryServiceChanged(\App\Models\CountryService $service): void
    {
        $provider = Provider::find($service->provider_id);
        $other = $provider ? self::counterpart($provider) : null;
        if (!$other) {
            return;
        }

        $active = (bool) $service->status;
        if (!$active) {
            // Pays sans cet opérateur : l'autre prend le relais (opérateur compris).
            self::setProviderStatus($other->id, true);
        }
        self::setCountryStatus($other->id, (int) $service->country_id, !$active);

        Cache::flush();
    }

    /** L'opérateur UV opposé (Paystack <-> Pixel Innov) ; null si ce n'en est pas un. */
    private static function counterpart(Provider $provider): ?Provider
    {
        if ((int) $provider->provider_type_id !== self::PROVIDER_TYPE_UV) {
            return null;
        }

        $name = strtolower((string) $provider->name);
        if (str_starts_with($name, 'paystack')) {
            return self::findProvider('pixel innov');
        }
        if (str_starts_with($name, 'pixel innov')) {
            return self::findProvider('paystack');
        }

        return null;
    }

    private static function setProviderStatus(int $providerId, bool $status): void
    {
        Provider::whereKey($providerId)->where('status', '!=', $status)->update(['status' => $status]);
    }

    private static function setCountryStatus(int $providerId, int $countryId, bool $status): void
    {
        $row = DB::table('country_services')
            ->where('provider_id', $providerId)
            ->where('country_id', $countryId);

        if ($row->exists()) {
            $row->update(['status' => $status, 'updated_at' => now()]);
        } elseif ($status) {
            DB::table('country_services')->insert([
                'provider_id' => $providerId,
                'country_id' => $countryId,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Applique la règle (Paystack prioritaire s'ils sont tous deux actifs) et retourne true si Pixel Innov a été désactivé quelque part.
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

    /**
     * Pixel Innov est-il le moyen de paiement UV actif pour ce pays ?
     * (opérateur actif, activé dans le pays, et Paystack non actif dans ce pays)
     */
    public static function pixelActiveForCountry(?int $countryId): bool
    {
        $pixel = self::findProvider('pixel innov');
        if (!$pixel || !$pixel->status) {
            return false;
        }

        $active = fn (int $providerId) => !$countryId || DB::table('country_services')
            ->where('provider_id', $providerId)
            ->where('country_id', $countryId)
            ->where('status', true)
            ->exists();

        if (!$active($pixel->id)) {
            return false;
        }

        $paystack = self::findProvider('paystack');
        if ($paystack && $paystack->status && $active($paystack->id)) {
            return false;
        }

        return true;
    }

    private static function findProvider(string $namePrefix): ?Provider
    {
        return Provider::where('provider_type_id', self::PROVIDER_TYPE_UV)
            ->whereRaw('LOWER(name) LIKE ?', [$namePrefix . '%'])
            ->first();
    }
}
