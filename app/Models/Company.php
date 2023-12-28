<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';

    protected $fillable = ['name', 'phone', 'logo', 'country', 'city', 'website', 'address'];
    protected static $logFillable = true;

    
}