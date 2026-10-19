<?php

namespace App\Models;

use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryService extends Model

{
    use \App\Models\Concerns\BatchesLazyLoads;

    protected $table = 'country_services';

    protected static function booted()
    {
        // Achat d'UV : Paystack actif dans un pays désactive Pixel Innov dans ce pays.
        static::saved(function (self $service) {
            if ($service->wasRecentlyCreated || $service->wasChanged('status')) {
                \App\Support\UvPaymentSwitch::enforce();
            }
        });
    }

    protected $fillable = ['country_id', 'provider_id', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
