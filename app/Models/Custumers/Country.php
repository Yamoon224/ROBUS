<?php

namespace App\Models\Custumers;

use App\Models\Currency;
use App\Models\Sid\SidUser;
use App\Models\CountryService;
use App\Models\Custumers\City;
use App\Models\Wallets\Wallet;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'countries';

    protected $guarded = [];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'name' => 'string',
        'currencie_id' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function country_services()
    {
        return $this->hasMany(CountryService::class, 'country_id', 'id', 'provider_id');
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'country_services', 'country_id', 'provider_id');
    }

    public function scopeCountries($query)
    {
        return $query->where('status', true)->get();
    }

    public function currencie()
    {
        return $this->belongsTo(Currency::class, 'currencie_id', 'id');
    }

    public function admins()
    {
        return $this->hasMany(SidUser::class);
    }

    public function walletBanks()
    {
        return $this->hasMany(WalletBank::class);
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function provider()
    {
        return $this->hasMany(Provider::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}