<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllCurrencieCollection;
use App\Models\Currency;

/**
 * @OA\Get(
 *      path="/currencies",
 *      tags={"currencies"},
 *      summary="Get List Of Countires",
 *      description="Returns all currencies in search currencies",
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
class CurrencieController extends Controller
{
    public function index()
    {
        $currencie = Currency::query();

        $currencie->when(request('code'), function ($q) {
            return $q->where('code', request('code'));
        });

        $currencie->when(request('name'), function ($q) {
            return $q->where('name', 'LIKE', '%' . request('name') . '%');
        });

        $currencie = $currencie->where('status', true)->orderBy('name', 'asc')->get();

        return new  AllCurrencieCollection($currencie);
    }
}