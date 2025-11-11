<?php

namespace App\Models\Wallets;

use App\Models\Wallets\Wallet;
use App\Models\Costs\ReloadCost;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Invoices\InvoiceReload;
use App\Models\Payments\PaymentReload;
use App\Models\Sid\SidUser;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletReload extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;


    protected $table = 'wallet_reloads';

    protected $hidden = [
        // 'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'provider_id' => "integer",
        'reload_cost_id' => "integer",
        // 'created_at' => 'datetime:d-m-Y H:i:s',
        'deleted_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];


    public function walletByUser()
    {
        return $this->belongsTo(Wallet::class, 'account_wallet', 'id');
    }

    public function sidUser()
    {
        return $this->hasOneThrough(
            SidUser::class,
            Wallet::class,
            'id',              // Clé primaire de Wallet
            'id',              // Clé primaire de SidUser
            'account_wallet',  // Clé étrangère dans WalletReload
            'sid_user_id'      // Clé étrangère dans Wallet
        );
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id')->withTrashed();
    }

    public function paymentReload()
    {
        return $this->hasOne(PaymentReload::class);
    }

    public function invoiceReload()
    {
        return $this->hasOne(InvoiceReload::class);
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
