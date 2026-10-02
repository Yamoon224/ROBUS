<?php

namespace App\Models;

use App\Models\Sid\SidUser;
use App\Models\Banks\BankCard;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kyc extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;


    protected $guarded = [];


    protected $casts = [
        'company_id' => "integer",
        'sid_user_id' => "integer",
    ];


    public function company()
    {
        return $this->belongsTo(Company::class)->withTrashed();
    }

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }

    public function walletCard()
    {
        return $this->belongsTo(BankCard::class, 'bank_card_id', 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}