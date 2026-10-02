<?php

namespace App\Models;

use App\Models\Costs\BankCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelBankIvoire extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;


    protected $guarded = [];


    protected $casts = [
        'bank_cost_id' => "integer",
    ];

    public function bankCost()
    {
        return $this->belongsTo(BankCost::class, 'bank_cost_id', 'id')->withTrashed();
    }
}