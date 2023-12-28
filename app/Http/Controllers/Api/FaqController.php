<?php

namespace App\Http\Controllers\Api;

use App\Models\Faq;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllFaqCollection;

/**
 * @OA\Get(
 *      path="/faqs",
 *      tags={"Faqs"},
 *      summary="Get List Of Fasq",
 *      description="Returns all Faqs",
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
class FaqController extends Controller
{
    public function index()
    {
        
        $faq = Faq::where('status', true)->orderBy('question', 'asc')->get();

        return new  AllFaqCollection($faq);
    }
}