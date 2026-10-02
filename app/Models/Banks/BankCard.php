<?php

namespace App\Models\Banks;

use App\Models\Kyc;
use App\Models\Company;
use App\Models\Custumers\City;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Spatie\Activitylog\LogOptions;
use App\Models\Payments\PaymentBank;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankCard extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'bank_cards';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'wallet_id' => "integer",
        'created_at' => 'datetime:d-m-Y H:i:s',
        'deleted_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];
    
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
    }

    public function paymentReload()
    {
        return $this->hasOne(PaymentBank::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'bank_card_id', 'id')->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }
    public function citie()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }

    public function typeCard()
    {
        return $this->belongsTo(Provider::class, 'bank_card_id', 'id');
    }

    public function kycs()
    {
        return $this->hasMany(Kyc::class);
    }
 
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}