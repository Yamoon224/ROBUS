<?php

namespace App\Http\Livewire\Historical;

use App\Models\PixelMobileZoneTransfer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PixelMobileZoneHisto extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchHistorical = '';
    public $statusFilter = '';
    public $startDate = '';
    public $endDate = '';

    public function render()
    {
        $walletUser = Auth::guard('web')->check()
            ? Auth::guard('web')->user()
            : (Auth::guard('integration')->check() ? Auth::guard('integration')->user() : null);

        if (!$walletUser) {
            abort(403);
        }

        $query = $this->baseQuery($walletUser);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('pixel_mobile_zone_transfers.created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59',
            ]);
        }

        if ($this->statusFilter !== '') {
            $query->where('pixel_mobile_zone_transfers.status', $this->statusFilter);
        }

        if ($this->searchHistorical !== '') {
            $this->resetPage();
            $search = '%' . $this->searchHistorical . '%';

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('pixel_mobile_zone_transfers.sender_phone', 'LIKE', $search)
                    ->orWhere('pixel_mobile_zone_transfers.recipient_phone', 'LIKE', $search)
                    ->orWhere('pixel_mobile_zone_transfers.sender_transaction_id', 'LIKE', $search)
                    ->orWhere('pixel_mobile_zone_transfers.recipient_transaction_id', 'LIKE', $search)
                    ->orWhere('pixel_mobile_zone_transfers.amount', 'LIKE', $search)
                    ->orWhereHas('senderService.provider', function ($providerQuery) use ($search) {
                        $providerQuery->where('name', 'LIKE', $search);
                    })
                    ->orWhereHas('recipientService.provider', function ($providerQuery) use ($search) {
                        $providerQuery->where('name', 'LIKE', $search);
                    })
                    ->orWhereHas('senderService.country', function ($countryQuery) use ($search) {
                        $countryQuery->where('name', 'LIKE', $search);
                    })
                    ->orWhereHas('recipientService.country', function ($countryQuery) use ($search) {
                        $countryQuery->where('name', 'LIKE', $search);
                    });
            });
        }

        $statsQuery = clone $query;
        $totalCount   = (clone $statsQuery)->count();
        $successCount = (clone $statsQuery)->where('pixel_mobile_zone_transfers.status', 'SUCCESS')->count();
        $pendingCount = (clone $statsQuery)->whereIn('pixel_mobile_zone_transfers.status', ['PENDING', 'PROCESSING'])->count();
        $errorCount   = (clone $statsQuery)->whereIn('pixel_mobile_zone_transfers.status', ['FAILED', 'ERROR', 'REFUNDED'])->count();
        $totalAmount  = (clone $statsQuery)->sum('pixel_mobile_zone_transfers.amount');

        return view('livewire.historical.pixel-mobile-zone-histo', [
            'pixel_mobile_zone_historicals' => $query
                ->orderBy('pixel_mobile_zone_transfers.created_at', 'desc')
                ->select('pixel_mobile_zone_transfers.*')
                ->paginate(15),
            'usersCountry' => $walletUser->country->currencie->name ?? '',
            'totalCount'   => $totalCount,
            'successCount' => $successCount,
            'pendingCount' => $pendingCount,
            'errorCount'   => $errorCount,
            'totalAmount'  => $totalAmount,
        ]);
    }

    private function baseQuery($walletUser)
    {
        return PixelMobileZoneTransfer::with([
            'senderService.provider',
            'senderService.country',
            'recipientService.provider',
            'recipientService.country',
            'wallet.sidUser.country',
            'wallet.sidUser.city',
        ])
            ->join('wallets', 'pixel_mobile_zone_transfers.wallet_id', '=', 'wallets.id')
            ->when($walletUser->is_under_distributer || $walletUser->is_api, function ($query) use ($walletUser) {
                return $query->where('wallets.sid_user_id', $walletUser->id);
            })
            ->when($walletUser->is_distributer, function ($query) use ($walletUser) {
                return $query->whereHas('wallet.sidUser', function ($userQuery) use ($walletUser) {
                    $userQuery->where('distributer_id', $walletUser->id);
                });
            })
            ->when($walletUser->is_com_zone, function ($query) use ($walletUser) {
                return $query->whereIn('wallets.sid_user_id', function ($subQuery) use ($walletUser) {
                    $subQuery->select('id')
                        ->from('sid_users')
                        ->where('com_zone_id', $walletUser->id);
                });
            });
    }
}
