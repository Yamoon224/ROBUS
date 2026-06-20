<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Sid\SidUser;


class ComZoneGoalReward extends Model
{

    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'transactions_count' => 'integer',
        'transactions_amount' => 'float',
        'reward_amount' => 'float',
        'balance_before' => 'float',
        'balance_after' => 'float',
        'paid_at' => 'datetime',
    ];

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }
}
