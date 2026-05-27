<?php

namespace App\Http\Services;

class GeoService
{
    const EARTH_RADIUS_M = 6371000; // mètres

    /**
     * Calcule la distance en mètres entre deux coordonnées GPS
     * Formule de Haversine — précision suffisante pour ≤ 1km
     */
    public static function distanceMeters(
        float $lat1, float $lng1,
        float $lat2, float $lng2
    ): float {
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS_M * $c;
    }
}