<?php

namespace App\Models\Wallets;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Custumers\Country;
use App\Models\Payments\TransactionCommission;
use App\Models\Sid\SidUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WalletPixelCommission extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'wallet_pixel_commissions';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'company_id' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime:d-m-Y H:i:s',
        'deleted_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
    }

    public function currencie()
    {
        return $this->belongsTo(Currency::class, 'currencie_id', 'id')->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }

    public function transaction()
    {
        return $this->hasOne(TransactionCommission::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
