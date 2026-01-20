<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Http\Services\HistoriqueService;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\Wallets\WalletBankSend;

class BankSendHisto extends Component
{


    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    public function render()
    {

        $walletTransaction =  WalletBankSend::whereIsWeb(false);

        $walletUser = Auth::guard('web')->user();

        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check()
                ? Auth::guard('integration')->user()
                : null);

        if ($walletUser->is_under_distributer  || $walletUser->is_api) {
            $walletTransaction->where('wallet_bank_sends.created_by', $walletUser->id);
        }


        if ($this->startDate && $this->endDate) {


            return view(
                'livewire.historical.bank-send-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsDaysProperty(WalletBankSend::class, $this->startDate, $this->endDate, 'wallet_bank_sends.created_by', 'wallet_bank_sends.updated_by'),
                    'bankSend_historicals' =>   $walletTransaction->join('wallets', 'wallet_bank_sends.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_bank_sends.is_web', false)->whereBetween('wallet_bank_sends.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_bank_sends.created_at', 'desc')->select('wallet_bank_sends.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        if ($this->searchHistorical != "") {

            $this->resetPage();

            $walletTransaction->orwhereHas(
                'transaction',
                function ($query) {
                    $query->where('operator_transaction_name', 'LIKE', '%' . $this->searchHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchHistorical  . '%');
                }
            )
                ->orWhere("wallet_bank_sends.amount", "LIKE",  "%" . $this->searchHistorical . "%")
                ->orWhere("wallet_bank_sends.phone", "LIKE",  "%" . $this->searchHistorical . "%");
        }

        if ($this->statusFilter != "") {


            return view(
                'livewire.historical.bank-send-histo',
                [
                    'user' =>  app(HistoriqueService::class)->getTransactionsStatusProperty(WalletBankSend::class, 'wallet_bank_sends.is_web', 'wallet_bank_sends.status', $this->statusFilter, 'wallet_bank_sends.created_by', 'wallet_bank_sends.updated_by'),
                    'bankSend_historicals' =>   $walletTransaction->join('wallets', 'wallet_bank_sends.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', $walletUser->country->id)->where('wallet_bank_sends.is_web', false)->orderBy('wallet_bank_sends.created_at', 'desc')->where("wallet_bank_sends.status", $this->statusFilter)->select('wallet_bank_sends.*')->paginate(15),
                    'usersCountry' =>   $walletUser->country->currencie->name,

                ]
            );
        }

        return view(
            'livewire.historical.bank-send-histo',
            [
                'user' =>  app(HistoriqueService::class)->getTransactionsAllDaysProperty(WalletBankSend::class, 'wallet_bank_sends.is_web', 'wallet_bank_sends.created_by', 'wallet_bank_sends.updated_by'),
                'bankSend_historicals' =>  $walletTransaction->join('wallets', 'wallet_bank_sends.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', $walletUser->country->id)->where('wallet_bank_sends.is_web', false)->orderBy('wallet_bank_sends.created_at', 'desc')->select('wallet_bank_sends.*')->paginate(15),
                'usersCountry' =>   $walletUser->country->currencie->name,
            ]
        );
    }

}
