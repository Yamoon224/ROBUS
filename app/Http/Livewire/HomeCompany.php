<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Categorie;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;

class HomeCompany extends Component
{
    public $startDate = '';
    public $endDate = '';
    public $wallet_id;
    public $user_id;
    public $categorieId;
    public $employerDesabled;
    public $amount_payment;
    public $choose_type_payement_emp;
    public $wallet_id_employer;
    public $amount_payment_wallet;

    public function cancelEmployesField()
    {

        $this->wallet_id = '';
        $this->amount_payment = '';
        $this->amount_payment_wallet = '';
        $this->wallet_id_employer = '';
        $this->categorieId = null;
        $this->choose_type_payement_emp = null;
        $this->emit('modal-hide', 'hide');
    }


    public function disable()
    {
        $check = SidUser::find($this->user_id);
        if (!empty($check)) {
            if ($check->company_id == firm()->id) $check->update(['company_id' => 1, 'categorie_id' => null]);
            else $check->update(['company_id' => firm()->id, 'categorie_id' => null]);
            $this->emit('modal-hide', 'hide');
            $this->cancelEmployesField();
            $this->emit('success', $check->company_id == firm()->id ? 'Client activée !' : 'Client désactivée !');
        }
    }

    public function update()
    {

        $this->validate([
            'wallet_id' => 'required',
            'categorieId' => 'required',
        ], [
            'wallet_id.required' => 'Votre wallet est obligatoire',
            'categorieId.required' => 'la categorie est obligatoire',

        ]);
        $check =  Wallet::where('wallet_id', $this->wallet_id)->first();

        $check->sidUser->update([
            'categorie_id'  => $this->categorieId,
        ]);

        $this->cancelEmployesField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Categorie modifié !');
    }

    public function edit($id, $state)
    {
        $check = Wallet::whereId($id)->first();
        $this->user_id = $check->sidUser->id;
        $this->wallet_id = $check->wallet_id;
        $this->employerDesabled = $check->company_id;
        $this->categorieId = $check->sidUser->categorie_id;
        $this->emit($state);
    }

    public function render()
    {
        $walletUser = Auth::guard('company')->user();
        $wallet_info_firstname = null;
        $wallet_info_lastname = null;
        $wallet_payment_firstname = null;
        $wallet_payment_lastname = null;

        $wallets = Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.is_custumers', true)
            // ->orWhere('sid_users.is_under_distributer', true)
            ->where('wallets.status', true)
            ->Where('wallets.country_id', firm()->country->id)
            ->get();

        $wallet_payments = Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('sid_users.company_id', firm()->id)
            // ->orWhere('sid_users.is_under_distributer', true)
            ->where('wallets.status', true)
            ->Where('wallets.country_id', firm()->country->id)
            ->get();
        if ($this->wallet_id != '') {


            foreach ($wallets  as $wallet_user) {

                $wallet_search_user = $wallet_user->where('wallet_id', $this->wallet_id)->first();

                if ($wallet_search_user == null) {

                    $wallet_info_firstname = "N'existe pas chez nous";
                } else {

                    $wallet_info_firstname = $wallet_search_user->sidUser->firstname ?? null;

                    $wallet_info_lastname = $wallet_search_user->sidUser->lastname ?? null;
                }
            }
        }

        if ($this->wallet_id_employer != '') {


            foreach ($wallet_payments  as $wallet_user) {

                $wallet_search_user = $wallet_user->where('wallet_id', $this->wallet_id_employer)->first();



                if ($wallet_search_user == null) {

                    $wallet_payment_firstname = "N'existe pas chez nous";
                } else {

                    $wallet_payment_firstname = $wallet_search_user->sidUser->firstname ?? null;

                    $wallet_payment_lastname = $wallet_search_user->sidUser->lastname ?? null;
                }
            }
        }

        return view('livewire.home-company', [
            'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
            'sum_error' =>  $this->TransactionErrorUser,
            'sum_success' =>  $this->TransactionSuccessUser,
            'pending_success' => $this->TransactionPenddingUser,
            "users" => SidUser::where('company_id', firm()->id)->orderBy('created_at', 'desc')->limit(3)->latest()->get(),
            'sum_amount' => $this->WalletTransactionSumUser,
            'wallet_info_searchs' => $wallet_info_firstname . '  ' . $wallet_info_lastname,
            'wallet_info_search_payment' => $wallet_payment_firstname . '  ' . $wallet_payment_lastname,
            "categories" => Categorie::where('sid_user_id', firm()->id)->orderBy("name", 'desc')->get(),
            'usersCountry' => $walletUser->country->currencie->name,
            'sum_transaction' => $this->WalletTransactionSumUser,
            'sum_transaction_month' => $this->WalletTransactionMonthUser,
            'realods' => $this->RealodsAdmin,
            // 'reloads_gain'  =>  $this->RealodsAdminGain,
            'reloads_days'  =>  $this->RealodsAdminDays,
        ]);
    }

    public function getRealodsAdminDaysProperty()
    {

        $reload1 = [];
        $reload2 = [];
        $reload3 = [];
        $reload4 = [];
        $reload5 = [];
        $reload6 = [];
        $reload7 = [];

        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::MONDAY) {

                $reload1[] = ($reload->amount);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::TUESDAY) {

                $reload2[] = ($reload->amount);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::WEDNESDAY) {

                $reload3[] = ($reload->amount);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::THURSDAY) {

                $reload4[] = ($reload->amount);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::FRIDAY) {

                $reload5[] = ($reload->amount);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SATURDAY) {

                $reload6[] = ($reload->amount);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SUNDAY) {

                $reload7[] = ($reload->amount);
            }
        }
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7)];
        // dd($reloads);
        return $reloads;
    }

    public function getRealodsAdminProperty()
    {

        $reload1 = [];
        $reload2 = [];
        $reload3 = [];
        $reload4 = [];
        $reload5 = [];
        $reload6 = [];
        $reload7 = [];
        $reload8 = [];
        $reload9 = [];
        $reload10 = [];
        $reload11 = [];
        $reload12 = [];

        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->amount);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->amount);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
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
                firm()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->where('wallet_transactions.status', 'PENDING')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionPendding;
    }
    public function getTransactionErrorUserProperty()
    {
        $transactionError = WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
        )
            ->where(
                'wallets.country_id',
                firm()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->where('wallet_transactions.status', 'ERROR')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionError;
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
                    firm()->country->id
                )
                ->select('wallet_transactions.*')
                ->where(
                    'wallet_transactions.created_by',
                    firm()->id
                )
                ->whereDate('wallet_transactions.created_at', Carbon::now())
                ->where('wallet_transactions.status', 'SUCCESS')
                ->get()
            as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
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
                firm()->country->id
            )
            ->select('wallet_transactions.*')
            ->where(
                'wallet_transactions.created_by',
                firm()->id
            )
            ->where('wallet_transactions.status', 'SUCCESS')
            ->whereDate('wallet_transactions.created_at', Carbon::now())
            ->count();
        return $transactionSuccess;
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
                firm()->country->id
            )
            ->where(
                'wallet_transactions.created_by',
                firm()->id
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
}
