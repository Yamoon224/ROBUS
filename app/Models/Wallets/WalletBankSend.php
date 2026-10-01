<?php

namespace App\Models\Wallets;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AdminsCom;
use App\Models\Wallets\Wallet;
use App\Models\Payments\Deposit;
use App\Models\Costs\BankCommission;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Invoices\InvoiceBankSend;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sid\SidUser;
use App\Models\Custumers\Country;

class WalletBankSend extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'wallet_bank_sends';

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

    public function invoiceBankSend()
    {
        return $this->hasOne(InvoiceBankSend::class);
    }

    public function depositCost()
    {
        return $this->belongsTo(BankCommission::class, 'bank_cost_id', 'id')->withTrashed();
    }

      public function country()
    {
        return $this->belongsTo(Country::class, 'countrie_id', 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
