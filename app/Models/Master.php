<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Sid\SidUser;

class Master extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    protected $table = 'masters';

    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }


    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->withTrashed();
    }

    public function sidUsers()
    {
        return $this->hasMany(SidUser::class);
    }

}
