<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCompanyStatus extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;

    protected $table = 'app_company_statuses';

    protected $fillable = [
        'status_app'
        , 'time_start', 'time_end', 'description', 'day_week'
    ];
}
