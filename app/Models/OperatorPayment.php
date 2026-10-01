<?php

namespace App\Models;

use App\Models\Custumers\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PaymentService;

class OperatorPayment extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    protected $table = 'operator_payments';

    protected $guarded = [];


    public function operator_countries()
    {
        return $this->belongsToMany(PaymentService::class, 'payment_services', 'operator_payment_id', 'country_id');
    }
}
