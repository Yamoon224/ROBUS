<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;

class Transferstate extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletTransaction::query();


        if ($walletUser->wallet->sidUser->is_under_distributer == true) {

            if ($this->startDate && $this->endDate) {

                $realod->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate]);
            }

            $data = [];
            foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get() as $bank) {
                $data[] = $bank->amount;
            }


            return view(
                'livewire.states.transferstate',
                [
                    'user_sum' => array_sum($data),
                    'reload_historicals' =>   $realod->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->select('wallet_transactions.*')->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletReloadSumGainUser,
                    'realods' => $this->RealodsUser,
                    'reloads_gain'  =>  $this->RealodsUserGain,

                ]
            );
        } else {



            if ($this->startDate && $this->endDate) {

                $realod->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate]);
            }

            $data = [];
            foreach (WalletTransaction::where('status', 'SUCCESS')->get() as $bank) {
                $data[] = $bank->amount;
            }


            return view(
                'livewire.states.transferstate',
                [
                    'user_sum' => array_sum($data),
                    'reload_historicals' =>   $realod->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->select('wallet_transactions.*')->orderBy('wallet_transactions.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletReloadSumGain,
                    'realods' => $this->RealodsAdmin,
                    '   '  =>  $this->RealodsAdminGain,
                ]
            );
        }
    }

    public function getWalletReloadSumGainProperty()
    {

        $data = [];
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }


    public function getWalletReloadSumGainUserProperty()
    {

        $data = [];
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
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

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->amount);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
    }

    public function getRealodsAdminGainProperty()
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

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->commissions);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
    }

    public function getRealodsUserProperty()
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

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->amount);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->amount);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
    }

    public function getRealodsUserGainProperty()
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

        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->commissions);
        foreach (WalletTransaction::join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_transactions.created_by', Auth::guard('web')->user()->id)->where('wallet_transactions.status', 'SUCCESS')->select('wallet_transactions.*')->where('wallet_transactions.created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->commissions);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
    }
}