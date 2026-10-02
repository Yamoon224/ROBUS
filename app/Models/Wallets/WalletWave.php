<?php

namespace App\Models\Wallets;


use App\Models\AdminsCom;
use App\Models\Costs\ReloadCost;
use App\Models\Invoices\InvoiceWave;
use App\Models\Payments\PaymentWave;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use App\Models\Sid\SidUser;

class WalletWave extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'wallet_waves';

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


   public function updatedByUser()
    {
        return $this->belongsTo(SidUser::class, 'updated_by', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id')->withTrashed();
    }

    public function transactionWave()
    {
        return $this->hasOne(PaymentWave::class);
    }

    public function invoiceTransactionWave()
    {
        return $this->hasOne(InvoiceWave::class);
    }

    public function adminCom()
    {
        return $this->hasOne(AdminsCom::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }

    public function reloadCost()
    {
        return $this->belongsTo(ReloadCost::class, 'reload_cost_id', 'id')->withTrashed();
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
