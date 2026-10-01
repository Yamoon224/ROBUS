<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppWebStatus extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;

    protected $table = 'app_web_statuses';

    protected $fillable = [
        'status_app'
        , 'time_start', 'time_end', 'description', 'day_week'
    ];
}
