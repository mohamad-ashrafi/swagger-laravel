<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create New Post with Token.
     *
     * @OA\Post(
     *     path="/api/user/post/create",
     *     summary="Create a new post",
     *     tags={"posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="New Post Title"),
     *                 @OA\Property(property="body", type="string", example="Content of the new post"),
     *                 @OA\Property(property="like", type="integer", example=0)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function store(CreatePostRequest $request)
    {
        $post = Post::create($request->validated());
        return new PostResource($post);
    }


    /**
     * Increase likes of a post by post ID.
     *
     * @OA\Post(
     *     path="/api/user/post/{id}/like",
     *     summary="Increase likes of a post",
     *     tags={"posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Post ID",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="like_count", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Likes updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function increaseLink($id, Request $request)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $likeCount = $request->input('like_count', 0);
        $post->increment('like', $likeCount);

        return new PostResource($post);
    }
}
