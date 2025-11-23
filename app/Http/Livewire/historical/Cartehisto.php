<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Events\AdminNotifEvent;
use App\Models\Payments\Deposit;
use App\Models\Costs\DepositCost;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Events\UpdateReloadCardEvent;
use App\Models\Wallets\WalletDeposit;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;
use App\Http\Services\HistoriqueService;

class Cartehisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchCarteHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";


    public function resetReloadCardField()
    {

        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {

        $cartes =  WalletDeposit::whereIsWeb(false);

      $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);

        if (sabdy()->is_under_distributer || $walletUser->is_api) {
            $cartes->where('wallet_deposits.created_by', sabdy()->id);
        }


        if ($this->startDate && $this->endDate) {

            // $cartes->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.cartehisto',
                [
                     'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletDeposit::class , $this->startDate,$this->endDate , 'wallet_deposits.created_by' , 'wallet_deposits.updated_by'),
                    'card_historicals' => $cartes->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->whereBetween('wallet_deposits.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,
                ]
            );
        }

        if ($this->searchCarteHistorical != "") {

            $this->resetPage();

            $cartes->orwhereHas(
                'deposit',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchCarteHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchCarteHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchCarteHistorical  . '%');
                }
            )
                ->orWhere("wallet_deposits.amount", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                ->orWhere("wallet_deposits.num_carte", "LIKE",  "%" . $this->searchCarteHistorical . "%")
                ->orWhere("wallet_deposits.name", "LIKE",  "%" . $this->searchCarteHistorical . "%");
        }

        if ($this->statusFilter != "") {

            return view(
                'livewire.historical.cartehisto',
                [
                     'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletDeposit::class , 'wallet_deposits.is_web', 'wallet_deposits.status' , $this->statusTransferFilter , 'wallet_deposits.created_by' , 'wallet_deposits.updated_by'),
                    'card_historicals' => $cartes->where("wallet_deposits.status", $this->statusFilter)->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.cartehisto',
            [

                 'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletDeposit::class , 'wallet_deposits.is_web' , 'wallet_deposits.created_by' , 'wallet_deposits.updated_by'),
                'card_historicals' => $cartes->join('wallets', 'wallet_deposits.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->orderBy('wallet_deposits.created_at', 'desc')->select('wallet_deposits.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,

            ]
        );
    }


}
