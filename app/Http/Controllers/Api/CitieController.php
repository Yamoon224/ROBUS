<?php

namespace App\Http\Controllers\Api;

use App\Models\Custumers\City;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllCitieCollection;

/**
 * 
 * @OA\Get(
 *      path="/cities",
 *      tags={"Cities"},
 *      summary="Get List Of Cities",
 *      description="Returns all cities and search cities",
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
 * 
 */
class CitieController extends Controller
{
    public function index()
    {
        $city = City::query();

        $city->when(request('country_id'), function ($q) {
            return $q->where('country_id', request('country_id'));
        });

        $city->when(request('name'), function ($q) {
            return $q->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $city = $city->where('status', true)->orderBy('name', 'asc')->get();

        return new  AllCitieCollection($city);
    }
}