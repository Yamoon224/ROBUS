<?php

namespace App\Models;

use App\Models\Custumers\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentService extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    protected $table = 'payment_services';

    protected $guarded = [];
        
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function operator()
    {
        return $this->belongsTo(OperatorPayment::class, 'operator_payment_id', 'id');
    }
}
