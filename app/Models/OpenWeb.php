<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenWeb extends Model
{
    use \App\Models\Concerns\BatchesLazyLoads;
    use HasFactory;
}
