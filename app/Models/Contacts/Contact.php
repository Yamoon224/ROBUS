<?php

namespace App\Models\Contacts;

use App\Models\Company;
use App\Models\Custumers\City;
use App\Models\Custumers\Zone;
use App\Models\Wallets\Wallet;
use App\Models\Custumer\Country;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'contacts';

    // protected $hidden = [
    //     'created_at',
    //     'updated_at',
    // ];

    protected $fillable = [
        'firstname',
        'lastname',
        'company_id',
        'country_id',
        'city_id',
        'contact_list_id',
        'status',
        'email',
        'phone',
        'created_by',
        'lot',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'country_id' => 'integer',
        'city_id' => 'integer',
        'contact_list_id' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'id')->withTrashed();
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function contactLists()
    {
        return $this->belongsTo(ContactList::class, 'contact_list_id', 'id')->withTrashed();
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id')->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}