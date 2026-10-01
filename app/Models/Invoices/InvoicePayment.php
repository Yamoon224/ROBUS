<?php

namespace App\Models\Invoices;

use Spatie\Activitylog\LogOptions;
use App\Models\Wallets\WalletPayment;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoicePayment extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'invoice_payments';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [ ];

    protected $casts = [
        'company_id' => "integer",
        'wallet_payment_id' => "integer",
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

     public function walletPayment()
    {
        return $this->belongsTo(WalletPayment::class , 'wallet_payment_id' , 'id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}