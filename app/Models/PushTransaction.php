<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushTransaction extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
}
