<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VirementPercent extends Model
{
     use HasFactory;

    use SoftDeletes;

    protected $table = 'virement_percents';

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

    // public function setPercentAdminAttribute($value)
    // {
    //     $this->attributes['percent_admin'] = json_encode($this->formatPercentage($value));
    // }

    public function setPercentMerchandsAttribute($value)
    {
        $this->attributes['percent_merchands'] = json_encode($this->formatPercentage($value));

    }

    public function setPercentMastersAttribute($value)
    {
        $this->attributes['percent_masters'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentBanqueAttribute($value)
    {
        $this->attributes['percent_banque'] = json_encode($this->formatPercentage($value));
    }


    public function setPercentCompanieAttribute($value)
    {
        $this->attributes['percent_companie'] = ($value / 100) ? json_encode($value / 100) : null;
    }


    public function setPercentWithDrawalAttribute($value)
    {
        $this->attributes['percent_with_drawal'] = json_encode($this->formatPercentage($value));
    }


    public function setPercentAdminAttribute($value)
    {

        $this->attributes['percent_admin'] = json_encode($this->formatPercentage($value));
    }
}
