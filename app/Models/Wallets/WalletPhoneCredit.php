<?php

namespace App\Models\Wallets;

use App\Models\AdminsCom;
use App\Models\Wallets\Wallet;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Costs\PhoneCreditCost;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoices\InvoicePhoneCredit;
use App\Models\Payments\PaymentPhoneCredit;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletPhoneCredit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'wallet_phone_credits';

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

    public function paymentPhone()
    {
        return $this->hasOne(PaymentPhoneCredit::class);
    }

    public function invoicePhone()
    {
        return $this->hasOne(InvoicePhoneCredit::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }
    public function phoneCreditCost()
    {
        return $this->belongsTo(PhoneCreditCost::class, 'phone_credit_cost_id', 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}