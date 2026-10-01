<?php

namespace App\Models;

use App\Models\Costs\BankCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\InterCost;
use App\Models\Costs\OrderCardCost;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\ReloadCost;
use App\Models\Costs\TransactionCost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryCost extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;

    protected $table = 'country_services';

    protected $guard = [];


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function bank()
    {
        return $this->belongsTo(BankCost::class, 'bank_id', 'id');
    }

    public function deposit()
    {
        return $this->belongsTo(DepositCost::class, 'deposit_id', 'id');
    }

    public function inter()
    {
        return $this->belongsTo(InterCost::class, 'inter_id', 'id');
    }
    public function orderCard()
    {
        return $this->belongsTo(OrderCardCost::class, 'order_card_id', 'id');
    }
    public function payment()
    {
        return $this->belongsTo(PaymentCost::class, 'payment_id', 'id');
    }
    public function phoneCredit()
    {
        return $this->belongsTo(PhoneCreditCost::class, 'phone_credit_id', 'id');
    }
    public function reload()
    {
        return $this->belongsTo(ReloadCost::class, 'reload_id', 'id');
    }
    public function transaction()
    {
        return $this->belongsTo(TransactionCost::class, '', 'id');
    }
}