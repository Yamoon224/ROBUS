<?php

namespace App\Models\Contacts;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactList extends Model
{
   use HasFactory;
   use SoftDeletes;

    protected $table = 'contact_lists';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'contacts',
        'created_by',
        'deleted_by',
        'status',
        'sid_user_id',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id', 'id')->withTrashed();
    }

}