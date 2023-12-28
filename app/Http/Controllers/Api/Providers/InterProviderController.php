<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Http\Controllers\Controller;
use App\Http\Services\ProvidersService;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\AllCountrieCollection;

/**
 * @OA\Get(
 *      path="/user/inter_providers",
 *      tags={"provides"},
 *      summary="Get List Of Internationnal providers",
 *      description="Returns all Internationnal providers",
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
 *  ),
 */

class InterProviderController extends Controller
{


    protected $providersService;
    
    public function __construct(
        ProvidersService $providersService,
    ) {
        $this->providersService = $providersService;
    }

    
    public function index()
    {
        $countriesOptions = $this->providersService->getProvidersForCountryInters();

        return new AllCountrieCollection($countriesOptions);
    }


}