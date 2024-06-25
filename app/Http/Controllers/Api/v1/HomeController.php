<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\JsonApiPaginate\JsonApiPaginate;
use Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider;

class HomeController extends Controller
{
    /**
     * Display a listing of the posts with pagination.
     *
     * @OA\Get(
     *     path="/api/user",
     *     summary="Show All Posts with pagination in Home page",
     *     tags={"Home"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of posts per page",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Post")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function index(HomeRequest $request)
    {
        $perPage = $request->input('per_page', 10);
        $posts = Post::paginate($perPage);
        return PostResource::collection($posts);
    }

}
