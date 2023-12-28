<?php

namespace App\Http\Livewire\Historical;

use Livewire\Component;
use App\Models\WithMerchant;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

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

        if ($this->startDate && $this->endDate) {

            $drawers->whereBetween('created_at', [$this->startDate, $this->endDate]);
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
            'sid_users.country_id',107) ->whereDate('date_removed', Carbon::now()))->get() as $item) {
            $data[] = $item->amount_with_draw;
        }

        return array_sum($data);
    }
}
