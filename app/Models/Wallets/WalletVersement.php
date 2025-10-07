<?php

namespace App\Models\Wallets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AdminsCom;
use App\Models\VirementPercent;
use App\Models\Wallets\Wallet;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Invoices\InvoiceDeposit;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Invoices\AutherTransactionInvoice;
use App\Models\Payments\AutherTransaction;

class WalletVersement extends Model
{
     use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'wallet_versements';

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

 
    public function transaction()
    {
        return $this->hasOne(AutherTransaction::class);
    }

    public function invoiceTransaction()
    {
        return $this->hasOne(AutherTransactionInvoice::class);
    }


    public function virementPercent()
    {
        return $this->belongsTo(VirementPercent::class, 'virement_percent_id', 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
