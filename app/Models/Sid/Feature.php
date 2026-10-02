<?php

namespace App\Models\Sid;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Feature extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
    use LogsActivity;

    protected $table = 'sid_features';

    protected $fillable = ['name', 'description', 'deleted_by'];
    protected static $logFillable = true;

    public function permissions()
    {
        return $this->hasMany(Permissions::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}