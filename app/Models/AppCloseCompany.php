<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCloseCompany extends Model
{
   
    use HasFactory;
    protected $table = 'app_close_companies';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    protected $fillable = ['day_of_week', 'time_start', 'time_end', 'description'];

    public static function getCloseTime()
    {
        return self::first(); 
    }
}
