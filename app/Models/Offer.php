<?php

namespace App\Models;

use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

     protected $fillable = [
        'name',
        'status',
        'amount',
        'description',
    ];

    public function walletTransactions()
    {
        return $this->hasMany(WalletPayment::class);
    }

    public function walletInters()
    {
        return $this->hasMany(WalletInter::class);
    }

}