<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCountrieCollection;
use App\Models\Kyc;

/**
 * @OA\Get(
 *      path="/kycs",
 *      tags={"validated account"},
 *      summary="Get List Of Docuements validated",
 *      description="Returns all validated account",
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
class KycController extends Controller
{
    public function index()
    {
        $countrie = Kyc::query();

        $countrie->when(request('code'), function ($q) {
            return $q->where('code', request('code'));
        });

        $countrie->when(request('name'), function ($q) {
            return $q->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $countrie->when(request('code'), function ($q) {
            return $q->where('code', 'LIKE', '%' . request('code') . '%');
        });

        $countrie = $countrie->where('robus', true)->orderBy('name', 'asc')->get();

        return new  AllCountrieCollection($countrie);
    }
}