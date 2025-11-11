<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\WithMerchant;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Models\Wallets\WalletAdmin;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Facades\Auth;

class CommissionUnderDistributer extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public  $startDate = "";
    public  $endDate =  "";
    public $searchDrawersHistorical = "";

    public function render()
    {
        $drawers =  WithMerchant::query();

        $walletUser = Auth::guard('web')->user();


        if ($this->startDate && $this->endDate) {

            // $drawers->whereBetween('created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.commission-under-distributer',
                [
                    'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                    'walletCommissions' => WalletCommission::where('status', 1)->sum('amount'),
                    'walletAdmin' => WalletAdmin::where('status', 1)->sum('amount'),
                    'payment_drawers' =>   $drawers->whereBetween('created_at', [$this->startDate, $this->endDate])->orderBy('created_at', 'desc')->paginate(20),
                    'sum_commissions' => $this->DrawerPaymentDays,
                ]
            );
        }

        if ($this->searchDrawersHistorical != "") {

            $this->resetPage();

            $drawers->orwhereHas(
                'sidUser',
                function ($query) {
                    $query->where('firstname', 'like', '%' . $this->searchDrawersHistorical . '%')
                        ->orWhere('lastname', 'like', '%' .  $this->searchDrawersHistorical   . '%');
                }
            )
                ->Where("with_merchants.amount", "like",  "%" . $this->searchDrawersHistorical . "%");
        }

        return view(
            'livewire.historical.commission-under-distributer',
            [
                'walletSolde' => Wallet::where('sid_user_id', $walletUser->id)->sum('amount'),
                'walletCommissions' => WalletCommission::where('status', 1)->sum('amount'),
                'walletAdmin' => WalletAdmin::where('status', 1)->sum('amount'),
                'payment_drawers' =>   $drawers->orderBy('created_at', 'desc')->paginate(20),
                'sum_commissions' => $this->DrawerPayment,
            ]
        );
    }

    public function getDrawerPaymentProperty()
    {

        $data = [];
        foreach ((WithMerchant::join('sid_users', 'with_merchants.sid_user_id', 'sid_users.id')
                ->where(
                    'sid_users.country_id',
                    107
                )->whereDate('date_removed', Carbon::now()))->get() as $item
        ) {
            $data[] = $item->amount_with_draw;
        }

        return array_sum($data);
    }


    public function getDrawerPaymentDaysProperty()
    {

        $data = [];
        foreach ((WithMerchant::join('sid_users', 'with_merchants.sid_user_id', 'sid_users.id')
                ->where(
                    'sid_users.country_id',
                    107
                )->whereBetween('with_merchants.created_at', [$this->startDate, $this->endDate]))->get() as $item
        ) {
            $data[] = $item->amount_with_draw;
        }

        return array_sum($data);
    }
}
