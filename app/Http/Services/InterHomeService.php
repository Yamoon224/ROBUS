<?php

namespace App\Http\Services;



use App\Models\AdminsCom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletXbet;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletTresortMoney;
use Illuminate\Support\Facades\Auth;

class InterHomeService
{

    public function getXbetCountByStatus($status)
    {
        return $this->getCountByStatus(WalletXbet::class, 'wallet_xbets', $status);
    }

    public function getPushCountByStatus($status)
    {
        return $this->getCountByStatus(WalletPush::class, 'wallet_pushes', $status);
    }

    public function getDjamoCountByStatus($status)
    {
        return $this->getCountByStatus(WalletDjamo::class, 'wallet_djamos', $status);
    }

    public function getVersementCountByStatus($status)
    {
        return $this->getCountByStatus(WalletVersement::class, 'wallet_versements', $status);
    }

     public function getTresortMoneyCountByStatus($status)
    {
        return $this->getCountByStatus(WalletTresortMoney::class, 'wallet_tresort_money', $status);
    }

    public function getBankSendCountByStatus($status)
    {
        return $this->getCountByStatus(WalletBankSend::class, 'wallet_bank_sends', $status);
    }
    public function getPhoneCreditCountByStatus($status)
    {
        return $this->getCountByStatus(WalletPhoneCredit::class, 'wallet_phone_credits', $status);
    }

    public function getDepositCountByStatus($status)
    {
        return $this->getCountByStatus(WalletDeposit::class, 'wallet_deposits', $status);
    }

    public function getInterCountByStatus($status)
    {
        return $this->getCountByStatus(WalletInter::class, 'wallet_inters', $status);
    }

    public function getVirementCountByStatus($status)
    {
        return $this->getCountByStatus(WalletBank::class, 'wallet_banks', $status);
    }

    public function getReloadCountByStatus($status)
    {
        return $this->getCountByStatus(WalletReload::class, 'wallet_reloads', $status);
    }

    public function getPaymentCountByStatus($status)
    {
        return $this->getCountByStatus(WalletPayment::class, 'wallet_payments', $status);
    }

    public function getTransactionCountByStatus($status)
    {
        return $this->getCountByStatus(WalletTransaction::class, 'wallet_transactions', $status);
    }


    public function getWalletDepositSum(string $model, string $table)
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        $walletDepositQuery = $model::join('wallets', "{$table}.wallet_id", '=', 'wallets.id')
            ->where('wallets.country_id', $userCountryId)
            ->whereBetween("{$table}.created_at", [Carbon::today(), Carbon::today()->endOfDay()])
            ->where("{$table}.status", 'SUCCESS');

        if (sabdy()->is_under_distributer) {
            $walletDepositQuery->where("{$table}.wallet_id", sabdy()->wallet->id);
        }

        return $walletDepositQuery->sum("{$table}.amount");
    }

    protected function getCountByStatus($model, string $table, string $status): int
    {
        $userCountryId = Auth::guard('web')->user()->country->id;

        $query = $model::join('wallets', "$table.wallet_id", '=', 'wallets.id')
            // ->where('wallets.country_id', $userCountryId)
            ->where("$table.status", $status)
            ->whereBetween("$table.created_at", [Carbon::today(), Carbon::today()->endOfDay()]);

        if (sabdy()->is_under_distributer) {
            $query->where("$table.wallet_id", sabdy()->wallet->id);
        }

        return $query->count();
    }


    public function getWalletReloads()
    {
        $data = [];

        foreach (
            WalletReload::join(
                'wallets',
                'wallet_reloads.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_reloads.*')
                ->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
                ->select('wallet_reloads.*')
                ->where('wallet_reloads.status', 'SUCCESS')
                ->get()
            as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
}
