<?php

namespace App\Models;

use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PixelInnoveService extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pixel_innove_services';

    protected $guarded = [];

    protected $casts = [
        'country_id' => 'integer',
        'provider_id' => 'integer',
        'service_id' => 'integer',
        'fixed_fee' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'status' => 'boolean',
        'is_xof' => 'boolean',
        'is_xaf' => 'boolean',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class, 'provider_type_id', 'id')->withTrashed();
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id')->withTrashed();
    }

    public function getFixedFeeAttribute($value)
    {
        return $value !== null ? (float) $value : null;
    }
}
