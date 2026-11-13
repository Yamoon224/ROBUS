<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\Custumers\Country;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletPixelReload;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Historique des opérations mobile money Pixel Innov (références PIX_*) d'un client.
 *
 * Mobile Zone = pays du destinataire identique à celui du client ; International Zone = pays différent
 * (le pays du destinataire est enregistré dans metadata.country_iso2).
 */
class PixelDeposits extends Component
{
    use WithPagination;

    public $walletPixels;
    public $zone = 'mobile'; // 'mobile' | 'international'
    public $search = '';

    public function mount($walletPixels, $zone = 'mobile')
    {
        $this->walletPixels = $walletPixels;
        $this->zone = $zone === 'international' ? 'international' : 'mobile';
    }

    public $flash = null;
    public $flashOk = true;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function cancelPending($id)
    {
        $result = app(\App\Http\Services\PixelCancelService::class)->cancelReload((int) $id);
        $this->flash   = $result['message'];
        $this->flashOk = $result['ok'];
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

        $query = WalletPixelReload::query()->includingOrphans()->where('wallet_reloads.wallet_id', $wallet->id ?? 0);

        // metadata peut être doublement encodé : on retire les antislashs avant de comparer
        $iso = strtoupper((string) Country::whereKey($wallet->country_id ?? 0)->value('code'));
        $raw = "REPLACE(wallet_reloads.metadata, CHAR(92), '')";
        $own = '%"country_iso2":"' . $iso . '"%';

        if ($this->zone === 'international') {
            $query->whereRaw("$raw LIKE '%\"country_iso2\":%'")->whereRaw("$raw NOT LIKE ?", [$own]);
        } else {
            $query->where(function ($q) use ($raw, $own) {
                $q->whereRaw("$raw LIKE ?", [$own])->orWhereRaw("$raw NOT LIKE '%\"country_iso2\":%'");
            });
        }

        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('reference', 'like', $s)
                    ->orWhere('metadata', 'like', $s)
                    ->orWhere('amount', 'like', $s);
            });
        }

        return view('livewire.transaction-details.pixel-deposits', [
            'zone_label' => $this->zone === 'international' ? 'Zone internationale' : 'Zone mobile',
            'canCancel'  => \App\Http\Services\PixelCancelService::userCanCancel(),
            'pixel_historicals' => $query->orderBy('created_at', 'desc')->paginate(20),
        ]);
    }
}
