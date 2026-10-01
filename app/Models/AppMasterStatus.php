<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppMasterStatus extends Model
{
    use HasFactory;

    protected $table = 'app_master_statuses';

    protected $fillable = [
        'status_app'
        , 'time_start', 'time_end', 'description', 'day_week'
    ];
}
