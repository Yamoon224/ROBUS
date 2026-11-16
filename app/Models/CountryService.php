<?php

namespace App\Models;

use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryService extends Model

{
    use \App\Models\Concerns\BatchesLazyLoads;

    protected $table = 'country_services';

    protected static function booted()
    {
        // Achat d'UV : Paystack et Pixel Innov se remplacent l'un l'autre dans un pays.
        static::saved(function (self $service) {
            if ($service->wasRecentlyCreated || $service->wasChanged('status')) {
                \App\Support\UvPaymentSwitch::countryServiceChanged($service);
            }
        });
    }

    protected $fillable = ['country_id', 'provider_id', 'status', 'status_sub_distributor'];

    protected $casts = [
        'status' => 'boolean',
    ];


    public const STATUS_CLIENT = 'country_services.status';
    public const STATUS_SUB_DISTRIBUTOR = 'country_services.status_sub_distributor';

    /**
     * Colonne de statut à lire pour l'acteur connecté : les sous-distributeurs ont leur
     * propre activation, tous les autres (clients, API, admin) suivent celle des clients.
     */
    public static function actorColumn(): string
    {
        return self::isSubDistributorActor() ? self::STATUS_SUB_DISTRIBUTOR : self::STATUS_CLIENT;
    }

    private static function isSubDistributorActor(): bool
    {
        try {
            $apiUser = function_exists('robus') ? robus() : null;
            if ($apiUser) {
                return (bool) $apiUser->is_under_distributer;
            }

            $webUser = \Illuminate\Support\Facades\Auth::guard('web')->user();

            return $webUser && ($webUser->is_under_distributer || $webUser->hasRole('sous-distributeur'));
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id')->withTrashed();
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
