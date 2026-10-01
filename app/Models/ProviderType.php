<?php

namespace App\Models;

use App\Models\Providers\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderType extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    protected $table = 'provider_types';

     protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
}