<?php

namespace App\Models;

use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use SoftDeletes;
    protected $table = 'operators';

    protected $guarded = [];


    public function operator_services()
    {
        return $this->hasMany(OperatorService::class, 'operator_id', 'id', 'sid_user_id');
    }

    public function sidUsers()
    {
        return $this->belongsToMany(SidUser::class, 'operator_services', 'operator_id', 'sid_user_id');
    }
}
