<?php

namespace App\Models\Costs;

use App\Models\CountryCost;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use App\Models\LevelDeposit;
use App\Models\LevelDepositIvoire;
use Spatie\Activitylog\LogOptions;
use App\Models\Wallets\WalletDeposit;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepositCost extends Model
{
    use HasFactory;
    
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'deposit_costs';

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
        return $this->hasMany(CountryCost::class, 'deposit_id', 'id', 'country_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_costs', 'country_id', 'deposit_id');
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
    
    public function getPercentMerchandsAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }


    private function formatPercentage($value)
    {
        return is_numeric($value) ? $value / 100 : null;
    }

    public function setPercentAttribute($value)
    {
        $this->attributes['percent'] = json_encode($this->formatPercentage($value));

    }


    public function setPercentAdminAttribute($value)
    {
        $this->attributes['percent_admin'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentMerchandsAttribute($value)
    {
        $this->attributes['percent_merchands'] = json_encode($this->formatPercentage($value));

    }

    public function setPercentMastersAttribute($value)
    {
        $this->attributes['percent_masters'] = json_encode($this->formatPercentage($value));
    }

    public function WalletDeposit()
    {
        return $this->hasOne(WalletDeposit::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }

      public function levelDeposits() {
        
        return $this->hasMany(LevelDeposit::class);
    }

    public function levelDepositIvories()
    {

        return $this->hasMany(LevelDepositIvoire::class);
    }
}