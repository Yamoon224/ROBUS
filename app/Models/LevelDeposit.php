<?php

namespace App\Models;

use App\Models\Costs\DepositCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelDeposit extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    // use LogsActivity;

    protected $table = "level_deposits";
    protected $guarded = [];


    protected $casts = [
        'deposit_cost_id' => "integer",
    ];

    public function getPercentAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    // public function setPercentAttribute($value)
    // {
    //     $this->attributes['percent'] = $value / 100 ? json_encode($value / 100) : null;
    // }

    // public function setComissionPercentDistributerAttribute($value)
    // {
    //     $this->attributes['comission_percent_distributer'] = ($value / 100) ? json_encode($value / 100) : null;
    // }

    // public function setPercentAdminAttribute($value)
    // {
    //     $this->attributes['percent_admin'] = ($value / 100) ? json_encode($value / 100) : null;
    // }

    // public function getComissionDistributerAttribute($value)
    // {
    //     $val = isset($value) ? json_decode($value, true) : null;
    //     return $val;
    // }

    // public function setComissionDistributerAttribute($value)
    // {
    //     $this->attributes['comission_distributer'] = $value / 100 ? json_encode($value / 100) : null;
    // }


    public function depositCost()
    {
        return $this->belongsTo(DepositCost::class, 'deposit_cost_id', 'id');
    }
}