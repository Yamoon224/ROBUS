<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    /**
     * @OA\POST(
     *      path="/user/comments/store",
     *      tags={"Comment card User"},
     *      summary="Create new Comment card",
     *      description="Faire des commentaires depuis son dashbord. 
     *       .    
     *         ",
     *  @OA\Parameter(
     *         in="path",
     *         name="email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\Response(
     *          response=200,
     *          description="success Pour un exemple sur une demande de rechargement de commande de carte",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="string", example="0708090708"),
     *              @OA\Property(property="email", type="string", example="ibe@gmail.com"),
     *              @OA\Property(property="status", type="string", example="Pending"),
     *          )
     *     ),
     *  @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="phone",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="email",
     *                          type="number"
     *                      ),
     *                    @OA\Property(
     *                          property="identity_doc",
     *                          type="string"
     *                      ),
     *                   @OA\Property(
     *                          property="identity_id",
     *                          type="string"
     *                      ),
     *                  @OA\Property(
     *                          property="identity_form",
     *                          type="string"
     *                      ),
     *                 ),
     * 
     *                example = {
     * 
     *                     "phone":"09090909",
     *                     "email":"uba@gmail.com",
     *                     "identity_doc":"/public/images/piece.jpg",
     *                     "identity_doc":"/public/images/piece.jpg",
     *                     "identity_doc":"/public/images/piece.jpg",
     * 
     *                }
     *                
     *             )
     *         )
     *      ),
     * 
     * @OA\Response(
     *          response=341,
     *          description="Error updalod image."
     *      ),
     *  ),
     */
    public function store(Request $request)
    {

        $validator = Validator::make(

            $request->all(),
            [
                'description' => 'required',
            ],
            [

                'description.required' => 'Votre description est obligatoire',

            ]
        );


        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Champ is required ',
                    'data' => $validator->errors()
                ],
                333
            );
        }

        $comment  = Comment::create([
            'sid_user_id' =>  Auth::guard('api')->user()->id,
            'description' => $request->description
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id'      => $comment->id,
                'firstname' => $comment->sidUser->phone,
            ],
            'message' => ('Comment has been created successfully.')
        ], 200);
    }



    /**
     * Get Detail comment card
     * @OA\Get (
     *      path="/user/comment/show/{id}",
     *      tags={"comment User"},
     *      summary="Show details comment card",
     *      description="Details sur les commentaire ",   
     *  @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="phone", type="string", example="+22309898989"),
     *              @OA\Property(property="email", type="double", example="uba@gmail.com"),
     *              @OA\Property(property="images", type="string", example="public/image/piece.jpg"),
     *              @OA\Property(property="Status", type="string", example="pending"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2022-08-11T09:25:53.000000Z")
     *         )
     *     )
     * )
     */
    public function show($comment)
    {

        $comment_user = Comment::find($comment)->first();


        if ($comment_user->id == null) {
            $message = __('message.is not card');
            return json_message_response($message, 401);
        }


        return response()->json([
            'success' => true,
            'message' => 'Demande success.',
            'data' =>  [
                'description' => $comment_user->description,
            ],
        ]);
    }

    public function index()
    {


        $comments = Comment::where('sid_user_id', Auth::guard('api')->user()->id)->first();

        return response()->json([
            'success' => true,
            'comment' =>  $comments,
        ], 200);
    }


    public function delete(Request $request)
    {

        try {


            $comment = Comment::find($request->comment_id);

            if ($comment != null) {
                $message = __('message.is not comment');
                return json_message_response($message, 401);
            }

            $comment->delete();
            return response()->json([
                'success' => true,
                'message' => 'Comment has been successfully removed from your wallet'
            ], 200);
        } catch (\Exception $exception) {
            $message = __('message.is not comment');
            return json_message_response($message, 401);
        }
    }
}