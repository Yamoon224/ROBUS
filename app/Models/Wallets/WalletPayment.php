<?php

namespace App\Models\Wallets;

use App\Models\Offer;
use App\Models\AdminsCom;
use App\Models\Wallets\Wallet;
use App\Models\Payments\Payment;
use App\Models\Costs\PaymentCost;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Invoices\InvoicePayment;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Sid\SidUser;

class WalletPayment extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'wallet_payments';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'provider_id' => "integer",
        'wallet_id' => "integer",
        'offer_id' => "integer",
        'payment_cost_id' => "integer",
        'created_at' => 'datetime:d-m-Y H:i:s',
        'deleted_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

       public function updatedByUser()
    {
        return $this->belongsTo(SidUser::class, 'updated_by', 'id');
    }

    
    public function adminCom(){
    	return $this->hasOne(AdminsCom::class);
    }
    
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id')->withTrashed();
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id')->withTrashed();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function invoicePaiement()
    {
        return $this->hasOne(InvoicePayment::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }
    public function paymentCost()
    {
        return $this->belongsTo(PaymentCost::class, 'payment_cost_id', 'id')->withTrashed();
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}