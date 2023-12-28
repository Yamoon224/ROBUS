<?php

namespace App\Models\Costs;

use App\Models\CountryCost;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderCardCost extends Model
{
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}