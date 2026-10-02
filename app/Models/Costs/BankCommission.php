<?php

namespace App\Models\Costs;

use App\Models\LevelBankAuther;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankCommission extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'bank_commissions';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    
    public function levelBankAuthers()
    {

        return $this->hasMany(LevelBankAuther::class);
    }

   
}
