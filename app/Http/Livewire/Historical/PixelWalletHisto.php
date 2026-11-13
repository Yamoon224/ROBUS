<?php

namespace App\Http\Livewire\Historical;

use App\Models\Custumers\Country;
use App\Models\Wallets\WalletPixelReload;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Historique global des opérations Pixel Innov effectuées depuis le wallet des clients
 * (wallet_reloads, références PIX_*), pour tous les pays.
 *
 * Mobile Zone = pays du destinataire identique à celui du wallet ; International Zone = pays différent.
 */
class PixelWalletHisto extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusFilter = '';
    public $zoneFilter = '';
    public $countryFilter = '';
    public $startDate = '';
    public $endDate = '';

    public $flash = null;
    public $flashOk = true;

    public function updated($name)
    {
        $this->resetPage();
    }

    public function cancelPending($id)
    {
        $result = app(\App\Http\Services\PixelCancelService::class)->cancelReload((int) $id);
        $this->flash   = $result['message'];
        $this->flashOk = $result['ok'];
    }

    public function render()
    {
        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check() ? Auth::guard('integration')->user() : null);

        if (!$walletUser) {
            abort(403);
        }

        // metadata peut être doublement encodé : on retire les antislashs avant de comparer
        $raw = "REPLACE(wallet_reloads.metadata, CHAR(92), '')";
        $sameCountry = "$raw LIKE CONCAT('%\"country_iso2\":\"', countries.code, '\"%')";
        $hasCountry = "$raw LIKE '%\"country_iso2\":%'";

        $query = WalletPixelReload::query()
            ->includingOrphans()
            ->join('wallets', 'wallet_reloads.wallet_id', '=', 'wallets.id')
            ->leftJoin('countries', 'wallets.country_id', '=', 'countries.id')
            ->with(['wallet.sidUser'])
            ->when($walletUser->is_under_distributer || $walletUser->is_api, function ($q) use ($walletUser) {
                $q->where('wallets.sid_user_id', $walletUser->id);
            })
            ->when($walletUser->is_com_zone, function ($q) use ($walletUser) {
                $q->whereIn('wallets.sid_user_id', function ($sub) use ($walletUser) {
                    $sub->select('id')->from('sid_users')->where('com_zone_id', $walletUser->id);
                });
            });

        if ($this->countryFilter !== '') {
            $query->where('wallets.country_id', $this->countryFilter);
        }

        if ($this->zoneFilter === 'international') {
            $query->whereRaw("$hasCountry AND NOT ($sameCountry)");
        } elseif ($this->zoneFilter === 'mobile') {
            $query->whereRaw("(NOT ($hasCountry) OR $sameCountry)");
        }

        if ($this->statusFilter !== '') {
            $query->where('wallet_reloads.status', $this->statusFilter);
        }

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('wallet_reloads.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ]);
        }

        if ($this->search !== '') {
            $s = '%' . $this->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('wallet_reloads.reference', 'like', $s)
                    ->orWhere('wallet_reloads.metadata', 'like', $s)
                    ->orWhere('wallet_reloads.amount', 'like', $s)
                    ->orWhereIn('wallets.sid_user_id', function ($sub) use ($s) {
                        $sub->select('id')->from('sid_users')
                            ->where('firstname', 'like', $s)
                            ->orWhere('lastname', 'like', $s)
                            ->orWhere('phone', 'like', $s);
                    });
            });
        }

        $stats = (clone $query)->reorder();

        return view('livewire.historical.pixel-wallet-histo', [
            'historicals'  => $query->select('wallet_reloads.*', 'countries.code as wallet_country_code', 'countries.name as wallet_country_name')
                ->orderBy('wallet_reloads.created_at', 'desc')->paginate(15),
            'countries'    => Country::orderBy('name')->get(['id', 'name']),
            'canCancel'    => \App\Http\Services\PixelCancelService::userCanCancel(),
            'totalCount'   => (clone $stats)->count(),
            'successCount' => (clone $stats)->where('wallet_reloads.status', 'SUCCESS')->count(),
            'pendingCount' => (clone $stats)->whereIn('wallet_reloads.status', ['PENDING', 'PROCESSING'])->count(),
            'errorCount'   => (clone $stats)->whereIn('wallet_reloads.status', ['FAILED', 'ERROR', 'REFUNDED'])->count(),
        ]);
    }
}
