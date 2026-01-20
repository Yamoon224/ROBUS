<?php

namespace App\Http\Services;

use App\Models\AdminsCom;
use Illuminate\Database\Eloquent\Builder;

class GainServiceBackend
{
    /**
     * Distribuer la commission aux admins pour les compagnies acceptées
     */

    /**
     * Query de base
     */
    protected function baseQuery(): Builder
    {
        return AdminsCom::query()
            ->where('status', 'SUCCESS')
            ->where('is_web', false);
    }

    /**
     * Calcul générique de commission
     */
    public function calculate(
        Builder $query,
        string $relationPath,
        callable $percentageResolver
    ): float {
        return $query
            ->with($relationPath . '.wallet.sidUser.country')
            ->get()
            ->sum(function ($adminCom) use ($percentageResolver, $relationPath) {

                $sidUser = data_get($adminCom, $relationPath)->wallet->sidUser;
                $amount  = $adminCom->amount;

                $percent = $percentageResolver($sidUser);

                return $percent ? $percent->percent_banque * $amount : 0;
            });
    }

    /**
     * PHONE
     */
    public function phone(callable $percentageResolver): float
    {
        return $this->calculate(
            $this->baseQuery()->whereNotNull('wallet_phone_credit_id'),
            'walletPhoneCredit',
            $percentageResolver
        );
    }

    /**
     * PAYMENTS
     */
    public function payments(callable $percentageResolver): float
    {
        return $this->calculate(
            $this->baseQuery()->whereNotNull('wallet_payment_id'),
            'walletPayment',
            $percentageResolver
        );
    }

    /**
     * TRANSACTIONS
     */
    public function transactions(callable $percentageResolver): float
    {
        return $this->calculate(
            $this->baseQuery()->whereNotNull('wallet_transaction_id'),
            'walletTransaction',
            $percentageResolver
        );
    }

    public function transactionBank(callable $percentageResolver): float
    {
        return $this->calculate(
            $this->baseQuery()->whereNotNull('wallet_bank_id'),
            'wallet_bank',
            $percentageResolver
        );
    }

     public function transactionInter(callable $percentageResolver): float
    {
        return $this->calculate(
            $this->baseQuery()->whereNotNull('wallet_inter_id'),
            'wallet_inter',
            $percentageResolver
        );
    }

      public function transactionPush(callable $percentageResolver): float
    {
        return $this->calculate(
            $this->baseQuery()->whereNotNull('wallet_inter_id'),
            'wallet_inter',
            $percentageResolver
        );
    }
}
