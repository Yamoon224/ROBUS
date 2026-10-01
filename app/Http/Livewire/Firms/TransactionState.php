<?php

namespace App\Http\Livewire\Firms;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;

class TransactionState extends Component
{

    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {


        $walletUser = firm();


        $realod = WalletTransaction::query();
        if ($this->startDate && $this->endDate) {

            $realod->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $data = [];
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }


        return view(
            'livewire.firms.transaction-state',
            [
                'user_sum' => array_sum($data),
                'reload_historicals' =>   $realod->where('wallet_id', firm()->wallet->id)->orderBy('created_at', 'desc')->paginate(15),
                'usersCountry' =>  $walletUser->country->currencie->name,
                'sum_commissions' => $this->WalletReloadSumGain,
                'realods' => $this->RealodsAdmin,
                'reloads_days'  =>  $this->RealodsAdminDays,
                'gainPaiementsMonth' => $this->RealodsAdminGain,
                'gainPaiementsDays'  =>  $this->RealodsAdminDaysGain,
            ]
        );
    }

    public function getWalletReloadSumGainProperty()
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->sum('commission_companie');
    }


    public function getWalletReloadSumGainUserProperty()
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->sum('commission_companie');
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

    public function getRealodsAdminDaysGainProperty()
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

                $reload1[] = ($reload->commission_companie);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::TUESDAY) {

                $reload2[] = ($reload->commission_companie);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::WEDNESDAY) {

                $reload3[] = ($reload->commission_companie);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::THURSDAY) {

                $reload4[] = ($reload->commission_companie);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::FRIDAY) {

                $reload5[] = ($reload->commission_companie);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SATURDAY) {

                $reload6[] = ($reload->commission_companie);
            }
        }
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SUNDAY) {

                $reload7[] = ($reload->commission_companie);
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

        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->commission_companie);
        foreach (WalletTransaction::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->commission_companie);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];
        // dd($reloads);
        return $reloads;
    }


}
