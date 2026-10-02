<?php

namespace App\Models;

use App\Models\Costs\DepositCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelDepositIvoire extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = "level_deposit_ivoires";

    protected $guarded = [];


    protected $casts = [
        'deposit_cost_id' => "integer",
    ];

    public function depositCost()
    {
        return $this->belongsTo(DepositCost::class, 'deposit_cost_id', 'id')->withTrashed();
    }
}