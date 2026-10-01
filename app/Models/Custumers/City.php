<?php

namespace App\Models\Custumers;

use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use App\Models\Custumers\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Custumers\Country as CustumersCountry;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class City extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'cities';

    protected $guarded = [];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'name' => 'string',
        'status' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function scopeCities($query)
    {
        return $query->where('status', true)->get();
    }

    public function admins()
    {
        return $this->hasMany(SidUser::class)->withTrashed();
    }

    public function countrie()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function cardBanks()
    {
        return $this->belongsTo(BankCard::class, 'bank_card_id', 'id')->withTrashed();
    }
}