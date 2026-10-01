<?php

namespace App\Models\Wallets;

use App\Models\AdminsCom;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Onexbet;
use App\Models\Payments\OneBetTransaction;
use App\Models\Providers\Provider;
use App\Models\Wallets\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Custumers\Country;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use App\Models\Sid\SidUser;

class WalletXbet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'wallet_xbets';

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
        return $this->hasOne(OneBetTransaction::class);
    }

    public function invoiceTransaction()
    {
        return $this->hasOne(OneBetInvoice::class);
    }

    public function adminCom()
    {
        return $this->hasOne(AdminsCom::class);
    }

      public function country()
    {
        return $this->belongsTo(Country::class, 'countrie_id', 'id')->withTrashed();
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }
    public function onexbet()
    {
        return $this->belongsTo(Onexbet::class, 'Onexbet_id', 'id')->withTrashed();
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
