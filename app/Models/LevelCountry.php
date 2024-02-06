<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelCountry extends Model
{
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
        $this->attributes['percent'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function getPercentMerchandsAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentMerchandsAttribute($value)
    {
        $this->attributes['percent_merchands'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function setPercentAdminAttribute($value)
    {
        $this->attributes['percent_admin'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function setPercentWithDrawalAttribute($value)
    {
        $this->attributes['percent_with_drawal'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function setPercentDepositAttribute($value)
    {
        $this->attributes['percent_deposit'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function setPercentUnderDistributerAttribute($value)
    {

        $this->attributes['percent_under_distributer'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function setPercentAdminUnderDistributerAttribute($value)
    {
        $this->attributes['percent_admin_under_distributer'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function setPercentDepositUnderDistributerAttribute($value)
    {
        $this->attributes['percent_deposit_under_distributer'] = ($value / 100) ? json_encode($value / 100) : null;
    }

    public function setPercentWithDrawalUnderDistributerAttribute($value)
    {
        $this->attributes['percent_with_drawal_under_distributer'] =( $value / 100 ) ? json_encode($value / 100) : null;
    }

    public function setPercentBanqueAttribute($value)
    {
        $this->attributes['percent_banque'] =( $value / 100 ) ? json_encode($value / 100) : null;
    }


    public function setPercentMastersAttribute($value)
    {
        $this->attributes['percent_masters'] =( $value / 100 ) ? json_encode($value / 100) : null;
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
