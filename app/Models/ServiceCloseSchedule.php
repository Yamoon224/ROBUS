<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCloseSchedule extends Model
{
    protected $table = 'service_close_schedules';

    protected $fillable = [
        'service', 'day_number', 'day_of_week', 'time_start', 'time_end',
        'description', 'status', 'created_by',
    ];

    protected $casts = ['status' => 'boolean'];
}
