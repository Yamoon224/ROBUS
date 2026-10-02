<?php

namespace App\Models\Costs;

use App\Models\CountryCost;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletInter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderCardCost extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'order_card_costs';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];


    protected $casts = [
        'amount' => 'double',
        'percent_merchands' => 'double',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    public function country_costs()
    {
        return $this->hasMany(CountryCost::class, 'order_card_id', 'id', 'country_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_costs', 'country_id', 'order_card_id');
    }
    public function scopeDepositCost($query)
    {
        return $query->where('status', true)->get();
    }

    public function getNameAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = isset($value) ? json_encode($value) : null;
    }

    public function WalletInter()
    {
        return $this->hasOne(WalletInter::class)->withTrashed();
    }


    private function formatPercentage($value)
    {
        return is_numeric($value) ? $value / 100 : null;
    }

    // public function setPercentAttribute($value)
    // {
    //     $this->attributes['percent'] = json_encode($this->formatPercentage($value));

    // }

    public function setPercentPaymentCompanieAttribute($value)
    {
        $this->attributes['percent_companie'] = $value / 100 ? json_encode($value / 100) : null;
    }


    // public function setPercentAdminAttribute($value)
    // {
    //     $this->attributes['percent_admin'] = json_encode($this->formatPercentage($value));
    // }

    // public function setPercentMerchandsAttribute($value)
    // {
    //     $this->attributes['percent_merchands'] = json_encode($this->formatPercentage($value));

    // }

    public function setPercentMastersAttribute($value)
    {
        $this->attributes['percent_masters'] = json_encode($this->formatPercentage($value));
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}