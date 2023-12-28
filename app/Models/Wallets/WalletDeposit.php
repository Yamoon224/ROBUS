<?php

namespace App\Models\Wallets;

use App\Models\AdminsCom;
use App\Models\Wallets\Wallet;
use App\Models\Payments\Deposit;
use App\Models\Costs\DepositCost;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Invoices\InvoiceDeposit;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletDeposit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'wallet_deposits';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'provider_id' => "integer",
        'wallet_id' => "integer",
        'created_at' => 'datetime:d-m-Y H:i:s',
        'deleted_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    public function adminCom(){
    	return $this->hasOne(AdminsCom::class);
    }
    
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id')->withTrashed();
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }

    public function invoiceDeposit()
    {
        return $this->hasOne(InvoiceDeposit::class);
    }

    public function depositCost()
    {
        return $this->belongsTo(DepositCost::class, 'deposit_cost_id', 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}