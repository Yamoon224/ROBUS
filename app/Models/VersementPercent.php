<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VersementPercent extends Model
{
     use HasFactory; use SoftDeletes;

    protected $table = 'versement_percents';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];


    protected $casts = [
        'percent' => 'double',
        'percent_bank' => 'double',
        'percent_admin' => 'double',
        'percent_under' => 'double',
        'percent_companie' => 'double',
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

    public function setPercentUnderAttribute($value)
    {
        $this->attributes['percent_under'] = json_encode($this->formatPercentage($value));
    }

    public function setPercentCompanieAttribute($value)
    {
        $this->attributes['percent_companie'] = json_encode($this->formatPercentage($value));

    }

    public function setPercentBankAttribute($value)
    {
        $this->attributes['percent_bank'] = json_encode($this->formatPercentage($value));
    }


    public function setPercentAdminAttribute($value)
    {

        $this->attributes['percent_admin'] = json_encode($this->formatPercentage($value));
    }
}
