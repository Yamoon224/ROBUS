<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Country;
use App\Models\Wallets\Wallet;
use App\Models\Providers\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Balance extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    protected $table = 'balances';

    
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $fillable = [
        'company_id',
        'wallet_id',
        'amount',
        'status'
    ];

     protected $casts = [
        'company_id' => "integer",
        'wallet_id' => "integer",
        'country_id' => "integer",
        'provider_id' => "integer",
        'amount' => "double",
        'status' => "boolean",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id', 'id')->withTrashed();
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class,'wallet_id', 'id')->withTrashed();
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class,'provider_id', 'id')->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id', 'id')->withTrashed();
    }
}