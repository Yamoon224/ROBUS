<?php

namespace App\Models;

use App\Models\Costs\BankCommission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LevelBankAuther extends Model
{
   use HasFactory;
    use SoftDeletes;

    protected $table = "level_bank_authers";
    protected $guarded = [];


    protected $casts = [
        'bank_commission_id' => "integer",
    ];

    public function getPercentAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }

    public function setPercentAttribute($value)
    {
        $this->attributes['percent'] = $value / 100 ? json_encode($value / 100) : null;
    }

    public function bankCommission()
    {
        return $this->belongsTo(BankCommission::class, 'bank_commission_id', 'id');
    }
}
