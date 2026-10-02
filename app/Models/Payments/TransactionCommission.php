<?php

namespace App\Models\Payments;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletCommission;
use App\Models\Wallets\WalletAdmin;

class TransactionCommission extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'transaction_commissions';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function walletPush()
    {
        return $this->belongsTo(WalletCommission::class, 'wallet_commission_id', 'id')->withTrashed();
    }

    public function walletAdmin()
    {
        return $this->belongsTo(WalletAdmin::class, 'wallet_admin_id', 'id')->withTrashed();
    }


    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }
}
