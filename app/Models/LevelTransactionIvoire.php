<?php

namespace App\Models;

use App\Models\Costs\TransactionCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelTransactionIvoire extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "level_transaction_ivoires";

    protected $guarded = [];


    protected $casts = [
        'transaction_cost_id' => "integer",
    ];

    public function transactionCost()
    {
        return $this->belongsTo(TransactionCost::class, 'transaction_cost_id', 'id')->withTrashed();
    }
}