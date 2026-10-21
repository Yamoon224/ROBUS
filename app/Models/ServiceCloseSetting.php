<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCloseSetting extends Model
{
    protected $table = 'service_close_settings';

    protected $fillable = ['service', 'manual_mode', 'manual_message', 'updated_by'];
}
