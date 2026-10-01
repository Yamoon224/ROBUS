<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppSetting extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory , SoftDeletes;

    protected $table = 'app_settings';

    protected $fillable = ['site_name', 'site_email', 'site_description', 'site_copyright', 'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url', 'support_email', 'support_number', 'notification_settings', 'auto_assign', 'distance_unit', 'distance'];


    protected $casts = [
        'auto_assign' => 'integer',
    ];
    public function getNotificationSettingsAttribute($value)
    {
        return isset($value) ? json_decode($value, TRUE) : [];
    }

    public function setNotificationSettingsAttribute($value)
    {
        $this->attributes['notification_settings'] = isset($value) ? json_encode($value) : null;
    }
}