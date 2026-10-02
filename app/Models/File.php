<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
     use HasFactory;
    use SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'origin',
        'key',
        'path',
        'type',
        'created_by',
        'deleted_by',
        'status'
    ];
   
  protected $casts = [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            ];
        
}