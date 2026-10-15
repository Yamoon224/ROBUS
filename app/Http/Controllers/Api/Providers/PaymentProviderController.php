<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Providers\Provider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProviderCollection;

/**
 * @OA\Get(
 *      path="/user/payments_providers",
 *      tags={"provides"},
 *      summary="Get List Of Paiment providers",
 *      description="Returns all Payment providers",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *           mediaType="application/json",
 *      )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 * @OA\Response(
 *      response=400,
 *      description="Bad Request"
 *   ),
 * @OA\Response(
 *      response=404,
 *      description="not found"
 *   ),
 *  )
 */

class PaymentProviderController extends Controller
{
    public function index()
    {
        $countryId = Auth::user()->country_id ?? null;
        $providers = Provider::query()->where('provider_type_id', 2);

        if ($countryId) {
            $providers->availableInCountry((int) $countryId);
        } else {
            $providers->whereStatus(true);
        }

        return new ProviderCollection($providers->orderBy('name', 'asc')->get());
    }
}
