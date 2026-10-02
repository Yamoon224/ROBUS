<?php

namespace App\Models\Sid;

use App\Models\Sid\Feature;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;

class Permissions extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use LogsActivity;

    protected $table = 'sid_permissions';

    protected $fillable = ['name', 'guard_name', 'feature_id', 'description'];

    protected static $logFillable = true;

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}