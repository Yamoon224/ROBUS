<?php

namespace App\Models\Payments;

use App\Models\Company;
use App\Models\Sid\SidUser;
use App\Models\User;
use App\Models\Wallets\WalletDeposit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'deposits';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [ ];

    protected $casts = [
        'wallet_deposit_id' => "integer",
        'user_id' => "integer",
        'sid_user_id' => "integer",
        'pay_amount' => "double",
        'received_amount'  => "double",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id', 'id')->withTrashed();
    }

    public function WalletDeposit()
    {
        return $this->belongsTo(WalletDeposit::class,'wallet_deposit_id', 'id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id')->withTrashed();
    }

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class,'sid_user_id', 'id')->withTrashed();
    }
 
}