<?php

namespace App\Models;

use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visite extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'visites';

    protected $guarded = [];

     public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }

     public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }
}

