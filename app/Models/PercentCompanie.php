<?php

namespace App\Models;

use App\Models\LevelTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PercentCompanie extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'percent_companies';

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


  
    public function scopePercentCompanie($query)
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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }

}
