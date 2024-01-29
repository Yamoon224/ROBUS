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

class HomeMasters extends Component
{

    public $startDate = '';
    public $endDate = '';

    public function render()
    {
        $walletUser = Auth::guard('web')->user();

        return view('livewire.home-masters');
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
}
