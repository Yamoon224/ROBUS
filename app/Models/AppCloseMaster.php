<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCloseMaster extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
   
    use HasFactory;
    protected $table = 'app_close_masters';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $fillable = ['day_of_week', 'time_start', 'time_end', 'description'];

    public static function getCloseTime()
    {
        return self::first(); // Vous pouvez ajouter une logique supplémentaire ici selon vos besoins
    }
}
