<?php

namespace App\Models\Wallets;

use App\Models\AdminsCom;
use App\Models\Wallets\Wallet;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Payments\Transaction;
use App\Models\Costs\TransactionCost;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoices\InvoiceTransaction;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'wallet_transactions';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'provider_id' => "integer",
        'wallet_id' => "integer",
        'transaction_cost_id' => "integer",
        'created_at' => 'datetime:d-m-Y H:i:s',
        'deleted_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

 

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id')->withTrashed();
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function invoiceTransaction()
    {
        return $this->hasOne(InvoiceTransaction::class);
    }

    public function adminCom()
    {
        return $this->hasOne(AdminsCom::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }
    public function transactionCost()
    {
        return $this->belongsTo(TransactionCost::class, 'transaction_cost_id', 'id')->withTrashed();
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }

}