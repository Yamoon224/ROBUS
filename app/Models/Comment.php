<?php

namespace App\Models;

use App\Models\Sid\SidUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;

    protected $table = 'comments';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $guarded = [];

    public function sidUser()
    {
        return $this->belongsTo(SidUser::class, 'sid_user_id', 'id')->withTrashed();
    }

    public function targetUser()
    {
        return $this->belongsTo(SidUser::class, 'target_sid_user_id', 'id')->withTrashed();
    }
}