<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Providers\Provider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProviderCollection;

/**
 * @OA\Get(
 *      path="/user/virement_providers",
 *      tags={"provides"},
 *      summary="Get List Of Virement  providers",
 *      description="Returns all Virement  providers",
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
class VirementProviderController extends Controller
{
    public function index()
    {
        return new ProviderCollection(Provider::whereStatus(true)
            ->where('provider_type_id',  4)
            ->orderBy('name', 'asc')->get());
    }
}