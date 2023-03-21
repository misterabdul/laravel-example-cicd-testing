<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ?Post $post = null): JsonResponse
    {
        if ($post !== null) {
            $post->load(['category', 'user']);

            return new JsonResponse(new PostResource($post));
        }

        $posts = Post::published()
            ->with(['category', 'user'])
            ->paginate();

        return new JsonResponse(new PostCollection($posts));
    }
}
