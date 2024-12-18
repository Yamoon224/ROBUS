<?php

namespace App\Models\Wallets;

use App\Models\AdminsCom;
use App\Models\Costs\BankCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\InterCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceInter;
use App\Models\Offer;
use App\Models\Payments\TransactionInter;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WalletInter extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'wallet_inters';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'provider_id' => "integer",
        // 'wallet_id' => "integer",
        // 'country_id' => "integer",
        'inter_cost_id' => "integer",
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
    public function interCost()
    {
        return $this->belongsTo(InterCost::class, 'inter_cost_id', 'id')->withTrashed();
    }

    public function depositCost()
    {
        return $this->belongsTo(DepositCost::class, 'deposit_cost_id', 'id')->withTrashed();
    }
    public function bankCost()
    {
        return $this->belongsTo(BankCost::class, 'bank_cost_id', 'id')->withTrashed();
    }
    public function phoneCost()
    {
        return $this->belongsTo(PhoneCreditCost::class, 'phone_credit_cost_id', 'id')->withTrashed();
    }

    public function transactionCost()
    {
        return $this->belongsTo(TransactionCost::class, 'transaction_cost_id', 'id')->withTrashed();
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function transactionInter()
    {
        return $this->hasOne(TransactionInter::class);
    }

    public function invoiceInter()
    {
        return $this->hasOne(InvoiceInter::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id')->withTrashed();
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}