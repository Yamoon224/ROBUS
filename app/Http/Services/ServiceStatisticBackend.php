<?php

namespace App\Http\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ServiceStatisticBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */

    public function getWalletSumGain($walletClass, string  $walletTransaction, string $walletCreatedBy, string $walletCreateAt, string $walletStatus, string $walletCommission)
    {
        $sumGain = $walletClass::join('wallets', $walletTransaction, '=', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) use ($walletCreatedBy) {
                return $query->where($walletCreatedBy, sabdy()->id);
            })
            ->when(sabdy()->is_com_zone, function ($query) {
                return $this->scopeComZoneLinkedUsers($query);
            })
            ->whereBetween($walletCreateAt, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($walletStatus, 'SUCCESS')
            ->sum($walletCommission);

        return $sumGain;
    }

    public function getWalletSumAmount($walletClass, string  $walletTransaction, string $walletCreatedBy, string $walletCreateAt, string $walletStatus, string $walletCommission)
    {
        $sumGain = $walletClass::join('wallets', $walletTransaction, '=', 'wallets.id')
            ->where('wallets.country_id', sabdy()->country->id)
            ->when(sabdy()->is_under_distributer, function ($query) use ($walletCreatedBy) {
                return $query->where($walletCreatedBy, sabdy()->id);
            })
            ->when(sabdy()->is_com_zone, function ($query) {
                return $this->scopeComZoneLinkedUsers($query);
            })
            ->whereBetween($walletCreateAt, [Carbon::today(), Carbon::today()->endOfDay()])
            ->where($walletStatus, 'SUCCESS')
            ->sum($walletCommission);

        return $sumGain;
    }

    public function getStatistical(
        $walletClass,
        string $walletTransaction,
        string $walletCreatedBy,
        string $walletStatus,
        string $walletCreated,
        string $field
    ) {
        $reloads = [];
        $userCountryId = Auth::guard('web')->user()->country->id;
        $tableName = (new $walletClass)->getTable();

        // Une seule requête groupée par mois au lieu de 12 parcours de table
        $sums = $walletClass::join('wallets', $walletTransaction, '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->when(sabdy()->is_under_distributer, function ($query) use ($walletCreatedBy) {
                return $query->where($walletCreatedBy, sabdy()->id);
            })
            ->when(sabdy()->is_com_zone, function ($query) {
                return $this->scopeComZoneLinkedUsers($query);
            })
            ->where($walletStatus, 'SUCCESS')
            ->whereNotNull($walletCreated)
            ->selectRaw("MONTH($walletCreated) as stat_month, SUM($tableName.$field) as total")
            ->groupBy('stat_month')
            ->pluck('total', 'stat_month');

        for ($month = 1; $month <= 12; $month++) {
            $reloads[$month] = 0 + ($sums[$month] ?? 0);
        }

        return array_values($reloads);
    }

    private function scopeComZoneLinkedUsers($query)
    {
        return $query->whereIn('wallets.sid_user_id', function ($subQuery) {
            $subQuery->select('id')
                ->from('sid_users')
                ->where('com_zone_id', sabdy()->id);
        });
    }
}
