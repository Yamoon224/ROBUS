<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Database\Eloquent\Collection;

class Home extends Component
{
    public $startDate = '';
    public $endDate = '';

    public function render()
    {
        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer == true) {
            return view('livewire.home', [
                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'sum_error' => $this->ReloadErrorUser + $this->VirementErrorUser + $this->PhoneCreditErrorUser + $this->DepositErrorUser +
                    $this->InterErrorUser +
                    $this->PaymentErrorUser +
                    $this->TransactionErrorUser,
                'sum_success' => $this->ReloadSuccessUser + $this->VirementSuccessUser + $this->PhoneCreditSuccessUser +
                    $this->DepositSuccessUser +
                    $this->InterSuccessUser +
                    $this->PaymentSuccessUser +
                    $this->TransactionSuccessUser,
                'pending_success' => $this->ReloadPenddingUser + $this->VirementPenddingUser +  $this->PhoneCreditPenddingUser + $this->DepositPenddingUser +
                    $this->InterPenddingUser +
                    $this->PaymentPenddingUser +
                    $this->TransactionPenddingUser,
                'sum_amount' => $this->WalletTransactionSumUser + $this->WalletBankSumUser + $this->WalletDepositSumUser + $this->WalletInterSumUser +
                    $this->WalletPhoneCreditSumUser +
                    $this->WalletReloadSumUser +
                    $this->WalletPaymentSumUser,
                'usersCountry' => $walletUser->country->currencie->name,
                'sum_bank' => $this->WalletBankSumUser,
                'sum_deposit' => $this->WalletDepositSumUser,
                'sum_payment' => $this->WalletPaymentSumUser,
                'sum_reload' => $this->WalletReloadSumUser,
                'sum_transaction' => $this->WalletTransactionSumUser,
                'sum_inter' => $this->WalletInterSumUser,
                'sum_phoneCredit' => $this->WalletPhoneCreditSumUser,
                'sum_month' => $this->WalletBankSumUser,
                'sum_gain' => $this->WalletDepositSumGainUser +  $this->WalletPaymentSumGainUser +  $this->WalletReloadSumGainUser +
                    $this->WalletTransactionSumGainUser +
                    $this->WalletBankSumGainUser +
                    $this->WalletPhoneCreditSumGainUser +
                    $this->WalletReloadSumGainUser +
                    $this->WalletInterSumGainUser,
                'sum_bank_month' => $this->WalletBankMonthUser,
                'sum_deposit_month' => $this->WalletDepositMonthUser,
                'sum_payment_month' => $this->WalletPaymentMonthUser,
                'sum_reload_month' => $this->WalletReloadMonthUser,
                'sum_transaction_month' => $this->WalletTransactionMonthUser,
                'sum_inter_month' => $this->WalletInterMonthUser,
                'sum_phoneCredit_month' => $this->WalletInterMonthUser,
                // Transaction days
                'account_bank_days' => $this->WalletBankAccountGainUser,
                'account_deposit_days' => $this->WalletDepositAccountGainUser,
                'account_payment_days' => $this->WalletPaymentAccountGainUser,
                'account_reload_days' => $this->WalletReloadAccountGainUser,
                'account_transaction_days' => $this->WalletTransactionAccountGainUser,
                'account_inter_days' => $this->WalletInterAccountGainUser,
                'account_phoneCredit_days' => $this->WalletPhoneCreditAccountGainUser,
            ]);
        } else {

            return view('livewire.home', [
                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'payment_error' => $this->PaymentError,
                'sum_error' => $this->ReloadError +  $this->VirementError + $this->PhoneCreditError + $this->DepositError +  $this->InterError +
                    $this->PaymentError +
                    $this->TransactionError,
                'sum_success' => $this->ReloadSuccess + $this->VirementSuccess +  $this->PhoneCreditSuccess +
                    $this->DepositSuccess +
                    $this->InterSuccess +
                    $this->PaymentSuccess +
                    $this->TransactionSuccess,
                'pending_success' => $this->ReloadPendding + $this->VirementPendding +  $this->PhoneCreditPendding +
                    $this->DepositPendding +
                    $this->InterPendding +
                    $this->PaymentPendding +
                    $this->TransactionPendding,
                'sum_amount' => $this->WalletTransactionSum + $this->WalletBankSum + $this->WalletDepositSum +
                    $this->WalletInterSum +
                    $this->WalletPhoneCreditSum +
                    $this->WalletReloadSum +
                    $this->WalletPaymentSum,
                'usersCountry' => $walletUser->country->currencie->name,
                'sum_bank' => $this->WalletBankSum,
                'sum_deposit' => $this->WalletDepositSum,
                'sum_payment' => $this->WalletPaymentSum,
                'sum_reload' => $this->WalletReloadSum,
                'sum_transaction' => $this->WalletTransactionSum,
                'sum_inter' => $this->WalletInterSum,
                'sum_phoneCredit' => $this->WalletPhoneCreditSum,
                'sum_month' => $this->WalletBankMonth,
                'sum_gain' => $this->WalletDepositSumGain +  $this->WalletPaymentSumGain + $this->WalletReloadSumGain +  $this->WalletTransactionSumGain +
                    $this->WalletInterSumGain +
                    $this->WalletInterSumGain +
                    $this->WalletInterSumGain +
                    $this->WalletInterSumGain,
                'sum_bank_month' => $this->WalletBankMonth,
                'sum_deposit_month' => $this->WalletDepositMonth,
                'sum_payment_month' => $this->WalletPaymentMonth,
                'sum_reload_month' => $this->WalletReloadMonth,
                'sum_transaction_month' => $this->WalletTransactionMonth,
                'sum_inter_month' => $this->WalletInterMonth,
                'sum_phoneCredit_month' => $this->WalletInterMonth,
                // Transaction days
                'account_bank_days' => $this->WalletBankAccountGainAdmin,
                'account_deposit_days' => $this->WalletDepositAccountGainAdmin,
                'account_payment_days' => $this->WalletPaymentAccountGainAdmin,
                'account_reload_days' => $this->WalletReloadAccountGainAdmin,
                'account_transaction_days' => $this->WalletTransactionAccountGainAdmin,
                'account_inter_days' => $this->WalletInterAccountGainAdmin,
                'account_phoneCredit_days' => $this->WalletPhoneCreditAccountGainAdmin,
            ]);
        }
    }

    // Show User Month
    public function getWalletBankMonthUserProperty()
    {
        $data = [];

        $result = WalletBank::join('wallets', 'wallets.id', 'wallet_banks.wallet_id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->whereDate('wallet_banks.created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('sum(wallet_banks.amount) as cumul_amount_chiffre'))
            ->groupBy('wallet_banks.created_at')
            ->get();

        foreach ($result as $sumBank) {
            $data[] = $sumBank->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletDepositMonthUserProperty()
    {
        $data = [];

        $result = walletDeposit::join('wallets', 'wallets.id', 'wallet_deposits.wallet_id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('web')->user()->id
            )
            ->whereDate(
                'wallet_deposits.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_deposits.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_deposits.created_at')
            ->get();

        foreach ($result as $sumDepos) {
            $data[] = $sumDepos->cumul_amount_chiffre;
        }

        return array_sum($data);
    }
    public function getWalletPaymentMonthUserProperty()
    {
        $data = [];

        $result = WalletPayment::join(
            'wallets',
            'wallets.id',
            'wallet_payments.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where(
                'wallet_payments.created_by',
                Auth::guard('web')->user()->id
            )
            ->whereDate(
                'wallet_payments.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_payments.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_payments.created_at')
            ->get();

        foreach ($result as $sumPay) {
            $data[] = $sumPay->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletTransactionMonthUserProperty()
    {
        $data = [];

        $result = WalletTransaction::join(
            'wallets',
            'wallets.id',
            'wallet_transactions.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('web')->user()->id
            )
            ->whereDate(
                'wallet_transactions.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw(
                    'sum(wallet_transactions.amount) as cumul_amount_chiffre'
                )
            )
            ->groupBy('wallet_transactions.created_at')
            ->get();

        foreach ($result as $sumTrans) {
            $data[] = $sumTrans->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletInterMonthUserProperty()
    {
        $data = [];

        $result = WalletInter::join(
            'wallets',
            'wallets.id',
            'wallet_inters.wallet_id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)
            ->whereDate(
                'wallet_inters.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_inters.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_inters.created_at')
            ->get();

        foreach ($result as $sumInter) {
            $data[] = $sumInter->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditMonthUserProperty()
    {
        $data = [];

        $result = WalletPhoneCredit::join(
            'wallets',
            'wallets.id',
            'wallet_phone_credits.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('web')->user()->id
            )
            ->whereDate(
                'wallet_phone_credits.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw(
                    'sum(wallet_phone_credits.amount) as cumul_amount_chiffre'
                )
            )
            ->groupBy('wallet_phone_credits.created_at')
            ->get();

        foreach ($result as $sumPhone) {
            $data[] = $sumPhone->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletReloadMonthUserProperty()
    {
        $data = [];

        $result = WalletReload::join(
            'wallets',
            'wallets.id',
            '=',
            'wallet_reloads.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
            ->whereDate(
                'wallet_reloads.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_reloads.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_reloads.created_at')
            ->get();

        foreach ($result as $sumReload) {
            $data[] = $sumReload->cumul_amount_chiffre;
        }
        return array_sum($data);
    }

    //    A month
    public function getWalletBankMonthProperty()
    {
        $data = [];

        $result = WalletBank::join(
            'wallets',
            'wallets.id',
            'wallet_banks.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate(
                'wallet_banks.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_banks.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_banks.created_at')
            ->get();
        foreach ($result as $sumBank) {
            $data[] = $sumBank->cumul_amount_chiffre;
        }
        return array_sum($data);
    }

    public function getWalletDepositMonthProperty()
    {
        $data = [];

        $result = walletDeposit::join(
            'wallets',
            'wallets.id',
            'wallet_deposits.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate(
                'wallet_deposits.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_deposits.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_deposits.created_at')
            ->get();

        foreach ($result as $sumDepos) {
            $data[] = $sumDepos->cumul_amount_chiffre;
        }

        return array_sum($data);
    }
    public function getWalletPaymentMonthProperty()
    {
        $data = [];

        $result = WalletPayment::join(
            'wallets',
            'wallets.id',
            'wallet_payments.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate(
                'wallet_payments.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_payments.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_payments.created_at')
            ->get();

        foreach ($result as $sumPay) {
            $data[] = $sumPay->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletTransactionMonthProperty()
    {
        $data = [];

        $result = WalletTransaction::join(
            'wallets',
            'wallets.id',
            'wallet_transactions.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate(
                'wallet_transactions.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw(
                    'sum(wallet_transactions.amount) as cumul_amount_chiffre'
                )
            )
            ->groupBy('wallet_transactions.created_at')
            ->get();

        foreach ($result as $sumTrans) {
            $data[] = $sumTrans->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletInterMonthProperty()
    {
        $data = [];

        $result = WalletInter::join(
            'wallets',
            'wallets.id',
            '=',
            'wallet_inters.wallet_id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate(
                'wallet_inters.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_inters.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_inters.created_at')
            ->get();

        foreach ($result as $sumInter) {
            $data[] = $sumInter->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditMonthProperty()
    {
        $data = [];

        $result = WalletPhoneCredit::join(
            'wallets',
            'wallets.id',
            '=',
            'wallet_phone_credits.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate(
                'wallet_phone_credits.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw(
                    'sum(wallet_phone_credits.amount) as cumul_amount_chiffre'
                )
            )
            ->groupBy('wallet_phone_credits.created_at')
            ->get();

        foreach ($result as $sumPhone) {
            $data[] = $sumPhone->cumul_amount_chiffre;
        }

        return array_sum($data);
    }

    public function getWalletReloadMonthProperty()
    {
        $data = [];

        $result = WalletReload::join(
            'wallets',
            'wallets.id',
            '=',
            'wallet_reloads.wallet_id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate(
                'wallet_reloads.created_at',
                '>=',
                Carbon::now()->subDays(30)
            )
            ->select(
                DB::raw('sum(wallet_reloads.amount) as cumul_amount_chiffre')
            )
            ->groupBy('wallet_reloads.created_at')
            ->get();

        foreach ($result as $sumReload) {
            $data[] = $sumReload->cumul_amount_chiffre;
        }
        return array_sum($data);
    }

    //   State gains Admin
    public function getWalletBankSumGainProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_banks.created_at', Carbon::now())
                ->where('wallet_banks.status', 'SUCCESS')
                ->select('wallet_banks.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumGainProperty()
    {
        $data = [];
        foreach (WalletDeposit::join(
                'wallets',
                'wallet_deposits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_deposits.created_at', Carbon::now())
                ->where('wallet_deposits.status', 'SUCCESS')
                ->select('wallet_deposits.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    public function getWalletPaymentSumGainProperty()
    {
        $data = [];
        foreach (WalletPayment::join(
                'wallets',
                'wallet_payments.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_payments.created_at', Carbon::now())
                ->where('wallet_payments.status', 'SUCCESS')
                ->select('wallet_payments.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumGainProperty()
    {
        $data = [];
        foreach (WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_transactions.created_at', Carbon::now())
                ->where('wallet_transactions.status', 'SUCCESS')
                ->select('wallet_transactions.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    public function getWalletInterSumGainProperty()
    {
        $data = [];
        foreach (WalletInter::join(
                'wallets',
                'wallet_inters.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallet_inters.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_inters.created_at', Carbon::now())
                ->where('wallet_inters.status', 'SUCCESS')
                ->select('wallet_inters.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditSumGainProperty()
    {
        $data = [];
        foreach (WalletPhoneCredit::join(
                'wallets',
                'wallet_phone_credits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_phone_credits.created_at', Carbon::now())
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->select('wallet_phone_credits.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumGainProperty()
    {
        $data = [];
        foreach (WalletReload::join(
                'wallets',
                'wallet_reloads.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_reloads.created_at', Carbon::now())
                ->where('wallet_reloads.status', 'SUCCESS')
                ->select('wallet_reloads.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions_admin;
        }

        return array_sum($data);
    }

    //   State gains User
    public function getWalletBankSumGainUserProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->select('wallet_banks.*')
            ->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumGainUserProperty()
    {
        $data = [];

        foreach (WalletDeposit::join(
                'wallets',
                'wallet_deposits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_deposits.created_at', Carbon::now())
                ->where(
                    'wallet_deposits.created_by',
                    Auth::guard('web')->user()->id
                )
                ->where('wallet_deposits.status', 'SUCCESS')
                ->select('wallet_deposits.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletPaymentSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPayment::join(
                'wallets',
                'wallet_payments.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_payments.created_at', Carbon::now())
                ->where(
                    'wallet_payments.created_by',
                    Auth::guard('web')->user()->id
                )
                ->where('wallet_payments.status', 'SUCCESS')
                ->select('wallet_payments.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumGainUserProperty()
    {
        $data = [];
        foreach (WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_transactions.created_at', Carbon::now())
                ->where(
                    'wallet_transactions.created_by',
                    Auth::guard('web')->user()->id
                )
                ->where('wallet_transactions.status', 'SUCCESS')
                ->select('wallet_transactions.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletInterSumGainUserProperty()
    {
        $data = [];
        foreach (WalletInter::join(
                'wallets',
                'wallet_inters.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallet_inters.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_inters.created_at', Carbon::now())
                ->where(
                    'wallet_inters.created_by',
                    Auth::guard('web')->user()->id
                )
                ->where('wallet_inters.status', 'SUCCESS')
                ->select('wallet_inters.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditSumGainUserProperty()
    {
        $data = [];
        foreach (WalletPhoneCredit::join(
                'wallets',
                'wallet_phone_credits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_phone_credits.created_at', Carbon::now())
                ->where(
                    'wallet_phone_credits.created_by',
                    Auth::guard('web')->user()->id
                )
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->select('wallet_phone_credits.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumGainUserProperty()
    {
        $data = [];
        foreach (WalletReload::join(
                'wallets',
                'wallet_reloads.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_reloads.created_at', Carbon::now())
                ->where(
                    'wallet_reloads.created_by',
                    Auth::guard('web')->user()->id
                )
                ->where('wallet_reloads.status', 'SUCCESS')
                ->select('wallet_reloads.*')
                ->get()
            as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }

    // Account Nombre Days Distributer and S-distributer transaction
    public function getWalletBankAccountGainUserProperty()
    {

        $account_valeur = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->select('wallet_banks.*')
            ->count();

        return $account_valeur;
    }

    public function getWalletDepositAccountGainUserProperty()
    {
        $account_valeur  =  WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletPaymentAccountGainUserProperty()
    {
        $account_valeur =   WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->where(
                'wallet_payments.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletTransactionAccountGainUserProperty()
    {
        $account_valeur =    WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletInterAccountGainUserProperty()
    {
        $account_valeur =    WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->where(
                'wallet_inters.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->count();;

        return $account_valeur;
    }
    public function getWalletPhoneCreditAccountGainUserProperty()
    {
        $account_valeur =     WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->count();;

        return $account_valeur;
    }

    public function getWalletReloadAccountGainUserProperty()
    {
        $account_valeur =   WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->where(
                'wallet_reloads.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->count();;

        return $account_valeur;
    }

    // Account Nombre Days admin transaction
    public function getWalletBankAccountGainAdminProperty()
    {

        $account_valeur = WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)
            ->whereDate('wallet_banks.created_at', Carbon::now())
            // ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->select('wallet_banks.*')
            ->count();

        return $account_valeur;
    }

    public function getWalletDepositAccountGainAdminProperty()
    {
        $account_valeur  =  WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            // ->where(
            //     'wallet_deposits.created_by',
            //     Auth::guard('web')->user()->id
            // )
            ->where('wallet_deposits.status', 'SUCCESS')
            ->select('wallet_deposits.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletPaymentAccountGainAdminProperty()
    {
        $account_valeur =   WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            // ->where(
            //     'wallet_payments.created_by',
            //     Auth::guard('web')->user()->id
            // )
            ->where('wallet_payments.status', 'SUCCESS')
            ->select('wallet_payments.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletTransactionAccountGainAdminProperty()
    {
        $account_valeur =    WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            // ->where(
            //     'wallet_transactions.created_by',
            //     Auth::guard('web')->user()->id
            // )
            ->where('wallet_transactions.status', 'SUCCESS')
            ->select('wallet_transactions.*')
            ->count();


        return $account_valeur;
    }

    public function getWalletInterAccountGainAdminProperty()
    {
        $account_valeur =    WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            // ->where(
            //     'wallet_inters.country_id',
            //     Auth::guard('web')->user()->country->id
            // )
            ->whereDate('wallet_inters.created_at', Carbon::now())
            // ->where(
            //     'wallet_inters.created_by',
            //     Auth::guard('web')->user()->id
            // )
            ->where('wallet_inters.status', 'SUCCESS')
            ->select('wallet_inters.*')
            ->count();;

        return $account_valeur;
    }
    public function getWalletPhoneCreditAccountGainAdminProperty()
    {
        $account_valeur =     WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            // ->where(
            //     'wallet_phone_credits.created_by',
            //     Auth::guard('web')->user()->id
            // )
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->select('wallet_phone_credits.*')
            ->count();;

        return $account_valeur;
    }

    public function getWalletReloadAccountGainAdminProperty()
    {
        $account_valeur =   WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->where(
                'wallet_reloads.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_reloads.status', 'SUCCESS')
            ->select('wallet_reloads.*')
            ->count();;

        return $account_valeur;
    }
    // Sum Admin
    public function getWalletBankSumProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->whereDate('wallet_banks.created_at', Carbon::now())
                ->select('wallet_banks.*')
                ->where('wallet_banks.status', 'SUCCESS')
                ->get()
            as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumProperty()
    {
        $data = [];

        foreach (WalletDeposit::join(
                'wallets',
                'wallet_deposits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_deposits.*')
                ->whereDate('wallet_deposits.created_at', Carbon::now())
                ->where('wallet_deposits.status', 'SUCCESS')
                ->get()
            as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentSumProperty()
    {
        $data = [];

        foreach (WalletPayment::join(
                'wallets',
                'wallet_payments.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_payments.*')
                ->whereDate('wallet_payments.created_at', Carbon::now())
                ->where('wallet_payments.status', 'SUCCESS')
                ->get()
            as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumProperty()
    {
        $data = [];

        foreach (WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_transactions.*')
                ->whereDate('wallet_transactions.created_at', Carbon::now())
                ->where('wallet_transactions.status', 'SUCCESS')
                ->get()
            as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletInterSumProperty()
    {
        $data = [];

        foreach (WalletInter::join(
                'wallets',
                'wallet_inters.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallet_inters.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_inters.*')
                ->whereDate('wallet_inters.created_at', Carbon::now())
                ->where('wallet_inters.status', 'SUCCESS')
                ->get()
            as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditSumProperty()
    {
        $data = [];

        foreach (WalletPhoneCredit::join(
                'wallets',
                'wallet_phone_credits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_phone_credits.*')
                ->whereDate('wallet_phone_credits.created_at', Carbon::now())
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->get()
            as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumProperty()
    {
        $data = [];

        foreach (WalletReload::join(
                'wallets',
                'wallet_reloads.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_reloads.*')
                ->whereDate('wallet_reloads.created_at', Carbon::now())
                ->where('wallet_reloads.status', 'SUCCESS')
                ->get()
            as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    // Sum User
    public function getWalletBankSumUserProperty()
    {
        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_banks.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_banks.created_at', Carbon::now())
                ->select('wallet_banks.*')
                ->where('wallet_banks.status', 'SUCCESS')
                ->get()
            as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositSumUserProperty()
    {
        $data = [];

        foreach (WalletDeposit::join(
                'wallets',
                'wallet_deposits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_deposits.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_deposits.created_at', Carbon::now())
                ->select('wallet_deposits.*')
                ->where('wallet_deposits.status', 'SUCCESS')
                ->get()
            as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentSumUserProperty()
    {
        $data = [];

        foreach (WalletPayment::join(
                'wallets',
                'wallet_payments.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_payments.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_payments.created_at', Carbon::now())
                ->select('wallet_payments.*')
                ->where('wallet_payments.status', 'SUCCESS')
                ->get()
            as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }

    public function getWalletTransactionSumUserProperty()
    {
        $data = [];

        foreach (WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_transactions.*')
                ->where(
                    'wallet_transactions.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_transactions.created_at', Carbon::now())
                ->where('wallet_transactions.status', 'SUCCESS')
                ->get()
            as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletInterSumUserProperty()
    {
        $data = [];

        foreach (WalletInter::join(
                'wallets',
                'wallet_inters.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallet_inters.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_inters.created_by',
                    Auth::guard('web')->user()->id
                )
                ->select('wallet_inters.*')
                ->whereDate('wallet_inters.created_at', Carbon::now())
                ->where('wallet_inters.status', 'SUCCESS')
                ->get()
            as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }
    public function getWalletPhoneCreditSumUserProperty()
    {
        $data = [];

        foreach (WalletPhoneCredit::join(
                'wallets',
                'wallet_phone_credits.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->where(
                    'wallet_phone_credits.created_by',
                    Auth::guard('web')->user()->id
                )
                ->select('wallet_phone_credits.*')
                ->whereDate('wallet_phone_credits.created_at', Carbon::now())
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->get()
            as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadSumUserProperty()
    {
        $data = [];

        foreach (WalletReload::join(
                'wallets',
                'wallet_reloads.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    Auth::guard('web')->user()->country->id
                )
                ->select('wallet_reloads.*')
                ->where(
                    'wallet_reloads.created_by',
                    Auth::guard('web')->user()->id
                )
                ->whereDate('wallet_reloads.created_at', Carbon::now())
                ->where('wallet_reloads.status', 'SUCCESS')
                ->get()
            as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }

    // Details Transaction User
    public function getTransactionErrorUserProperty()
    {
        $transactionError = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_transactions.status', 'ERROR')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessUserProperty()
    {
        $transactionSuccess = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_transactions.status', 'SUCCESS')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingUserProperty()
    {
        $transactionPendding = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_transactions.status', 'PENDING')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorUserProperty()
    {
        $paimentError = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'ERROR')
            ->where(
                'wallet_payments.created_by',
                Auth::guard('web')->user()->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessUserProperty()
    {
        $paimentSuccess = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')
            ->where(
                'wallet_payments.created_by',
                Auth::guard('web')->user()->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingUserProperty()
    {
        $paimentPendding = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'PENDING')
            ->where(
                'wallet_payments.created_by',
                Auth::guard('web')->user()->id
            )
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentPendding;
    }

    public function getReloadErrorUserProperty()
    {
        $reloadError = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'ERROR')
            ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessUserProperty()
    {
        $reloadSuccess = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')
            ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingUserProperty()
    {
        $reloadPendding = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'PENDING')
            ->where('wallet_reloads.created_by', Auth::guard('web')->user()->id)
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessUserProperty()
    {
        $virementSuccess = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'SUCCESS')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingUserProperty()
    {
        $virementPendding = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'PENDING')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorUserProperty()
    {
        $virementError = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_banks.status', 'ERROR')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementError;
    }

    public function getInterErrorUserProperty()
    {
        $interError = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_inters.status', 'ERROR')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interError;
    }

    public function getInterSuccessUserProperty()
    {
        $interSuccess = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_inters.*')
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)
            ->where('wallet_inters.status', 'SUCCESS')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingUserProperty()
    {
        $interPendding = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where('wallet_inters.created_by', Auth::guard('web')->user()->id)
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'PENDING')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorUserProperty()
    {
        $phoneCreditError = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_phone_credits.*')
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_phone_credits.status', 'ERROR')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessUserProperty()
    {
        $phoneCreditSuccess = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_phone_credits.*')
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingUserProperty()
    {
        $phoneCreditPendding = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where(
                'wallet_phone_credits.created_by',
                Auth::guard('web')->user()->id
            )
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'PENDING')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorUserProperty()
    {
        $depositError = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_deposits.*')
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_deposits.status', 'ERROR')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositError;
    }

    public function getDepositSuccessUserProperty()
    {
        $depositSuccess = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_deposits.*')
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('web')->user()->id
            )
            ->where('wallet_deposits.status', 'SUCCESS')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingUserProperty()
    {
        $depositPendding = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->where(
                'wallet_deposits.created_by',
                Auth::guard('web')->user()->id
            )
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'PENDING')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositPendding;
    }

    // Details Transaction Admin
    public function getTransactionErrorProperty()
    {
        $transactionError = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'ERROR')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionError;
    }

    public function getTransactionSuccessProperty()
    {
        $transactionSuccess = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'SUCCESS')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionSuccess;
    }

    public function getTransactionPenddingProperty()
    {
        $transactionPendding = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'PENDING')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionPendding;
    }

    public function getPaymentErrorProperty()
    {
        $paimentError = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'ERROR')
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentError;
    }

    public function getPaymentSuccessProperty()
    {
        $paimentSuccess = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentSuccess;
    }

    public function getPaymentPenddingProperty()
    {
        $paimentPendding = WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'PENDING')
            ->whereDate('wallet_payments.created_at', Carbon::now())
            ->count();
        return $paimentPendding;
    }

    public function getReloadErrorProperty()
    {
        $reloadError = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'ERROR')
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadError;
    }

    public function getReloadSuccessProperty()
    {
        $reloadSuccess = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadSuccess;
    }

    public function getReloadPenddingProperty()
    {
        $reloadPendding = WalletReload::join(
            'wallets',
            'wallet_reloads.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'PENDING')
            ->whereDate('wallet_reloads.created_at', Carbon::now())
            ->count();
        return $reloadPendding;
    }

    public function getVirementSuccessProperty()
    {
        $virementSuccess = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementSuccess;
    }

    public function getVirementPenddingProperty()
    {
        $virementPendding = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'PENDING')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementPendding;
    }

    public function getVirementErrorProperty()
    {
        $virementError = WalletBank::join(
            'wallets',
            'wallet_banks.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'ERROR')
            ->whereDate('wallet_banks.created_at', Carbon::now())
            ->count();
        return $virementError;
    }

    public function getInterErrorProperty()
    {
        $interError = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'ERROR')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interError;
    }

    public function getInterSuccessProperty()
    {
        $interSuccess = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'SUCCESS')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interSuccess;
    }

    public function getInterPenddingProperty()
    {
        $interPendding = WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallet_inters.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'PENDING')
            ->whereDate('wallet_inters.created_at', Carbon::now())
            ->count();
        return $interPendding;
    }

    public function getPhoneCreditErrorProperty()
    {
        $phoneCreditError = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'ERROR')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditError;
    }

    public function getPhoneCreditSuccessProperty()
    {
        $phoneCreditSuccess = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditSuccess;
    }

    public function getPhoneCreditPenddingProperty()
    {
        $phoneCreditPendding = WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'PENDING')
            ->whereDate('wallet_phone_credits.created_at', Carbon::now())
            ->count();
        return $phoneCreditPendding;
    }

    public function getDepositErrorProperty()
    {
        $depositError = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'ERROR')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositError;
    }

    public function getDepositSuccessProperty()
    {
        $depositSuccess = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'SUCCESS')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositSuccess;
    }

    public function getDepositPenddingProperty()
    {
        $depositPendding = WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                Auth::guard('web')->user()->country->id
            )
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'PENDING')
            ->whereDate('wallet_deposits.created_at', Carbon::now())
            ->count();
        return $depositPendding;
    }
}
