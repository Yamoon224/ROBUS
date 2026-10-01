<?php

namespace App\Models;

use App\Models\Costs\TransactionCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelTransaction extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = "level_transactions";

    protected $guarded = [];


    public function transactionCost()
    {
        return $this->belongsTo(TransactionCost::class, 'transaction_cost_id', 'id')->withTrashed();
    }


    public function setPercentAttribute($value)
    {
        $this->attributes['percent'] = ($value / 100) ? json_encode($value / 100) : null;
    }
}
