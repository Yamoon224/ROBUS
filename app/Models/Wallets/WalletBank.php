<?php

namespace App\Models\Wallets;

use App\Models\AdminsCom;
use App\Models\Costs\BankCost;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Traits\ActivityWalletBank;
use Spatie\Activitylog\LogOptions;
use App\Models\Invoices\InvoiceBank;
use App\Models\Payments\PaymentBank;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletBank extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    // use ActivityWalletBank;

    protected $table = 'wallet_banks';

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

    public function paymentBank()
    {
        return $this->hasOne(PaymentBank::class);
    }

    public function invoiceBank()
    {
        return $this->hasOne(InvoiceBank::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }
    public function bankCost()
    {
        return $this->belongsTo(BankCost::class, 'bank_cost_id', 'id')->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}