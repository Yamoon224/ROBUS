<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    
    /**
     * @OA\Get(
     *      path="/user/notifications",
     *      tags={"provides"},
     *      summary="Get List of notifications",
     *      description="Returns all Notifications User",
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
    public function index()
    {

        return response()->json([
            'noread' => Auth::guard('api')->user()->unreadNotifications()->orderBy('created_at', 'desc')->latest()->get(),
            'data' => Auth::guard('api')->user()->notifications()->orderBy('created_at' , 'desc')->latest()->get(),
            'status' => true,
        ]);
    }

    public function notification_show($id)
    {


        $notification = Auth::guard('api')->user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json([
                'success' => true,
                'message' => 'Marque comme lu.',
                'data' => $notification
            ], 200);
        } else {
            $message = __('message.is not notiification');
            return json_message_response($message, 333);
        }
    }
}