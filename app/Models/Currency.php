<?php

namespace App\Models;

use App\Models\Custumers\Country;
use App\Models\Wallets\Wallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'currencies';

    protected $fillable = [
        'code',
        'name',
        'value',
        'status'
    ];

    protected $casts = [
        'code' => "string",
        'value' => "double",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
        
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function countries()
    {
        return $this->hasMany(Country::class);
    }
}