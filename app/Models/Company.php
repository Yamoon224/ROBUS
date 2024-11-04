<?php

namespace App\Models;

use App\Models\PercentCompanie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $guarded = [];

  protected $hidden = [
        'created_at',
        'updated_at',
    ];

    
    public function percent()
    {
        return $this->belongsTo(PercentCompanie::class, 'percent_companie_id', 'id')->withTrashed();
    }
}