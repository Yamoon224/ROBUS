<?php

namespace App\Models\Custumers;

use App\Models\User;
use App\Models\Sid\SidUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'zones';

        
    protected $fillable = [
        'id',
        'slug',
        'name',
        'status',
        'description',
        'created_by'
    ];

    protected $casts = [
        'name' => 'string',
        'status' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
        
    public function sidUsers()
    {
      return $this->hasMany(SidUser::class);
    }

    public function users()
    {
      return $this->hasMany(User::class);
    }
 

}