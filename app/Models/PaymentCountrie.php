<?php

namespace App\Models;

use App\Models\Costs\PaymentCost;
use App\Models\Custumers\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentCountrie extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'payment_countries';

    protected $fillable = ['country_id', 'payment_cost_id'];


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function paymentCost()
    {
        return $this->belongsTo(PaymentCost::class, 'payment_cost_id', 'id');
    }
}