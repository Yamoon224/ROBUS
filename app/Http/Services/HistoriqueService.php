<?php

namespace App\Http\Services;



use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Http\Services\ConvertionService;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;

class HistoriqueService
{

    protected $convertionService;

    public function mount(ConvertionService $convertionService)
    {
        $this->convertionService = $convertionService;
    }

    public function getTransactionAllamount($walletModel)
    {


        return $walletModel::whereStatus('SUCCESS')
        ->sum('amount');
    }

    public function getTransactionAllCount($walletModel, $wallet_transactions, $transaction_days)
    {


        return $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->count();
    }


    // count valeurs
    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        return $walletModel::join(
            'wallets',
            $wallet_transactions,
            'wallets.id'
        )
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            ->where($wallet_status, $status)
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->count();
    }

    // Sum amount days valeurs
    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }

    // Sum amount month valeurs
    public function getDaysTransaction($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        return $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereMonth($transaction_days, now()->month)
            ->where(function ($query) use ($walletMy) {
                if (sabdy()->is_distributer) {
                    $query->where($walletMy, '=', sabdy()->id);
                } else {
                    $query->where($walletMy, '=', sabdy()->id)->orWhere($walletMy, '!=', sabdy()->id);
                }
            })
            // ->where($transaction_status, 'SUCCESS')
            ->where(function ($query) {
                $countryId = sabdy()->country->id;
                if (sabdy()->is_distributer) {
                    $query->where('wallets.country_id', '=', sabdy()->id);
                } else {

                    $query->where('wallets.country_id', '!=', $countryId)
                        ->orWhere('wallets.country_id', '=', $countryId);
                }
            })
            ->sum($wallet_amount);
    }

    public function getSDistributeurSumAdmin()
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', 107)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }

    public function getSDistributeurSumCountryAdmin($countrie)
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_under_distributer', true)
            ->where('wallets.country_id', $countrie)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }

    public function getCustumersSumCountrieAdmin($countrie)
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_custumers', true)
            ->where('wallets.country_id', $countrie)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }


    public function getCustumersSumAdmin()
    {


        $data = [];

        foreach (Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_custumers', true)
            ->where('wallets.country_id', 107)
            ->select('wallets.*')
            ->get() as $wallet) {
            $data[] = $wallet->amount;
        }

        return array_sum($data);
    }
}
