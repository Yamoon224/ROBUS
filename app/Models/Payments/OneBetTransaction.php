<?php

namespace App\Models\Payments;

use App\Models\Wallets\WalletXbet;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletTresortMoney;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sid\SidUser;

class OneBetTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'one_bet_transactions';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'sid_user_id' => "integer",
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function WalletXbet()
    {
        return $this->belongsTo(WalletXbet::class, 'wallet_xbet_id', 'id')->withTrashed();
    }

    public function WalletPush()
    {
        return $this->belongsTo(WalletPush::class, 'wallet_push_id', 'id')->withTrashed();
    }


    public function WalletDjamo()
    {
        return $this->belongsTo(WalletDjamo::class, 'wallet_djamo_id', 'id')->withTrashed();
    }

    public function WalletTresort()
    {
        return $this->belongsTo(WalletTresortMoney::class, 'wallet_tresort_money_id', 'id')->withTrashed();
    }


    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }
}
