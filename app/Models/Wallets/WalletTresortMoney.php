<?php

namespace App\Models\Wallets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Payments\OneBetTransaction;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use Spatie\Activitylog\LogOptions;
use App\Models\AdminsCom;
use App\Models\ArgentTresort;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Invoices\AutherTransactionInvoice;
use App\Models\Payments\AutherTransaction;
use App\Models\Sid\SidUser;

class WalletTresortMoney extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'wallet_tresort_money';

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

   
    public function transaction()
    {
        return $this->hasOne(AutherTransaction::class);
    }

    public function invoiceTransaction()
    {
        return $this->hasOne(AutherTransactionInvoice::class);
    }

    public function adminCom()
    {
        return $this->hasOne(AdminsCom::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }
    public function ArgentTresort()
    {
        return $this->belongsTo(ArgentTresort::class, 'ArgentTresort_id', 'id')->withTrashed();
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
