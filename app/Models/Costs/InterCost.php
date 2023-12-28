<?php

namespace App\Models\Costs;

use App\Models\CountryCost;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use Spatie\Activitylog\LogOptions;
use App\Models\Wallets\WalletInter;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterCost extends Model
{
     use HasFactory;
     use SoftDeletes;
    use LogsActivity;

     protected $table = 'inter_costs';

     protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [];


    protected $casts = [
        'percent' => 'double',
        'percent_merchands' => 'double',
         'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    public function country_costs()
    {
        return $this->hasMany(CountryCost::class, 'inter_id', 'id', 'country_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_costs', 'country_id', 'inter_id');
    }
    public function scopeDepositCost($query)
    {
       return $query->where('status' , true)->get();  
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

    public function getPercentAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }
    
    public function setPercentAttribute($value)
    {
        $this->attributes['percent'] = $value / 100 ? json_encode($value / 100) : null;
    }

    public function getPercentMerchandsAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentMerchandsAttribute($value)
    {
        $this->attributes['percent_merchands'] = $value / 100 ? json_encode($value / 100) : null;
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