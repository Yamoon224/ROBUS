<?php

namespace App\Models\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderService extends Model
{
    use HasFactory;
      use SoftDeletes;

    protected $table = 'provider_services';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = [ ];

    protected $casts = [
        'provider_id' => "integer",
        'status' => "boolean",
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];

    public function provider()
    {
        return $this->belongsTo(Provider::class,'provider_id', 'id')->withTrashed();
    }

}