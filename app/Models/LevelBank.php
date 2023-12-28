<?php

namespace App\Models;

use App\Models\Costs\BankCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelBank extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use LogsActivity;

    protected $table = "level_banks";
    protected $guarded = [];


    protected $casts = [
        'bank_cost_id' => "integer",
    ];

    public function getPercentAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentAttribute($value)
    {
        $this->attributes['percent'] = $value / 100 ? json_encode($value / 100) : null;
    }

    public function getComissionDistributerAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setComissionDistributerAttribute($value)
    {
        $this->attributes['comission_distributer'] = $value / 100 ? json_encode($value / 100) : null;
    }


    public function bankCost()
    {
        return $this->belongsTo(BankCost::class, 'bank_cost_id', 'id');
    }
}