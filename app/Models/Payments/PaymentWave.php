<?php

namespace App\Models\Payments;

use App\Models\Company;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletWave;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentWave extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'payment_waves';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [ ];

    protected $casts = [
        'wallet_wave_id' => "integer",
        'sid_user_id' => "integer",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id', 'id')->withTrashed();
    }

    public function WalletWave()
    {
        return $this->belongsTo(WalletWave::class,'wallet_wave_id', 'id')->withTrashed();
    }

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class,'sid_user_id', 'id')->withTrashed();
    }
}
