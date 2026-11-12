<?php

namespace App\Http\Livewire\TransactionDetails;

use App\Models\PixelMobileZoneTransfer;
use App\Models\Wallets\Wallet;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Transferts Mobile Zone / International Zone (pixel_mobile_zone_transfers) d'un client ou sous-distributeur.
 *
 * Mobile Zone = pays expéditeur identique au pays destinataire ; International Zone = pays différents.
 */
class PixelZoneTransfers extends Component
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

        $query = PixelMobileZoneTransfer::with(['senderService.provider', 'recipientService.provider'])
            ->where('wallet_id', $wallet->id ?? 0);

        if ($this->zone === 'international') {
            $query->whereColumn('sender_country_iso2', '!=', 'recipient_country_iso2');
        } else {
            $query->where(function ($q) {
                $q->whereColumn('sender_country_iso2', 'recipient_country_iso2')
                    ->orWhereNull('sender_country_iso2')
                    ->orWhereNull('recipient_country_iso2');
            });
        }

        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('sender_transaction_id', 'like', $s)
                    ->orWhere('recipient_transaction_id', 'like', $s)
                    ->orWhere('sender_phone', 'like', $s)
                    ->orWhere('recipient_phone', 'like', $s)
                    ->orWhere('amount', 'like', $s);
            });
        }

        return view('livewire.transaction-details.pixel-zone-transfers', [
            'zone_label'  => $this->zone === 'international' ? 'Zone internationale' : 'Zone mobile',
            'transfers'   => $query->orderBy('created_at', 'desc')->paginate(20),
        ]);
    }
}
