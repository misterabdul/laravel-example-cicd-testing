<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostCollection;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MyPostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var \App\Models\User */
        $me = $request->user();
        $posts = Post::ofUser($me)
            ->with(['category', 'user'])
            ->paginate();

        return new JsonResponse(new PostCollection($posts));
    }
}
