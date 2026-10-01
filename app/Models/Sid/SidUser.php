<?php

namespace App\Models\Sid;

// use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\Categorie;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Custumers\City;
use App\Models\Custumers\Country;
use App\Models\Kyc;
use App\Models\Master;
use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\WalletDrawal;
use App\Models\IntegrationClient;
use App\Models\Wallets\Wallet;
use App\Models\WithMerchant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class SidUser extends Authenticatable
{
    use Notifiable, SoftDeletes, HasFactory, HasApiTokens;
    // use Impersonate
    use LogsActivity;
    // use Impersonate;

    protected $table = 'sid_users';

    use HasRoles;

    protected $guarded = [];

    protected static $logFillable = true;

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function publicReviews()
    {
        return $this->hasMany(Comment::class, 'target_sid_user_id')->where('is_public', true);
    }


    public function withMerchants()
    {
        return $this->hasMany(WithMerchant::class);
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }


    public function integrator()
    {
        return $this->hasOne(IntegrationClient::class, 'sid_user_id', 'id');
    }


    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id', 'id');
    }

    public function operatorServices()
    {
        return $this->hasMany(OperatorService::class, 'sid_user_id', 'id');
    }

    public function master()
    {
        return $this->belongsTo(SidUser::class, 'master_id', 'id');
    }

    public function comzone()
    {
        return $this->belongsTo(SidUser::class, 'com_zone_id', 'id');
    }

    public function linkedComzoneUsers()
    {
        return $this->hasMany(SidUser::class, 'com_zone_id', 'id');
    }


    public function wallet()
    {
        return $this->hasOne(Wallet::class)->withTrashed();
    }



    public function walletDrawal()
    {
        return $this->hasOne(WalletDrawal::class)->withTrashed();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id')->withTrashed();
    }

    public function kyc()
    {
        return $this->hasOne(Kyc::class);
    }


    public function operator_services()
    {
        return $this->hasMany(OperatorService::class, 'sid_user_id', 'id', 'operator_id');
    }

    public function operators()
    {
        return $this->belongsToMany(Operator::class, 'operator_services', 'sid_user_id', 'operator_id');
    }

    public function dateFormatedCreated()
    {
        return date_format($this->created_at, 'd-m-y');
    }

    public function getPhoneWithoutIndicativeAttribute()
    {
        $countrie = $this->country;
        if ($countrie && $this->phone && str_starts_with($this->phone, $countrie->indecative)) {
            return substr($this->phone, strlen($countrie->indecative));
        }
        return $this->phone;
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function get_role()
    {
        return DB::table('sid_model_has_roles')
            ->join('sid_users', 'sid_users.id', '=', 'sid_model_has_roles.model_id')
            ->join('sid_roles', 'sid_model_has_roles.role_id', '=', 'sid_roles.id')
            ->where('sid_users.id', $this->id)
            ->select('sid_roles.name', 'sid_roles.id')
            ->get();
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone' => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function getCreatedAtAttribute($value)
    {
        return isset($value) ? Carbon::parse($value)->format('d-m-Y H:i:s') : null;
    }
    public function getUpdatedAtAttribute($value)
    {
        return isset($value) ? Carbon::parse($value)->format('d-m-Y H:i:s') : null;
    }
    public function getDeletedAtAttribute($value)
    {
        return isset($value) ? Carbon::parse($value)->format('d-m-Y H:i:s') : null;
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Robus');
    }
}
