<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelCountry extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'level_countries';

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

    public function scopeBankCost($query)
    {
        return $query->where('status', true)->get();
    }

    // public function getNameAttribute($value)
    // {
    //     $val = isset($value) ? json_decode($value, true) : null;
    //     return $val;
    // }

    // public function setNameAttribute($value)
    // {
    //     $this->attributes['name'] = isset($value) ? json_encode($value) : null;
    // }

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

    public function getPercentMerchandsAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentMerchandsAttribute($value)
    {
        $this->attributes['percent_merchands'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentAdminAttribute($value)
    {
        $this->attributes['percent_admin'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentWithDrawalAttribute($value)
    {

        $this->attributes['percent_with_drawal'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentDepositAttribute($value)
    {

        $this->attributes['percent_deposit'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentUnderDistributerAttribute($value)
    {

        $this->attributes['percent_under_distributer'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentAdminUnderDistributerAttribute($value)
    {

        $this->attributes['percent_admin_under_distributer'] = json_encode($this->formatPercentage($value));
    }


    public function setPercentCompanieAttribute($value)
    {
        $this->attributes['percent_companie'] = ($value / 100) ? json_encode($value / 100) : null;
    }


    public function setPercentDepositUnderDistributerAttribute($value)
    {

        $this->attributes['percent_deposit_under_distributer'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentWithDrawalUnderDistributerAttribute($value)
    {

        $this->attributes['percent_with_drawal_under_distributer'] = json_encode($this->formatPercentage($value));
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
        $this->attributes['percent_banque'] = json_encode($this->formatPercentage($value));
    }


    public function setPercentMastersAttribute($value)
    {

        $this->attributes['percent_masters'] = json_encode($this->formatPercentage($value));
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
