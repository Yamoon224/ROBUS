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
            ->whereDate($walletCreateAt, Carbon::today())
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
            ->whereDate($walletCreateAt, Carbon::today())
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

        for ($month = 1; $month <= 12; $month++) {
            $reloads[$month] = $walletClass::join('wallets', $walletTransaction, '=', 'wallets.id')
                ->where('wallets.country_id', $userCountryId)
                ->when(sabdy()->is_under_distributer, function ($query) use ($walletCreatedBy) {
                    return $query->where($walletCreatedBy, sabdy()->id);
                })
                ->where($walletStatus, 'SUCCESS')
                ->where($walletCreated, 'like', '%-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-%')
                ->sum("$tableName.$field");
        }

        return array_values($reloads);
    }
}
