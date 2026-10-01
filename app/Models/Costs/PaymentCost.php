<?php

namespace App\Models\Costs;

use App\Models\CountryCost;
use Illuminate\Support\Carbon;
use App\Models\Custumers\Country;
use Spatie\Activitylog\LogOptions;
use App\Models\Wallets\WalletPayment;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentCost extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

     protected $table = 'payment_costs';

     protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [];


    protected $casts = [
        'amount_auther' => 'double',
        'amount_canal' => 'double',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    public function country_costs()
    {
        return $this->hasMany(CountryCost::class, 'payment_id', 'id', 'country_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_costs', 'country_id', 'payment_id');
    }
    public function scopePayment($query)
    {
       return $query->where('status' , true)->get();  
    }
    
     public function getNameAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = isset($value) ? json_encode($value) : null;
    }

    public function getPercentAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }
    
    public function setPercentAttribute($value)
    {
        $this->attributes['percent'] = $value / 100 ? json_encode($value / 100) : null;
    }
    public function getPercentMerchandsAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentMerchandsAttribute($value)
    {
        $this->attributes['percent_merchands'] = $value / 100 ? json_encode($value / 100) : null;
    }
    

    public function setPercentPaymentMastersAttribute($value)
    {
        $this->attributes['percent_masters'] = $value / 100 ? json_encode($value / 100) : null;
    }
   
    public function setPercentPaymentCompanieAttribute($value)
    {
        $this->attributes['percent_companie'] = $value / 100 ? json_encode($value / 100) : null;
    }

    public function WalletPayment()
    {
        return $this->hasOne(WalletPayment::class)->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}