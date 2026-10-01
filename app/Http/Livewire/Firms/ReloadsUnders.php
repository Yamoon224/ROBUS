<?php

namespace App\Http\Livewire\Firms;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletReload;


class ReloadsUnders extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchRealod = "";
    public $statusFilter = "";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    // valeur wallet id
    protected $listeners = [
        "echo-private:wallet-reload-created,.Illuminate\Notifications\Events\BroadcastNotificationCreated" => 'notifyNewReload',
        'echo:wallet-reload-created,.Created_wallet_reload' => 'notifyNewReload',
        'echo:updated_reload,.update_wallet_reload' => 'notifyNewReload',
        'echo:update_all_wallet,.wallet_update_all' => 'notifyNewReload',
        // Choose
        'echo:wallet-reload-choose-created,.Created_wallet_reload_choose' => 'notifyNewReload',
    ];



    public function render()
    {

        $walletId = firm()->wallet->id;
        $walletUser = firm();
        $transfersQuery =  WalletReload::where('wallet_id', $walletId);

        if ($this->startDate && $this->endDate) {
            $transfersQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }
    
        if (!empty($this->searchTransferHistorical)) {
            $this->resetPage();
            $transfersQuery->where(function ($query) {
                $query->whereHas('paymentReload', function ($subQuery) {
                    $subQuery->where('operator_transaction_name', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                        ->orWhere('transaction_id', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                        ->orWhere('trans_comp_id', 'LIKE', '%' . $this->searchTransferHistorical . '%');
                })
                ->orWhere('amount', 'LIKE', '%' . $this->searchTransferHistorical . '%')
                ->orWhere('num_wallet', 'LIKE', '%' . $this->searchTransferHistorical . '%');
            });
        }
    
        if (!empty($this->statusTransferFilter)) {
            $transfersQuery->where('status', $this->statusTransferFilter);
        }
    
        $transferHistoricals = $transfersQuery->orderByDesc('created_at')->paginate(15);
    

        return view(
            'livewire.firms.reloads-unders',
            [
                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'WalletReloads' => $this->WalletReloads,
                'reload_error' => WalletReload::where('wallet_id', $walletUser->wallet->id)->where('status', 'ERROR')->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])->count(),
                'reload_success' =>   WalletReload::where('wallet_id', $walletUser->wallet->id)->where('status', 'SUCCESS')->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])->count(),
                'reload_pendding' =>   WalletReload::where('wallet_id', $walletUser->wallet->id)->where('status', 'PENDING')->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])->count(),
                'reaload_uv' => $transferHistoricals,
                'usersCountry' => $walletUser->country->currencie->name,
                'realods' => $this->RealodsAdmin,
                'reloads_days'  =>  $this->RealodsAdminDays,
            ]
        );
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

        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::MONDAY) {

                $reload1[] = ($reload->amount);
            }
        }
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {

            if ($reload->created_at->dayOfWeek === Carbon::TUESDAY) {

                $reload2[] = ($reload->amount);
            }
        }
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::WEDNESDAY) {

                $reload3[] = ($reload->amount);
            }
        }
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::THURSDAY) {

                $reload4[] = ($reload->amount);
            }
        }
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::FRIDAY) {

                $reload5[] = ($reload->amount);
            }
        }
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
            if ($reload->created_at->dayOfWeek === Carbon::SATURDAY) {

                $reload6[] = ($reload->amount);
            }
        }
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->get() as $reload) {
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

        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-01-%')->get() as $reload)
            $reload1[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-02-%')->get() as $reload)
            $reload2[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-03-%')->get() as $reload)
            $reload3[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-04-%')->get() as $reload)
            $reload4[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-05-%')->get() as $reload)
            $reload5[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-06-%')->get() as $reload)
            $reload6[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-07-%')->get() as $reload)
            $reload7[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-08-%')->get() as $reload)
            $reload8[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-09-%')->get() as $reload)
            $reload9[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-10-%')->get() as $reload)
            $reload10[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-11-%')->get() as $reload)
            $reload11[] = ($reload->amount);
        foreach (WalletReload::where('wallet_id', firm()->wallet->id)->where('status', 'SUCCESS')->where('created_at', 'like', '%-12-%')->get() as $reload)
            $reload12[] = ($reload->amount);
        $reloads = [array_sum($reload1), array_sum($reload2), array_sum($reload3), array_sum($reload4), array_sum($reload5), array_sum($reload6), array_sum($reload7), array_sum($reload8), array_sum($reload9), array_sum($reload10), array_sum($reload11), array_sum($reload12)];

        return $reloads;
    }


    public function getWalletReloadsProperty()
    {

        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where('wallets.country_id', firm()->country->id)->where('wallet_reloads.status', 'SUCCESS')->select('wallet_reloads.*')->sum('wallet_reloads.amount');
    }

 
}
