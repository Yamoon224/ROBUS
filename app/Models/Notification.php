<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;

    protected $table = 'notifications';
}