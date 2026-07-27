<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallets\WalletTransaction;
use App\Http\Services\HistoriqueService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;


class TransferHisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchTransferHistorical = "";
    public $statusTransferFilter = "";
    public  $startDate = "";
    public  $endDate =  "";
    public  $beginDate = "";
    public  $endDateT =  "";



    public function resetTransactionField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {
        $walletUser = Auth::guard('web')->user() ?? Auth::guard('integration')->user();
        if (!$walletUser) abort(403);

        $transfers = WalletTransaction::with([
            'destinationWallet.sidUser.country',
            'destinationWallet.sidUser.city',
        ])->whereIsWeb(false);

        // Restriction par rôle
        if ($walletUser->is_under_distributer || $walletUser->is_api) {
            $transfers->where('wallet_transactions.created_by', $walletUser->id);
        } else {
            // Admins / superviseurs : limite par défaut à 30 jours
            $transfers->whereBetween('wallet_transactions.created_at', [
                Carbon::now()->subDays(30),
                Carbon::now()
            ]);
        }

        // Filtre dates
        if ($this->startDate && $this->endDate) {
            $transfers->whereBetween('wallet_transactions.created_at', [$this->startDate, $this->endDate]);
        }

        // Recherche
        if ($this->searchTransferHistorical != "") {
            $search = '%' . $this->searchTransferHistorical . '%';
            $transfers->where(function ($q) use ($search) {
                $q->where('wallet_transactions.amount', 'LIKE', $search)
                    ->orWhere('wallet_transactions.num_wallet', 'LIKE', $search)
                    ->orWhere('wallet_transactions.phone', 'LIKE', $search)
                    ->orWhereHas('transaction', function ($sub) use ($search) {
                        $sub->where('operator_transaction_name', 'LIKE', $search)
                            ->orWhere('transaction_id', 'LIKE', $search)
                            ->orWhere('trans_comp_id', 'LIKE', $search);
                    });
            });
        }

        // Filtre statut
        if ($this->statusTransferFilter != "") {
            $transfers->where('wallet_transactions.status', $this->statusTransferFilter);
        }

        $transfers = $transfers->orderBy('wallet_transactions.created_at', 'desc')->paginate(15);

        // Récupération des stats (via service, mais avec cache)
        $userStats = Cache::remember("histo_user_{$walletUser->id}", 600, function () use ($walletUser) {
            return app(HistoriqueService::class)->getTransactionsAllDaysProperty(
                WalletTransaction::class,
                'wallet_transactions.is_web',
                'wallet_transactions.created_by',
                'wallet_transactions.updated_by'
            );
        });

        return view('livewire.historical.transfer-histo', [
            'user' => $userStats,
            'transfer_historicals' => $transfers,
            'usersCountry' => $walletUser->country->currencie->name,
        ]);
    }
}
