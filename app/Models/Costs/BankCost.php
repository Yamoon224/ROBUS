<?php

namespace App\Models\Costs;

use App\Models\CountryCost;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use App\Models\LevelBank;
use App\Models\LevelBankIvoire;
use App\Models\Wallets\WalletBank;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankCost extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'bank_costs';

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
        return $this->hasMany(CountryCost::class, 'bank_id', 'id', 'country_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_costs', 'country_id', 'bank_id');
    }

    public function scopeBankCost($query)
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


    private function formatPercentage($value)
    {
        return is_numeric($value) ? $value / 100 : null;
    }


    public function getPercentAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentAttribute($value)
    {
        $this->attributes['percent'] = json_encode($this->formatPercentage($value));
    }

    public function getPercentMerchandsAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentMerchandsAttribute($value)
    {
        $this->attributes['percent_merchands'] = json_encode($this->formatPercentage($value));

    }

    public function setPercentMastersAttribute($value)
    {
        $this->attributes['percent_masters'] = json_encode($this->formatPercentage($value));
    }

    public function walletBank()
    {
        return $this->hasOne(WalletBank::class);
    }

    public function levelBanks()
    {

        return $this->hasMany(LevelBank::class);
    }

    public function levelBankIvories()
    {

        return $this->hasMany(LevelBankIvoire::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
