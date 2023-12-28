<?php

namespace App\Models\Payments;

use App\Models\User;
use App\Models\Company;
use App\Models\Sid\SidUser;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\WalletPhoneCredit;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentPhoneCredit extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'payment_phone_credits';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [ ];

    protected $casts = [
        'wallet_phone_credit_id' => "integer",
        'user_id' => "integer",
        'sid_user_id' => "integer",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id', 'id')->withTrashed();
    }

    public function WalletPhoneCredit()
    {
        return $this->belongsTo(WalletPhoneCredit::class,'wallet_phone_credit_id', 'id')->withTrashed();
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