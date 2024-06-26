<?php

namespace App\Http\Controllers\Api\Providers;

use App\Models\Providers\Provider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProviderCollection;

/**
 * @OA\Get(
 *      path="/user/reload_providers",
 *      tags={"provides"},
 *      summary="Get List Of Reload  providers",
 *      description="Returns all Reload  providers",
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
class ReloadProviderController extends Controller
{
    public function index()
    {
        return new ProviderCollection(Provider::whereStatus(true)
            ->where('name', '!=', 'Wave')
            ->where('provider_type_id',  1)
            ->orderBy('name', 'asc')->get());
    }


    public function index_masters()
    {
        return new ProviderCollection(Provider::whereStatus(true)
            ->whereName('ROBUS')
            ->where('provider_type_id',  1)->get());
    }
}
