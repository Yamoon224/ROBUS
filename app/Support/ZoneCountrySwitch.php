<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class ZoneCountrySwitch
{
    public const INTERNATIONAL = 'international_zone';
    public const DEPOSIT = 'deposit_zone';

    private const TYPE_NAMES = [
        'international zone' => self::INTERNATIONAL,
        'depot zone'         => self::DEPOSIT,
    ];

    public static function zoneForTypeName(?string $name): ?string
    {
        return self::TYPE_NAMES[strtolower(trim((string) $name))] ?? null;
    }

    // Sans ligne pour le pays, la zone reste active.
    public static function isEnabled(string $zone, $countryId): bool
    {
        if (!$countryId) {
            return true;
        }

        $status = DB::table('country_zone_switches')
            ->where('country_id', $countryId)
            ->where('zone', $zone)
            ->value('status');

        return $status === null || (bool) $status;
    }

    public static function set(string $zone, int $countryId, bool $status): void
    {
        DB::table('country_zone_switches')->updateOrInsert(
            ['country_id' => $countryId, 'zone' => $zone],
            ['status' => $status, 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
