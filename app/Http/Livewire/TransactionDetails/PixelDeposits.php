<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletPixelReload;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Historique des dépôts mobile money (Pixel Innov, références PIX_*) d'un client.
 */
class PixelDeposits extends Component
{
    use WithPagination;

    public $walletPixels;
    public $search = '';

    public function mount($walletPixels)
    {
        $this->walletPixels = $walletPixels;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function gotoPagePaie($page)
    {
        $this->setPage($page);
    }

    public function nextPagePaie()
    {
        $this->setPage($this->page + 1);
    }

    public function previousPagePaie()
    {
        $this->setPage(max($this->page - 1, 1));
    }

    public function render()
    {
        $wallet = Wallet::where('sid_user_id', $this->walletPixels)->first();

        $query = WalletPixelReload::query()->where('wallet_reloads.wallet_id', $wallet->id ?? 0);

        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('reference', 'like', $s)
                    ->orWhere('metadata', 'like', $s)
                    ->orWhere('amount', 'like', $s);
            });
        }

        return view('livewire.transaction-details.pixel-deposits', [
            'pixel_historicals' => $query->orderBy('created_at', 'desc')->paginate(20),
        ]);
    }
}
