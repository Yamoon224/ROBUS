<?php

namespace App\Models;

use App\Models\Activitie;
use App\Models\Advertissement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvertissementActivitie extends Model
{
    use HasFactory;

    protected $table = 'advertissement_activities';

    protected $guarded = [];


    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

       
    public function activitie()
    {
        return $this->belongsTo(Activitie::class, 'activitie_id', 'id');
    }

    public function adventissement()
    {
        return $this->belongsTo(Advertissement::class, 'advertissement_id', 'id');
    }
}
