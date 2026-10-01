<?php

namespace App\Models\Invoices;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wallets\WalletTransaction;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceInter extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'invoice_inters';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [ ];

    protected $casts = [
        'company_id' => "integer",
        'wallet_inter_id' => "integer",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    
    public function getTitleAttribute($value)
    {
        $val = isset($value) ? json_decode($value, true) : null;
        return $val;
    }
    
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = isset($value) ? json_encode($value) : null;
    }
    
    public function walletTransaction()
    {
        return $this->belongsTo(WalletTransaction::class , 'transaction_inter_id' , 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
    
}