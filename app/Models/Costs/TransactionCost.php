<?php

namespace App\Models\Costs;

use App\Models\CountryCost;
use App\Models\LevelTransaction;
use App\Models\Custumers\Country;
use Spatie\Activitylog\LogOptions;
use App\Models\LevelTransactionIvoire;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\WalletTransaction;
use App\Models\LevelTransactionIvoireInter;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionCost extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'transaction_costs';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'percent' => 'double',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function country_costs()
    {
        return $this->hasMany(CountryCost::class, 'transaction_id', 'id', 'country_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_costs', 'country_id', 'transaction_id');
    }
    public function scopeTransactionCost($query)
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

    public function getPercentAttribute($value)
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

    public function setPercentCompanieAttribute($value)
    {
        $this->attributes['percent_companie'] = $value / 100 ? json_encode($value / 100) : null;
    }
    

    public function setPercentCustumDrawalAttribute($value)
    {
        $this->attributes['percent_custum_drawal'] = $value / 100 ? json_encode($value / 100) : null;
    }


    public function setPercentCustumDrawalAdminAttribute($value)
    {
        $this->attributes['percent_custum_drawal_admin'] = $value / 100 ? json_encode($value / 100) : null;
    }

    public function setPercentCustumDrawalCompanieAttribute($value)
    {
        $this->attributes['percent_custum_drawal_companie'] = $value / 100 ? json_encode($value / 100) : null;
    }


    public function setPercentCustumDrawalMasterAttribute($value)
    {
        $this->attributes['percent_custum_drawal_master'] = $value / 100 ? json_encode($value / 100) : null;
    }
    
    public function setPercentBanqueAttribute($value)
    {
        $this->attributes['percent_banque'] = $value / 100 ? json_encode($value / 100) : null;
    }

      public function setPercentCustumDrawalDepositAttribute($value)
    {
        $this->attributes['percent_custum_drawal_deposit'] = $value / 100 ? json_encode($value / 100) : null;
    }


    public function setPercentUnderScannerAttribute($value)
    {
        $this->attributes['percent_under_scanner'] = $value / 100 ? json_encode($value / 100) : null;
    }

    
    public function getPercentAdminAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }


    public function getPercentDepositAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentDepositAttribute($value)
    {
        $this->attributes['percent_deposit'] = json_encode($this->formatPercentage($value));
    }

    public function getPercentWithDrawalAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentWithDrawalAttribute($value)
    {
        $this->attributes['percent_with_drawal'] = json_encode($this->formatPercentage($value));
    }


    public function setPercentWebAttribute($value)
    {
        $this->attributes['percent_web'] = json_encode($this->formatPercentage($value));
    }


    public function WalletTransaction()
    {
        return $this->hasOne(WalletTransaction::class)->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }

    public function levelTransactions()
    {

        return $this->hasMany(LevelTransaction::class);
    }

    public function levelTransactionIvoires()
    {

        return $this->hasMany(LevelTransactionIvoire::class);
    }

    public function levelTransactionIvoireInters()
    {

        return $this->hasMany(LevelTransactionIvoireInter::class);
    }
}
