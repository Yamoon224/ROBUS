<?php

namespace App\Http\Livewire\States;

use Livewire\Component;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\Auth;

class Bankstate extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = Auth::guard('web')->user();


        $realod = WalletBank::query();


        if ($walletUser->wallet->sidUser->is_distributer == true || $walletUser->wallet->sidUser->is_under_distributer == true || $walletUser->wallet->sidUser->is_custumers == true) {

            if ($this->startDate && $this->endDate) {

                $realod->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate]);
            }

            $data = [];
            foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
                $data[] = $bank->amount;
            }

            return view(
                'livewire.states.bankstate',
                [
                    'user_sum' => array_sum($data),
                    'reload_historicals' =>   $realod->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletReloadSumGainUser,
                    'realods' => $this->RealodsUser,
                    'reloads_gain'  =>  $this->RealodsUserGain,

                ]
            );
        } else if ($walletUser->wallet->sidUser->is_distributer == false && $walletUser->wallet->sidUser->is_under_distributer == false && $walletUser->wallet->sidUser->is_custumers == false) {


            if ($this->startDate && $this->endDate) {

                $realod->whereBetween('wallet_banks.created_at', [$this->startDate, $this->endDate]);
            }

            $data = [];
            foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
                $data[] = $bank->amount;
            }

            return view(
                'livewire.states.bankstate',
                [
                    'user_sum' => array_sum($data),
                    'reload_historicals' =>   $realod->join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->select('wallet_banks.*')->orderBy('wallet_banks.created_at', 'desc')->paginate(15),
                    'usersCountry' =>  $walletUser->country->currencie->name,
                    'sum_commissions' => $this->WalletReloadSumGain,
                    'realods' => $this->RealodsAdmin,
                    'reloads_gain'  =>  $this->RealodsAdminGain,
                ]
            );
        }
    }


    public function getWalletReloadSumGainProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
            $data[] = $bank->commissions;
        }

        return array_sum($data);
    }


    public function getWalletReloadSumGainUserProperty()
    {

        $data = [];
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->get() as $bank) {
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

        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-12-%')->get() as $reload)
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

        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-12-%')->get() as $reload)
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

        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->amount);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-12-%')->get() as $reload)
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

        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->commissions);
        foreach (WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where('wallet_banks.created_by', Auth::guard('web')->user()->id)->where('wallet_banks.status', 'SUCCESS')->select('wallet_banks.*')->where('wallet_banks.created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->commissions);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
    }
}