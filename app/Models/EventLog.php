<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    use HasFactory;
    protected $table = 'event_logs';
    protected $fillable = ['event_name', 'event_data', 'event_user' , 'type_user_event' , 'user_countrie'
     , 'amount_send' , 'currencie' , 'type_user'];
}
