<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Providers\Provider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ProvidersService;
use App\Http\Resources\ProviderCollection;

/**
 * @OA\Get(
 *      path="/user/transaction_providers",
 *      tags={"provides"},
 *      summary="Get List Of Transaction  providers",
 *      description="Returns all Transaction  providers",
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
class TransactionProviderController extends Controller
{

    protected $providersService;
    
    public function __construct(
        ProvidersService $providersService,
    ) {
        $this->providersService = $providersService;
    }

    public function index()
    {
        $providerOptions =  $this->providersService->getProvidersForCountry();

        return new ProviderCollection($providerOptions);
    }

}
