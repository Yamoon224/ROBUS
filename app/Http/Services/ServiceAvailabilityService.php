<?php

namespace App\Http\Services;

use App\Models\ServiceCloseSchedule;
use App\Models\ServiceCloseSetting;
use Illuminate\Support\Carbon;

/**
 * Disponibilité d'un service web (Mobile Zone, International Zone).
 * Priorité : mode manuel (forcé ouvert / forcé fermé) > plage horaire du jour > ouvert.
 */
class ServiceAvailabilityService
{
    public const SERVICES = [
        'mobile_zone' => 'Transfert Mobile Zone',
        'international_zone' => 'Transfert International Zone',
    ];

    public const MODE_OPEN = 'open';
    public const MODE_CLOSED = 'closed';

    public static function dayNumber(?Carbon $date = null): int
    {
        $day = ($date ?? Carbon::now())->dayOfWeek;

        return $day == 0 ? 7 : $day;
    }

    public static function manualMode(string $service): ?string
    {
        $mode = ServiceCloseSetting::where('service', $service)->value('manual_mode');

        return in_array($mode, [self::MODE_OPEN, self::MODE_CLOSED], true) ? $mode : null;
    }

    /**
     * @return array{open: bool, reason: string, manual_mode: ?string, today: ?ServiceCloseSchedule, next: ?ServiceCloseSchedule, message: ?string}
     */
    public static function status(string $service, ?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();
        $dayNumber = self::dayNumber($now);

        $today = ServiceCloseSchedule::where('service', $service)->where('day_number', $dayNumber)->first();
        $next = ServiceCloseSchedule::where('service', $service)
            ->where('day_number', $dayNumber == 7 ? 1 : $dayNumber + 1)->first();

        $setting = ServiceCloseSetting::where('service', $service)->first();
        $mode = $setting && in_array($setting->manual_mode, [self::MODE_OPEN, self::MODE_CLOSED], true)
            ? $setting->manual_mode : null;

        $result = [
            'open' => true,
            'reason' => 'default',
            'manual_mode' => $mode,
            'today' => $today,
            'next' => $next,
            'message' => null,
        ];

        // Le mode manuel prime sur les horaires
        if ($mode === self::MODE_OPEN) {
            return ['reason' => 'manual'] + $result;
        }

        if ($mode === self::MODE_CLOSED) {
            return [
                'open' => false,
                'reason' => 'manual',
                'message' => $setting->manual_message,
            ] + $result;
        }

        if ($today && $today->status) {
            $current = $now->format('H:i:s');

            if ($current < $today->time_start || $current > $today->time_end) {
                return [
                    'open' => false,
                    'reason' => 'schedule',
                    'message' => $today->description,
                ] + $result;
            }

            $result['reason'] = 'schedule';
        }

        return $result;
    }

    public static function isOpen(string $service): bool
    {
        return self::status($service)['open'];
    }
}
